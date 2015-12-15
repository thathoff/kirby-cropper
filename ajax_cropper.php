<?php

$isAdminOrEditor = site()->user() && (site()->user()->hasRole('admin') || site()->user()->hasRole('editor'));

if($_POST['action'] == "crop" && $isAdminOrEditor) {
    if (
        empty($_POST[ 'root' ]) ||
        empty($_POST[ 'width' ]) ||
        empty($_POST[ 'height' ]) ||
        empty($_POST[ 'x' ]) ||
        empty($_POST[ 'y' ])
    ) {
        return false;
    }

    $fileToCrop = $_POST['root'];

    if (strpos(str_replace(array('..'), '', $fileToCrop), system::realpath('content')) === 0) {
        $file = $fileToCrop;
        $image = new Imagick($file);
        $image->cropImage(
            $_POST[ 'width' ],
            $_POST[ 'height' ],
            $_POST[ 'x' ],
            $_POST[ 'y' ]
        );
    }

    if ($writeImage = $image->writeImage($file)) {
        echo "ok";
    }
}
