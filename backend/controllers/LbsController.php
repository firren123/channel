<?php
/**
 * 定位服务
 *
 * PHP Version 5
 * 定位服务
 *
 * @category  I500M
 * @package   Lbs
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/15 上午10:42
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\controllers;

use common\helpers\RequestHelper;
use backend\models\Lbs;
use yii\helpers\ArrayHelper;

/**
 * Lbs
 *
 * @category Channel
 * @package  Lbs
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class LbsController extends BaseController
{
    /**
     * 获取附近的商家
     * @return string
     */
    public function actionNearShop()
    {
        $model = new Lbs();
        $lng = RequestHelper::get('lng', 0.000000, 'float');
        $lat = RequestHelper::get('lat', 0.000000, 'float');
        $dis = RequestHelper::get('dis', 2, 'intval');
        $shop_list = $model->getNearByShop($lng, $lat, $dis, 200);
        //$shop_list = $model->getNearShopPage($lng, $lat, $dis, 3, 2);
        //var_dump($shop_list);
        if (!empty($shop_list)) {
            return $this->returnJsonMsg('200', $shop_list, '商家获取成功');
        } else {
            return $this->returnJsonMsg('404', [], '附近暂无服务商，敬请期待！');
        }
    }
    /**
     * 获取附近的商家带分页
     * @return string
     */
    public function actionNearShopPage()
    {
        $model = new Lbs();
        $lng = RequestHelper::get('lng', 0.000000, 'float');
        $lat = RequestHelper::get('lat', 0.000000, 'float');
        $dis = RequestHelper::get('dis', 2, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        if (empty($lng) || empty($lat)) {
            return $this->returnJsonMsg('101', [], '参数错误！');
        }
        $shop_list = $model->getNearShopPage($lng, $lat, $dis, 3, $page);
        //var_dump($shop_list);
        if (!empty($shop_list)) {
            return $this->returnJsonMsg('200', $shop_list, '商家获取成功');
        } else {
            return $this->returnJsonMsg('404', [], '附近暂无服务商，敬请期待！');
        }
    }
    /**
     * 获取附近的人
     * @return string
     */
    public function actionNearPeople()
    {

    }

    /**
     * 用户位置日志记录
     * @return string
     */
    public function actionUserLog()
    {
        $mobile = RequestHelper::get('mobile', '');
        $lng = RequestHelper::get('lng', 0.000000, 'float');
        $lat = RequestHelper::get('lat', 0.000000, 'float');
        $poi = RequestHelper::get('poi', '');
        if (empty($lng) || empty($lat) || empty($poi)) {
            return $this->returnJsonMsg('102', [], '参数不完整');
        }
        $model = new Lbs();
        $re = $model->insertPoiLog($mobile, $lng, $lat, $poi);
        if ($re) {
            return $this->returnJsonMsg('200', $re, 'ok');
        } else {
            return $this->returnJsonMsg('101', $re, 'error');
        }

    }
    public function actionGetPoint()
    {
        $address = RequestHelper::get('address', '');

        if (!empty($address)) {
            $model = new Lbs();
            $res = $model->getPointByAddress($address);
            if (!empty($res['result'])) {
                $data = ArrayHelper::getValue($res, 'result.location', []);
                return $this->returnJsonMsg('200', $data, 'ok');
            } else {
                return $this->returnJsonMsg('404', [], '暂无数据');
            }

        } else {
            return $this->returnJsonMsg('102', [], '参数不正确');
        }

    }
}
