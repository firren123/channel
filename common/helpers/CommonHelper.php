<?php
/**
 * 共同函数库
 *
 * PHP Version 5
 * 一些共同函数接口
 *
 * @category  I500M
 * @package   Channel
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @time      15/4/1 下午2:57
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      http://www.i500m.com/
 */
namespace common\helpers;

/**
 * Class CommonHelper
 * @category  PHP
 * @package   CommonHelper
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CommonHelper
{
    /**
     * 简介：Ajax 返回数据格式
     * @author  lichenjun@iyangpin.com。
     * @param string $code 代码
     * @param string $msg  提示信息
     * @param array  $data 数组
     * @return array
     */
    public static function ajaxReturn($code = '', $msg = '', $data = [])
    {
        $rs = [];
        $rs['code'] = !empty($code) ? $code : 'ok';
        $rs['msg'] = !empty($msg) ? $msg : '';
        $rs['data'] = !empty($data) ? $data : [];
        die(json_encode($rs));
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param int $length xx
     * @return string
     */
    public static function generatePassword($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $password;
    }

    /**
     * 简介：xx
     * @author  lichenjun@iyangpin.com。
     * @param string $str x
     * @param string $s   x
     * @return mixed
     */
    public static function replaceSpace($str, $s = ",")
    {
        $content = array(" ", "　", "\t", "\n", "\r", "\r\n", " ");
        $ret = str_replace($content, $s, $str);
        if ($ret) {
            return $ret;
        } else {
            return "";
        }
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $str xx
     * @return string
     */
    public static function semiangle($str)
    {
        $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ');

        return strtr($str, $arr);
    }

    /**
     * 获取用户主机系统
     * @return string
     */
    public static function getOS()
    {
        $agent = \Yii::$app->request->getUserAgent();
        if (stripos($agent, 'win') && stripos($agent, 'nt 6.1')) {
            $os = 'Windows 7';
        } elseif (stripos($agent, 'win') && (stripos($agent, 'nt 6.3') || stripos($agent, 'nt 6.2'))) {
            $os = 'Windows 8';
        } elseif (stripos($agent, 'win') && stripos($agent, 'nt 6.0')) {
            $os = 'Windows Vista';
        } elseif (stripos($agent, 'win') && stripos($agent, 'nt 5.2')) {
            $os = 'Windows Server 2003';
        } elseif (stripos($agent, 'win') && stripos($agent, 'nt 5.1')) {
            $os = 'Windows XP';
        } elseif (stripos($agent, 'win') && stripos($agent, 'nt 5')) {
            $os = 'Windows Server 2000';
        } elseif (stripos($agent, 'win') && stripos($agent, 'nt')) {
            $os = 'Windows NT';
        } elseif (stripos($agent, 'win') && stripos($agent, '32')) {
            $os = 'Windows 32';
        } elseif (stripos($agent, 'linux')) {
            $os = 'Linux';
        } elseif (stripos($agent, 'unix')) {
            $os = 'Unix';
        } elseif (stripos($agent, 'Mac') && stripos($agent, 'PC')) {
            $os = 'Macintosh';
        } elseif (stripos($agent, 'Mac') && stripos($agent, 'os')) {
            $os = 'Mac OS';
        } elseif (stripos($agent, 'FreeBSD')) {
            $os = 'FreeBSD';
        } elseif (stripos($agent, 'offline')) {
            $os = 'offline';
        } else {
            $os = 'Unknown';
        }
        return $os;
    }

    /**
     * 获取客户端浏览器
     * @return string
     */
    public static function getBrowser()
    {
        $agent = \Yii::$app->request->getUserAgent();
        if (strpos($agent, "Maxthon 2.0")) {
            return "Maxthon 2.0";
        }
        if (strpos($agent, "MSIE 9.0")) {
            return "ie9";
        }
        if (strpos($agent, "MSIE 8.0")) {
            return "ie8";
        }
        if (strpos($agent, "MSIE 7.0")) {
            return "ie7";
        }
        if (strpos($agent, "Firefox") || substr($agent, 0, 7) == 'Firefox') {
            return "Firefox";
        }
        if (strpos($agent, "Chrome") || substr($agent, 0, 6) == 'Chrome') {
            return "Chrome";
        }
        if (strpos($agent, "Safari") || substr($agent, 0, 6) == 'Safari') {
            return "Safari";
        }
        if (strpos($agent, "Opera") || substr($agent, 0, 5) == 'Opera') {
            return "Opera";
        }
        if (strpos($agent, "MSIE 10")) {
            return "ie10";
        }
        if (strpos($agent, "MSIE 11")) {
            return "ie11";
        }
        if (strpos($agent, "rv:11")) {
            return "ie11";
        }
        if (strpos($agent, "AppleWebKit")) {
            return "AppleWebKit";
        }
        return "unknown";

    }

    /**
     * 获取客户端ip地址
     * @return string
     */
    public static function getIp()
    {
        return \Yii::$app->request->getUserIP();
    }
}
