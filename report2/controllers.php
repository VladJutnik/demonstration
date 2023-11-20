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
    use PrintT, Slider;

    public function actionIndexListEditing()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (Yii::$app->user->can('admin')) {

            $searchModel = new OrganizationSearchEditing();
            $search = Yii::$app->request->queryParams;

            $dataProvider = $searchModel->search($search);
            return $this->render('index-list-editing', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'region_items' => Yii::$app->myComponent->RegionItems($search['OrganizationSearchEditing']['federal_district_id']),
                'municipality_items' => Yii::$app->myComponent->MunicipalityItems($search['OrganizationSearchEditing']['region_id']),
            ]);
        } else {
            return $this->goHome();
        }
    }

}
