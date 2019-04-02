<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Thread */

$this->title = 'Update Thread: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Threads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => $model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
