<?php
/**
 * 全检检索相关
 *
 * PHP Version 5
 * 全检检索相关
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
    public $pageSize = 10;

    /**
     * 商品检索
     * @return array
     * @throws \Exception
     * @throws \XSException
     */
    public function actionIndex()
    {
        $keywords = RequestHelper::get('keywords', '');
        $cate_id = RequestHelper::get('cate_id', '');
        $second_id = RequestHelper::get('second_id', '');
        $brand_id = RequestHelper::get('brand_id', '');
        $price = RequestHelper::get('price', '');

        $sort = RequestHelper::get('sort', '');
//        $rate = RequestHelper::get('rate', '');
//        $sales_num = RequestHelper::get('sales_num', '');

        $this->pageSize = RequestHelper::get('per-page', $this->pageSize);
        $psort = ['buy_price'=>'origin_price', 'sale_profit_margin'=>'rate', 'sales_num'=>'sales_num'];
        $search_order = [];
        if (!empty($sort) && in_array($sort, array_keys($psort))) {
            $sort = $psort[$sort];
            $order = RequestHelper::get('order', 'DESC', 'strtoupper');
            $order = ($order == 'DESC') ? SORT_DESC : SORT_ASC;
            $search_order = [$sort=>$order];
        }

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
        //var_dump($search_order);
        $count = Product::find()->where($map)->andWhere($keywords)->andWhere($price_map)->count();
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $this->pageSize]);
        $list = Product::find()->where($map)
            ->andWhere($keywords)
            ->andWhere($price_map)
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($search_order)
            ->asArray()
            ->all();


        $db = Product::getDb();
        $search = $db->getSearch();
        //echo $search->getLastCount();
       // exit();
        if (!empty($keywords)) {
            foreach ($list as $k => $v) {
                //$list[$k]['name'] = $search->highlight($v['name']); // 高亮处理 subject 字段
                unset($list[$k]['description']);
            }
            $corrected = $search->getCorrectedQuery($keywords);//你要找的是不是
            if (count($corrected) !== 0) {
                // 有纠错建议，列出来看看；此情况就会得到 "测试" 这一建议

            }
            $data = [
                'total'=>$count,
                'item'=>$list,
                'corrected'=>$corrected
            ];
        } else {
            foreach ($list as $k => $v) {
                unset($list[$k]['description']);
            }
            $data = [
                'total'=>$count,
                'item'=>$list
            ];
        }

        return $this->returnJsonMsg(200, $data, 'OK');

        //$words = $search->getRelatedQuery('德国');

        //var_dump($words);
    }

    /**
     * 搜索建议
     * @return array
     * @throws \Exception
     * @throws \XSException
     */
    public function actionSuggest()
    {
        $keywords = RequestHelper::get('keywords', '');
        if (!empty($keywords)) {
            $db = Product::getDb();
            $search = $db->getSearch();

            $suggest = $search->getExpandedQuery($keywords);
            return $this->returnJsonMsg(200, $suggest, 'OK');
        } else {
            return $this->returnJsonMsg(101, [], '请输入关键词');
        }

    }
    public function actionTest()
    {
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
        $db = \Yii::$app->xunsearch->getDatabase('product');

        $index = $db->getIndex();

        //var_dump($db);
        //  exit();
        //$db = (\Yii::$app->xunsearch)('demo');
        $doc = $db->xs;
        // var_dump($doc);exit();
        $search = $db->getSearch();
        //$query = '项目'; // 这里的搜索语句很简单，就一个短语
        if (!empty($query)) {
            $search->setQuery($query); // 设置搜索语句
        }
        if (!empty($cate_id)) {
            $search->setQuery(['cate_first_id', $cate_id]); // 设置搜索语句
        }

        echo $search->getQuery();

        //$search->addWeight('subject', 'xunsearch'); // 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
        //$search->setLimit(5, 0); // 设置返回结果最多为 5 条，并跳过前 10 条
        // echo $search->getQuery();
        $docs = $search->search(); // 执行搜索，将搜索结果文档保存在 $docs 数组中
        $count = $search->count(); // 获取搜索结果的匹配总数估算值

        $words = $search->getHotQuery(6, 'lastnum'); // 获取前 6 个总热门搜索词


        var_dump($docs);
    }
}
