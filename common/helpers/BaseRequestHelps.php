<?php
/**
 * Created by PhpStorm.
 * User: lbc
 * Date: 15/3/17
 * Time: 上午10:42
 */

namespace common\helpers;


class BaseRequestHelps {

    public static function get($name = '', $default = '', $filter = null){
        return self::getParams($name, $default, $filter, $_GET);
    }

    public static function post($name = '', $default = '', $filter = null){
        return self::getParams($name, $default, $filter, $_POST);
    }

    public static function put($name = '', $default = '', $filter = null){
        static $_PUT	=	null;
        if(is_null($_PUT)){
            parse_str(file_get_contents('php://input'), $_PUT);
        }
        return self::getParams($name, $default, $filter, $_PUT);
    }

    public static function getMethod(){
        switch($_SERVER['REQUEST_METHOD']) {
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

    public static function getParams($name, $default = '', $filter = null, $input = null){
        if('' == $name){
            $data       =   $input;
            $filters    =   isset($filter)?$filter:'htmlspecialchars';
            if($filters) {
                if(is_string($filters)){
                    $filters    =   explode(',',$filters);
                }
                foreach($filters as $filter){
                    $data   =   self::array_map_recursive($filter,$data); // 参数过滤
                }
            }
        }elseif(isset($input[$name])) { // 取值操作
            $data       =   $input[$name];
            $filters    =   isset($filter)?$filter:'htmlspecialchars';
            if($filters) {
                if(is_string($filters)){
                    if(0 === strpos($filters,'/')){
                        if(1 !== preg_match($filters,(string)$data)){
                            // 支持正则验证
                            return   isset($default) ? $default : null;
                        }
                    }else{
                        $filters    =   explode(',',$filters);
                    }
                }elseif(is_int($filters)){
                    $filters    =   array($filters);
                }

                if (is_array($filters)) {
                    foreach($filters as $filter){
                        if (function_exists($filter)) {
                            $data   =   is_array($data) ? self::array_map_recursive($filter,$data) : $filter($data); // 参数过滤
                        }else{
                            $data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
                            if(false === $data) {
                                return   isset($default) ? $default : null;
                            }
                        }
                    }
                }
            }

        }else{ // 变量默认值
            $data       =    isset($default)?$default:null;
        }
        return $data;
    }

    /**
     * 简介：test
     * @author  lichenjun@iyangpin.com。
     * @param $filter
     * @param $data
     * @return array
     */
    public static function array_map_recursive($filter, $data) {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? self::array_map_recursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }
}