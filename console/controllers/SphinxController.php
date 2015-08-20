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
 * @time      15/8/14 上午11:40
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace console\controllers;

use console\models\i500m\Product;
use yii\console\Controller;

/**
 * Sphinx
 *
 * @category Channel
 * @package  Sphinx
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class SphinxController extends Controller
{
    public function actionSync()
    {
        $model = new Product();
        $table = 'products_rt';
        $field = 'id,name,cate_first_id,brand_id,bar_code,description,is_self,fixed_price,status,single';

        $page = 1;
        $pageSize = 100;
        $count = $model->getCount(['single'=>1]);
        $key = ['id','name','cate_first_id','brand_id','bar_code','description','is_self','fixed_price','status','single'];
//        $values = [
//            [3,'润坤大厦',8,9,'1234132','asdfasd阿斯顿北京 中国，润村大厦',2,3,4,6],
//            [4,'m718文创园 青年路', 2, 3, 'asdfa2','青年路京 中国，常营',5,11,4,3],
//        ];
//        $re = \Yii::$app->sphinx->createCommand()->batchInsert('shoprt', $key, $values)->execute();
//        var_dump($re);
//        exit();
        $pageCount = ceil($count/$pageSize);
        while ($page <= $pageCount) {
            //echo $page;
            $list = $model->getPageList(['single'=>1], $field, '', $page, $pageSize);
            //var_dump($list);exit();
            foreach ($list as $k=>$v) {
                $values[$k] = [
                    intval($v['id']),
                    $v['name'],
                    $v['cate_first_id'],
                    $v['brand_id'],
                    $v['bar_code'],
                    $v['description'],
                    $v['is_self'],
                    $v['fixed_price'],
                    $v['status'],
                    $v['single'],
                ];
            }
           // var_dump(\Yii::$app->sphinx);exit();

            $re = \Yii::$app->sphinx->createCommand()->batchInsert($table, $key, $values)->execute();
            unset($values);
            if (empty($re)) {
                echo '第'.$page."页商品信息写入sphinx失败!\n";
                \Yii::error('第'.$page.'页商品信息写入sphinx成功!');
                return false;
            } else {
                unset($re);
                echo '第'.$page."页商品信息写入sphinx成功!\n";
            }
            $page++;
        }
    }
}
