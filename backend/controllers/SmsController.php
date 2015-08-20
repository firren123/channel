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


namespace backend\controllers;

use common\helpers\RequestHelper;
use yii\web\Controller;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * SmsController
 *
 * @category Channel
 * @package  SmsController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class SmsController extends Controller
{
    public $enableCsrfValidation = false;
    public $conn = null;
    public $ch = '';
    public $host = '118.186.247.55';
    public $port = '5672';
    public $user = '500m';
    public $pass = 'gbjY51Rpstx';
    public $vhost = '500m';

    /**
     * 声明
     *
     * @return array
     */
    public function init()
    {
        parent::init();
        $this->conn = new AMQPConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        if (!$this->conn) {
            exit('环境错误,联系管理员');
        }
        $this->ch = $this->conn->channel();
    }

    /**
     * 短信进入队列
     *
     * @return array
     */
    public function actionGetAdd()
    {
        $mobile = RequestHelper::post('mobile', 0);
        $content = RequestHelper::post('content', '');
        if (empty($content) and $mobile==0) {
            echo json_encode(['code' => '102', 'data' => '', 'msg' => '参数错误']);
        } else {
            $data['mobile'] = $mobile;
            $data['content'] = $content;
            $exchange = 'sms_exchange';
            $queue = 'sms_queue';
            $this->ch->queue_declare($queue, false, false, false, false);
            $this->ch->exchange_declare($exchange, 'direct', false, false, false);
            $this->ch->queue_bind($queue, $exchange);
            $msg = new AMQPMessage(json_encode($data));
            $this->ch->basic_publish($msg, "", $queue);
            $this->ch->close();
            echo json_encode(['code' => '200', 'data' => $data, 'msg' => '成功']);
        }
    }
}
