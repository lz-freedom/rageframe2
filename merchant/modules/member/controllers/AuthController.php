<?php

namespace merchant\modules\member\controllers;

use Yii;
use common\enums\StatusEnum;
use common\traits\MerchantCurd;
use common\models\member\Auth;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
 * Class AuthController
 * @package merchant\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthController extends BaseController
{
    use MerchantCurd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Auth::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->andWhere(['>', 'member_id', 0]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}