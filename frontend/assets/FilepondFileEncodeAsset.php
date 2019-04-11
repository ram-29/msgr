<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond file encode plugin library.
 */
class FilepondFileEncodeAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-file-encode';

    // public $css = [
    //     'dist/filepond-plugin-file-encode.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-file-encode.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
