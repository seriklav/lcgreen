<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RestoreForm extends Model
{
    public $username;
    public $emailCode;
    public $emailCodeCheck;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'emailCode'], 'required'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            ['emailCode', 'string', 'length' => [5,5]],
            ['emailCode', 'emailCheckCode'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Ваш логин',
            'emailCode' => 'Полученный код Email',
            'verifyCode' => 'Код подтверждения',
        ];
    }
    public function emailCheckCode($attribute)
    {
        if($this->emailCode != $this->emailCodeCheck)
            $this->addError($attribute, 'Неверный Email код!');
    }
}
