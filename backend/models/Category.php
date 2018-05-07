<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int    $id   栏目ID
 * @property string $name 栏目名
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 20],
            [['content'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'content' => 'Content'
        ];
    }
    /**
     * 获取分类的枚举值
     *
     * @author   gongxiangzhu
     * @dateTime 2018/5/7 15:08
     *
     * @param  integer $id
     * @param  string  $name
     *
     * @return mixed
     */
    public static function dropDownList(){
        $query = static::find();
        $enums = $query->all();
        return $enums ? ArrayHelper::map($enums, 'id', 'name') : [];

    }
}
