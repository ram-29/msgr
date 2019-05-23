<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Getter;
use common\helpers\Logger;

preg_match_all('/(?<=\s|^)[a-z]/i', Yii::$app->name, $x);
$mName = implode('', $x[0]);

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

<header class="main-header">

    <?= Html::a('<span class="logo-mini">'.$mName.'</span><span class="logo-lg">'.Yii::$app->name.'</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Url::to(['/img/user-default.png']) ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs">
                            <?= $mUser; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Url::to(['/img/user-default.png']) ?>" class="img-circle" alt="User Image"/>

                            <p class="text-muted">
                                <?= Yii::$app->user->identity->profile->name ?>
                                <small><?php // Yii::$app->user->identity->username ?></small>
                            </p>
                        </li>
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/user/security/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
