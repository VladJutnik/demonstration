<?php

namespace backend\controllers;

use common\models\AuthItem;
use common\models\V2DetiAnket;
use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class Controller extends Controller
{
    use RegionReport;

    public function actionReportAdminMatrixNewCommon()
    {
        ini_set('max_execution_time', 5600);
        ini_set('memory_limit', '12092M');
        ini_set("pcre.backtrack_limit", "5000000");

        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $modelReport = new Report();
        $modelDeti = new V2DetiAnket();

        $modeDetiAnket = new DetiAnket();

        $modelReport->federal_district_idReport = Yii::$app->user->identity->federal_district_id;
        $modelReport->region_idReport = Yii::$app->user->identity->region_id;
        $modelReport->municipality_idReport = Yii::$app->user->identity->municipality_id;

        $district_items = $this->getArrayDistrictItems(true); //пролучаем список областей!
        $region_items = $this->getArrayRegionItems(Yii::$app->user->identity->federal_district_id,
            true); //пролучаем список областей!
        $municipality_items = $this->getArrayMunicipalityItems(Yii::$app->user->identity->region_id,
            true); //пролучаем список областей!
        $org_items = $this->getArrayOrganizationItems(Yii::$app->user->identity->municipality_id, 5,
            true); //пролучаем список областей!

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['Report'];
            $modelReport->federal_district_idReport = $post['federal_district_idReport'];
            $modelReport->region_idReport = $post['region_idReport'];
            $modelReport->municipality_idReport = $post['municipality_idReport'];
            $modelReport->organization_idReport = $post['organization_idReport'];
            $modelReport->year = $post['year'];

            $region_items = $this->getArrayRegionItems($post['federal_district_idReport'], true); //пролучаем список областей!
            $municipality_items = $this->getArrayMunicipalityItems($post['region_idReport'],
                true); //пролучаем список областей!
            $org_items = $this->getArrayOrganizationItems($post['municipality_idReport'], 5,
                true); //пролучаем список областей!

            $where_v2_deti_anket = [];
            ($post['federal_district_idReport'] && $post['federal_district_idReport'] !== 'v') ? $where_v2_deti_anket += ['v2_deti_anket.federal_district_id' => $post['federal_district_idReport']] : $where_v2_deti_anket += [];
            ($post['region_idReport'] && $post['region_idReport'] !== 'v') ? $where_v2_deti_anket += ['v2_deti_anket.region_id' => $post['region_idReport']] : $where_v2_deti_anket += [];
            ($post['municipality_idReport'] && $post['municipality_idReport'] !== 'v') ? $where_v2_deti_anket += ['v2_deti_anket.municipality_id' => $post['municipality_idReport']] : $where_v2_deti_anket += [];
            ($post['organization_idReport'] && $post['organization_idReport'] !== 'v') ? $where_v2_deti_anket += ['v2_deti_anket.organization_id' => $post['organization_idReport']] : $where_v2_deti_anket += [];
            $andWhere_v2_deti_anket = [
                'v2_deti_anket.year' => 2023,
            ];
            $rows_v2_deti_anket = (new \yii\db\Query())
                ->from('v2_deti_anket')
                ->join('left JOIN', 'v2_deti_anket_table_18_27', 'v2_deti_anket_table_18_27.id = v2_deti_anket.table_18_27')
                ->join('left JOIN', 'v2_deti_anket_table_28_34', 'v2_deti_anket_table_28_34.id = v2_deti_anket.table_28_34')
                ->join('left JOIN', 'v2_deti_anket_table_45_48', 'v2_deti_anket_table_45_48.id = v2_deti_anket.table_45_48')
                ->join('inner JOIN', 'organization', 'organization.id = v2_deti_anket.organization_id')
                ->where($where_v2_deti_anket)
                ->andWhere($andWhere_v2_deti_anket)
                ->all();
            //print_r('<pre>');
            //print_r($rows);
            //print_r('</pre>');
            $result = [];
            $resultAnketCount = [
                'countUnaccountedFor' => 0,//количество не учтенных анкет
                'countAnket' => 0,//всего анкет
            ];
            foreach ($rows_v2_deti_anket as $row) {
                $resultAnketOne = $modelDeti->getResultAnket2($row);
                //0-сельская, 1-городская  //определил какая область
                if ($row['terrain'] == 0) {
                    $arraName = 'village';
                } else {
                    $arraName = 'city';
                }
                //определяем какая ростовка
                if ($row['field1_1'] == 3) {
                    $arraName2 = '3';
                } elseif ($row['field1_1'] == 6) {
                    $arraName2 = '6';
                }  elseif ($row['field1_1'] == 11) {
                    $arraName2 = '11';
                } else {
                    $arraName2 = 'ini_v2';
                }
                if (
                    $row['field1_16'] == 0 ||
                    $row['field1_16'] == 1 ||
                    $row['field1_16'] == '' ||
                    $row['field1_17'] == 0 ||
                    $row['field1_17'] == 1 ||
                    $row['field1_17'] == ''
                ) {
                    $resultAnketCount['countUnaccountedFor']++;
                } else {
                    //$row['field1_16']; //10. Укажите массу тела (кг) //$row['field1_17']; //10.1 Укажите длину тела в см //возраст $row['field1_4'];

                    $resultAnketOne2 = $modelDeti->getResultAnket22($row);
                    $sex = ($row['field1_5'] == '1') ? 0 : 1; //пол
                    $arraName_sex = ($row['field1_5'] == '1') ? 'men' : 'woman'; //пол
                    $imt = $modelDeti->get_imt($row['field1_16'], $row['field1_17']);
                    $imtStr = $modelDeti->getImtNewNew($imt, $sex, $row['field1_4']);
                    if ($imtStr === 'Дефицит массы тела') {
                        $arraName3 = 'dif';
                    } elseif ($imtStr === 'Ожирение') {
                        $arraName3 = 'ojir3';
                    } elseif ($imtStr === 'Избыточная масса тела') {
                        $arraName3 = 'izbitok';
                    } else {
                        $arraName3 = 'norm';
                    }
                    //ЭТО КУДА НУЖНО ПОЛОЖИТЬ (СУММИРОВАТЬ) РЕЗУЛЬТАТ АНКЕТЫ $result[$arraName][$arraName2][$arraName3]
                    foreach ($resultAnketOne2 as $key => $one) {
                        if (is_numeric($one)) {
                            $result['table2'][$arraName_sex][$arraName3][$arraName2][$key] += $one;
                            $result['table2'][$arraName_sex][$key] += $one;
                            $result['table2'][$arraName3][$arraName2][$key] += $one;
                        } else {
                            $result['table2'][$arraName_sex][$arraName3][$arraName2][$key] = $one;
                            $result['table2'][$arraName_sex][$key] = $one;
                            $result['table2'][$arraName3][$arraName2][$key] = $one;
                        }
                    }
                    $resultAnketCount['countAnket']++;
                }
                //ЭТО КУДА НУЖНО ПОЛОЖИТЬ (СУММИРОВАТЬ) РЕЗУЛЬТАТ АНКЕТЫ $result[$arraName][$arraName2][$arraName3]
                //ЭТО САМ РЕЗУЛЬТАТ $resultAnketOne
                foreach ($resultAnketOne as $key => $one) {
                    if (is_numeric($one)) {
                        $result['table1'][$arraName][$arraName2][$key] += $one;
                    } else {
                        $result['table1'][$arraName][$arraName2][$key] = $one;
                    }
                }
            }

            $where = [];
            ($post['federal_district_idReport'] && $post['federal_district_idReport'] !== 'v') ? $where += ['deti_anket.federal_district_id' => $post['federal_district_idReport']] : $where += [];
            ($post['region_idReport'] && $post['region_idReport'] !== 'v') ? $where += ['deti_anket.region_id' => $post['region_idReport']] : $where += [];
            ($post['municipality_idReport'] && $post['municipality_idReport'] !== 'v') ? $where += ['deti_anket.municipality_id' => $post['municipality_idReport']] : $where += [];
            ($post['organization_idReport'] && $post['organization_idReport'] !== 'v') ? $where += ['deti_anket.organization_id' => $post['organization_idReport']] : $where += [];
            $andWhere = [
                'deti_anket.year' => 2023,
            ];
            $rows = (new \yii\db\Query())
                ->from('deti_anket')
                ->join('left JOIN', 'deti_anket_table_18_27', 'deti_anket_table_18_27.id = deti_anket.table_18_27')
                ->join('left JOIN', 'deti_anket_table_28_34', 'deti_anket_table_28_34.id = deti_anket.table_28_34')
                ->join('left JOIN', 'deti_anket_table_35_44', 'deti_anket_table_35_44.id = deti_anket.table_35_44')
                ->join('left JOIN', 'deti_anket_table_45_48', 'deti_anket_table_45_48.id = deti_anket.table_45_48')
                ->join('inner JOIN', 'organization', 'organization.id = deti_anket.organization_id')
                ->where($where)
                ->andWhere($andWhere)
                ->all();

            foreach ($rows as $row) {
                $resultAnketOne = $modelDeti->getResultAnket2($row);
                //0-сельская, 1-городская  //определил какая область
                if ($row['terrain'] == 0) {
                    $arraName = 'village';
                } else {
                    $arraName = 'city';
                }
                //определяем какая ростовка
                if ($row['field1_1'] == 2) {
                    $arraName2 = '2';
                } elseif ($row['field1_1'] == 5) {
                    $arraName2 = '5';
                }  elseif ($row['field1_1'] == 10) {
                    $arraName2 = '10';
                } else {
                    $arraName2 = 'ini';
                }
                if (
                    $row['field1_16'] == 0 ||
                    $row['field1_16'] == 1 ||
                    $row['field1_16'] == '' ||
                    $row['field1_17'] == 0 ||
                    $row['field1_17'] == 1 ||
                    $row['field1_17'] == ''
                ) {
                    $resultAnketCount['countUnaccountedFor']++;
                } else {
                    //$row['field1_16']; //10. Укажите массу тела (кг) //$row['field1_17']; //10.1 Укажите длину тела в см //возраст $row['field1_4'];

                    $resultAnketOne2 = $modelDeti->getResultAnket22($row);
                    $sex = ($row['field1_5'] == '1') ? 0 : 1; //пол
                    $arraName_sex = ($row['field1_5'] == '1') ? 'men' : 'woman'; //пол
                    $imt = $modelDeti->get_imt($row['field1_16'], $row['field1_17']);
                    $imtStr = $modelDeti->getImtNewNew($imt, $sex, $row['field1_4']);
                    if ($imtStr === 'Дефицит массы тела') {
                        $arraName3 = 'dif';
                    } elseif ($imtStr === 'Ожирение') {
                        $arraName3 = 'ojir3';
                    } elseif ($imtStr === 'Избыточная масса тела') {
                        $arraName3 = 'izbitok';
                    } else {
                        $arraName3 = 'norm';
                    }
                    //ЭТО КУДА НУЖНО ПОЛОЖИТЬ (СУММИРОВАТЬ) РЕЗУЛЬТАТ АНКЕТЫ $result[$arraName][$arraName2][$arraName3]
                    foreach ($resultAnketOne2 as $key => $one) {
                        if (is_numeric($one)) {
                            $result['table2'][$arraName_sex][$arraName3][$arraName2][$key] += $one;
                            $result['table2'][$arraName_sex][$key] += $one;
                            $result['table2'][$arraName3][$arraName2][$key] += $one;
                        } else {
                            $result['table2'][$arraName_sex][$arraName3][$arraName2][$key] = $one;
                            $result['table2'][$arraName_sex][$key] = $one;
                            $result['table2'][$arraName3][$arraName2][$key] = $one;
                        }
                    }
                    $resultAnketCount['countAnket']++;
                }
                //ЭТО КУДА НУЖНО ПОЛОЖИТЬ (СУММИРОВАТЬ) РЕЗУЛЬТАТ АНКЕТЫ $result[$arraName][$arraName2][$arraName3]
                //ЭТО САМ РЕЗУЛЬТАТ $resultAnketOne
                foreach ($resultAnketOne as $key => $one) {
                    if (is_numeric($one)) {
                        $result['table1'][$arraName][$arraName2][$key] += $one;
                    } else {
                        $result['table1'][$arraName][$arraName2][$key] = $one;
                    }
                }
            }
            //print_r('<pre>');
            //print_r($resultAnketCount);
            //print_r('<br>');
            //print_r($result);
            //print_r('</pre>');

            //print_r('<pre>');
            //print_r($result);
            //print_r('</pre>');
            //exit();

            if (!$rows_v2_deti_anket && !$rows) {
                Yii::$app->session->setFlash('error', 'Данных не найдено!');
            }
        }

        return $this->render('/v2-deti-anket/report-admin-matrix-new-common', [
            'hasAccessFederalDistrict' => (Yii::$app->user->can('admin')) ? true : false,
            'hasAccessRegion' => (Yii::$app->user->can('admin')) ? true : false,
            'hasAccessMunicipality' => (Yii::$app->user->can('admin')) ? true : false,
            'hasAccessOrg' => (Yii::$app->user->can('admin')) ? true : false,

            'modelDeti' => $modelDeti,
            'result' => $result,
            'resultAnketCount' => $resultAnketCount,
            'modelReport' => $modelReport,
            'district_items' => $district_items,
            'region_items' => $region_items,
            'municipality_items' => $municipality_items,
            'org_items' => $org_items,
            'numStr' => $numStr,
            'showReport' => Yii::$app->request->post()['Report']['showReport'],
        ]);
    }

}
