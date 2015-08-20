<?php
/**
 * Icomet相关功能
 *
 * PHP Version 5
 *
 * @category  CHANNEL
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/8/18 13:43
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace backend\controllers;

use Yii;
use common\helpers\RequestHelper;
use common\helpers\ZcommonHelper;


/**
 * Icomet相关功能
 *
 * @category CHANNEL
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class CometController extends BaseController
{
    private $_icomet_url_admin = '';
    //private $_icomet_url_user = '';
    private $_switch_log = 1;//1=写log开启，其他=关闭

    /**
     * Action之前的处理
     *
     * //z20150818 关闭csrf
     *
     * Author zhengyu@iyangpin.com
     *
     * @param \yii\base\Action $action action
     *
     * @return bool
     *
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        $this->_icomet_url_admin = Yii::$app->params['icomet_url_admin'];

        return parent::beforeAction($action);
    }


    /**
     * 记录日志
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $str 内容
     *
     * @return void
     */
    private function _zlog($str = '')
    {
        if ($this->_switch_log !== 1) {
            return;
        }

        $log_dir = "/tmp/icometlog";
        $str_today = date("Ymd", time());
        //$str_sep = "----------------------------------------";

        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0700);
        }
        $full_file_path = $log_dir . '/tmp_icomet_' . $str_today;

        $content = date("H:i:s ") . $str . "\n";
        file_put_contents($full_file_path, $content, FILE_APPEND);

        return;
    }

    /**
     * Sign
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionSign()
    {
        $cname = RequestHelper::post('cname', '', 'trim');
        $expires = RequestHelper::post('expires', -1, 'intval');

        $helper_zcommon = new ZcommonHelper();
        $url = $this->_icomet_url_admin . "sign?cname=" . $cname . "&expires=" . $expires;
        $json = $helper_zcommon->zcurl('get', $url);
        //{"type":"sign","cname":"ch1","seq":1,"token":"xxxx","expires":30,"sub_timeout":30}

        if ($helper_zcommon->zcheckJson($json) === false) {
            $this->_zlog("[sign][失败][非json]:\n" . $json);
            $arr_return = array('zresult' => 0, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        }
        //$arr = json_decode($json, true);
        $this->_zlog("[sign][成功][是json]:\n" . $json);
        echo $json;
        return;
    }


    /**
     * Pub
     *
     * 发布唯一1条 clear+pub
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionPubone()
    {
        $cname = RequestHelper::post('cname', '', 'trim');
        $content = RequestHelper::post('content', '', 'trim');

        $helper_zcommon = new ZcommonHelper();


        $url_clear = $this->_icomet_url_admin . "clear?cname=" . $cname;
        $str_clear_result = $helper_zcommon->zcurl('get', $url_clear);
        //channel[ch1] not connected
        //ok 2
        $arr_tmp = explode(" ", $str_clear_result);
        if ($arr_tmp[0] != 'ok') {
            $this->_zlog("[pub_one][clear失败][非ok]:\n" . $str_clear_result);
            //$arr_return = array('zresult' => 0, 'zmsg' => '');
            //echo json_encode($arr_return);
            //return;
        }

        $url_pub = $this->_icomet_url_admin . "pub?cname=" . $cname . "&content=" . $content;
        $json_pub_result = $helper_zcommon->zcurl('get', $url_pub);
        if ($helper_zcommon->zcheckJson($json_pub_result) === false) {
            $this->_zlog("[pub_one][pub失败][非json]:\n" . $json_pub_result);
            $arr_return = array('zresult' => 0, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        }

        $this->_zlog("[pub_one][pub成功][是json]:\n" . $json_pub_result);
        echo $json_pub_result;
        return;
    }


    /**
     * Pub
     *
     * 新增发布1条 pub
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionPubqueue()
    {
        $cname = RequestHelper::post('cname', '', 'trim');
        $content = RequestHelper::post('content', '', 'trim');

        $helper_zcommon = new ZcommonHelper();


        $url_pub = $this->_icomet_url_admin . "pub?cname=" . $cname . "&content=" . $content;
        $json_pub_result = $helper_zcommon->zcurl('get', $url_pub);
        if ($helper_zcommon->zcheckJson($json_pub_result) === false) {
            $this->_zlog("[pub_queue][pub失败][非json]:\n" . $json_pub_result);
            $arr_return = array('zresult' => 0, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        }

        $this->_zlog("[pub_queue][pub成功][是json]:\n" . $json_pub_result);
        echo $json_pub_result;
        return;
    }


    /**
     * Clear
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionClear()
    {
        $cname = RequestHelper::post('cname', '', 'trim');

        $helper_zcommon = new ZcommonHelper();


        $url = $this->_icomet_url_admin . "clear?cname=" . $cname;
        $str_result = $helper_zcommon->zcurl('get', $url);
        //channel[ch1] not connected
        //ok 2
        $arr_tmp = explode(" ", $str_result);
        if ($arr_tmp[0] != 'ok') {
            $this->_zlog("[clear][clear失败][非ok]:\n" . $str_result);
            $arr_return = array('zresult' => 0, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        } else {
            //clear 成功就不记log了
            $arr_return = array('zresult' => 1, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        }
    }


    /**
     * Close
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionClose()
    {
        $cname = RequestHelper::post('cname', '', 'trim');

        $helper_zcommon = new ZcommonHelper();


        $url = $this->_icomet_url_admin . "close?cname=" . $cname;
        $str_result = $helper_zcommon->zcurl('get', $url);
        //channel[ch1] not connected
        //ok 2
        $arr_tmp = explode(" ", $str_result);
        if ($arr_tmp[0] != 'ok') {
            $this->_zlog("[close][close失败][非ok]:\n" . $str_result);
            $arr_return = array('zresult' => 0, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        } else {
            //成功就不记log了
            $arr_return = array('zresult' => 1, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        }
    }


    /**
     * Info
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionInfo()
    {
        $cname = RequestHelper::post('cname', '', 'trim');

        $helper_zcommon = new ZcommonHelper();


        $url = $this->_icomet_url_admin . "info?cname=" . $cname;
        $json_result = $helper_zcommon->zcurl('get', $url);
        if ($helper_zcommon->zcheckJson($json_result) === false) {
            $this->_zlog("[info][失败][非json]:\n" . $json_result);
            $arr_return = array('zresult' => 0, 'zmsg' => '');
            echo json_encode($arr_return);
            return;
        }

        $this->_zlog("[info][成功][是json]:\n" . $json_result);
        echo $json_result;
        return;
    }

}
