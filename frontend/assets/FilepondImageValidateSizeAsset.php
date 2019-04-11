<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image validate size plugin library.
 */
class FilepondImageValidateSizeAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-validate-size';

    // public $css = [
    //     'dist/filepond-plugin-image-validate-size.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-image-validate-size.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
