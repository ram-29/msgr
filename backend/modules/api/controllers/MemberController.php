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
        unset($actions['check']);
        
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

    /**
     * {@inheritdoc}
     */
    public function actionId()
    {

        $model = new \common\models\Member();
        $model->load(\Yii::$app->request->post(), '');
        $username = \Yii::$app->request->getBodyParam('username');

        $details = $model->findByMemberUsername($username);

        //Check if account is existing
        if($details == null) {
            $model->setAttrs();
            $model->save();
            return $model;
        } else {
            return $details;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actionUnread($id)
    {
        $model = \common\models\Member::findOne(['intranet_id' => $id]);

        $BK_HTTP_URL = 'http://bk.msgr.io';
        $BK_HTTPS_URL = 'https://bk.msgr.io';

        if(!empty($model)) {
            return (new  \yii\httpclient\Client())
                ->get("{$BK_HTTP_URL}/api/member/{$model->id}?expand=unread_count")
                ->send()->getData();
        }

        return new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
}
