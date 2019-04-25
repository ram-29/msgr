<?php

namespace backend\modules\api\controllers;

/**
 * Thread controller for the `Api` module
 */
class UserController extends \yii\rest\ActiveController
{

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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['fetchId']);
        
        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function actionFetchId()
    {
        return 0;
    }
}
