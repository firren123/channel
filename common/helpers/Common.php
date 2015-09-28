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
//        //如果大于100米 小于 1公里的话 显示为米
//        if ($distance >= 0.1 & $distance < 1) {
//            $dis = ($distance * 1000).'m';
//        }
//        //如果小于等于100米 则显示小于100m
//        if ($distance < 0.1) {
//            $dis = '小于100m';
//        }


        //精确到十米 2015-09-26
        if ($distance >= 0.01 & $distance < 1) {
            $dis = ($distance * 1000).'m';
        }
        //如果小于等于100米 则显示小于100m
        if ($distance < 0.01) {
            $dis = '小于10m';
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
    /**
     * 根据城市id 获取小区表名
     * @param int $city_id 城市id
     * @return string
     */
    public static function getCommunityTable($city_id)
    {
        switch($city_id)
        {
            case 1:
                $table_name = 'community_beijing';
                break;
            case 258:
                $table_name = 'community_chengdu';
                break;
            case 261:
                $table_name = 'community_luzhou';
                break;
            case 14:
                $table_name = 'community_taiyuan';
                break;
            case 222:
                $table_name = 'community_nanning';
                break;
            case 230:
                $table_name = 'community_yulin';
                break;
            case 223:
                $table_name = 'community_liuzhou';
                break;
            case 224:
                $table_name = 'community_guilin';
                break;
            case 2:
                $table_name = 'community_tianjin';
                break;
            case 3:
                $table_name = 'community_shijiazhuang';
                break;
            case 74:
                $table_name = 'community_nanjing';
                break;
            case 257:
                $table_name = 'community_chongqing';
                break;
            case 311:
                $table_name = 'community_xian';
                break;
            case 135:
                $table_name = 'community_jinan';
                break;
            case 4:
                $table_name = 'community_tangshan';
                break;
            case 5:
                $table_name = 'community_qinhuangdao';
                break;
            case 6:
                $table_name = 'community_handan';
                break;
            case 7:
                $table_name = 'community_xingtai';
                break;
            case 8:
                $table_name = 'community_baoding';
                break;
            case 9:
                $table_name = 'community_zhangjiakou';
                break;
            case 10:
                $table_name = 'community_chengde';
                break;
            case 11:
                $table_name = 'community_hengshui';
                break;
            case 12:
                $table_name = 'community_langfang';
                break;
            case 13:
                $table_name = 'community_cangzhou';
                break;
            case 136:
                $table_name = 'community_qingdao';
                break;
            case 75:
                $table_name = 'community_wuxi';
                break;
            case 78:
                $table_name = 'community_suzhou';
                break;
            case 335:
                $table_name = 'community_xining';
                break;
            case 288:
                $table_name = 'community_kunming';
                break;

            default:
                $table_name = 'community_beijing';
        }
        return $table_name;
    }
}
