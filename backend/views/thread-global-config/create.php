<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ThreadGlobalConfig */

$this->title = 'Create Thread Global Config';
$this->params['breadcrumbs'][] = ['label' => 'Thread Global Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-global-config-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
