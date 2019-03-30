<?php

use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use yii\widgets\Breadcrumbs;
use dominus77\sweetalert2\Alert;

?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?= Breadcrumbs::widget([
            'homeLink'=> [
                'label' => '',
                'template' => '<li>'.Html::a(FA::icon('home', ['aria-hidden' => 'true'])->fixedWidth(), ['/']).'</li>'
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

    </section>

    <?php if (in_array(Yii::$app->controller->module->id, ['forums', 'rbac', 'user', 'messenger', 'auditlogs'])) :?>

        <section class="content">
            <?= Alert::widget(['useSessionFlash' => true]) ?>

            <div class="box box-primary">
                <div class="box-body">
                    <?= $content; ?>
                </div>
            </div>
        </section>

    <?php else :?>

        <section class="content">
            <?= Alert::widget(['useSessionFlash' => true]) ?>
            <?= $content; ?>
        </section>

    <?php endif ?>

</div>

<footer class="main-footer clearfix">
    <div class="pull-right">
        Copyright &copy; <?= date('Y', time()) ?>. <?= Yii::$app->name ?>. All rights
    reserved.
    </div>
</footer>
