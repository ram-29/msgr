<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/base/default.js'
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'dmstr\web\AdminLteAsset',
        'dominus77\sweetalert2\assets\SweetAlert2Asset',
        'madand\momentjs\MomentJsAllLocalesAsset',
        'backend\assets\LodashAsset'
    ];
}
