<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for Filepond library.
 */
class FilepondAsset extends AssetBundle
{
    public $sourcePath = '@npm/filepond';

    public $css = [
        'dist/filepond.min.css'
    ];

    public $js = [
        'dist/filepond.min.js'
    ];
}
