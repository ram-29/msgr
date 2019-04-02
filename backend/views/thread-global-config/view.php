<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadGlobalConfig */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Thread Global Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-global-config-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->_id], [
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
                '_id',
                'name',
                'color',
                'emoji',
                'picx',
            ],
        ]) ?>
    </div>
</div>
