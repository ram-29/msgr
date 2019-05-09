<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for nanogallery2 library.
 */
class Nanogallery2Asset extends AssetBundle
{
    public $sourcePath = '@npm/nanogallery2/dist';

    public $js = [
        'jquery.nanogallery2.min.js',
    ];

    public $css = [
        'css/font/ngy2_icon_font.woff',
        'css/font/ngy2_icon_font.woff2',
        'css/nanogallery2.min.css',
        'css/nanogallery2.woff.min.css'
    ];
    
}
