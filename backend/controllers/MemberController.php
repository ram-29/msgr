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
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
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
        unset($actions['update']);
        
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
    public function actionUpdate($id)
    {
        $model = \common\models\Member::findOne($id);
        $params = \Yii::$app->request->getBodyParams();
        $model->load($params, '');
        if($params['type'] == 'CONNECT') {
            $model->status = 'ACTIVE';
            $model->logged_at = date("Y-m-d H:i:s", time());
            $model->save();
        } else {
            $model->status = 'INACTIVE';
            $model->save();
        }
        return $model;
    }
}