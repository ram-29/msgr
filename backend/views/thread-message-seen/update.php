<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessageSeen */

$this->title = 'Update Thread Message Seen: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Thread Message Seens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-message-seen-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
