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
    public function actionIndex()
    {
        $BK_URL = Getter::getUrl();

        // Get messenger members.
        $members = (new Client())
            ->get("{$BK_URL}/api/member")
            ->send()->getData();

        return $this->render('index', compact("members"));
    }

}
