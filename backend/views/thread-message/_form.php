<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-message-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'thread_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'file')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'deleted_by')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
