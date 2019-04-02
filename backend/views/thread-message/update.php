<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessage */

$this->title = 'Update Thread Message: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Thread Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => $model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-message-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
