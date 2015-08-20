<?php
/**
 * 商品RT索引
 *
 * PHP Version 5
 * 商品RT索引操作类
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/14 上午9:44
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models;

use yii\sphinx\ActiveRecord;

/**
 * ProductsRt
 *
 * @category Channel
 * @package  ProductsRt
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class ProductsRt extends ActiveRecord
{
    /**
     * 定义索引名称
     * @return string
     */
    public static function indexName()
    {
        return 'products_rt';
    }

    /**
     * 插入商品
     * @param array $data 插入索引的数据
     * @return bool
     */
    public function insertProducts($data)
    {
        $re = false;
        if (!empty($data) && is_array($data)) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
            $re = $this->insert();
        }
        return $re;
    }

    /**
     * 修改商品信息
     * @param int   $product_id 商品id
     * @param array $data       要修改的数据
     * @return bool|int
     */
    public function updateProducts($product_id, $data)
    {
        $re = false;
        if (!empty($product_id) && !empty($data)) {
            $model = $this->findOne(['id'=>$product_id]);
           // $this->id = $product_id;
            foreach ($data as $k => $v) {
                $model->$k = $v;
            }
            $re = $model->update();
        }
        return $re;
    }
}
