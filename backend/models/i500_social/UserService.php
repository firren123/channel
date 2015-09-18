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
 * @time      15/9/18 上午11:01 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models\i500_social;

/**
 * Products
 *
 * @category Channel
 * @package  Products
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class UserService extends SocialBase
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%i500_service_setting}}';
    }
}
