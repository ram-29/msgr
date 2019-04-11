<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image exif orientation plugin library.
 */
class FilepondImageExifOrientationAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-exif-orientation';

    public $js = [
        'dist/filepond-plugin-image-exif-orientation.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
