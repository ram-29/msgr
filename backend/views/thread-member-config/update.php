<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMemberConfig */

$this->title = 'Update Thread Member Config: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Thread Member Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="thread-member-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
