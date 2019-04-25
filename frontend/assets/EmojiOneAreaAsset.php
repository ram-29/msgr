<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for emojionearea library.
 */
class EmojiOneAreaAsset extends AssetBundle
{
    public $sourcePath = '@bower/emojionearea';

    public $css = [
        'dist/emojionearea.min.css'
    ];

    public $js = [
        'dist/emojionearea.min.js'
    ];
}
