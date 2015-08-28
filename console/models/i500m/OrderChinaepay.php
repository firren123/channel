<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  OrderChinaepay.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/28 下午3:16
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace console\models\i500m;


/**
 * Class OrderChinaepay
 * @category  PHP
 * @package   Channel
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class OrderChinaepay extends BaseFive
{
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%order_chinaepay}}";
    }
}