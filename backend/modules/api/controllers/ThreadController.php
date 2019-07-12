<?php
namespace backend\modules\api\controllers;
use common\helpers\Logger;

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
        $model = new \common\models\Thread();
        $params = \Yii::$app->request->getBodyParams();
        $model->load($params, '');

        // Check if has thread for type "simple";
        // if($params['type'] == 'SIMPLE') {
        //     if(\array_key_exists('members', $params)) {
        //         $threadMember = new \common\models\ThreadMember();    
        //         $hasThread = $threadMember->findThreadByMember($params['members'][0], $params['members'][1]);

        //         return $hasThread;
        //     }
        // }

        // Create Thread.
        $model->setAttrs();
        $model->save();

        // Create ThreadGlobalConfig.
        $thGlobCfg = new \common\models\ThreadGlobalConfig();
        $thGlobCfg->id = $model->id;
        $thGlobCfg->name = \array_key_exists('name', $params) ? 
            $params['name'] : 'new Group()';
        $thGlobCfg->save();

        // Create ThreadMembers
        if(\array_key_exists('members', $params)) {
            foreach ($params['members'] as $member) {
                $thMember = new \common\models\ThreadMember();
                $thMember->setAttrs();

                $thMember->thread_id = $model->id;
                $thMember->member_id = $member['member_id'];
                $thMember->role = $member['role'];
                $thMember->save();
            }

            // Get group admin/creator.
            $mAdmin = array_filter($params['members'], function($m) { return $m['role'] === 'ADMIN'; })[0];
            $mMember = \common\models\Member::findOne($mAdmin['member_id']);

            // Create "%user% created the group %groupname%." action notif.
            if($model->type == 'GROUP') {
                $thMessage = new \common\models\ThreadMessage();
                $thMessage->setAttrs();
                
                $thMessage->thread_id = $model->id;
                $thMessage->member_id = null;
                $thMessage->type = 'NOTIF';
                $thMessage->text = "{$mMember->name} created the group {$thGlobCfg->name}.";
                $thMessage->file = null;
                $thMessage->file_name = null;
                $thMessage->file_type = null;
                $thMessage->created_at = date('Y-m-d H:i:s');
                $thMessage->deleted_by = null;
                $thMessage->save();
            }
        }

        // Return Thread.
        return [
            'id' => $model->id,
            'type' => $model->type,
            'name' => $thGlobCfg->name,
            'thread_members' => $model->threadMembers
        ];
    }
}

