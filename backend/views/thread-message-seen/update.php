<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessageSeen */

$this->title = 'Update Thread Message Seen: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Thread Message Seens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => $model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-message-seen-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
