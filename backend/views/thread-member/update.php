<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMember */

$this->title = 'Update Thread Member: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Thread Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => $model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-member-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
