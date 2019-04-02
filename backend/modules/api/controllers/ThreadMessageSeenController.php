<?php

namespace backend\modules\api\controllers;

/**
 * ThreadMessageSeen controller for the `Api` module
 */
class ThreadMessageSeenController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\ThreadMessageSeen';

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
