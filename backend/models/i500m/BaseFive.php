<?php
/**
 * I500m数据库操作基础类
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/16 下午11:02
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models\i500m;

use backend\models\Base;

/**
 * I500Base
 *
 * @category Channel
 * @package  Products
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class BaseFive extends Base
{
    /**
     * 设置数据库连接
     * @return mixed
     */
    public static function getDB()
    {
        return \Yii::$app->db_500m;
    }
}
