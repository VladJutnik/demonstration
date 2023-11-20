<?php

namespace backend\controllers;

use common\models\AuthItem;
use common\models\OrganizationSearchEditing;
use common\models\V2DetiAnket;
use backend\traits\PrintT;
use backend\traits\Slider;
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
    public function actionReportRegNew() {
        $model = new OrganizationAlias();
        $model->federal_district_id = 0;
        $model->region_id = 0;
        $model->type_lager_id = 0;

        if (Yii::$app->request->post()) {
//            print_r(Yii::$app->request->post());
//            exit;
            $post = Yii::$app->request->post()['Organization'];

            $model->federal_district_id = $post['federal_district_id'];
            $model->region_id = $post['region_id'];
            $model->municipality_id = $post['municipality_id'];
            $model->type_lager_id = $post['type_lager_id'];
            $model->inn = $post['inn'];
            $model->status = $post['status'];

            if ($post['federal_district_id'] == 0) {
                $districts = FederalDistrict::find()->all();
            }
            else {
                $districts = FederalDistrict::find()->all();
                //$districts = FederalDistrict::find()->where(['id'=>$post['federal_district_id']])->all();
                $regions = Region::find()->where(['district_id' => $post['federal_district_id']])->all();
                $region_item = ArrayHelper::map($regions, 'id', 'name');
                $municipality = Municipality::find()->where(['region_id' => $post['region_id']])->all();
                $municipality_item = ArrayHelper::map($municipality, 'id', 'name');
            }

            $where = [];
            ($post['federal_district_id'] && $post['federal_district_id'] !== 0) ? $where += ['organization.federal_district_id' => $post['federal_district_id']] : $where += [];
            ($post['region_id'] && $post['region_id'] !== 0) ? $where += ['organization.region_id' => $post['region_id']] : $where += [];
            /*($post['municipality_id'] && $post['municipality_id'] != 0) ? $where += ['organization.municipality_id' => $post['municipality_id']] : $where += [];*/
            ($post['type_lager_id'] == '0') ? $where += [] : $where += ['organization.type_lager_id' => $post['type_lager_id']];

            $date_str = "2023-01-01 12:00:00"; // предположим, что у нас есть строка с датой и временем
            $rows = (new \yii\db\Query())
                ->select(
                    [
                        'organization.id as organizationId',
                        'organization.federal_district_id as federal_district_id',
                        'organization.region_id as region_id',
                        'organization.title as title',
                        'organization.created_at as created_at',
                        '(SELECT name FROM `type_lager` WHERE type_lager.id = organization.type_lager_id) AS `nameType_lager`',
                        'kids.id as kidsId',
                        'kids.season as kidsSeason',
                        'kids.change_camp as kidsChange_camp',
                        'medicals.number_med as medicalsNumber_med',
                        '(
                            SELECT user_autorization_statistic.created_at FROM `user` 
                            inner join user_autorization_statistic on user_autorization_statistic.user_id = user.id
                            WHERE user.organization_id = organization.id
                            ORDER BY user_autorization_statistic.created_at DESC
                            limit 1
                        ) AS `autorization_statistic_created_at`',
                        '(SELECT COUNT(*) FROM `menus` WHERE organization_id = organization.id and status_archive = 0) AS `countMenus`',
                    ]
                )
                ->from('organization')
                ->join('left JOIN', 'kids', 'kids.organization_id = organization.id  ')
                ->join('left JOIN', 'medicals', 'kids.id = medicals.kids_id ')
                ->where($where)
                //->andWhere(['>','organization.created_at',$date_str])
                ->andWhere(['not in', 'organization.id', [10, 156, 143, 134, 30, 16193, 18090]])
                ->andWhere(['<>', 'organization.federal_district_id', ''])
                ->andWhere(['<>', 'organization.region_id', ''])
                ->andWhere(['organization.type_org' => 1])
                ->andWhere(['kids.year' => 2023])
                ->all();

            $result = [];
            $resultOrganizationId = [];
            $resultCount = [];
            $resultSchool = [];

            //$result['str'] = [];
            $result['okrug'][$rows[0]['federal_district_id']] = [];
            //$result['region'][$rows[0]['federal_district_id']][$rows[0]['region_id']] = [];
            $result['region'][$rows[0]['region_id']] = [];
            $result['itog'] = [];

            foreach ($rows as $row) {
                //теоритически должен показывать только те организации которые заходили раньше $date_str
                //if($row['autorization_statistic_created_at'] && ($row['autorization_statistic_created_at'] > $date_str)){
                $resultOrganizationId[] = $row['organizationId'];
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['region_id'] = $row['region_id']; //в каком регионе школа
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countMenus'] = $row['countMenus']; //количесвто меню у школы
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['autorization_statistic_created_at'] = $row['autorization_statistic_created_at']; //количесвто работающих школ
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['created_at'] = $row['created_at']; //дата регистрации
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countSchool'] = 1; //количесвто работающих школ
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countNoSchool'] = 0; //количесвто работающих школ
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['nameType_lager'] = $row['nameType_lager']; //тип лагерярегистрация организации
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalKids'] += ($row['kidsId'] !== '') ? 1 : 0; //количесвто меню у школы
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalMedicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //количесвто меню у школы
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalMedicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //количесвто меню у школы
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalChildrenSeason'][$row['kidsSeason']] += ($row['kidsId'] !== '') ? 1 : 0; // всего количество детей по сезонам
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalChildrenChangeCamp'][$row['kidsSeason']][$row['kidsChange_camp']] += ($row['kidsId'] !== '') ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //всего количество детей по сезонам и сменам
                //подсчет общего количества по федеральному округу
                $result['okrug'][$row['federal_district_id']]['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                $result['okrug'][$row['federal_district_id']]['region_id'] = $row['region_id']; //в каком регионе школа
                $result['okrug'][$row['federal_district_id']]['countMenus'] = $row['countMenus']; //количесвто меню у школы
                $result['okrug'][$row['federal_district_id']]['totalKids'] += ($row['kidsId'] !== '') ? 1 : 0; //количесвто меню у школы
                $result['okrug'][$row['federal_district_id']]['totalMedicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //количесвто меню у школы
                $result['okrug'][$row['federal_district_id']]['totalMedicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //количесвто меню у школы
                $result['okrug'][$row['federal_district_id']]['totalType_lager'][$row['nameType_lager']] += 1; // всего количество детей по сезонам
                $result['okrug'][$row['federal_district_id']]['totalChildrenSeason'][$row['kidsSeason']] += ($row['kidsId'] !== '') ? 1 : 0; // всего количество детей по сезонам
                $result['okrug'][$row['federal_district_id']]['totalChildrenChangeCamp'][$row['kidsSeason']][$row['kidsChange_camp']] += ($row['kidsId'] !== '') ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['okrug'][$row['federal_district_id']]['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['okrug'][$row['federal_district_id']]['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //всего количество детей по сезонам и сменам
                //подсчет общего количества по региону
                $result['region'][$row['region_id']]['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                $result['region'][$row['region_id']]['region_id'] = $row['region_id']; //в каком регионе школа
                $result['region'][$row['region_id']]['countMenus'] = $row['countMenus']; //количесвто меню у школы
                $result['region'][$row['region_id']]['totalKids'] += ($row['kidsId'] !== '') ? 1 : 0; //количесвто меню у школы
                $result['region'][$row['region_id']]['totalMedicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //количесвто меню у школы
                $result['region'][$row['region_id']]['totalMedicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //количесвто меню у школы
                $result['region'][$row['region_id']]['totalType_lager'][$row['nameType_lager']] += 1; //количесвто меню у школы
                $result['region'][$row['region_id']]['totalChildrenSeason'][$row['kidsSeason']] += ($row['kidsId'] !== '') ? 1 : 0; // всего количество детей по сезонам
                $result['region'][$row['region_id']]['totalChildrenChangeCamp'][$row['kidsSeason']][$row['kidsChange_camp']] += ($row['kidsId'] !== '') ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['region'][$row['region_id']]['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['region'][$row['region_id']]['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //всего количество детей по сезонам и сменам
                //подсчет общего количества по всей РФ
                $result['itog']['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                $result['itog']['region_id'] = $row['region_id']; //в каком регионе школа
                $result['itog']['countMenus'] = $row['countMenus']; //количесвто меню у школы
                $result['itog']['totalKids'] += ($row['kidsId'] !== '') ? 1 : 0; //количесвто меню у школы
                $result['itog']['totalMedicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //количесвто меню у школы
                $result['itog']['totalMedicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //количесвто меню у школы
                $result['itog']['totalType_lager'][$row['nameType_lager']] += 1;//количесвто меню у школы
                $result['itog']['totalChildrenSeason'][$row['kidsSeason']] += ($row['kidsId'] !== '') ? 1 : 0; // всего количество детей по сезонам
                $result['itog']['totalChildrenChangeCamp'][$row['kidsSeason']][$row['kidsChange_camp']] += ($row['kidsId'] !== '') ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['itog']['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med1'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 0) ? 1 : 0; //всего количество детей по сезонам и сменам
                $result['itog']['totalChildrenChangeCampMed'][$row['kidsSeason']][$row['kidsChange_camp']]['medicalsNumber_med2'] += ($row['medicalsNumber_med'] != '' && $row['medicalsNumber_med'] == 1) ? 1 : 0; //всего количество детей по сезонам и сменам

            }
//                /$countOrg = count($result['school']);
            if ($post['inn'] == 2) {
                $whereAnd = ($resultOrganizationId) ? ['not in', 'organization.id', $resultOrganizationId] : [];
                $rowsNameOrg = (new \yii\db\Query())
                    ->select(
                        [
                            'organization.id as organizationId',
                            'organization.federal_district_id as federal_district_id',
                            'organization.region_id as region_id',
                            'organization.title as title',
                            'organization.created_at as created_at',
                            '(SELECT name FROM `type_lager` WHERE type_lager.id = organization.type_lager_id) AS `nameType_lager`',
                            '(
                            SELECT user_autorization_statistic.created_at FROM `user` 
                            inner join user_autorization_statistic on user_autorization_statistic.user_id = user.id
                            WHERE user.organization_id = organization.id
                            ORDER BY user_autorization_statistic.created_at DESC
                            limit 1
                        ) AS `autorization_statistic_created_at`',
                            '(SELECT COUNT(*) FROM `menus` WHERE organization_id = organization.id and status_archive = 0) AS `countMenus`',
                        ]
                    )
                    ->from('organization')
                    ->where($where)
                    //->andWhere(['>','organization.created_at',$date_str])
                    ->andWhere(['not in', 'organization.id', [10, 156, 143, 134, 30, 16193, 18090]])
                    ->andWhere($whereAnd)
                    ->andWhere(['<>', 'organization.federal_district_id', ''])
                    ->andWhere(['<>', 'organization.region_id', ''])
                    ->andWhere(['organization.type_org' => 1])
                    ->all();

                foreach ($rowsNameOrg as $row) {
                    if ($row['autorization_statistic_created_at'] > '2023-01-01' || $row['created_at'] > '2023-01-01') {
                        //if($row['autorization_statistic_created_at'] && ($row['autorization_statistic_created_at'] > $date_str)){
                        $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                        $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['region_id'] = $row['region_id']; //в каком регионе школа
                        $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countMenus'] = $row['countMenus']; //количесвто меню у школы
                        $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['created_at'] = $row['created_at']; //дата регистрации
                        $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['nameType_lager'] = $row['nameType_lager']; //тип лагерярегистрация организации
                        if ($row['countMenus'] > 0) {
                            $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countSchool'] = 1; //количесвто работающих школ
                            $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countNoSchool'] = 0; //количесвто работающих школ
                        }
                        else {
                            $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countSchool'] = 0; //количесвто работающих школ
                            $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['countNoSchool'] = 1; //количесвто работающих школ
                        }
                        $result['school'][$row['federal_district_id']][$row['region_id']][$row['title']]['autorization_statistic_created_at'] = $row['autorization_statistic_created_at']; //количесвто работающих школ

                        //подсчет общего количества по федеральному округу
                        $result['okrug'][$row['federal_district_id']]['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                        $result['okrug'][$row['federal_district_id']]['region_id'] = $row['region_id']; //в каком регионе школа
                        $result['okrug'][$row['federal_district_id']]['countMenus'] = $row['countMenus']; //количесвто меню у школы
                        $result['okrug'][$row['federal_district_id']]['totalType_lager'][$row['nameType_lager']] += 1; // всего количество детей по сезонам
                         //подсчет общего количества по региону
                        $result['region'][$row['region_id']]['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                        $result['region'][$row['region_id']]['region_id'] = $row['region_id']; //в каком регионе школа
                        $result['region'][$row['region_id']]['countMenus'] = $row['countMenus']; //количесвто меню у школы
                        $result['region'][$row['region_id']]['totalType_lager'][$row['nameType_lager']] += 1; //количесвто меню у школы
                        if ($row['countMenus'] > 0) {
                            $result['region'][$row['region_id']]['countSchool'] = 1; //количесвто работающих школ
                            $result['region'][$row['region_id']]['countNoSchool'] = 0; //количесвто работающих школ
                        }
                        else {
                            $result['region'][$row['region_id']]['countSchool'] = 0; //количесвто работающих школ
                            $result['region'][$row['region_id']]['countNoSchool'] = 1; //количесвто работающих школ
                        }
                        //подсчет общего количества по всей РФ
                        $result['itog']['federal_district_id'] = $row['federal_district_id']; //в каком округе школа
                        $result['itog']['region_id'] = $row['region_id']; //в каком регионе школа
                        $result['itog']['countMenus'] = $row['countMenus']; //количесвто меню у школы
                        $result['itog']['totalType_lager'][$row['nameType_lager']] += 1;//количесвто меню у школы
                    }


                }
            }
            if ($result['school']) {
                ksort($result['school']);
            }

            if (!$rows && !$rowsNameOrg) {
                Yii::$app->session->setFlash('error', 'Данных не найдено!');
            }

        }

        return $this->render('report-reg-new', [
            'model' => $model,
            'districts' => $districts, //ФО
            'region_item' => $region_item,
            'municipality_item' => $municipality_item,
            'result' => $result,
            'resultCount' => $resultCount,
        ]);
    }
}
