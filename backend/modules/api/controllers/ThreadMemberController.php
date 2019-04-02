<?php

namespace backend\modules\api\controllers;

/**
 * ThreadMember controller for the `Api` module
 */
class ThreadMemberController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\ThreadMember';

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
