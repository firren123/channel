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
                case 1:
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

                default:

                    break;
                }
            } else {
                $m = 0;
            }
        }

    }
}
