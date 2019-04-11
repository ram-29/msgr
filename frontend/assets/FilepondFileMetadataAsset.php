<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond file metadata plugin library.
 */
class FilepondFileMetadataAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-file-metadata';

    // public $css = [
    //     'dist/filepond-plugin-file-metadata.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-file-metadata.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
