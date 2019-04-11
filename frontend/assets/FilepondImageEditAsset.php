<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image edit plugin library.
 */
class FilepondImageEditAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-edit';

    public $css = [
        'dist/filepond-plugin-image-edit.min.css'
    ];

    public $js = [
        'dist/filepond-plugin-image-edit.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
