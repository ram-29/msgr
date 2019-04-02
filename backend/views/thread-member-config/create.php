<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ThreadMemberConfig */

$this->title = 'Create Thread Member Config';
$this->params['breadcrumbs'][] = ['label' => 'Thread Member Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-member-config-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
