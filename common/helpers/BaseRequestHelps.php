<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  MqController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/9 上午10:10
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace common\helpers;

/**
 * BaseRequestHelps
 *
 * @category Admin
 * @package  BaseRequestHelps
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class BaseRequestHelps
{
    /**
     * 单行函数说明
     *
     * @param string $name    名称
     * @param string $default 值
     * @param null   $filter  值
     *
     * @return array|mixed|null|string
     */
    public static function get($name = '', $default = '', $filter = null)
    {
        return self::getParams($name, $default, $filter, $_GET);
    }

    /**
     * 单行函数说明
     *
     * @param string $name    名称
     * @param string $default 值
     * @param null   $filter  值
     *
     * @return array|mixed|null|string
     */
    public static function post($name = '', $default = '', $filter = null)
    {
        return self::getParams($name, $default, $filter, $_POST);
    }

    /**
     * 单行函数说明
     *
     * @param string $name    名称
     * @param string $default 值
     * @param null   $filter  值
     *
     * @return array|mixed|null|string
     */
    public static function put($name = '', $default = '', $filter = null)
    {
        static $_PUT	=	null;
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
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $input  =  'POST';
                break;
            case 'PUT':
                $input 	=	'PUT';
                break;
            default:
                $input  =  'GET';
        }
        return $input;
    }

    /**
     * 单行函数说明
     *
     * @param $name
     * @param string $default
     * @param null $filter
     * @param null $input
     *
     * @return array|mixed|null|string
     */
    public static function getParams($name, $default = '', $filter = null, $input = null)
    {
        if ('' == $name) {
            $data       =   $input;
            $filters    =   isset($filter)?$filter:'htmlspecialchars';
            if ($filters) {
                if (is_string($filters)) {
                    $filters    =   explode(',', $filters);
                }
                foreach ($filters as $filter) {
                    $data   =   self::array_map_recursive($filter, $data); // 参数过滤
                }
            }
        } elseif (isset($input[$name])) { // 取值操作
            $data = $input[$name];
            $filters = isset($filter)?$filter:'htmlspecialchars';
            if ($filters) {
                if (is_string($filters)) {
                    if (strpos($filters, '/') === 0) {
                        if (preg_match($filters, (string)$data)!==1) {
                            // 支持正则验证
                            return   isset($default) ? $default : null;
                        }
                    } else {
                        $filters    =   explode(',', $filters);
                    }
                } elseif (is_int($filters)) {
                    $filters    =   array($filters);
                }
                if (is_array($filters)) {
                    foreach ($filters as $filter) {
                        if (function_exists($filter)) {
                            $data   =   is_array($data) ? self::array_map_recursive($filter, $data) : $filter($data); // 参数过滤
                        } else {
                            $data   =   filter_var($data, is_int($filter) ? $filter : filter_id($filter));
                            if (false === $data) {
                                return   isset($default) ? $default : null;
                            }
                        }
                    }
                }
            }
        } else { // 变量默认值
            $data       =    isset($default)?$default:null;
        }
        return $data;
    }

    /**
     * 单行函数说明
     *
     * @param $filter
     * @param $data
     *
     * @return array
     */
    public static function array_map_recursive($filter, $data)
    {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? self::array_map_recursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }
}
