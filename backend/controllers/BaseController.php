<?php
/**
 * 签名
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  BaseController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/3 下午5:53
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\controllers;

use common\helpers\Common;
use common\helpers\RequestHelper;
use yii\web\Controller;
use Yii;

/**
 * BaseController
 *
 * @category Channel
 * @package  BaseController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class BaseController extends Controller
{
    public    $size = 20;
    protected $params = null;
    private $_startTime;
    private $_uniqueCode;
    /**
     * 初始化
     * @return array
     */
    public function init()
    {
        parent::init();
        //获取请求类型
        $method = RequestHelper::getMethod();
        switch ($method) {
        case 'POST' :
            $this->params = RequestHelper::post();
            break;
        case 'PUT' :
            $this->params = RequestHelper::put();
            break;
        default :
            $this->params = RequestHelper::get();
            break;
        }
        file_put_contents('/tmp/app_request_param.log', "请求时间：".date('Y-m-d H:i:s')." 请求参数:". var_export($this->params, true)."\n", FILE_APPEND);
        if (!\Yii::$app->params['sign_debug']) {
            //var_dump($this->params);
            if (!isset($this->params['appId'])) {
                $this->returnJsonMsg('501', [], Common::C('code', '501'));
                $this->_recordLog();
                die();
            }
            $app_id = $this->params['appId'];
            unset($this->params['appId']);

            if (!isset($this->params['timestamp'])) {
                $this->returnJsonMsg('502', [], Common::C('code', '502'));
                $this->_recordLog();
                die();
            }
            $timestamp = $this->params['timestamp'];
            unset($this->params['timestamp']);

            if (!isset($this->params['sign'])) {
                $this->returnJsonMsg('503', [], Common::C('code', '503'));
                $this->_recordLog();
                die();
            }
            $sign = $this->params['sign'];
            unset($this->params['sign']);

            $this->_verifySign($sign, $app_id, $timestamp);       //验证签名
        }
        //exit();
    }
    /**
     * 签名验证
     * @param string $sign      签名
     * @param string $app_id    AppID
     * @param string $timestamp 时间戳
     * @return array
     */
    private function _verifySign($sign = '', $app_id = '', $timestamp = '')
    {
        if ($this->_createSign($app_id, $timestamp) != $sign) {
            $this->returnJsonMsg('505', [], Common::C('code', '505'));
            $this->_recordLog();
            die();
        }
    }

    /**
     * 构造签名
     * @param string $app_id    AppID
     * @param string $timestamp 时间戳
     * @return string
     */
    private function _createSign($app_id = '', $timestamp = '')
    {
        $app_code = \Yii::$app->params['APP_CODE'];

        if (!isset($app_code[$app_id])) {
            $this->returnJsonMsg('403', ['appId'=>$app_id], 'The appID does not exist');
        }
        $val  = '';
        if ($this->params) {
            $params = $this->params;
            krsort($params);
            foreach ($params as $k=>$v) {
                $val .= $v;
            }
            file_put_contents('/tmp/server_data.log', var_export($params, true), FILE_APPEND);
        }
        $sign = md5(md5(md5($app_code[$app_id].$timestamp).md5($timestamp)).md5($val));
        return $sign;
    }
    /**
     * 返回JSON格式的数据
     * @param string $code    错误代码
     * @param array  $data    数据
     * @param string $message 错误说明
     * @return array
     */
    public function returnJsonMsg($code='', $data=array(), $message='')
    {
        $arr = array(
            'code' => $code,
            'data' => $data,
            'message' => $message,
        );
        //file_put_contents('/tmp/app_send_log.log', "执行时间：".date('Y-m-d H:i:s')." 返回结果".var_export($arr, true)."\n", FILE_APPEND);
        $ret = json_encode($arr);
        $ret_str = str_replace('null', '""', $ret);
        echo $ret_str;
        //\Yii::$app->end();
        //die($ret_str);
    }
    public function error()
    {

    }

    /**
     * 前置操作
     * @param \yii\base\Action $action 控制器类
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->_startTime = microtime(true);
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->_uniqueCode = uniqid();
        $path = Yii::$app->request->getUrl();
        $action = $action->controller->id;
        file_put_contents('/tmp/channel.log', $this->_uniqueCode .'| start |' .date("Y-m-d H:i:s") .' |type: '.$action. ' | path:'. $path."\n", FILE_APPEND);
        return true;
    }

    /**
     * 后置操作
     *
     * @param \yii\base\Action $action 控制器类
     * @param mixed            $result 结果集
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        $this->_recordLog();
        return parent::afterAction($action, $result);
    }
    private function _recordLog()
    {
        $time = microtime(true) - $this->_startTime;
        $end = $this->_uniqueCode.'| end |'.date("Y-m-d H:i:s"). ' | time:'. $time."\n";
        file_put_contents('/tmp/channel.log', $end, FILE_APPEND);
    }
}
