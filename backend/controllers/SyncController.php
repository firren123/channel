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

use backend\models\i500m\Products;
use common\helpers\RequestHelper;
use backend\models\Article;
use backend\models\Product;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class SyncController extends BaseController
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
    public function actionSyncGoods()
    {
        $goods_id = RequestHelper::get('goods_id', 0, 'intval');
        //Database::getIndex()->flushIndex();
        if (!empty($goods_id)) {
            $model = new Products();
           // $fields = 'id,name,bar_code,description,cate_first_id,brand_id, is_self, fixed_price,status,single';
            $fields = [
                'id',
                'name',
                'title',
                'description',
                'cate_first_id',
                'cate_second_id',
                'brand_id',
                'total_num',
                'warning_num',
                'origin_price',
                'sale_price',
                'status',
                'create_time',
                'single',
                'bar_code',
                'attr_value',
                'shop_price',
                'sale_profit_margin',
                'sales_num',
                'image'
            ];
            $info = $model->getInfo(['id'=>$goods_id], true, $fields);
            if (!empty($info)) {
                //$model = new Product();
                $model = Product::findOne($goods_id);
                if (!empty($model)) {

                } else {
                    $model = new Product();

                }
                foreach ($info as $k => $v) {
                    $model->$k = $v;
                }
                $re = $model->save();
                if ($re) {
                    $db = Product::getDb();
                    $db->getIndex()->flushIndex();
                    return $this->returnJsonMsg('200', [], '同步成功！');
                } else {
                    return $this->returnJsonMsg('102', [], '同步失败，内部错误！');
                }
            } else {
                return $this->returnJsonMsg('102', [], '商品不存在');
            }

        }
        return $this->returnJsonMsg('101', [], '无效的参数id');

    }

    /**
     * 商品批量操作 type 1 上架 2 下架 3 发布 4 取消发布
     * @return json
     */
    public function actionBatchGoods()
    {
        $goods_id = RequestHelper::get('goods_id', '');
        $type = RequestHelper::get('type', 0, 'intval');
        if (!empty($goods_id)) {
            $goods_data = explode(',', $goods_id);
            if (!empty($goods_data)) {
                if (in_array($type, [1, 2, 3, 4])) {
                    if (in_array($type, [1, 2])) {//上下架
                        $re = Product::updateAll(['status'=>$type], ['id'=>$goods_data]);


                    } else {
                        if ($type == 3) {
                            $re = Product::updateAll(['single'=>1], ['id'=>$goods_data]);
                        } else {
                            $re = Product::updateAll(['single'=>2], ['id'=>$goods_data]);
                        }

                    }
                    if ($re > 0) {
                        $db = Product::getDb();
                        $db->getIndex()->flushIndex();
                    }
                } else {
                    return $this->returnJsonMsg('103', [], '类型id错误');
                }


            } else {
                return $this->returnJsonMsg('102', [], '商家id错误');
            }
        } else {
            return $this->returnJsonMsg('101', [], '无效的参数id');
        }
    }

}
