<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond image crop plugin library.
 */
class FilepondImageCropAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-image-crop';

    public $js = [
        'dist/filepond-plugin-image-crop.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
