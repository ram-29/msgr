<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'https://fonts.googleapis.com/css?family=Poppins',
        'css/app.min.css',
    ];

    public $js = [
        'js/base/default.js'
    ];
    
    public $depends = [
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'dominus77\sweetalert2\assets\SweetAlert2Asset',
        'madand\momentjs\MomentJsAllLocalesAsset',
        'frontend\assets\LodashAsset',
        'frontend\assets\OverlayScrollbarAsset',
        'frontend\assets\EmojiOneAreaAsset',
        'frontend\assets\SocketIOAsset',
    ];
}
