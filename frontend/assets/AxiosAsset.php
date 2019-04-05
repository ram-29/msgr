<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for axios library.
 */
class AxiosAsset extends AssetBundle
{
    public $sourcePath = '@npm/axios';

    public $js = [
        'dist/axios.min.js'
    ];
}
