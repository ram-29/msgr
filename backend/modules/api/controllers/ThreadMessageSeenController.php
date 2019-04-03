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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        
        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        // Initalize a model.
        $model = new \common\models\ThreadMessageSeen();
        $model->load(\Yii::$app->request->post(), '');

        // Set attributes.
        $model->setAttrs();

        // Save & return.
        $model->save();
        return $model;
    }
}
