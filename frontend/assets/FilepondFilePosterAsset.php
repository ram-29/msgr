<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond file poster plugin library.
 */
class FilepondFilePosterAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond-plugin-file-poster';

    public $css = [
        'dist/filepond-plugin-file-poster.min.css'
    ];

    public $js = [
        'dist/filepond-plugin-file-poster.min.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];
}
