<?php

namespace backend\modules\api\controllers;

/**
 * ThreadMessage controller for the `Api` module
 */
class ThreadMessageController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\ThreadMessage';

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
