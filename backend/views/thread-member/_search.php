<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'thread_id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'nickname') ?>

    <?= $form->field($model, 'role') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
