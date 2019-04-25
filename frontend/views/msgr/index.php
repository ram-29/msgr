<?php

/* @var $this \yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
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

        <div class="msgr-sidebar-list">
            <?php foreach(range(0, 15) as $i) :?>

                <?php $idx = array_rand([1, 2, 3]) + 1 ?>

                <div class="msgr-sidebar-list-item">
                    <div class="msgr-sidebar-list-item-content">
                        <?= Html::img("@web/img/{$idx}.png", [
                            'class' => 'img-circle', 
                            'alt' => 'User image'
                        ]) ?>
                        <div class="msgr-sidebar-list-item-content-details">
                            <h4>John Doe</h4>
                            <p><?= Getter::getShortenedText('Lorem ipsum dolor sit amet.') ?></p>
                        </div>
                    </div>

                    <div class="msgr-sidebar-list-item-settings">
                        <span>Mon</span>
                        <?= Html::button(FA::icon('cog')->fixedWidth(), [
                            'class' => 'btn btn-default btn-sm',
                            'id' => 'btn-list-item-setting'
                        ]) ?>
                    </div>
                </div>

            <?php endforeach ?>
        </div>

    </div>

    <div class="msgr-main">
        
        <div class="msgr-main-header">
            <div class="msgr-main-header-details">
                <h4>John Doe</h4>
                <p>Active 2m ago</p>
            </div>
            <?= Html::button(FA::icon('info')->size(FA::SIZE_LARGE)->fixedWidth(), [
                'class' => 'btn btn-default btn-sm'
            ]) ?>
        </div>

        <div class="msgr-main-content">
            <div class="msgr-main-content-chatbox">
                <div class="msgr-main-content-chatbox-header">
                    <?= Html::img("@web/img/1.png", [
                        'class' => 'img-circle', 
                        'alt' => 'User image'
                    ]) ?>
                    <div class="msgr-main-content-chatbox-header-details">
                        <h4>John Doe</h4>
                        <p>Software Engineer at Google</p>
                    </div>
                </div>

                <div class="msgr-main-content-chatbox-list">
                    <?php foreach (range(0, 15) as $i) :?>
                        <?php $idx = array_rand([1, 2]) + 1 ?>

                        <div class="msgr-main-content-chatbox-list-item">
                            <span>Mar 16, 2019, 7:27 PM</span>

                            <div class="msgr-main-content-chatbox-list-item-details <?= $idx == 1 ? "owner" : "" ?>">
                                <?= Html::img("@web/img/1.png", [ 
                                    'class' => 'img-circle', 
                                    'alt' => 'User image',
                                ]) ?>

                                <div class="msgr-main-content-chatbox-list-item-details-content">
                                    <?php if($idx == 1) :?>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing.</p>
                                        <p>Delectus laborum recusandae dolores inventore, ratione magnam.</p>
                                        <p>Eveniet sit ut, veniam accusantium deserunt dicta impedit quisquam suscipit vel voluptatibus temporibus dolorem autem vitae quidem magnam, dolore minus dignissimos soluta repudiandae, nisi at a consequuntur? Accusantium, fugiat dolorem?</p>
                                    <?php else :?>
                                        <p>Magnam fugiat iste totam, ut saepe ex.</p>
                                        <p>Pariatur, rem tempore numquam harum vitae nisi!</p>
                                        <p>Nihil aut quasi, omnis voluptatem corporis eum qui illo esse blanditiis beatae, ullam fugiat voluptatibus. Nulla voluptates excepturi nesciunt voluptate cum officiis incidunt ad aperiam, reiciendis reprehenderit quos tempore eaque?</p>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach ?>
                </div>
                
                <div class="msgr-main-content-chatbox-input">
                    <div class="input-group" style="width:100%">
                        <?= Html::textarea('content-chatbox-input-box', null, [
                            'id' => 'content-chatbox-input-box',
                            'placeholder' => 'Type a message ..',
                        ]) ?>
                    </div>

                    <div class="msgr-main-content-chatbox-input-tools">
                        <div class="msgr-main-content-chatbox-input-tools-left">
                            <?= Html::button(FA::icon('picture-o')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                'class' => 'btn btn-default btn-sm',
                                'id' => 'btn-chatbox-photo',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-html' => 'true',
                                'title' => 'Attach a photo'
                            ]) ?>
                            <?= Html::button(FA::icon('paperclip')->size(FA::SIZE_LARGE)->fixedWidth(), [
                                'class' => 'btn btn-default btn-sm',
                                'id' => 'btn-chatbox-file',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-html' => 'true',
                                'title' => 'Attach a file'
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
                        <?php foreach(range(0, 15) as $i) :?>

                            <?php $idx = array_rand([1, 2, 3]) + 1 ?>

                            <div class="msgr-main-content-tools-user-list-item">
                                <div class="msgr-main-content-tools-user-list-item-content">
                                    <?= Html::img("@web/img/{$idx}.png", [
                                        'class' => 'img-circle', 
                                        'alt' => 'User image'
                                    ]) ?>

                                    <div class="msgr-main-content-tools-user-list-item-content-details">
                                        <h4>John Doe</h4>
                                        <p>Associate Software Engineer</p>
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
                                        'title' => 'Add this person to a group'
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
                        <div role="tabpanel" class="tab-pane fade in active" id="images">Images</div>
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
