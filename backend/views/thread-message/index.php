<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ThreadMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Thread Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-message-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('Create Thread Message', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'thread_id',
                'member_id',
                'text:ntext',
                'file:ntext',
                // 'file_name:ntext',
                // 'file_type:ntext',
                // 'created_at',
                // 'deleted_by:ntext',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
