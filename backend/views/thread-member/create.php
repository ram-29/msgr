<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ThreadMember */

$this->title = 'Create Thread Member';
$this->params['breadcrumbs'][] = ['label' => 'Thread Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-member-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
