<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessage */

$this->title = 'Create Thread Message';
$this->params['breadcrumbs'][] = ['label' => 'Thread Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-message-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
