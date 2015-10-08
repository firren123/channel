<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  PushController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/28 下午3:23
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\controllers;
use common\helpers\RequestHelper;
use common\vendor\Push\PushSDK;

/**
 * Class PushController
 * @category  PHP
 * @package   Channel
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */

class PushController extends BaseController
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        if (isset($_GET['dev']) && $_GET['dev'] == 1) {
            //获取请求类型
        } else {
            parent::init();
        }
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * param int    $msg_type   消息格式1消息,2通知
     * param int    $range_type 推送类型//0：tag组播//1：广播//2：批量单播//3：组合运算//4：精准推送//5：LBS推送//6：系统预留//7：单播
     * @return string
     */
    public function actionPushMsgToSingleDevice()
    {
        $channelId = RequestHelper::post('channel_id');
        $description = RequestHelper::post('description');
        $title = RequestHelper::post('title');
        if ($channelId == '' || $description == '' || $title == '') {
            echo json_encode(array('code'=>101, 'data'=>'', 'msg'=>'缺少字段'));
            exit;
        }
        $custom_content = RequestHelper::post('custom_content');
        $push = new PushSDK();
        $message = [
            'description'=> $description,
            'title' => $title,
            'custom_content' =>json_encode($custom_content)
        ];
        // 设置消息类型为 通知类型.
        $opts = array(
            'msg_type' => 1,     //0：透传消息 1：通知
        );
        // 向目标设备发送一条消息
        $rs = $push->pushMsgToSingleDevice($channelId, $message, $opts);

        // 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
        if ($rs === false) {
            $code = $push->getLastErrorCode();
            $msg = $push->getLastErrorMsg();
            echo json_encode(array('code'=>$code,'data'=>'', 'msg'=>$msg));
            file_put_contents('/tmp/baidu_push.log', "请求时间：".date('Y-m-d H:i:s')." 请求参数:". var_export($this->params, true)."|发送失败状态码".$code.";msg:".$msg."\n", FILE_APPEND);
        } else {
            // 将打印出消息的id,发送时间等相关信息.
            echo json_encode(array('code'=>200,'data'=>$rs, 'msg'=>'成功'));
            file_put_contents('/tmp/baidu_push.log', "请求时间：".date('Y-m-d H:i:s')." 请求参数:". var_export($this->params, true)."|发送成功:".var_export($rs,true)."\n", FILE_APPEND);
        }
        exit;

    }

    /**
     * 简介：群发
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionPushMsgToAll()
    {
        $sdk = new PushSDK();
        // 创建消息内容
        $msg = array(
            'title' => '推送',
            'description' => '我是群发消息',
        );

        // 消息控制选项。
        $opts = array(
            'msg_type' => 1,
        );

        // 发送
                $rs = $sdk -> pushMsgToAll($msg, $opts);

        if ($rs !== false) {
            print_r($rs);    // 将打印出 msg_id 及 timestamp
        }
    }

    /**
     * 简介：按照组推送
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionPushMsgToTag()
    {
        $sdk = new PushSDK();
        // 创建消息内容
        $msg = array(
            'description' => 'notice msg',
        );

        // 消息控制选项。
        $opts = array(
            'msg_type' => 1,
        );

        // 发送
        $rs = $sdk -> pushMsgToTag('MyTagName',$msg, $opts);

        if($rs !== false){
            print_r($rs);    // 将打印出 msg_id 及 send_time
        }
    }

    public function actionPushBatchUniMsg()
    {
        $sdk = new PushSDK();
        // 创建消息内容
        $msg = array(
            'description' => 'notice msg',
        );

        // 消息控制选项。
        $opts = array(
            'msg_type' => 1,
        );

        // 发送给以下五个设备，每个设备ID应与终端设备上产生的 channel_id 一一对应。
        $idArr = array(
            '000000000000001',
            '000000000000002',
            '000000000000003',
            '000000000000004',
            '000000000000005',
        );

        // 发送
        $rs = $sdk -> pushBatchUniMsg($idArr, $msg, $opts);

        if($rs !== false){
            print_r($rs);    // 将打印出 msg_id 及 send_time
        }
    }
}