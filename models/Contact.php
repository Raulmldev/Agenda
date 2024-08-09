<?php
namespace app\models;

use yii\base\Model;

class Contact extends Model
{
    public $name;
    public $phone;
    public $email;

    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            ['email', 'email'],
        ];
    }
}
?>