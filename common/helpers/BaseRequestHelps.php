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
 * Class BaseRequestHelps
 * @category  PHP
 * @package   BaseRequestHelps
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class BaseRequestHelps
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $name    xx
     * @param string $default xx
     * @param null   $filter  xx
     * @return array|mixed|null|string
     */
    public static function get($name='', $default = '', $filter = null)
    {
        return self::getParams($name, $default, $filter, $_GET);
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $name    xx
     * @param string $default xx
     * @param null   $filter  xx
     * @return array|mixed|null|string
     */
    public static function post($name='', $default = '', $filter = null)
    {
        return self::getParams($name, $default, $filter, $_POST);
    }

    /**
     * 单行函数说明
     *
     * @param string $name    xx
     * @param string $default xx
     * @param null   $filter  xx
     *
     * @return array|mixed|null|string
     */
    public static function put($name = '', $default = '', $filter = null)
    {
        static $_PUT = null;
        if (is_null($_PUT)) {
            parse_str(file_get_contents('php://input'), $_PUT);
        }
        return self::getParams($name, $default, $filter, $_PUT);
    }
    /**
     * 单行函数说明
     *
     * @return string
     */
    public static function getMethod()
    {
        switch($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $input  =  'POST';
            break;
        case 'PUT':
            $input  =  'PUT';
            break;
        default:
            $input  =  'GET';
        }
        return $input;
    }
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $name    xx
     * @param string $default xx
     * @param null   $filter  xx
     * @param null   $input   xx
     * @return array|mixed|null|string
     */
    public static function getParams($name, $default = '', $filter = null, $input = null)
    {
        if (isset($input[$name])) { // 取值操作
            $data = $input[$name];
            $filters = isset($filter) ? $filter : 'htmlspecialchars';
            if ($filters) {
                if (is_string($filters)) {
                    if (0 === strpos($filters, '/')) {
                        if (1 !== preg_match($filters, (string)$data)) {
                            // 支持正则验证
                            return isset($default) ? $default : null;
                        }
                    } else {
                        $filters = explode(',', $filters);
                    }
                } elseif (is_int($filters)) {
                    $filters = array($filters);
                }

                if (is_array($filters)) {
                    foreach ($filters as $filter) {
                        if (function_exists($filter)) {
                            $data = is_array($data) ? self::arrayMapRecursive($filter, $data) : $filter($data); // 参数过滤
                        } else {
                            $data = filter_var($data, is_int($filter) ? $filter : filter_id($filter));
                            if (false === $data) {
                                return isset($default) ? $default : null;
                            }
                        }
                    }
                }
            }

        } else { // 变量默认值
            $data = isset($default) ? $default : null;
        }
        return $data;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $filter xx
     * @param array  $data   xx
     * @return array
     */
    public static function arrayMapRecursive($filter, $data)
    {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? self::arrayMapRecursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }
}
