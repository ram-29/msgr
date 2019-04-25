<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessageSeen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-message-seen-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'thread_message_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'seen_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
