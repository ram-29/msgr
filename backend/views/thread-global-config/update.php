<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadGlobalConfig */

$this->title = 'Update Thread Global Config: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Thread Global Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-global-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
