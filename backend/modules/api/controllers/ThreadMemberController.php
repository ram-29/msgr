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

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'], 
                'Access-Control-Request-Method' => ['GET'], 
                'Access-Control-Request-Headers' => ['*'], 
                'Access-Control-Allow-Credentials' => null, 
                'Access-Control-Max-Age' => 86400, 
                'Access-Control-Expose-Headers' => []
            ]
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
        unset($actions['delete']);
        
        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        // Initalize a model.
        $model = new \common\models\ThreadMember();
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
    public function actionDelete($id)
    {
        // Check body params if "group" attr. is available
        $params = \Yii::$app->request->bodyParams;

        $threadMember;
        if(\array_key_exists('group', $params)) {
            $threadMember = \common\models\ThreadMember::findOne([
                'thread_id' => $params['group'],
                'member_id' => $id
            ]);
        } else {
            $threadMember = \common\models\ThreadMember::findOne($id);
        }

        if(!empty($threadMember)) {
            $threadMember->delete();
            return $threadMember;
        }

        return new \yii\web\NotFoundHttpException();
    }
}
