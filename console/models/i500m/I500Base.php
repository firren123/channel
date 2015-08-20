<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/15 下午2:05
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace console\models\i500m;

use console\models\Base;

/**
 * I500Base
 *
 * @category Channel
 * @package  I500Base
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class I500Base extends Base
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


