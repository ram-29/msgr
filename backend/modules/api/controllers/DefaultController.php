<?php

namespace backend\modules\api\controllers;

/**
 * Default controller for the `Api` module
 */
class DefaultController extends \yii\web\Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
