<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for overlayscrollbars library.
 */
class OverlayScrollbarAsset extends AssetBundle
{
    public $sourcePath = '@npm/overlayscrollbars';

    public $css = [
        'css/OverlayScrollbars.min.css'
    ];

    public $js = [
        'js/OverlayScrollbars.min.js'
    ];
}
