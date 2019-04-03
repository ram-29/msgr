<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Thread */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thread-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList([ 'SIMPLE' => 'SIMPLE', 'GROUP' => 'GROUP', ], ['prompt' => '']) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
