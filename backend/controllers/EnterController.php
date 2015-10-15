<?php
/**
 * 进入队列
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


namespace backend\controllers;

use common\helpers\RequestHelper;
use yii\web\Controller;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * EnterController
 *
 * @category Channel
 * @package  EnterController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class EnterController extends BaseController
{
    public $enableCsrfValidation = false;
    public $conn = null;
    public $ch = '';
    public $mq = [];

    /**
     * 声明
     *
     * @return array
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
     * 短信进入队列(type=1)
     *
     * @return array
     */
    public function actionSmsAdd()
    {
        $mobile = RequestHelper::post('mobile', 0);
        $content = RequestHelper::post('content', '');
        $time = date('Y-m-d H:i:s');
        $data['mobile'] = $mobile;
        $data['content'] = $content;
        $data['type'] = 1;
        if (empty($content) and $mobile==0) {
            $list = ['code' => '102', 'data' => $data, 'msg' => '参数错误'];
        } else {
            $exchange = $this->mq['exchange'];
            $queue = $this->mq['queue'];
            $this->ch->queue_declare($queue, false, false, false, false);
            $this->ch->exchange_declare($exchange, 'direct', false, false, false);
            $this->ch->queue_bind($queue, $exchange);
            $msg = new AMQPMessage(json_encode($data));
            $this->ch->basic_publish($msg, "", $queue);
            $this->ch->close();
            $list = ['code' => '200', 'data' => $data, 'msg' => '成功'];
        }
        file_put_contents('/tmp/channel_in.log', $time . '|' . json_encode($list) . "\r\n", FILE_APPEND);
        echo json_encode($list);
    }
    /**
     * 订单进入队列(type=2)
     *
     * @return array
     */
    public function actionOrderAdd()
    {
        $order_sn = RequestHelper::get('order_sn', 0);
        $user_id = RequestHelper::get('user_id', 0);
        $yhbh = RequestHelper::get('yhbh', 0);
        $money = RequestHelper::get('money', 0);
        $data['order_sn'] = $order_sn;
        $data['yhbh'] = $yhbh;
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['type'] = 2;
        $time = date('Y-m-d H:i:s');
        if (empty($content) and $money==0 and $yhbh==0 and $user_id==0) {
            $list = ['code' => '102', 'data' => $data, 'msg' => '参数错误'];
        } else {
            $exchange = $this->mq['exchange'];
            $queue = $this->mq['queue'];
            $this->ch->queue_declare($queue, false, false, false, false);
            $this->ch->exchange_declare($exchange, 'direct', false, false, false);
            $this->ch->queue_bind($queue, $exchange);
            $msg = new AMQPMessage(json_encode($data));
            $this->ch->basic_publish($msg, "", $queue);
            $this->ch->close();
            $list = ['code' => '200', 'data' => $data, 'msg' => '成功'];
        }
        file_put_contents('/tmp/channel_in.log', $time . '|' . json_encode($list) . "\r\n", FILE_APPEND);
        echo json_encode($list);
    }
}
