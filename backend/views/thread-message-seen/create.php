<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ThreadMessageSeen */

$this->title = 'Create Thread Message Seen';
$this->params['breadcrumbs'][] = ['label' => 'Thread Message Seens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-message-seen-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
