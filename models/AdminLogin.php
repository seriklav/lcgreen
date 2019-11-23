<?php
namespace app\models;

use yii\base\Model;
/**
 * UploadForm is the model behind the upload form.
 */
class AdminLogin extends Model
{
    public $username;
    public $password;
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string','length' => [3,50] ]
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль'
        ];
    }
}