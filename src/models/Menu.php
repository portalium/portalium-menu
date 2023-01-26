<?php

namespace portalium\menu\models;

use Yii;
use portalium\menu\Module;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property int $id_menu
 * @property string $name
 * @property string $slug
 * @property int $type
 * @property string $date_create
 * @property string $date_update
 */
class Menu extends \yii\db\ActiveRecord
{
    const TYPE = [
        'web' => '1',
        'mobile' => '2'
    ];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => date("Y-m-d H:i:s"),
            ],
            [
                'class' => 'yii\behaviors\BlameableBehavior',
                'createdByAttribute' => 'id_user',
                'updatedByAttribute' => 'id_user',
                'value' => isset(Yii::$app->user) ? Yii::$app->user->id : 0,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%' . Module::$tablePrefix . 'menu}}';
    }

    public function extraFields()
    {
        return ['items']; // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'type'], 'required'],
            [['type', 'id_user'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_menu' => Module::t('Menu ID'),
            'name' => Module::t('Name'),
            'slug' => Module::t('Slug'),
            'type' => Module::t('Type'),
            'id_user' => Module::t('User ID'),
            'date_create' => Module::t('Date Created'),
            'date_update' => Module::t('Date Updated'),
        ];
    }

    public static function getTypes()
    {
        return [
            '1' => Module::t('Web'),
            '2' => Module::t('Mobile')
        ];
    }

    public function getItems()
    {
        //sort by sort
        return $this->hasMany(MenuItem::class, ['id_menu' => 'id_menu'])->orderBy('sort');
    }

    public static function getMenuWithChildren($slug)
    {
        $menu = self::find()->where(['slug' => 'web-menu'])->one();
        $result = [];
        foreach ($menu->items as $item) {
            if (!isset($item->parent)) {
                $result[] = [
                    'title' => isset($item->module) ? Yii::$app->getModule($item->module)->t($item->label) : Module::t($item->label),
                    'id' => $item->id_item,
                    'hasChildren' => $item->hasChildren(),
                    'children' => MenuItem::getMenuTree($item->id_item)
                ];
            }
        }
        return $result;
    }
}
