<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond file validate type plugin library.
 */
class FilepondFileValidateTypeAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-file-validate-type';

    // public $css = [
    //     'dist/filepond-plugin-file-validate-type.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-file-validate-type.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
