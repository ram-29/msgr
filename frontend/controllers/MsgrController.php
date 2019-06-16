<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

use common\models\Member;

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
     * @return mixed
     */
    public function actionIndex($u = 123)
    {
        // 312615cc-96f1-4e0f-9da5-ef482e72d889 : Patricia Lesback
        // f9c159af-6f58-441d-b26f-a6ab4b497eaf : Maria Powell
        // 4f0da359-0f14-4913-93af-0a648cdf3cf9 : Nicholas Runolfsdottir
        
        // 0bc08464-87b4-4c6a-afea-6960bf90b02b : Weissnat Kurtis
        // 17626558-9d93-420d-9b3e-4234a80859dc : Howell Ervin

        $M_ID = '0bc08464-87b4-4c6a-afea-6960bf90b02b';
        $M_NAME = 'Weissnat Kurtis';
        $PUB_VAPID_KEY = 'BM_rgVMC88LMFjWGiTQOHVKUF4W7An0fT_2k9Z60AQYxH656dcRwyeFQ7vZRo6sGNPyQlNKksPHdgvNZWWuqjTQ';

        $BK_URL = Getter::getUrl();

        // Get messenger members.
        $members = (new Client())
            ->get("{$BK_URL}/api/member")
            ->send()->getData();

        return $this->render('index', compact("members", "M_ID", "M_NAME", "PUB_VAPID_KEY"));
    }

    /**
     * Auto generates member.
     * @return void
     */
    public function actionGenerate()
    {
        $response = (new Client())
            ->get('https://jsonplaceholder.typicode.com/users')
            ->send();

        $mPersons = array_map(function($x){
            $y = ['.', 'Mrs', 'Miss', 'Ms', 'Master', 'Dr', 'Mr'];
            $z = explode(' ', trim(str_replace($y, '', $x['name'])));
            $a = ['President', 'Vice-president', 'Secretary', 'Treasurer'];
            $s = ['M', 'F'];

            return [
                'name' => "{$z[1]} {$z[0]}",
                'sex' => $s[array_rand($s)],
                'status' => 'ACTIVE',
                'joined_at' => date('Y-m-d H:i:s')
            ];
        }, $response->getData());

        foreach($mPersons as $p) {
            $mMember = new Member();

            $mMember->setAttrs();

            $mMember->name = $p['name'];
            $mMember->sex = $p['sex'];
            $mMember->status = $p['status'];
            $mMember->save();
        }

        Yii::$app->response->format = 'json';
        return [ 'success' => true ];
    }

}
