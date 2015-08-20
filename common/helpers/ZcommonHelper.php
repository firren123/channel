<?php
/**
 * 新版Wap-郑宇所做页面可复用的方法
 *
 * PHP Version 5
 *
 * @category  CHANNEL
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/8/13 16:55
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace common\helpers;


/**
 * 新版Wap-郑宇所做页面可复用的方法
 *
 * @category CHANNEL
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class ZcommonHelper
{
    //private $_default_arr_where = array();
    //private $_default_str_andwhere = '';
    //private $_default_arr_order = array();
    //private $_default_str_field = '*';
    //private $_default_int_offset = -1;
    //private $_default_int_limit = -1;


    /**
     * 方法curl
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $type           类型 get post
     * @param string $url            url
     * @param array  $arr_post_param post参数
     *
     * @return string 字符串
     */
    public function zcurl($type = 'get', $url = '', $arr_post_param = array())
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arr_post_param));
        } else {
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $str_return = curl_exec($ch);
        curl_close($ch);

        return $str_return;
    }

    /**
     * 生成随机字符串
     *
     * Author zhengyu@iyangpin.com
     *
     * @param int    $length 长度
     * @param string $chars  范围
     *
     * @return string
     */
    public function generateRandomString($length = 1, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str_return = '';
        for ($i = 0; $i < $length; $i++) {
            $str_return .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str_return;
    }


    /**
     * 记录日志
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $str       内容
     * @param string $file_path 文件路径
     *
     * @return void
     */
    public function zlog($str = '', $file_path = '')
    {
        $str_today = date("Ymd", time());
        //$str_sep = "----------------------------------------";

        if ($file_path == '') {
            //$content = "REMOTE_ADDR=" . $_SERVER['REMOTE_ADDR']
            //    . ", HTTP_X_FORWARDED_FOR=" . $_SERVER['HTTP_X_FORWARDED_FOR']
            //    . "\n" . date("H:i:s ") . $str;
            $content = date("H:i:s ") . $str;
            file_put_contents('/tmp/weixinlog/tmp_weixin_' . $str_today, $content, FILE_APPEND);
        }

        return;
    }

    /**
     * 检查是否是json
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $str 待检查
     *
     * @return bool true=是，false=否
     */
    public function zcheckJson($str = '')
    {
        return !(is_null(json_decode($str)));
    }



}
