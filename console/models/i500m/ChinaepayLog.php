<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  ChinaepayLog.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/28 下午3:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace console\models\i500m;

/**
 * Class ChinaepayLog
 * @category  PHP
 * @package   Channel
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ChinaepayLog extends BaseFive
{
    /**
     * 简介：连接数据库
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%chinaepay_log}}";
    }
}
