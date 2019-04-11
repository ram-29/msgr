<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond file rename plugin library.
 */
class FilepondFileRenameAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-file-rename';

    // public $css = [
    //     'dist/filepond-plugin-file-rename.min.css'
    // ];

    public $js = [
        'dist/filepond-plugin-file-rename.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
