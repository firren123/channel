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

use linslin\yii2\curl\Curl;
/**
 * Class CurlHelper
 * @category  PHP
 * @package   CurlHelper
 * @filename  CurlHelper
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  ${DATE} ${TIME}
 * @link      http://www.i500m.com/
 */
class CurlHelper
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $app_key   xx
     * @param string $timestamp xx
     * @param array  $data      xx
     * @return string
     */
    private static function _createSign($app_key = '', $timestamp = '', $data = array())
    {

        $val = '';
        krsort($data);
        foreach ($data as $k => $v) {
            //如果是数组
            if (is_array($v)) {
                $val .= implode('', $v);
            } else {
                $val .= $v;
            }

        }
        file_put_contents('/tmp/wap_data.log', var_export($data, true), FILE_APPEND);
        return md5(md5(md5($app_key . $timestamp) . md5($timestamp)) . md5($val));
    }
    public static function get($url = '', $type = 'server')
    {
        $host = '';
        $app_id = \Yii::$app->params['appId'];
        $app_key = \Yii::$app->params['appKey'];

        $timestamp = time();
        $data = array();
        if('server' == $type){
            $host = \Yii::$app->params['serverUrl'];
            $ex = explode('?', $url);
            if(isset($ex[1])){
                $param = explode('&', $ex[1]);
                $data = array();
                foreach($param as $k=>$v){
                    $ex_v = explode('=', $v);
                    $data[$ex_v[0]] = $ex_v[1];
                }
            }
            $sign = self::_createSign($app_key, $timestamp, $data);
            if (stripos($url, '?') === false) {
                $url .= '?appId=' . $app_id . '&timestamp=' . $timestamp . '&sign=' . $sign;
            } else {
                $url .= '&appId=' . $app_id . '&timestamp=' . $timestamp . '&sign=' . $sign;
            }
        }elseif('api' == $type){
            $access_token = \Yii::$app->params['access_token'];
            $host = \Yii::$app->params['apiUrl'];
            if (stripos($url, '?') === false) {
                $url .= '?access-token=' . $access_token;
            } else {
                $url .= '&access-token=' . $access_token;
            }
        }else{

        }
        $url = strstr($url, 'http://') ? $url : $host.$url;

        $curl = new Curl();
        $response = $curl->get($url);
        $response = json_decode($response,true);
        return $response;
    }

    public static function post($url = '', $post = array(), $type = 'server'){
        $host = '';
        $app_id = \Yii::$app->params['appId'];
        $app_key = \Yii::$app->params['appKey'];
        $timestamp = time();
        if('server' == $type){
            $host = \Yii::$app->params['serverUrl'];
            $sign = self::_createSign($app_key, $timestamp, $post);
            $post['appId']      = $app_id;
            $post['timestamp']  = $timestamp;
            $post['sign']       = $sign;
        }elseif('api' == $type){
            $host = \Yii::$app->params['apiUrl'];
            $access_token = \Yii::$app->params['access_token'];
            if (stripos($url, '?') === false) {
                $url .= '?access-token=' . $access_token;
            } else {
                $url .= '&access-token=' . $access_token;
            }
        }else{

        }
        $url = strstr($url, 'http://') ? $url : $host.$url;
        $curl = new Curl();
        $response = $curl->reset()
            ->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($post
                ))
            ->post($url);
        file_put_contents('/tmp/shop_response.log', $url."|结果".$response."\n\r", FILE_APPEND);
        $response = json_decode($response,true);
        return $response;
    }

    /**
     * 强制restful put 修改数据
     * @param string $url url中需带要修改的id
     * @param array $post
     * @return mixed
     */
    public static function put($url = '', $post = array()){


        $host = \Yii::$app->params['apiUrl'];
        $access_token = \Yii::$app->params['access_token'];
        if (stripos($url, '?') === false) {
            $url .= '?access-token=' . $access_token;
        } else {
            $url .= '&access-token=' . $access_token;
        }
        $url = strstr($url, 'http://') ? $url : $host.$url;
        $curl = new Curl();
        $response = $curl->reset()
            ->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($post)
            )
            ->put($url);
        $response = json_decode($response,true);
        return $response;
    }

    /**
     * 删除 restful api
     * @param string $url
     * @return mixed
     */
    public static function delete($url = ''){


        $host = \Yii::$app->params['apiUrl'];
        $access_token = \Yii::$app->params['access_token'];
        if (stripos($url, '?') === false) {
            $url .= '?access-token=' . $access_token;
        } else {
            $url .= '&access-token=' . $access_token;
        }
        $url = strstr($url, 'http://') ? $url : $host.$url;
        $curl = new Curl();
        $response = $curl->delete($url);
        $response = json_decode($response,true);
        return $response;
    }
}

