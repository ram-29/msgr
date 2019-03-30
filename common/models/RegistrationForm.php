<?php

namespace common\models;

use Yii;
use dektrium\user\models\User;
use dektrium\user\models\Profile;
use dektrium\user\models\RegistrationForm as BaseRegistrationForm;

/**
 * RegistrationForm
 */
class RegistrationForm extends BaseRegistrationForm
{
    public $name;

    public function rules()
    {
        $rules = parent::rules();

        // Overriden Rules
        $rules['usernameLength'] = ['username', 'string', 'length' => [ 6, 25 ]];

        // name rules
        $rules['nameTrim'] = ['name', 'trim'];
        $rules['nameRequired'] = ['name', 'required'];
        $rules['nameLength'] = ['name', 'string', 'max' => 50];

        return $rules;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        
        $labels['name'] = Yii::t('user', 'Full Name');

        return $labels;
    }

    public function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);

        $profile = Yii::createObject(Profile::className());
        $profile->setAttributes(['name' => $this->name]);

        $user->setProfile($profile);
    }
}
