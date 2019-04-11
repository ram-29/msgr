<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image resize plugin library.
 */
class FilepondImageResizeAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-resize';

    // public $css = [
    //     'dist/filepond-plugin-image-resize.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-image-resize.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
