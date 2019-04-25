<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMember */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Thread Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-member-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'thread_id',
                'member_id',
                'nickname',
                'role',
            ],
        ]) ?>
    </div>
</div>
