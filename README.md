# Cropper – Kirby Cropper Field

This additional panel field for [Kirby 2](http://getkirby.com) allows you to crop images in the Panel.

## Installation

### Git Submodule

```bash
$ cd your/project/root
$ git submodule add --name kirby-cropper https://github.com/blankogmbh/kirby-cropper.git site/fields/cropper
$ cd site/fields/cropper
$ git submodule update --init --recursive
```

Add the following to you site/config/config.php

```php
c::set('routes', array(
    // https://github.com/blankogmbh/kirby-cropper
    array(
        'pattern' => 'ajax-cropper',
        'action' => function () {
            include('site/fields/cropper/ajax_cropper.php');
        },
        'method' => 'POST'
    )
));
```

To Update your cropper field submodule to the latest available release follow these steps:

```bash
$ cd your/project/root
$ cd site/fields/cropper
$ git checkout master
$ git pull
$ cd ../../../
$ git commit -a -m "Update cropper submodule"
```

## Usage

### Within your blueprints

As soon as you dropped the field extension into your fields folder you can use it in your blueprints: simply add `cropper` fields to your blueprints and set some options (where applicable).

```yaml
fields:
    cropper:
        label: Cropping Area
        type: cropper
```
