<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMemberConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-member-config-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, '_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'thread_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'is_muted')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
