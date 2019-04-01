<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for SocketIO library.
 */
class SocketIOAsset extends AssetBundle
{
    public $sourcePath = '@npm/socket.io-client';

    public $js = [
        'dist/socket.io.js'
    ];
}
