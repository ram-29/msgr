<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

use common\helpers\Logger;
use common\helpers\Getter;

/**
 * Msgr controller
 */
class MsgrController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($u = 123)
    {
        // 312615cc-96f1-4e0f-9da5-ef482e72d889 : Patricia Lesback

        // $M_ID = 'f9c159af-6f58-441d-b26f-a6ab4b497eaf';
        $M_ID = '312615cc-96f1-4e0f-9da5-ef482e72d889';

        $M_NAME = 'Maria Powell';

        $BK_URL = Getter::getUrl();

        // Get messenger members.
        $members = (new Client())
            ->get("{$BK_URL}/api/member")
            ->send()->getData();

        return $this->render('index', compact("members", "M_ID", "M_NAME"));
    }

}
