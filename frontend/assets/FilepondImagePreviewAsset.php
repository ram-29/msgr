<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image preview plugin library.
 */
class FilepondImagePreviewAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-preview';

    public $css = [
        'dist/filepond-plugin-image-preview.min.css'
    ];

    public $js = [
        'dist/filepond-plugin-image-preview.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
