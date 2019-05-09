<?php

/* @var $this \yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use rmrevin\yii\fontawesome\FA;

use common\helpers\Getter;

$this->title = Yii::$app->name;
?>
<div class="msgr-index">

    <div class="msgr-sidebar">

        <div class="msgr-sidebar-header">
            <?= Html::button(
                FA::icon('cog')->size(FA::SIZE_LARGE)->fixedWidth(), [
                    'class' => 'btn btn-default btn-sm',
                    'id' => 'btn-header-setting'
                ])
            ?>

            <h4><?= Yii::$app->name ?></h4>

            <?= Html::button(
                FA::icon('pencil-square-o')->size(FA::SIZE_LARGE)->fixedWidth(), [
                    'class' => 'btn btn-default btn-sm',
                    'id' => 'btn-header-write-message'
                ])
            ?>
        </div>

        <div class="msgr-sidebar-search">
            <div class="input-group" style="width:100%;">
                <?= Html::input('text', 'sidebar-search', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Search a name ..'
                ]) ?>
            </div>
        </div>

        <div class="msgr-sidebar-list"></div>

    </div>

    <div class="msgr-main">
        
        <div class="msgr-main-header">
            <div class="msgr-main-header-details">
                <h4></h4>
                <!-- <p>Active 2m ago</p> -->
            </div>
            <?= Html::button(FA::icon('info')->size(FA::SIZE_LARGE)->fixedWidth(), [
                'class' => 'btn btn-default btn-sm'
            ]) ?>
        </div>

        <div class="msgr-main-content">
            <div class="msgr-main-content-chatbox">
                <div class="msgr-main-content-chatbox-header">
                    <img class="img-circle" src="">
                    <div class="msgr-main-content-chatbox-header-details">
                        <h4></h4>
                        <!-- <p>Software Engineer at Google</p> -->
                    </div>
                </div>

                <div class="msgr-main-content-chatbox-list"></div>
                
                <div class="msgr-main-content-chatbox-input">
                    <div class="input-group" style="width:100%; visibility:hidden;">
                        <?= Html::textarea('content-chatbox-input-box', null, [
                            'id' => 'content-chatbox-input-box',
                            'placeholder' => 'Type a message ..',
                        ]) ?>
                    </div>
                    <div class="msgr-main-content-chatbox-input-tools" style="visibility:hidden;">
                        <div class="msgr-main-content-chatbox-input-tools-left">
                            <?= Html::button(FA::icon('picture-o')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                'class' => 'btn btn-default btn-sm',
                                'id' => 'btn-chatbox-photo',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-html' => 'true',
                                'title' => 'Attach a photo',
                                'data-conn' => 'null',
                                'onclick' => new JsExpression('initUpload(this, "IMG")'),
                            ]) ?>
                            <?= Html::button(FA::icon('paperclip')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                'class' => 'btn btn-default btn-sm',
                                'id' => 'btn-chatbox-file',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-html' => 'true',
                                'title' => 'Attach a file',
                                'data-conn' => 'null',
                                'onclick' => new JsExpression('initUpload(this, "FILE")'),
                            ]) ?>
                            <?= Html::button(FA::icon('smile-o')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                'class' => 'btn btn-default btn-sm',
                                'id' => 'btn-chatbox-emoji',
                            ]) ?>
                        </div>

                        <div class="msgr-main-content-chatbox-input-tools-right">
                            <?= Html::button(FA::icon('paper-plane')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                'class' => 'btn btn-default btn-sm',
                                'id' => 'btn-chatbox-send',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-html' => 'true',
                                'title' => 'Press Enter to send<br/>Press Shift+Enter to add a new paragraph'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="msgr-main-content-tools">
                <div class="msgr-main-content-tools-user">
                    <div class="msgr-main-content-tools-user-header">
                        <?= FA::icon('user')->size(FA::SIZE_LARGE)->fixedWidth() ?>
                        <h4>Employee List</h4>
                    </div>

                    <div class="msgr-main-content-tools-user-list">
                        <?php foreach($members as $i => $member) :?>
                            <?php $img = $member['sex'] == 'M' ? "1" : "2"; ?>

                            <div class="msgr-main-content-tools-user-list-item">
                                <div class="msgr-main-content-tools-user-list-item-content">
                                    <?= Html::img("@web/img/{$img}.png", [
                                        'class' => 'img-circle',
                                        'alt' => 'User image'
                                    ]) ?>

                                    <div class="msgr-main-content-tools-user-list-item-content-details">
                                        <h4 data-id="<?= $member['id'] ?>"><?= $member['name'] ?></h4>
                                        <!-- <p>Associate Software Engineer</p> -->
                                    </div>
                                </div>

                                <div class="msgr-main-content-tools-user-list-item-tools-right">
                                    <?= Html::button(FA::icon('comments-o')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                        'class' => 'btn btn-default btn-sm',
                                        'id' => 'btn-user-chat',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'left',
                                        'data-html' => 'true',
                                        'title' => 'Chat this person'
                                    ]) ?>
                                    <?= Html::button(FA::icon('user-plus')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                        'class' => 'btn btn-default btn-sm',
                                        'id' => 'btn-user-group',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'left',
                                        'data-html' => 'true',
                                        'title' => 'Add this person to a group',
                                        'onclick' => new JsExpression("groupConfirm(this)")
                                    ]) ?>
                                </div>
                            </div>

                        <?php endforeach ?>
                    </div>
                </div>

                <div class="msgr-main-content-tools-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
                        <li role="presentation"><a href="#files" aria-controls="files" role="tab" data-toggle="tab">Files</a></li>
                        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="images">
                            <div id="nanogallery2-image"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="files">Files</div>
                        <div role="tabpanel" class="tab-pane fade" id="settings">Settings</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php
    $this->registerJsFile(
        Url::to(['/js/app.min.js']), [
        'depends' => 'frontend\assets\AppAsset'
    ]);
?>
