<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for lodash library.
 */
class LodashAsset extends AssetBundle
{
    public $sourcePath = '@bower/lodash';

    public $js = [
        'lodash.min.js',
    ];
}
