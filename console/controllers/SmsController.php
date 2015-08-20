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

use yii\console\Controller;
use console\models\i500m\QueueSms;
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
class SmsController extends Controller
{

    public $conn = null;
    public $ch = '';
    public $host = '118.186.247.55';
    public $port = '5672';
    public $user = '500m';
    public $pass = 'gbjY51Rpstx';
    public $vhost = '500m';
    public $channel = 'sms';

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
        $this->conn = new AMQPConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $this->ch = $this->conn->channel();
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAddMsg()
    {
        while (1) {
            $this->_addMsg();
        }

    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    private function _addMsg()
    {
        $exchange = 'sms_exchange';
        $queue = 'sms_queue';
        $this->ch->queue_declare($queue, false, false, false, false);
        $this->ch->exchange_declare($exchange, 'direct', false, false, false);
        $this->ch->queue_bind($queue, $exchange);
        $msg = $this->ch->basic_get($queue);
        if ($msg) {
            $this->ch->basic_ack($msg->delivery_info['delivery_tag']);
            $msg = json_decode($msg->body, true);
            $msg['create_time'] = date('Y-m-d H:i:s');
            $smsModel = new QueueSms();
            $ret = $smsModel->insertInfo($msg);
            if ($ret) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}
