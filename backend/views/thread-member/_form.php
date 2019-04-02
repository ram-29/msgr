<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ThreadMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-member-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, '_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'thread_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'member_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'role')->dropDownList([ 'ADMIN' => 'ADMIN', 'MEMBER' => 'MEMBER', ], ['prompt' => '']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
