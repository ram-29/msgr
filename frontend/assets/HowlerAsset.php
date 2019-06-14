<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for howler library.
 */
class HowlerAsset extends AssetBundle
{
    public $sourcePath = '@npm/howler/dist';

    public $js = [
        'howler.min.js',
        'howler.spatial.min.js',
    ];
}
