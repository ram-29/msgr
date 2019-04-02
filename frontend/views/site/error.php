<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Url;
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;

$img = 'https://www.bizmarquee.com/wp-content/uploads/2018/01/meme-jackie-chan-1280x1024.jpg';

$this->title = $name;
?>
<div class="site-error">

    <div style="display:flex; align-items:center; justify-content:center; height:100vh;">
        <?= Html::img($img, [
            'style' => 'height:50%; width:50%; margin-right:4rem;', 
            'alt' => 'Bigyan ng Jackie Chan',
        ]) ?>

        <div class="site-error-content">
            <h1><?= Html::encode($this->title) ?></h1>
            <h4><?= nl2br(Html::encode($message)) ?></h4>
            <p>
                The above error occurred while the Web server was processing your request. <br/>
                Please contact us if you think this is a server error. Thank you.
            </p>
            <?= Html::a(FA::icon('arrow-left')->fixedWidth().' Go back', Url::to(['/msgr']), [
                'class' => 'btn btn-primary'
            ]) ?>
        </div>
    </div>

</div>
