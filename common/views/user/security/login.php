<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dektrium\user\widgets\Connect;
use common\helpers\Getter;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('user', 'Log In');
$this->params['breadcrumbs'][] = $this->title;

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<?php if(strpos(Yii::getAlias('@webroot'), 'backend') !== false) :?>

    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b><?= Yii::$app->name ?></b></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Log in to start your session</p>

            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

            <?= $form
                ->field($model, 'login', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('login')]) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
            ?>

            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                </div>
                <div class="col-xs-4">
                    <?= Html::submitButton('Log in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <?= Connect::widget([
                'baseAuthUrl' => ['/user/security/auth'],
            ]) ?>

        </div>
    </div>

<?php else :?>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                    ]); ?>
                    
                        <?= $form->field($model, 'login') ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?= Html::submitButton(Yii::t('user', 'Log In'), ['class' => 'btn btn-success btn-block']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php endif ?>