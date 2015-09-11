<?php
/**
 * 订单相关业务
 *
 * PHP Version 5
 * 订单相关业务
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/9/6 下午6:03 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\controllers;

use common\helpers\RequestHelper;
/**
 * Order
 *
 * @category Channel
 * @package  Order
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class OrderController extends BaseController
{
    public function actionCreateOrderSn()
    {
        $province_id = RequestHelper::get('province_id', 0, 'intval');
        $mobile = RequestHelper::get('mobile', '');
        if (strlen($province_id) > 2) {
            return $this->returnJsonMsg(101, [], '无效的省id');
        }
        if (strlen($mobile) != 11) {
            return $this->returnJsonMsg(102, [], '无效的手机号');
        }
        $province_id = str_pad($province_id, 2, 0, STR_PAD_LEFT);
        $mobile = substr($mobile, -4);
        $time = microtime();
        $now = explode(' ', $time);
        $microsecond = ceil($now[0] * 1000000);
        $order_sn = $province_id.date("ymdHis", $now[1]).$microsecond.$mobile;
        return $this->returnJsonMsg(200, $order_sn, 'SUCCESS');
    }
}
