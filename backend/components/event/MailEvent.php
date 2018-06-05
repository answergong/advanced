<?php
/**
 * Created by PhpStorm.
 * User: gxz
 * Date: 2018/6/5
 * Time: 16:37
 */

namespace backend\components\event;

class MailEvent extends \yii\base\Event
{
    public $email;

    public $subject;

    public $content;

}