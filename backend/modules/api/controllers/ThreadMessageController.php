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
        
        // Save & return.
        $model->save();

        return $model;
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

            $model->file_name = '/web/files/'.$model->thread_id.'/documents/' . $filename;
            $model->file_type = $upload->type;
            $model->file = Yii::$app->homeUrl;

            $upload->saveAs($directory_file);  

            // Save & return.
            $model->save(); 
           
        }

        return $model;        
    }
   
}
