<?php

use common\models\Municipality;
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
$this->title = 'Сводный отчет по анкетированию, с планируемой и фактической информацией';

?>

<div class="users-report-form container"><h5 align="center"><?= Html::encode($this->title) ?></h5>
    <?php
    $form = ActiveForm::begin(); ?>
    <?=
    $this->render(
        '/report/_title-report',
        [
            'form' => $form,
            'modelReport' => $modelReport,

            'district_items' => $district_items,
            'region_items' => $region_items,
            'org_items' => [],
            'hasAccessFederalDistrict' => $hasAccessFederalDistrict,
            'hasAccessRegion' => $hasAccessRegion,
        ]
    ); ?>
    <div class="form-group row">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-success main-color form-control col-12 mt-3']) ?>
    </div>
    <?php
    ActiveForm::end(); ?>
</div>
<?
if ($result) {
    ?>
    <input type="button" class="btn btn-warning btn-block table2excel mb-3 mt-3"
           title="Вы можете скачать в формате Excel" value="Скачать в Excel" id="pechat222">
    <div class="table-responsive">
        <table id="tableId2" class="table table-bordered table-sm table2excel_with_colors">
            <thead>
            <tr>
                <th rowspan="2" class="text-center">№</th>
                <th rowspan="2" class="text-center">Федеральный округ</th>
                <th rowspan="2" class="text-center">Субъект Федерации</th>
                <th colspan="2" class="text-center">Анкета №1</th>
                <th class="text-center">Анкета №2</th>
                <th colspan="4" class="text-center">Анкета №3</th>
                <th colspan="3" class="text-center">Анкета №4</th>
            </tr>
            <tr>
                <th class="text-center">План</th>
                <th class="text-center">Факт</th>
                <th class="text-center">Факт</th>
                <th class="text-center">Факт 2кл</th>
                <th class="text-center">Факт 5кл</th>
                <th class="text-center">Факт 10кл</th>
                <th class="text-center">Факт Все классы</th>
                <th class="text-center">Факт 3 кл</th>
                <th class="text-center">Факт 6 кл</th>
                <th class="text-center">Факт Все классы</th>
            </tr>
            </thead>
            <tbody>
            <?
            $firstKey = array_key_first($result['region']);
            $region = $result['region'][$firstKey]['region_id'];
            $okrug = $result['region'][$firstKey]['federal_district_id'];
            $countStr = 1;
            foreach ($result['region'] as $key => $row) {
                ?>
                <? if ($hasAccessFederalDistrict) { ?>
                    <? if ($okrug !== $row['federal_district_id']) {
                        ?>
                        <tr class="bg-info">
                            <th class="text-center"
                                colspan="3"><?= Yii::$app->myComponent->get_federal_name($okrug) ?></th>
                            <td class="text-center"><?= $result['okrug'][$okrug]['planDirector'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['factDirector'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['factFood'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket2'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket5'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket10'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['fackt2Kl'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['fackt5Kl'] ?></td>
                            <td class="text-center"><?= $result['okrug'][$okrug]['facktALL'] ?></td>

                        </tr>
                        <?
                        $countStr = 1;
                    } ?>
                <? } ?>
                <?
                $region = $row['region_id'];
                $okrug = $row['federal_district_id'];
                ?>
                <tr>
                    <td class="text-center"><?= $countStr ?></td>
                    <td colspan="2"
                        class="text-center"><?= Yii::$app->myComponent->get_region_name($row['region_id']) ?></td>
                    <td class="text-center"><?= $row['planDirector'] ?></td>
                    <td class="text-center"><?= $row['factDirector'] ?></td>
                    <td class="text-center"><?= $row['factFood'] ?></td>
                    <td class="text-center"><?= $row['factDetiAnket2'] ?></td>
                    <td class="text-center"><?= $row['factDetiAnket5'] ?></td>
                    <td class="text-center"><?= $row['factDetiAnket10'] ?></td>
                    <td class="text-center"><?= $row['factDetiAnket'] ?></td>
                    <td class="text-center"><?= $row['fackt2Kl'] ?></td>
                    <td class="text-center"><?= $row['fackt5Kl'] ?></td>
                    <td class="text-center"><?= $row['facktALL'] ?></td>
                </tr>
                <?
                $countStr++;

            } ?>
            <? if ($hasAccessFederalDistrict) { ?>
                <tr class="bg-info">
                    <th class="text-center" colspan="3"
                        class="textItog"><?= Yii::$app->myComponent->get_federal_name($okrug) ?></th>
                    <td class="text-center"><?= $result['okrug'][$okrug]['planDirector'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['factDirector'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['factFood'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket2'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket5'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket10'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['factDetiAnket'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['fackt2Kl'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['fackt5Kl'] ?></td>
                    <td class="text-center"><?= $result['okrug'][$okrug]['facktALL'] ?></td>
                </tr>
                <?
            } ?>
            <tr class="bg-danger">
                <th class="text-center" colspan="3" class="textItog">ИТОГ</th>
                <td class="text-center"><?= $result['itog']['planDirector'] ?></td>
                <td class="text-center"><?= $result['itog']['factDirector'] ?></td>
                <td class="text-center"><?= $result['itog']['factFood'] ?></td>
                <td class="text-center"><?= $result['itog']['factDetiAnket2'] ?></td>
                <td class="text-center"><?= $result['itog']['factDetiAnket5'] ?></td>
                <td class="text-center"><?= $result['itog']['factDetiAnket10'] ?></td>
                <td class="text-center"><?= $result['itog']['factDetiAnket'] ?></td>
                <td class="text-center"><?= $result['itog']['fackt2Kl'] ?></td>
                <td class="text-center"><?= $result['itog']['fackt5Kl'] ?></td>
                <td class="text-center"><?= $result['itog']['facktALL'] ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <?
}
?>
<?
$script = <<< JS
   
    $("#pechat222").click(function () {
    var table = $('#tableId2');
        if (table && table.length) {
            var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
            $(table).table2excel({
                exclude: ".noExl",
                name: "Excel Document Name",
                filename: "Сводный отчет по анкетированию, с планируемой и фактической информацией.xls",
                fileext: ".xls",
                exclude_img: true,
                exclude_links: true,
                exclude_inputs: true,
                preserveColors: preserveColors
            });
        }
    });                       
   
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>


