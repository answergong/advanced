<?php
/**
 * Created by PhpStorm.
 * User: gxz
 * Date: 2018/5/7
 * Time: 10:50
 */

namespace backend\components;

use Yii;
use yii\base\Object;

class ThemeControl extends \yii\base\ActionFilter
{
    public function init()
    {
        $switch = intval(Yii::$app->request->get('switch'));
        $theme = $switch ? 'spring' : 'christmas';

        Yii::$app->view->theme = Yii::createObject([
            'class' => 'yii\base\Theme',
            'pathMap' => [
                '@app/views' => [
                    "@app/themes/{$theme}",
                ]
            ],
        ]);
    }
}