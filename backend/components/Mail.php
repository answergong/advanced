<?php
/**
 * Created by PhpStorm.
 * User: gxz
 * Date: 2018/6/5
 * Time: 15:16
 */

namespace backend\components;

class Mail
{
    /**
     * 发送邮件的方法
     *
     * @author   gongxiangzhu
     * @dateTime 2018/6/5 16:47
     *
     * @param  integer $id
     * @param  string  $name
     *
     * @return mixed
     */
    public static function sendMail($event)
    {
        echo 'I  am sending email<br>';
        echo "email is {$event->email} <br>";
        echo "subject is {$event->subject} <br>";
        echo "content is {$event->content}";
        //真实发送邮件
        $mail = \Yii::$app->mailer->compose();
        $mail->setTo($event->email);
        $mail->setSubject($event->subject);
        $mail->setTextBody($event->content);
        return $mail->send();
    }

}