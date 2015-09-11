<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  MqController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/9 下午3:47
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace console\controllers;

use console\models\i500m\ChinaepayLog;
use console\models\i500m\OrderChinaepay;
use linslin\yii2\curl\Curl;
use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPConnection;

/**
 * Class MqController
 * @category  PHP
 * @package   Channel
 * @filename  MqController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/9 下午3:47
 * @link      http://www.i500m.com/
 */
class ChannelController extends Controller
{

    public $conn = null;
    public $ch = '';
    public $mq = array();

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
        $this->mq = \Yii::$app->params['mq'];
        $this->conn = new AMQPConnection($this->mq['host'], $this->mq['port'], $this->mq['user'], $this->mq['pass'], $this->mq['vhost']);
        if (!$this->conn) {
            exit('环境错误,联系管理员');
        }
        $this->ch = $this->conn->channel();
    }

    /**
     * 简介：插入短信表
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAddMsg()
    {
        $m = 1;
        while ($m) {
            $this->ch->queue_declare($this->mq['queue'], false, false, false, false);
            $this->ch->exchange_declare($this->mq['exchange'], 'direct', false, false, false);
            $this->ch->queue_bind($this->mq['queue'], $this->mq['exchange']);
            $msg = $this->ch->basic_get($this->mq['queue']);
            if ($msg) {
                $info = json_decode($msg->body, true);
                switch ($info['type']) {
                case 1: //发送短信
                    $info['create_time'] = date('Y-m-d H:i:s');
                    unset($info['type']);
                    $comm = @\Yii::$app->db_500m;
                    $ret = $comm->createCommand()->insert('queue_sms', $info)->execute();
                    if ($ret) {
                        $this->ch->basic_ack($msg->delivery_info['delivery_tag']);
                        //echo 1;
                    } else {
                        $time = date('Y-m-d H:i:s');
                        file_put_contents('/tmp/sms-filed.log', $time . '|' . $msg->body . "\r\n", FILE_APPEND);
                    }
                    break;
                case 2: //调用电网接口
                    unset($info['type']);
                    $order_sn = $info['order_sn'];
                    unset($info['order_sn']);
                    $info['userid'] = $info['user_id'];
                    unset($info['user_id']);
                    $info['timestamp'] = time();
                    $info['sign'] = $this->_signSocial($info);
                    $info['appId'] = 'I500_SOCIAL';
                    $info['dev'] = 4;
                    $curl = new Curl();
                    $url = \Yii::$app->params['socialUrl'].'/v1/vas/payznb';
                    $response = $curl->reset()
                        ->setOption(
                            CURLOPT_POSTFIELDS, http_build_query(
                                $info
                            )
                        )
                        ->post($url);
                    $response = json_decode($response, true);
                    $ChinaepayLogModel = new ChinaepayLog();
                    $data = [];
                    $data['order_sn'] = $order_sn;
                    $data['price'] = $info['money'];
                    $data['type'] = 1;
                    $data['create_time'] = date('Y-m-d H:i:s');
                    if ($response['code'] == 1) {
                        $data['serial_sn'] = $response['data']['orderid'];
                        $data['result_code'] = 200;
                        $data['remarks'] = '充值成功';
                    } else {
                        $data['serial_sn'] = '';
                        $data['result_code'] = $response['msg'];
                        $data['remarks'] = '充值失败';
                    }
                    $r = $ChinaepayLogModel->insertInfo($data);
                    $orderChunaepayModel = new OrderChinaepay();
                    $data_info = [];
                    $data_info['handle_time'] = date('Y-m-d H:i:s');
                    $data_info['handle_status'] = $response['code'] == 1?1:2;
                    $orderChunaepayModel->updateInfo($data_info, ['order_sn'=>$order_sn]);
                    $this->ch->basic_ack($msg->delivery_info['delivery_tag']);
                    file_put_contents('/tmp/social_response.log', $url."|结果".$response."\n\r", FILE_APPEND);
                    break;
                default:

                    break;
                }
            } else {
                $m = 0;
            }
        }

    }

    /**
     * 简介：生成sign
     * @author  lichenjun@iyangpin.com。
     * @param array $params 数组
     * @return string
     */
    private function _signSocial($params)
    {
        //$params 是传递的参数
        $app_code = 'DKJA@(SL)RssMAKDKas!L';
        $timestamp = $params['timestamp'];
        unset($params['timestamp']);
        $val = '';
        foreach ($params as $k => $v) {
            $val .= $v;
        }
        return $sign = md5(md5(md5($app_code.$timestamp).md5($timestamp)).md5($val));
    }
}
