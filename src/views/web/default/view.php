<?php

use yii\helpers\Html;
use portalium\theme\widgets\DetailView;
use portalium\menu\Module;
use portalium\theme\widgets\Panel;
/* @var $this yii\web\View */
/* @var $model portalium\menu\models\Menu */

$fullTitle = $model->name;
$shortTitle = mb_strimwidth($fullTitle, 0, 30, '...');

$this->title = $shortTitle;


$this->params['breadcrumbs'][] = ['label' => Module::t('Menu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => Html::tag('span', $shortTitle, [
        'title' => $fullTitle,
        'style' => 'cursor: pointer;',
    ]),
    'encode' => false,
];
\yii\web\YiiAsset::register($this);
?>
<div class="menu-view">

    <?php Panel::begin([
        'title' => Module::t('Menu'),
        'actions' => [
            Html::a(Module::t('Update'), ['update', 'id' => $model->id_menu], ['class' => 'btn btn-primary']),
            Html::a(Module::t('Delete'), ['delete', 'id' => $model->id_menu], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Module::t('Are you sure you want to delete this item?'),
                    'method' => 'post',
                ]
            ]),
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_menu',
            'name',
            'slug',
            'date_create',
            'date_update',
        ],
    ]) ?>
    <?php Panel::end() ?>

</div>