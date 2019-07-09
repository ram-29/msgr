<?php

namespace backend\modules\api\controllers;

/**
 * ThreadMessage controller for the `Api` module
 */
class ThreadMessageController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\ThreadMessage';

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
        
        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate()
    {
        // Initalize a model.
        $model = new \common\models\ThreadMessage();
        $model->load(\Yii::$app->request->post(), '');

        // Set attributes.
        $model->setAttrs();

        // Save ThreadMessage.
        $model->save();

        // Get Thread & ThreadMembers.
        $mTh = \common\models\Thread::findOne($model->thread_id);
        $mThMem = array_filter($mTh->threadMembers, function($mThm) use ($model) {
            return $mThm['member_id'] !== $model->member_id;
        });

        if($model->type == 'MSG') {
            // Add a ThreadMessageSeen.
            foreach($mThMem as $mThmem) {
                // Initialize a ThreadMessageSeen model.
                $mThMsgSeen = new \common\models\ThreadMessageSeen();

                // Set Attrs.
                $mThMsgSeen->setAttrs();

                // Save ThreadMessageSeen.
                $mThMsgSeen->thread_message_id = $model->id;
                $mThMsgSeen->member_id = $mThmem['member_id'];
                $mThMsgSeen->seen_at = null;
                $mThMsgSeen->save();
            }
        }

        // Return model.
        return $model;
    }
}
