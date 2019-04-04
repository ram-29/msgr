<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use dominus77\sweetalert2\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?php if (in_array(Yii::$app->controller->module->id, ['forums', 'rbac', 'user', 'messenger', 'auditlogs', 'utility'])) :?>

        <style> html, body { overflow-y: auto !important; } </style>

        <div class="container">
            <div class="row">
                <div class="col-md-12" style="margin-top:5%;">
                    <?= Alert::widget(['useSessionFlash' => true]) ?>
                    
                    <h1><?= ucwords(Yii::$app->controller->module->id) ?></h1>
                    <div class="well"><?= $content; ?></div>
                </div>
            </div>
        </div>

    <?php else :?>

        <?= Alert::widget(['useSessionFlash' => true]) ?>
        <?= $content ?>

    <?php endif ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
