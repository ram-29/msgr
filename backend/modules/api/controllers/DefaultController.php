<?php

namespace backend\modules\api\controllers;

/**
 * Default controller for the `Api` module
 */
class DefaultController extends \yii\web\Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
