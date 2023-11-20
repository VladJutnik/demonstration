<?php


namespace common\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrganizationSearchEditing extends Organization
{
    public $federal_district_id;
    public $region_id;
    //public $year;
    public $userName;
    public $userLogin;
    public $organizationTitle;
    public $decryptionOrganizationType;
    public $organizationYear;
    public $userEmail;

    public function rules()
    {
        return [
            [
                [
                    'number',
                    'federal_district_id',
                    'region_id',
                    'municipality_id',
                    'title',
                    'organizationYear',
                    'userName',
                    'userEmail',
                    'userLogin',
                    'organizationTitle',
                    'decryptionOrganizationType',
                    'registration_status',
                ],
                'safe'
            ],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        if (Yii::$app->user->can('admin')) {
            $where = [];

            $where += !empty($params['OrganizationSearchEditing']['federal_district_id']) ? ['organization.federal_district_id' => $params['OrganizationSearchEditing']['federal_district_id']] : [];
            $where += (!empty($params['OrganizationSearchEditing']['region_id']) && !empty($params['OrganizationSearchEditing']['federal_district_id'])) ? ['organization.region_id' => $params['OrganizationSearchEditing']['region_id']] : [];
            $where += (!empty($params['OrganizationSearchEditing']['federal_district_id']) && !empty($params['OrganizationSearchEditing']['region_id']) && !empty($params['OrganizationSearchEditing']['municipality_id'])) ? ['organization.municipality_id' => $params['OrganizationSearchEditing']['municipality_id']] : [];
            $where += !empty($params['OrganizationSearchEditing']['organizationYear']) ? ['organization.year' => $params['OrganizationSearchEditing']['organizationYear']] : [];
            $where += !empty($params['OrganizationSearchEditing']['decryptionOrganizationType']) ? ['organization_type.id' => $params['OrganizationSearchEditing']['decryptionOrganizationType']] : [];
            $where += !empty($params['OrganizationSearchEditing']['registration_status']) ? ['organization.registration_status' => $params['OrganizationSearchEditing']['registration_status']] : [];


            $query = (new \yii\db\Query());
            $query->select(
                [
                    'user.id as userId',
                    'user.name as userName',
                    'user.email as userEmail',
                    'organization.id as organizationId',
                    'organization.sch2 as sch2',
                    'organization.sch5 as sch5',
                    'organization.sch10 as sch10',
                    'organization.sch2_again as sch2_again',
                    'organization.sch5_again as sch5_again',
                    'organization.sch10_again as sch10_again',
                    'organization.registration_status as registration_status',
                    'organization_type.decryption as decryptionOrganizationType',
                    'organization.title as organizationTitle',
                    'organization.year as organizationYear',
                    'organization.number as number',
                    'organization.federal_district_id as federal_district_id',
                    'organization.region_id as region_id',
                    'organization.municipality_id as municipality_id',
                ]
            );
            $query->from('organization');
            $query->innerJoin('user', 'user.organization_id = organization.id');
            $query->innerJoin('organization_type', 'organization.organization_type_id = organization_type.id');
            $query->where($where);
            $query->orderBy(['organization.federal_district_id' => SORT_ASC, 'organization.region_id' => SORT_ASC]);


            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                return $dataProvider;
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    //'forcePageParam' => false,
                    //'pageSizeParam' => false,
                    'pageSize' => 250,
                ],
            ]);
            if(!empty($params['OrganizationSearchEditing']['userLogin'])){
                $query->andFilterWhere(['like', 'user.login', $params['OrganizationSearchEditing']['userLogin']]);
            }
            if(!empty($params['OrganizationSearchEditing']['userEmail'])){
                $query->andFilterWhere(['like', 'user.email', $params['OrganizationSearchEditing']['userEmail']]);
            }
            if(!empty($params['OrganizationSearchEditing']['userName'])){
                $query->andFilterWhere(['like', 'user.name', $params['OrganizationSearchEditing']['userName']]);
            }
            if(!empty($params['OrganizationSearchEditing']['organizationTitle'])){
                $query->andFilterWhere(['like', 'organization.title', $params['OrganizationSearchEditing']['organizationTitle']]);
            }

        }
        return $dataProvider;
    }
}