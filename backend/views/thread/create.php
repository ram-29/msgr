<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Thread */

$this->title = 'Create Thread';
$this->params['breadcrumbs'][] = ['label' => 'Threads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
