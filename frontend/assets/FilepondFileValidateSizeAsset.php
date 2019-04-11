<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond file validate size plugin library.
 */
class FilepondFileValidateSizeAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-file-validate-size';

    // public $css = [
    //     'dist/filepond-plugin-file-validate-size.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-file-validate-size.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
