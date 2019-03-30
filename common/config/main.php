<?php
return [
    'name' => 'Yii2 Advanced App',
    'timeZone' => 'Asia/Manila',
    'aliases' => [
        '@bower' => '@vendor/bower',
        '@npm'   => '@vendor/npm',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-black-light',
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@common/views/user',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\formatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'currencyCode' => 'PHP',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
            'numberFormatterSymbols' => [
                NumberFormatter::CURRENCY_SYMBOL => 'Php ', // &#8369;
            ],
        ],
    ],
    'modules' => [
        'gridview' =>  [
            'class' => 'kartik\grid\Module'
        ],
        'utility' => [
            'class' => 'c006\utility\migration\Module',
        ],
        'file' => [
            'class' => 'file\FileModule',
            'webDir' => 'files',
            'tempPath' => '@backend/web/assets/uploads/temp',
            'storePath' => '@backend/web/assets/uploads/store',
            'rules' => [
                'maxFiles' => 20,
                'maxSize' => 1024 * 1024 * 20
            ],
        ],
        'rbac' => [
            'class' => 'dektrium\rbac\RbacWebModule',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'enableFlashMessages' => false,
            'enableRegistration' => true, # Remove this afterwards.
            'admins'=> [ 'r2am9d' ],
            'modelMap' => [
                'RegistrationForm' => 'common\models\RegistrationForm'
            ],
            'controllerMap' => [
                'registration' => [
                    'class' => \dektrium\user\controllers\RegistrationController::className(),
                    'on ' . \dektrium\user\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                        $user = \dektrium\user\models\User::findOne(['username' => $e->form->username, 'email' => $e->form->email]);
                        if ($user) { Yii::$app->user->switchIdentity($user); }
                        Yii::$app->response->redirect(['/'])->send();
                        Yii::$app->end();
                    }
                ],
                'security' => [
                    'class' => \dektrium\user\controllers\SecurityController::className(),
                    'on ' . \dektrium\user\controllers\SecurityController::EVENT_AFTER_LOGIN => function ($e) {
                        Yii::$app->response->redirect(['/'])->send();
                        Yii::$app->end();
                    }
                ],
            ],
        ],
    ]
];
