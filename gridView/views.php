<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Редактирование организации';
?>
<div class="user-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    //'template' => '',
                    'template' => '<div>{editing}</div> <div class="mt-1">{transfer-municipal}</div> ',
                    'contentOptions' => ['class' => 'action-column'],
                    'buttons' => [
                        'editing' => function ($url, $model, $key) {
                            if(Yii::$app->user->can('admin')){

                                return Html::a(
                                    'Редактирование',
                                    ['editing-plan?id=' . $model['organizationId']],
                                    [
                                        'title' => Yii::t('yii', 'Отредактирование информации по планированию'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-block btn-sm btn-outline-success',
                                    ]
                                );
                            }
                        },
                        'transfer-municipal' => function ($url, $model, $key) {
                            if(Yii::$app->user->can('admin')){
                                return Html::a(
                                    'Муниципальный',
                                    ['editing-municipal?id=' . $model['organizationId']],
                                    [
                                        'title' => Yii::t('yii', 'Сменить муниципальный район'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-block btn-sm btn-primary',
                                    ]
                                );
                            }
                        },
                    ],
                ],
                [
                    'attribute' => 'userId',
                    'label' => 'userId',
                    'value' => function ($model) {
                        return $model['userId'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'userName',
                    'label' => 'Пользователь',
                    'value' => function ($model) {
                        return $model['userName'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'userEmail',
                    'label' => 'Email',
                    'value' => function ($model) {
                        return $model['userEmail'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'organizationTitle',
                    'label' => 'Название организации',
                    'value' => function ($model) {
                        return $model['organizationTitle'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'sch2',
                    'label' => '2 класс',
                    'value' => function ($model) {
                        return $model['sch2'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'sch5',
                    'label' => '5 класс',
                    'value' => function ($model) {
                        return $model['sch5'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'sch10',
                    'label' => '10 класс',
                    'value' => function ($model) {
                        return $model['sch10'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'sch2_again',
                    'label' => '3 класс',
                    'value' => function ($model) {
                        return $model['sch2_again'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'sch5_again',
                    'label' => '6 класс',
                    'value' => function ($model) {
                        return $model['sch5_again'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'sch10_again',
                    'label' => '11 класс',
                    'value' => function ($model) {
                        return $model['sch10_again'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'label' => 'Федеральный округ',
                    'attribute' => 'federal_district_id',
                    'value' => function ($model) {
                        return Yii::$app->myComponent->get_federal_name($model['federal_district_id']);
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                    'filter' => Yii::$app->myComponent->FederalDistrictItems(),
                    'filterInputOptions' => ['prompt' => 'выберите', 'class' => 'form-control', 'id' => null],
                    'visible' => Yii::$app->user->can('admin'),
                ],
                [
                    'attribute' => 'region_id',
                    'label' => 'Регион',
                    'value' => function ($model) {
                        return Yii::$app->myComponent->get_region_name($model['region_id']);
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                    'filter' => $region_items,
                    'filterInputOptions' => ['prompt' => 'выберите', 'class' => 'form-control', 'id' => null],
                    'visible' => Yii::$app->user->can('admin'),
                ],
                [
                    'attribute' => 'municipality_id',
                    'label' => 'Муниципальное',
                    'value' => function ($model) {
                        return Yii::$app->myComponent->get_municipality_name($model['municipality_id']);
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                    'filter' => $municipality_items,
                    'filterInputOptions' => ['prompt' => 'выберите', 'class' => 'form-control', 'id' => null],
                    'visible' => Yii::$app->user->can('admin'),
                ],
                [
                    'attribute' => 'organId',
                    'label' => 'organizationId',
                    'value' => function ($model) {
                        return $model['organizationId'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'number',
                    'label' => 'Номер орган',
                    'value' => function ($model) {
                        return $model['number'];
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'decryptionOrganizationType',
                    'label' => 'Тип орган',
                    'value' => function ($model) {
                        return $model['decryptionOrganizationType'];
                    },
                    'filter' => Yii::$app->myComponent->organizationTypeItems(),
                    'filterInputOptions' => ['prompt' => 'выберите', 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'registration_status',
                    'label' => 'Регистрация',
                    'value' => function ($model) {
                        if ($model['registration_status'] == '3'){
                            return 'самостоятельно';
                        } else {
                            return 'по плану';
                        }

                    },
                    'filter' => ['1'=> 'загруженные', '3'=> 'сами зарегались'],
                    'filterInputOptions' => ['prompt' => 'выберите', 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['class' => 'grid_table_th text-center'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'organizationYear',
                    'label' => 'Год орган',
                    'value' => function ($model) {
                        return $model['organizationYear'];
                    },
                    'filter' => Yii::$app->myComponent->yearItems(),
                    'filterInputOptions' => ['prompt' => 'выберите', 'class' => 'form-control', 'id' => null],
                    'headerOptions' => ['class' => 'grid_table_th text-center'],
                    'contentOptions' => ['class' => ''],
                ],
            ],
        ]); ?>
    </div>
    <br>
    <br>
</div>
