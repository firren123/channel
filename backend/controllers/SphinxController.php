<?php
/**
 * Sphinx搜索处理类
 *
 * PHP Version 5
 * Sphinx搜索处理类
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/13 下午5:04
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\controllers;

use backend\models\i500m\Products;
use backend\models\ProductsRt;
use common\helpers\RequestHelper;
use common\vendor\Scws;

/**
 * Sphinx
 *
 * @category Channel
 * @package  Sphinx
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class SphinxController extends BaseController
{
    /**
     * 商品搜索
     * @return string
     */
    public function actionSearchGoods()
    {
        $keywords = RequestHelper::get('keywords', '');
        $cat_id = RequestHelper::get('cat_id', 0, 'intval');
        $brand_id = RequestHelper::get('brand_id', 0, 'intval');
        $map = [];
        if (!empty($cat_id)) {
            $map['cate_first_id'] = intval($cat_id);
        }
        if (!empty($brand_id)) {
            $map['brand_id'] = $brand_id;
        }
        if (!empty($keywords)) {
            $keywords = Scws::getWords($keywords);
        }
        $model = new ProductsRt();
        $list = $model->find()->where($map)->match($keywords)->asArray()->all();
        return $this->returnJsonMsg('200', $list, 'ok');
    }

    /**
     * 新增商品实时索引
     * @return bool
     */
    public function actionInsertGoods()
    {
        $goods_id = RequestHelper::get('goods_id', 0, 'intval');
        if (!empty($goods_id)) {
            $model = new Products();
            $fields = 'id,name,bar_code,description,cate_first_id,brand_id, is_self, fixed_price,status,single';
            $info = $model->getInfo(['id'=>$goods_id], true, $fields);
            if (!empty($info)) {
                $model = new ProductsRt();
                $res = $model->findOne(['id'=>$goods_id]);
                if ($res) {
                    return $this->returnJsonMsg('104', [], '此商品已经存在');
                } else {
                    $re = $model->insertProducts($info);
                    if ($re) {
                        return $this->returnJsonMsg('200', [], '同步成功！');
                    } else {
                        return $this->returnJsonMsg('102', [], '同步失败，内部错误！');
                    }
                }


            } else {
                return $this->returnJsonMsg('102', [], '同步失败，内部错误！');
            }
        } else {
            return $this->returnJsonMsg('103', [], '商品不存在');
        }
    }

    /**
     * 根据商品商品信息修改实时索引
     * @return bool|int
     */
    public function actionSyncGoods()
    {
        $goods_id = RequestHelper::get('goods_id', 0, 'intval');
        if (!empty($goods_id)) {
            $model = new Products();
            $fields = 'id,name,bar_code,description,cate_first_id,brand_id, is_self, fixed_price,status,single';
            $info = $model->getInfo(['id'=>$goods_id], true, $fields);
            if (!empty($info)) {
                $model = new ProductsRt();
                $re = $model->updateProducts($goods_id, $info);
                if ($re) {
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
}
