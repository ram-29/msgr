<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for SocketIO file upload library.
 */
class SocketIOFileUploadAsset extends AssetBundle
{
    public $sourcePath = '@npm/socketio-file-upload';

    public $js = [
        'client.js'
    ];
}
