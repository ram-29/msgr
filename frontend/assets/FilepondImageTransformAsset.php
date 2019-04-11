<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image transform plugin library.
 */
class FilepondImageTransformAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-transform';

    // public $css = [
    //     'dist/filepond-plugin-image-transform.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-image-transform.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
