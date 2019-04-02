<?php

namespace backend\modules\api\controllers;

/**
 * ThreadGlobalConfig controller for the `Api` module
 */
class ThreadGlobalConfigController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\ThreadGlobalConfig';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
		$behaviors['bootstrap'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

		return $behaviors;
    }
}
