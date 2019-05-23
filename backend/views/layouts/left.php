<?php

use yii\helpers\Url;
use common\helpers\Getter;
use yii\helpers\ArrayHelper;

$mIdentity = Yii::$app->user->identity ?: [];
if(empty(Yii::$app->user->identity)) {
    return Yii::$app->response->redirect(['user/login'])->send();
} else {
    $mUser = isset($mIdentity->userinfo) ?
        Getter::getModifiedName(Yii::$app->user->identity->userinfo) :
        Yii::$app->user->identity->profile->name;
}

/* @var $this \yii\web\View */
/* @var $content string */
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Url::to(['/img/user-default.png']) ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $mUser; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form> -->
        <!-- /.search form -->

        <?php
            $exp = (strpos(Yii::getAlias('@webroot'), 'backend') !== false) && 
                (ArrayHelper::keyExists('Administrator', Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())));


            $menuItems = [
                ['label' => 'Main Navigation', 'options' => ['class' => 'header']],
                [
                    'label' => 'Member', 'icon' => 'user', 'url' => ['/member'],
                    'active' => (strpos(Yii::$app->request->url, '/member') !== false) ? true : false
                ],
                [
                    'label' => 'Thread', 'icon' => 'list', 'url' => ['/thread'],
                    'active' => (strpos(Yii::$app->request->url, '/thread') !== false) ? true : false
                ],

                ['label' => 'System Tools', 'options' => ['class' => 'header']],
                [
                    'label' => 'User Management', 'icon' => 'wrench', 'url' => ['/user/admin'], 
                    'visible' => !($exp), 
                    'active' => 
                        (strpos(Yii::$app->request->pathInfo, 'user') !== false) ||
                        (strpos(Yii::$app->request->pathInfo, 'rbac') !== false) ? true : false
                ],
            ];
    
            if (YII_ENV == 'dev') {
                $debugItems = [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                ];

                foreach ($debugItems as $d) {
                    array_push($menuItems, $d);
                }
            }

        ?>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => $menuItems
        ]) ?>

    </section>

</aside>
