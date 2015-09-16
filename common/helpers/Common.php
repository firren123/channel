<?php
/**
 * 公共方法库
 * @category  WAP
 * @package   公共方法库
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/3/19 16:28
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      linxinliang@iyangpin.com
 */

namespace common\helpers;

use Yii;

class Common
{
    /**
     * 验证手机号
     * @param $mobile 手机号
     * @return bool
     */
    public static function validateMobile($mobile)
    {
        $preg = Yii::$app->params['mobilePreg'];
        if (preg_match($preg, $mobile)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 获取6位随机数
     * @return int
     */
    public static function getRandomNumber()
    {
        return mt_rand(100000, 999999);
    }

    /**
     * 将公里数转换成友好的距离
     * @param float $distance 浮点数的公里数
     * @return string
     */
    public static function getDistance($distance)
    {
        $distance = number_format($distance, 3);
        $dis = $distance.'km';
        //如果大于100米 小于 1公里的话 显示为米
        if ($distance >= 0.1 & $distance < 1) {
            $dis = ($distance * 1000).'m';
        }
        //如果小于等于100米 则显示小于100m
        if ($distance < 0.1) {
            $dis = '小于100m';
        }
        return $dis;
    }
    /**
     * 获取参数
     * @param string $param1 第一个参数
     * @param string $param2 第二个参数
     * @return int
     */
    public static function C($param1 = '', $param2 = '')
    {
        if ($param1 && $param2) {
            return \Yii::$app->params[$param1][$param2];
        } else {
            return \Yii::$app->params[$param1];
        }
    }
}
