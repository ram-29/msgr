<?php

namespace backend\modules\api\controllers;

/**
 * Thread controller for the `Api` module
 */
class ThreadController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Thread';

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

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => '\yii\filters\Cors',
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
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
        $model = new \common\models\Thread();
        $model->load(\Yii::$app->request->post(), '');

        // Set attributes.
        $model->setAttrs();

        // Save Thread.
        $model->save();

        // Create ThreadGlobalConfig.
        $thGlobCfg = new \common\models\ThreadGlobalConfig();
        $thGlobCfg->id = $model->id;
        $thGlobCfg->name = 'new Group()';
        $thGlobCfg->save();

        // Return Thread.
        return $model;
    }
}
