<?php
/**
 * Created by PhpStorm.
 * User: gxz
 * Date: 2018/6/5
 * Time: 15:32
 */

namespace backend\controllers;

use backend\components\event\MailEvent;
use yii\web\Controller;

/**
 * 发送邮件
 *
 * @author   gongxiangzhu
 * @dateTime 2018/6/5 15:48
 *
 *
 * @return mixed
 */
class  SendMailController extends Controller
{
    const SEND_MAIL = 'send_mail';

    public function init()
    {
        parent::init();
        //绑定邮件类，当事件触发的时候，调用我们刚刚定义的邮件类Mail
        $this->on(self::SEND_MAIL, ['backend\components\Mail', 'sendMail']);
    }

    public function actionSend()
    {
        //触发邮件事件
        $event = new MailEvent();
        $event->email = 'cwei@mail.ustc.edu.cn';
        $event->subject = '宫相柱爱微微的测试邮件';
        $event->content = '我是测试邮件的正文内容,哈哈,小微微';
        $this->trigger(self::SEND_MAIL, $event);
    }
}