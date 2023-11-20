<?php

namespace backend\controllers;

use common\models\AuthItem;
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

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'logout',
                            'profile',
                            'edit',
                            'report-anket1',
                            'report-anket2',
                            'report-anket3',
                            'report-anket4',

                            //методы для подгрузки в отчет!
                            'region-list-report',
                            'municipality-list-report',
                            'organization-list-report',
                        ],
                        'allow' => true,
                        'roles' => [
                            'admin',
                            'director_school',
                            'food_organizer',
                            'rospotrebnadzor',
                            'curator',
                        ],
                    ],
                    [
                        'actions' => [
                            'profile',
                            'edit',
                            'report-anket1',
                            'report-anket2',
                            'report-anket3',

                            //методы для подгрузки в отчет!
                            'region-list-report',
                            'municipality-list-report',
                            'organization-list-report',
                            'planned-actually',
                        ],
                        'allow' => true,
                        'roles' => [
                            'curator',
                        ],
                    ],
                    [
                        'actions' => [
                            'index',
                            'deleted-users',
                            'rospotrebnadzor-index-adm',
                            'rospotrebnadzor-index-adm-new',
                            'generate-new-password',
                        ],
                        'allow' => true,
                        'roles' => [
                            'admin',
                        ],
                    ],
                    [
                        'actions' => [
                            'rospotrebnadzor-index',
                            'login-school',
                            'planned-actually',
                        ],
                        'allow' => true,
                        'roles' => [
                            'admin',
                            'rospotrebnadzor',
                        ],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash(
                        "error",
                        "У Вас нет доступа к этой  странице, пожалуйста, обратитесь к администратору!"
                    );
                    if (Yii::$app->user->isGuest) {
                        return $this->goHome();
                    } else {
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                },
            ],
        ];
    }

    public function actionPlannedActually()
    {

        $hasAccessFederalDistrict = (Yii::$app->user->can('admin') || Yii::$app->user->can('curator')) ? true : false;
        $hasAccessRegion = (Yii::$app->user->can('admin') || Yii::$app->user->can('curator')) ? true : false;
        $hasAccessShow = (Yii::$app->user->can('admin')) ? true : false;
        $hasAccessOrgStatus = (Yii::$app->user->can('admin') || Yii::$app->user->can('curator') || Yii::$app->user->can('rospotrebnadzor')) ? true : false;
        $hasAccessYear = (Yii::$app->user->can('admin') || Yii::$app->user->can('curator') || Yii::$app->user->can('rospotrebnadzor')) ? true : false;

        $modelReport = new Report();
        $modelReport->year  = 2023;


        if (Yii::$app->user->can('curator')) {
            $strArrya = explode('/', Yii::$app->user->identity->work_position);
            $district_items = $this->getArrayDistrictItemsCurat($strArrya);
            $region_items = $this->getArrayRegionItems(
                $strArrya[0],
                true
            ); //пролучаем список областей!
        } else {
            $modelReport->federal_district_idReport = Yii::$app->user->identity->federal_district_id;
            $modelReport->region_idReport = Yii::$app->user->identity->region_id;
            $modelReport->municipality_idReport ='v';

            $district_items = $this->getArrayDistrictItems(true); //пролучаем список областей!
            $region_items = $this->getArrayRegionItems(
                Yii::$app->user->identity->federal_district_id,
                true
            ); //пролучаем список областей!
        }

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['Report'];
            $modelReport->federal_district_idReport = $post['federal_district_idReport'];
            $modelReport->region_idReport = $post['region_idReport'];
            $modelReport->municipality_idReport = $post['municipality_idReport'];
            $modelReport->yearReport = $post['region_idReport'];
            $modelReport->showReport = $post['showReport'];
            $modelReport->year = $post['year'];
            $modelReport->status_idReport = $post['status_idReport'];

            $where = ['organization_type_id' => 5];
            //!!! Собирать будет только по тем результатам что пришли!!!
            if ($post['federal_district_idReport'] && $post['federal_district_idReport'] !== 'v') {
                $where += ['federal_district_id' => $post['federal_district_idReport']];
                $region_items = $this->getArrayRegionItems(
                    $post['federal_district_idReport'],
                    true
                ); //пролучаем список областей!
            }

            if ($post['region_idReport'] && $post['region_idReport'] !== 'v') {
                $where += ['region_id' => $post['region_idReport']];
                $municipality_items = $this->getArrayMunicipalityItems(
                    $post['region_idReport'],
                    true
                );
            }

            if (Yii::$app->user->can('rospotrebnadzor')) {
                $where += [
                    'federal_district_id' => Yii::$app->user->identity->federal_district_id,
                    'region_id' => Yii::$app->user->identity->region_id
                ];
            }


            $where += ['organization.year' => 2023];

            //SELECT `id`, `federal_district_id`, `region_id`, `municipality_id`, `title`, (SELECT id from `director` where `director`.organization_id = `organization`.id) as 'result' FROM `organization` order by `federal_district_id`, `region_id`, `municipality_id` ASC
            $rows = (new \yii\db\Query())
                ->select(
                    [
                        '(
                            SELECT count(*) 
                            from `director` 
		                    INNER JOIN  `organization` as  organization4 ON director.organization_id = organization4.id
                            where 
                            `director`.`year` = 2023 AND
                            `director`.federal_district_id = `organization`.federal_district_id AND
                            `director`.region_id = `organization`.region_id AND
                            `organization4`.`year` = 2023 AND
                            `organization4`.registration_status = 1 
                           
                        ) as factDirector',
                        '(
                            SELECT count(*) from `organization` as  organization2 
                            where 
                            `organization2`.federal_district_id = `organization`.federal_district_id AND
                            `organization2`.region_id = `organization`.region_id AND
                            `organization2`.organization_type_id = 5 AND
                            `organization2`.registration_status = 1 AND
                            `organization2`.`year` = 2023
                        ) as planDirector',
                        '(
                            SELECT count(*) 
                            from `food` 
                            where 
                            `food`.`year` = 2023 AND
                            `food`.federal_district_id = `organization`.federal_district_id AND
                            `food`.region_id = `organization`.region_id
                        ) as factFood',
                        '(
                            SELECT count(*) 
                            from `deti_anket` 
                            where 
                            `deti_anket`.`year` = 2023 AND
                            `deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `deti_anket`.region_id = `organization`.region_id
                        ) as factDetiAnket',
                        '(
                            SELECT count(*) 
                            from `deti_anket` 
                            where 
                            `deti_anket`.`year` = 2023 AND
                            `deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `deti_anket`.region_id = `organization`.region_id and field1_1 = 2
                        ) as factDetiAnket2',
                        '(
                            SELECT count(*) 
                            from `deti_anket` 
                            where 
                            `deti_anket`.`year` = 2023 AND
                            `deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `deti_anket`.region_id = `organization`.region_id and field1_1 = 5
                        ) as factDetiAnket5',
                        '(
                            SELECT count(*) 
                            from `deti_anket` 
                            where 
                            `deti_anket`.`year` = 2023 AND
                            `deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `deti_anket`.region_id = `organization`.region_id and field1_1 = 10
                        ) as factDetiAnket10',
                        'sum(sch2_again) AS `plan2Kl`',
                        'sum(sch5_again) AS `plan5Kl`',
                        '(
                            SELECT count(*) 
                            from `v2_deti_anket` 
                            where    
                            `v2_deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `v2_deti_anket`.region_id = `organization`.region_id and 
                            `v2_deti_anket`.field1_1 = 3 and 
                            `v2_deti_anket`.year = 2023
                        ) as fackt2Kl',
                        '(
                            SELECT count(*) 
                            from `v2_deti_anket` 
                            where    
                            `v2_deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `v2_deti_anket`.region_id = `organization`.region_id and 
                            `v2_deti_anket`.field1_1 = 6 and 
                            `v2_deti_anket`.year = 2023
                        ) as fackt5Kl',
                        '(
                            SELECT count(*) 
                            from `v2_deti_anket` 
                            where    
                            `v2_deti_anket`.federal_district_id = `organization`.federal_district_id AND
                            `v2_deti_anket`.region_id = `organization`.region_id and 
                            `v2_deti_anket`.year = 2023
                        ) as facktALL',
                        'organization.federal_district_id AS `federal_district_id`',
                        'organization.region_id AS `region_id`',
                        'sum(sch2) as sch2',
                        'sum(sch5) as sch5',
                        'sum(sch10) as sch10',
                    ]
                )
                ->from('organization')
                ->where($where)
                ->andWhere(['organization.registration_status' => 1])
                ->andWhere(['organization.year' => 2023])
                ->andWhere(['not in', 'organization.id', [1, 3, 4, 5, 6, 7, 10, 11]])
                ->groupBy(['region_id'])
                ->orderBy([
                    'organization.federal_district_id' => SORT_ASC,
                    'organization.region_id' => SORT_ASC,
                ])
                /*->orderBy([
                    'federal_district_id' => SORT_ASC,
                    'region_id' => SORT_ASC,
                    'municipality_id' => SORT_ASC,
                ])*/
                //->asArray()
                ->all();

            if (!$rows) {
                Yii::$app->session->setFlash('error', 'Данных не найдено!');
            }
            else {
                //$result['str'] = [];
                $result['okrug'][$rows[0]['federal_district_id']] = [];
                //$result['region'][$rows[0]['federal_district_id']][$rows[0]['region_id']] = [];
                $result['region'][$rows[0]['region_id']] = [];
                $result['itog'] = [];

                $i = 0;
                $arr = [
                    'factDirector',
                    'planDirector',
                    'factFood',
                    'factDetiAnket2',
                    'factDetiAnket5',
                    'factDetiAnket10',
                    'factDetiAnket',
                    'plan2Kl',
                    'plan5Kl',
                    'fackt2Kl',
                    'fackt5Kl',
                    'facktALL',
                    'federal_district_id',
                    'region_id',
                    'sch2',
                    'sch5',
                    'sch10',
                ];
                foreach ($rows as $row) {
                    foreach ($arr as $one){
                        //$result['str'][$i][$one] += $row[$one];
                        $result['okrug'][$row['federal_district_id']][$one] += $row[$one];
                        $result['region'][$row['region_id']][$one] += $row[$one];
                        $result['itog'][$one] += $row[$one];
                    }
                    $i++;
                }

            }
        }

        return $this->render(
            'planned-actually',
            [
                'modelReport' => $modelReport,
                'district_items' => $district_items,
                'region_items' => $region_items,
                'municipality_items' => $municipality_items,
                'hasAccessFederalDistrict' => $hasAccessFederalDistrict,
                'hasAccessRegion' => $hasAccessRegion,
                'hasAccessYear' => $hasAccessYear,
                'result' => $result,
                'hasAccessShow' => $hasAccessShow,
                'hasAccessOrgStatus' => $hasAccessOrgStatus,
            ]
        );
    }

}
