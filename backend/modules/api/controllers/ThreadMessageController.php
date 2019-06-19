<?php

namespace backend\modules\api\controllers;

use Yii;


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
        ];

		return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['upload-image']);
        
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

        // Return model.
        // $model->recipients = $mThMem;
        $data = [
            'thread_id' => $model->thread_id,
            'text' => $model->text,
            'member_id' => $model->member_id,
            'id' => $model->id,
            'recipients' => $mThMem
        ];

        return $data;
    }


    public function actionUploadImage() 
    {
        // Initalize a model.
        $model = new \common\models\ThreadMessage();
        $params = \Yii::$app->request->getBodyParams();
        $model->load($params, '');

        // Set attributes.
        $model->setAttrs();

        $frontend_root = Yii::getAlias('@frontend/web/files');

        if (!file_exists($frontend_root.'/'.$model->thread_id . '/images/')) {
            mkdir($frontend_root.'/'.$model->thread_id. '/images/', 0777, true);
        } 

        $uploads = \yii\web\UploadedFile::getInstancesByName('file');

        if (empty($uploads)){
            return false;
        } 
        
        $path = $frontend_root.'/'.$model->thread_id . '/' . 'images/';

        foreach ($uploads as $upload) {

            $filename =  time() .'_'. $upload->name;
            
            $directory_file = $path . $filename;

            $model->file_name = '../frontend/web/files/'.$model->thread_id.'/images/' . $filename;
            $model->file_type = $upload->type;
            $model->file = $filename;

            $upload->saveAs($directory_file);  

            // Save & return.
            $model->save(); 
           
        }

        return $model;        
    }

     public function actionUploadDocuments() 
    {
        // Initalize a model.
        $model = new \common\models\ThreadMessage();
        $params = \Yii::$app->request->getBodyParams();
        $model->load($params, '');

        // Set attributes.
        $model->setAttrs();

        $frontend_root = Yii::getAlias('@frontend/web/files');

        if (!file_exists($frontend_root.'/'.$model->thread_id . '/documents/')) {
            mkdir($frontend_root.'/'.$model->thread_id. '/documents/', 0777, true);
        } 

        $uploads = \yii\web\UploadedFile::getInstancesByName('file');

        if (empty($uploads)){
            return false;
        } 
        
        $path = $frontend_root.'/'.$model->thread_id . '/' . 'documents/';

        foreach ($uploads as $upload) {

            $filename =  time() .'_'. $upload->name;
            
            $directory_file = $path . $filename;

            $model->file_name = '../frontend/web/files/'.$model->thread_id.'/documents/' . $filename;
            $model->file_type = $upload->type;
            $model->file = $upload->name;

            $upload->saveAs($directory_file);  

            // Save & return.
            $model->save(); 
           
        }

        return $model;        
    }
   
}
