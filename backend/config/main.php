<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'backend\controllers',
    'modules' => [
        'api' => [
            'basePath' => '@backend/modules/api',
            'class' => 'backend\modules\api\Module',
        ],
        'user' => [
            'as backend' => [
                'class' => 'dektrium\user\filters\BackendFilter',
                // 'class' => 'niksko12\user\filters\BackendFilter'
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        // 'user' => [
        //     'identityClass' => 'common\models\User',
        //     'enableAutoLogin' => true,
        //     'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        // ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            // 'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/thread', 'pluralize' => false, 'tokens' => ['{id}' => '<id:[\w-]+>']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/member', 'pluralize' => false,
                    'extraPatterns' => [
                        'POST id' => 'id',
                    ],
                 'tokens' => ['{id}' => '<id:[\w-]+>']],

                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/thread-member', 'pluralize' => false, 'tokens' => ['{id}' => '<id:[\w-]+>']],

                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/thread-global-config', 'pluralize' => false, 'tokens' => ['{id}' => '<id:[\w-]+>']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/thread-member-config', 'pluralize' => false, 'tokens' => ['{id}' => '<id:[\w-]+>']],

                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/thread-message', 'pluralize' => false, 'tokens' => ['{id}' => '<id:[\w-]+>']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/thread-message-seen', 'pluralize' => false, 'tokens' => ['{id}' => '<id:[\w-]+>']],
            ],
        ],
    ],
    'params' => $params,
];
