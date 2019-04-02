<?php

namespace backend\modules\api\controllers;

/**
 * ThreadMemberConfig controller for the `Api` module
 */
class ThreadMemberConfigController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\ThreadMemberConfig';

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
