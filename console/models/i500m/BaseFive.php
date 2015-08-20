<?php
/**
 * I500M库
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

namespace console\models\i500m;

use console\models\Base;

/**
 * I500Base
 *
 * @category Admin
 * @package  I500Base
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class BaseFive extends Base
{
    /**
     * 数据表
     *
     * @return mixed
     */
    public static function getDB()
    {
        $ret = \Yii::$app->db_500m;
        if ($ret) {
            return $ret;
        } else {
            return null;
        }
    }
}
