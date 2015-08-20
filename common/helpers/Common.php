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
     * 截取字符串
     * @param $string     字符串
     * @param $length     限制长度
     * @param string $etc 后缀
     * @return string
     */
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }

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
     * 获取客户端IP
     * @return string
     */
    public static function getIp()
    {
        $ip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
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
     * 获取短信模板
     * @param int $type 类型
     * @param int $code 验证码
     * @return string
     */
    public static function getSmsTemplate($type = 1, $code = 0)
    {
        switch ($type) {
            case 1 :
                /**登陆发送验证码**/
                $temp = '【i500】尊敬的用户，您的i500登录验证码为'.$code.'，如非本人操作请忽略此短信，如有疑问请咨询400-661-1690';
                break;
            case 2 :
                /**第一次登陆发送密码**/
                $temp = '【i500】登陆密码为'.$code.'，如非本人操作请忽略此短信，如有疑问请咨询400-661-1690';
                break;
            case 3 :
                /**找回密码**/
                $temp = '【i500】操作为找回密码的验证码为'.$code.'，如非本人操作请忽略此短信，如有疑问请咨询400-661-1690';
                break;
            default :
                $temp = '';
        }
        return $temp;
    }

    /**
     * 将公里数转换成友好的距离
     * @param float $distance 浮点数的公里数
     * @return string
     */
    public static function getDistance($distance)
    {
        $distance = number_format($distance, 3);
        $dis = $distance.'公里';
        //如果大于100米 小于 1公里的话 显示为米
        if ($distance >= 0.1 & $distance < 1) {
            $dis = ($distance * 1000).'米';
        }
        //如果小于等于100米 则显示小于100m
        if ($distance < 0.1) {
            $dis = '小于100米';
        }
        return $dis;
    }
}
