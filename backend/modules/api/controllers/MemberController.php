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
        $model = new \common\models\Member();
        $model->load(\Yii::$app->request->post(), '');

        // Set attributes.
        $model->setAttrs();

        // Save & return.
        $model->save();
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function actionId()
    {   
        $model = new \common\models\Member();
        $model->load(\Yii::$app->request->post(), '');

        $details = $model->findByUsername($model->username);

        //Check if account is existing
        if($details == null) {
            $model->setAttrs();
            $model->save();

            return $model;

        } else {
            
            return $details;
        }

    }

}
