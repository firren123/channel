<?php
/**
 * 商品模型
 *
 * PHP Version 5
 * 商品模型 商品相关数据
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/16 下午10:56
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models\i500m;

/**
 * Products
 *
 * @category Channel
 * @package  Products
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class Products extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%product}}';
    }
}