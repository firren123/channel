<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/10/9 下午4:28 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\controllers;

use common\helpers\RequestHelper;
use backend\models\Article;
use backend\models\Product;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class SearchController extends BaseController
{
    public $pageSize = 20;
    public function actionIndex()
    {
        $model = new Product();
        $keywords = RequestHelper::get('keywords', '');
        $cate_id = RequestHelper::get('cate_id', '');
        $second_id = RequestHelper::get('second_id', '');
        $brand_id = RequestHelper::get('brand_id', '');
        $price = RequestHelper::get('price', '');
        $map = [];
        if (!empty($cate_id)) {
            $map['cate_first_id'] = $cate_id;
        }
        if (!empty($second_id)) {
            $map['cate_second_id'] = $second_id;
        }
        if (!empty($brand_id)) {
            $map['brand_id'] = $brand_id;
        }
        $price_map = [];
        if (!empty($price)) {
            $between = explode('-', $price);
            if (!empty($between)) {
                $start = ArrayHelper::getValue($between, 0);
                $end = ArrayHelper::getValue($between, 1, $start);
            }
            $price_map = ['BETWEEN', 'origin_price', intval($start), intval($end)];
        }
       // var_dump($price_map);
        //$map = ['cate_first_id' => $cate_id, 'cate_second_id' => $second_id, 'brand_id' => $brand_id]; ->andWhere($price_map)

        //$list = $query->orderBy([$order=>$sort])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $query = $model->find()->where($map)->andWhere($keywords)->andWhere(['BETWEEN', 'origin_price', 419,1000]);
        echo $count = $query->count();
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $this->pageSize]);
        $list = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
//$db = \Yii::$app->xunsearch->getDatabase('product');
        $db = Product::getDb();
        $search = $db->getSearch();
        foreach ($list as $k => $v) {
            $list[$k]['name'] = $search->highlight($v['name']); // 高亮处理 subject 字段
            unset($list[$k]['description']);
        }
        var_dump($list);
    }
}
