<?php

use common\models\Kids;
use common\models\Medicals;
use common\models\Menus;
use common\models\Municipality;
use common\models\TypeLager;
use common\models\User;
use common\models\FederalDistrict;
use common\models\Region;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use common\models\Organization;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="users-report-form container"><h2 align="center">Отчет по регистрациям (ПС "Оценка эффективности
            оздоровления")</h2>
        <?php
        $form = ActiveForm::begin();
        echo $form
            ->field($model, 'federal_district_id', $two_column)
            ->dropDownList($federal_district_item,
                [
                    'class' => 'form-control col-8'
                ]);
        echo $form
            ->field($model, 'region_id', $two_column)
            ->dropDownList($region_item,
                [
                    'class' => 'form-control col-8'
                ]);
        echo $form
            ->field($model, 'type_lager_id', $two_column)
            ->dropDownList($type_lager_item,
                [
                    'class' => 'form-control col-8'
                ]); ?>
        <div class="text-center mt-2"><i>ЕСЛИ Вы хотите включить орагнизации которые не начинали работать отчет может
                долго считать, рекомендуется выгружать по федеральному округу:</i></div>
        <? echo $form
            ->field($model, 'inn', $two_column)
            ->dropDownList($type_lager_item2,
                [
                    'class' => 'form-control col-8'
                ])->label('Тип выгрузки'); ?>

        <div class="text-center mt-2"><i>ЕСЛИ Вы хотите посмотреть списком организации по регионам:</i></div>
        <? echo $form
            ->field($model, 'status', $two_column)
            ->dropDownList($type_lager_item3,
                [
                    'class' => 'form-control col-8'
                ])->label('Показать/скрыть лагеря');
        ?>
        <div class="form-group row">
            <?= Html::submitButton('Показать', ['class' => 'btn btn-success form-control col-12 mt-3']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<? if ($result['school']) { ?>
    <input type="button" class="btn btn-warning btn-block table2excel mb-3 mt-3"
           title="Вы можете скачать в формате Excel" value="Скачать в Excel" id="pechat222">
    <div class="table-responsive">
        <table id="tableId2" class="table table-bordered table-sm table2excel_with_colors">
            <thead>
            <tr>
                <th class="text-center" rowspan="3" colspan="1">№</th>
                <th class="text-center" rowspan="3" colspan="1">Федеральный округ</th>
                <th class="text-center" rowspan="3" colspan="1">Субъект Федерации</th>
                <th class="text-center" rowspan="3" colspan="1">Название организации / количество школ</th>
                <th class="text-center" rowspan="3" colspan="1">Внесено меню всего</th>
                <th class="text-center" rowspan="3" colspan="1">Дата регистрации</th>
                <th class="text-center" rowspan="3" colspan="1">Дата входа последняя</th>
                <th class="text-center" rowspan="2" colspan="2">Лагерь</th>
                <th class="text-center" rowspan="2" colspan="4">Тип лагеря</th>
                <th class="text-center" rowspan="2" colspan="3">Все сезоны / все смены</th>
                <th class="text-center" colspan="18">Лето</th>

            </tr>
            <tr>
                <th class="text-center" colspan="3">1 смена</th>
                <th class="text-center" colspan="3">2 смена</th>
                <th class="text-center" colspan="3">3 смена</th>
                <th class="text-center" colspan="3">4 смена</th>
                <th class="text-center" colspan="3">5 смена</th>
                <th class="text-center" colspan="3">6 смена</th>

            </tr>
            <tr>
                <th class="text-center" colspan="1">Работает</th>
                <th class="text-center" colspan="1">Не работает</th>

                <th class="text-center" rowspan="1" colspan="1">Стационарно-загородная организация отдыха и
                    оздоровления
                </th>
                <th class="text-center" rowspan="1" colspan="1">Организация санаторного типа</th>
                <th class="text-center" rowspan="1" colspan="1">Организация с дневным пребыванием</th>
                <th class="text-center" rowspan="1" colspan="1">Палаточный лагерь</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>

                <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
                <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>
                <!-- <?php
                /*        for ($i = 0; $i < 6; $i++)
                        {
                            echo '<th class="text-center" rowspan="1" colspan="1">Внесено детей</th>';
                            echo '<th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>';
                            echo '<th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>';
                        }
                        */ ?>
        <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
        <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
        <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>-->
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($result['school'] as $keyOkrug => $rowOkrug) {
                //$keyOkrug - id федерального округа
                ksort($rowOkrug);
                foreach ($rowOkrug as $keyRegion => $rowRegion) {
                    foreach ($rowRegion as $key => $row) { //школа
                        ?>
                        <? if ($model->status == 2) {
                            ?>

                            <tr>
                                <td class="text-center"><?= Yii::$app->myComponent->get_federal_name($row['federal_district_id']) ?></td>
                                <td class="text-center"><?= Yii::$app->myComponent->get_region_name($row['region_id']) ?></td>
                                <td class="text-center"><?= $key ?></td>
                                <td class="text-center"><?= $row['countMenus']; ?></td>
                                <td class="text-center"><?= $row['created_at']; ?></td>
                                <td class="text-center"><?= ($row['autorization_statistic_created_at']) ? $row['autorization_statistic_created_at'] : '-' ?></td>
                                <!--Стационарно-загородная организация отдыха и оздоровления-->
                                <td class="text-center"><?= $row['countSchool']; ?></td>
                                <td class="text-center"><?= $row['countNoSchool']; ?></td>

                                <td class="text-center"><?= ($row['nameType_lager'] === 'Стационарно-загородная организация отдыха и оздоровления') ? '1' : '0' ?></td>
                                <!--Стационарно-загородная организация отдыха и оздоровления-->
                                <td class="text-center"><?= ($row['nameType_lager'] === 'Организация санаторного типа') ? '1' : '0' ?></td>
                                <!--Организация санаторного типа-->
                                <td class="text-center"><?= ($row['nameType_lager'] === 'Организация с дневным пребыванием') ? '1' : '0' ?></td>
                                <!--Организация с дневным пребыванием-->
                                <td class="text-center"><?= ($row['nameType_lager'] === 'Палаточный лагерь') ? '1' : '0' ?></td>
                                <!--Палаточный лагерь-->

                                <td class="text-center"><?= ($row['totalKids']) ? $row['totalKids'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalMedicalsNumber_med1']) ? $row['totalMedicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalMedicalsNumber_med2']) ? $row['totalMedicalsNumber_med2'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCamp'][2][1]) ? $row['totalChildrenChangeCamp'][2][1] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1']) ? $row['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2']) ? $row['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCamp'][2][2]) ? $row['totalChildrenChangeCamp'][2][2] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1']) ? $row['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2']) ? $row['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCamp'][2][3]) ? $row['totalChildrenChangeCamp'][2][3] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1']) ? $row['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2']) ? $row['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCamp'][2][4]) ? $row['totalChildrenChangeCamp'][2][4] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1']) ? $row['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2']) ? $row['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCamp'][2][5]) ? $row['totalChildrenChangeCamp'][2][5] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1']) ? $row['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2']) ? $row['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCamp'][2][6]) ? $row['totalChildrenChangeCamp'][2][6] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1']) ? $row['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1'] : '0' ?></td>
                                <td class="text-center"><?= ($row['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2']) ? $row['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2'] : '0' ?></td>

                            </tr>
                            <?
                        } ?>
                    <? } ?>
                    <tr>
                        <td class="text-center bg-light"
                            colspan="2"><?= Yii::$app->myComponent->get_federal_name($keyOkrug) ?></td>
                        <td class="text-center bg-light"><?= Yii::$app->myComponent->get_region_name($keyRegion) ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalKids']) ? $result['region'][$keyRegion]['totalKids'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalMedicalsNumber_med1']) ? $result['region'][$keyRegion]['totalMedicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalMedicalsNumber_med2']) ? $result['region'][$keyRegion]['totalMedicalsNumber_med2'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalChildrenChangeCamp'][2][1]) ? $result['region'][$keyRegion]['totalChildrenChangeCamp'][2][1] : '0' ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalChildrenChangeCamp'][2][2]) ? $result['region'][$keyRegion]['totalChildrenChangeCamp'][2][2] : '0' ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalChildrenChangeCamp'][2][3]) ? $result['region'][$keyRegion]['totalChildrenChangeCamp'][2][3] : '0' ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalChildrenChangeCamp'][2][4]) ? $result['region'][$keyRegion]['totalChildrenChangeCamp'][2][4] : '0' ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalChildrenChangeCamp'][2][5]) ? $result['region'][$keyRegion]['totalChildrenChangeCamp'][2][5] : '0' ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2'] : 0 ?></td>
                        <td class="text-center bg-light"><?= ($result['region'][$keyRegion]['totalChildrenChangeCamp'][2][6]) ? $result['region'][$keyRegion]['totalChildrenChangeCamp'][2][6] : '0' ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1'] : 0 ?></td>
                        <td class="text-center bg-light "><?= ($result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2']) ? $result['region'][$keyRegion]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2'] : 0 ?></td>

                    </tr>
                <? } ?>
                <tr>
                    <td class="text-center bg-secondary"
                        colspan="3"><?= Yii::$app->myComponent->get_federal_name($keyOkrug) ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalKids']) ? $result['okrug'][$keyOkrug]['totalKids'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalMedicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalMedicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalMedicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalMedicalsNumber_med2'] : 0 ?></td>

                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][1]) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][1] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][2]) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][2] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][3]) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][3] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][4]) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][4] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][5]) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][5] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][6]) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCamp'][2][6] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1'] : 0 ?></td>
                    <td class="text-center bg-secondary"><?= ($result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2']) ? $result['okrug'][$keyOkrug]['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2'] : 0 ?></td>

                </tr>
            <? } ?>
            <tr>
                <td class="text-center bg-info" colspan="3">ИТОГ</td>
                <td class="text-center bg-info"><?= ($result['itog']['totalKids']) ? $result['itog']['totalKids'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalMedicalsNumber_med1']) ? $result['itog']['totalMedicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalMedicalsNumber_med2']) ? $result['itog']['totalMedicalsNumber_med2'] : 0 ?></td>

                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCamp'][2][1]) ? $result['itog']['totalChildrenChangeCamp'][2][1] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1']) ? $result['itog']['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2']) ? $result['itog']['totalChildrenChangeCampMed'][2][1]['medicalsNumber_med2'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCamp'][2][2]) ? $result['itog']['totalChildrenChangeCamp'][2][2] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1']) ? $result['itog']['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2']) ? $result['itog']['totalChildrenChangeCampMed'][2][2]['medicalsNumber_med2'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCamp'][2][3]) ? $result['itog']['totalChildrenChangeCamp'][2][3] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1']) ? $result['itog']['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2']) ? $result['itog']['totalChildrenChangeCampMed'][2][3]['medicalsNumber_med2'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCamp'][2][4]) ? $result['itog']['totalChildrenChangeCamp'][2][4] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1']) ? $result['itog']['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2']) ? $result['itog']['totalChildrenChangeCampMed'][2][4]['medicalsNumber_med2'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCamp'][2][5]) ? $result['itog']['totalChildrenChangeCamp'][2][5] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1']) ? $result['itog']['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2']) ? $result['itog']['totalChildrenChangeCampMed'][2][5]['medicalsNumber_med2'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCamp'][2][6]) ? $result['itog']['totalChildrenChangeCamp'][2][6] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1']) ? $result['itog']['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med1'] : 0 ?></td>
                <td class="text-center bg-info"><?= ($result['itog']['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2']) ? $result['itog']['totalChildrenChangeCampMed'][2][6]['medicalsNumber_med2'] : 0 ?></td>

            </tr>
            </tbody>
        </table>
    </div>
<? } ?>
<?
$script = <<< JS
 
 
    $("#pechat222").click(function () {
    var table = $('#tableId2');
        if (table && table.length) {
            var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
            $(table).table2excel({
                exclude: ".noExl",
                name: "Excel Document Name",
                filename: "Сводный отчет по детям.xls",
                fileext: ".xls",
                exclude_img: true,
                exclude_links: true,
                exclude_inputs: true,
                preserveColors: preserveColors
            });
        }
    });
$('#organization-federal_district_id').change(function() {
    var value = $('#organization-federal_district_id option:selected').val();
    $.ajax({
         url: "../organizations/search",
              type: "GET",      // тип запроса
              data: { // действия
                  'id': value
              },
              // Данные пришли
              success: function( data ) {
                    $("#organization-region_id").empty();
                    $("#organization-region_id").append(data);
                    $("#organization-municipality_id").empty();
                    $('#organization-municipality_id').append('<option value="0">Все</option>');
              },
              error: function(err) {
                 console.log(err);
              }
         })
});
$('#organization-region_id').change(function() {
    var value1 = $('#organization-region_id option:selected').val();
    $.ajax({
         url: "../organizations/search-municipality",
              type: "GET",      // тип запроса
              data: { // действия
                  'id': value1
              },
              // Данные пришли
              success: function( data1 ) {
                  $("#organization-municipality_id").empty();
                  $("#organization-municipality_id").append(data1);
              },
              error: function(err) {
                 console.log(err);
              }
         })
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>