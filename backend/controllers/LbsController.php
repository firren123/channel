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

use backend\models\i500_social\UserService;
use backend\models\SocialMongo;
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
    public $enableCsrfValidation = false;
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
        $shop_list = $model->getNearByShop($lng, $lat, $dis);
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

    /**
     * 获取搜索建议词
     * @return array
     */
    public function actionGetSuggest()
    {
        $keywords = RequestHelper::get('keywords', '');
        $province = RequestHelper::get('province', '北京市');
        $suggest = [];
        if (!empty($keywords)) {
            $model = new Lbs();
            $res = $model->getSuggest($keywords, $province);
            if ($res['status'] == 0) {
                $data = ArrayHelper::getValue($res, 'data', []);
                $pos = [];
                //var_dump($data);
                if (!empty($data)) {
                    foreach ($data as $k => $v) {
                        $location = $model->convertToBaidu($v['location']['lng'], $v['location']['lat']);
                       // var_dump($location);exit();

                       // var_dump($v['location']);exit();
                        if (!empty($location)) {
                            $pos['x'] = number_format($location['x'], 6);
                            $pos['y'] = number_format($location['y'], 6);
                        }


                        $suggest[$k] = [
                            'title'=>$v['title'],
                            'address'=>$v['address'],
                            'location'=>$pos,
                        ];
                    }
                }
            }
            //var_dump($res);
            return $this->returnJsonMsg('200', $suggest, 'ok');
        }
    }
    /**
     * 检查是否在用户服务范围之内
     * @return array
     */
    public function actionCheckAddress()
    {
        $model = new Lbs();
        $lng = RequestHelper::get('lng', 0.000000, 'float');
        $lat = RequestHelper::get('lat', 0.000000, 'float');
        $shop_id = RequestHelper::get('shop_id', "0");
        $res = $model->checkAddress($lng, $lat, $shop_id);
        //$shop_list = $model->getNearShopPage($lng, $lat, $dis, 3, 2);
        if (!empty($res)) {
            return $this->returnJsonMsg('200', [], '此地址在服务范围之内');
        } else {
            return $this->returnJsonMsg('101', [], '此地址不在服务范围之内！');
        }
    }
    /**
     * 根据gps坐标 或者百度坐标获取poi
     *  type 1 百度 2 gps
     * @return array
     */
    public function actionGetPoi()
    {
        $model = new Lbs();
        $lng = RequestHelper::get('lng', 0.000000, 'float');
        $lat = RequestHelper::get('lat', 0.000000, 'float');
        $type = RequestHelper::get('type', 1, 'intval');
        if (in_array($type, [1, 2])) {
            if ($type == 2) {

                $location = $model->convertToBaidu($lng, $lat, 1);
                if (!empty($location)) {
                    $lng = number_format($location['x'], 6);
                    $lat = number_format($location['y'], 6);
                }
            }
        }
        $name = $model->getPoi($lng, $lat);
        if (!empty($name)) {
            return $this->returnJsonMsg('200', ['name'=>$name], '位置获取成功');
        } else {
            return $this->returnJsonMsg('404', [], '暂无数据！');
        }
    }
    public function actionAddUserService()
    {
        $mobile = RequestHelper::post('mobile','');
        $model = new UserService();
        $select = ['uid', 'mobile', 'name', 'province_id', 'lng', 'lat', 'user_name', 'user_card', 'user_sex', 'user_age', 'user_home', 'audit_status', 'status', 'is_deleted', 'create_time'];
        $info = $model->getInfo(['mobile'=>$mobile], true, $select);
        if (!empty($info)) {
            $info['lng'] = floatval($info['lng']);
            $info['lat'] = floatval($info['lat']);
            $info['loc'] = [floatval($info['lng']),floatval($info['lat'])];
            $info['audit_status'] = intval($info['audit_status']);
            $info['status'] = intval($info['status']);
            $info['is_deleted'] = intval($info['is_deleted']);
            $mongo = new SocialMongo();

            $re = $mongo->addOne($info);
            if ($re) {
                $this->returnJsonMsg(200, [], 'SUCCESS');
            } else {
                $this->returnJsonMsg(101, [], '添加失败！');
            }
        } else {
            $this->returnJsonMsg(102, [], '无数据！');
        }

    }
    public function actionEditUserService()
    {
        $mobile = RequestHelper::post('mobile','');
        $model = new UserService();
        $select = ['uid', 'mobile', 'name', 'province_id', 'lng', 'lat', 'user_name', 'user_card', 'user_sex', 'user_age', 'user_home', 'audit_status', 'status', 'is_deleted', 'create_time'];
        $info = $model->getInfo(['mobile'=>$mobile], true, $select);
        if (empty($info)) {
            $this->returnJsonMsg(102, [], '无数据！');
        } else {
            $info['lng'] = floatval($info['lng']);
            $info['lat'] = floatval($info['lat']);
            $info['loc'] = [floatval($info['lng']),floatval($info['lat'])];
            $info['audit_status'] = intval($info['audit_status']);
            $info['status'] = intval($info['status']);
            $info['is_deleted'] = intval($info['is_deleted']);
            $mongo = new SocialMongo();
            $re = $mongo->editOne($mobile, $info);
            if ($re) {
                $this->returnJsonMsg(200, [], 'SUCCESS');
            } else {
                $this->returnJsonMsg(101, [], '更新失败！');
            }
        }

    }
    public function actionGetNearUser()
    {
        $model = new Lbs();
        $lng = RequestHelper::get('lng', 0.000000, 'float');
        $lat = RequestHelper::get('lat', 0.000000, 'float');
        $dis = RequestHelper::get('dis', 2, 'intval');
        $shop_list = $model->getNearUser($lng, $lat, $dis);
        //$shop_list = $model->getNearShopPage($lng, $lat, $dis, 3, 2);
        //var_dump($shop_list);
        if (!empty($shop_list)) {
            return $this->returnJsonMsg('200', $shop_list, '商家获取成功');
        } else {
            return $this->returnJsonMsg('404', [], '附近暂无服务商，敬请期待！');
        }
    }
}
