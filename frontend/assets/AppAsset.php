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
        'frontend\assets\SocketIOFileUploadAsset',
        'frontend\assets\AxiosAsset',

        'frontend\assets\FilepondAsset',
        'frontend\assets\FilepondImageCropAsset',
        'frontend\assets\FilepondImageEditAsset',
        'frontend\assets\FilepondImageExifOrientationAsset',
        'frontend\assets\FilepondImagePreviewAsset',
        'frontend\assets\FilepondImageResizeAsset',
        'frontend\assets\FilepondImageValidateSizeAsset',
        'frontend\assets\FilepondImageTransformAsset',

        'frontend\assets\FilepondFileEncodeAsset',
        'frontend\assets\FilepondFileMetadataAsset',
        'frontend\assets\FilepondFilePosterAsset',
        'frontend\assets\FilepondFileRenameAsset',
        'frontend\assets\FilepondFileValidateSizeAsset',
        'frontend\assets\FilepondFileValidateTypeAsset',

        'frontend\assets\Nanogallery2Asset',
    ];
}
