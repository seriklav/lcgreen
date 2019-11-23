<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;
/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends ActiveReCord
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;
    public $EmailCode;
    public $EmailCodeCheck;
    public $password;
    public $repeat_password;

    public static  function tableName()
    {
        return 'user';
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'sex', 'dateBirth', 'country', 'region', 'city'], 'required'],
            [['name', 'surname', 'sex', 'dateBirth', 'country', 'region', 'city', 'email','father', 'pass','phone','skype','password'], 'safe'],
            [['file'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 30,'checkExtensionByMimeType'=>false],
            [['password','repeat_password'], 'string','length' => [6,50] ],
            ['password', 'compare', 'compareAttribute'=> 'repeat_password', 'message' => "Пароль не совпадает!"],
            ['repeat_password', 'compare', 'compareAttribute'=> 'password', 'message' => "Повтор пароля не совпадает!"],
            ['password', 'emailCheckPass'],
            ['EmailCode', 'string','length' => [5,5]],
            ['email', 'email'],
            ['email', 'emailCheck'],
            ['EmailCode', 'emailCheckCode']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'E-mail',
            'surname' => 'Фамилия',
            'sex' => 'Пол',
            'dateBirth' => 'Дата рождения',
            'country' => 'Страна',
            'region' => 'Регион',
            'city' => 'Город',
            'emailRepeat' => 'Повтор Email',
            'password' => 'Пароль',
            'repeat_password' => 'Повтор пароля',
            'EmailCode' => 'Email код',
            'agree' => 'Согласны с условиями',
            'sponsor' => 'Cпонсор'
        ];
    }
  public function emailCheck($attribute)
    {
        if($this->email != Yii::$app->user->identity->email && !$this->EmailCode)
            $this->addError($attribute, 'Чтобы сменить почту нужно ввести Email код!');
    }
    public function emailCheckPass($attribute)
    {
        if($this->repeat_password && $this->password && !$this->EmailCode)
            $this->addError($attribute, 'Чтобы сменить пароль нужно ввести Email код!');
    }
    public function emailCheckCode($attribute)
    {
        if($this->email != Yii::$app->user->identity->email && $this->EmailCode != $this->EmailCodeCheck || $this->repeat_password && $this->password && $this->EmailCode != $this->EmailCodeCheck)
            $this->addError($attribute, 'Неверный Email код!');
    }
}