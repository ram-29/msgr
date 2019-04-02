<?php

namespace backend\modules\api\controllers;

/**
 * Member controller for the `Api` module
 */
class MemberController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Member';

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
