<?php
return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname='.Yii::getAlias('@db'),
        ],
    ],
];
