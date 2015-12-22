<div class="input input-with-selectbox">
    <div class="selectbox-wrapper">
        <select class="selectbox" name="cropper" id="cropperSelect">
            <option></option>
            <?php foreach ($field->files() as $file): ?>
                <option
                    value='{
                        "filename": "<?php echo $file->filename() ?>",
                        "url": "<?php echo $file->url() ?>",
                        "root": "<?php echo $file->root() ?>"
                    }'>
                    <?php echo $file->filename() ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>
</div>

<div class="croppingarea" style="display: none">
    <div class="croppingarea--imagewrapper">
        <img id="image" class="img-responsive" src="" alt="Picture">
    </div>

    <?php if(count($field->ratios())): ?>
        <?php
        foreach($field->ratios() as $ratio):
            if (is_array($ratio)) {
                $label = $ratio[ 'label' ];
                $ratio = $ratio[ 'value' ];
            } else {
                $label = $ratio;
            }
            $ratioExploded = explode('/', $ratio);
            ?>
            <button class="btn cropperChangeAspectRatio" data-aspectratio="<?php echo ((int)$ratioExploded[0] / (int)$ratioExploded[1]) ?>"><?php echo html($label) ?></button>
        <?php endforeach ?>
    <?php endif ?>

    <?php if($field->disallowfree() != true): ?>
        <button class="btn cropperChangeAspectRatio" data-aspectratio="NaN">free aspect ratio</button>
    <?php endif ?>

    <button class="btn btn-positive" id="cropperSaveButton">Crop It Now!</button>
</div>

<script>
    $( function () {
        var $previews = $( '.preview' ),
            $image = $( '#image' ),
            $croppingArea = $( '.croppingarea' );

        $image.cropper( {
            aspectRatio: <?php
            $firstRadio = $field->ratios();
            $firstRadio = $firstRadio[0];

            if(is_array($firstRadio)) {
                $firstRadio = $firstRadio[ 'value' ];
            }

            $firstRadio = $firstRadio[0];

            echo $firstRadio ? $firstRadio : 'NaN'
            ?>,
            zoomable: false,
            build: function ( e ) {
                var $clone = $( this ).clone();

                $clone.css( {
                    display: 'block',
                    width: '100%',
                    minWidth: 0,
                    minHeight: 0,
                    maxWidth: 'none',
                    maxHeight: 'none'
                } );
            },
            crop: function ( e ) {
                var imageData = $( this ).cropper( 'getImageData' ),
                    imagePath = $( this ).attr( 'src' ),
                    data = $( this ).cropper( 'getData' );
            }
        } );

        $( '#cropperSelect' ).change( function ( e ) {
            var obj;

            console.log($( this ).val());

            if ($( this ).val()) {
                obj = JSON.parse( $( this ).val() );

                $croppingArea.show();
                $image.cropper( 'replace', obj.url );
            } else {
                $croppingArea.hide();
            }

        } );

        $( '.cropperChangeAspectRatio' ).on( 'click', function ( e ) {
            e.preventDefault();

            $image.cropper( "setAspectRatio", $( this ).data( 'aspectratio' ) );
        } );

        $( '#cropperSaveButton' ).on( 'click', function ( e ) {
            e.preventDefault();

            var data = $image.cropper( 'getData' ),
                selectData = JSON.parse( $( '#cropperSelect' ).val() );

            $croppingArea.hide();

            data.action = 'crop';
            data.root = selectData.root;
            data.image = $image.attr( 'src' );

            $.ajax( {
                url: '<?php echo url('/ajax-cropper') ?>',
                type: 'post',
                data: data,
                success: function ( data, status ) {
                    if ( data == "ok" ) {
                        $( '#cropperSelect' ).val('');

                        // ToDo: Reload image and/or output success message
                        location.reload();
                        // $image.cropper( 'replace', data.url );
                    }
                },
                error: function ( xhr, desc, err ) {
                    console.log( xhr );
                    console.log( "Details: " + desc + "\nError:" + err );
                }
            } );
        } );
    } );
</script>
