<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  QueueController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/7 下午1:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace console\models\i500m;

/**
 * QueueSms
 *
 * @category Admin
 * @package  QueueSms
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class QueueSms extends BaseFive
{
    /**
     * 数据表连接
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%queue_sms}}";
    }

    /**
     * 字段值
     *
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'mobile' => '手机号码',
            'content' => '短信内容',
            'send_time' =>'定时发送',
            'create_time' =>'添加时间',
        );
    }

    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['mobile'],'required','message' => '手机号码 不能为空.'],
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式输入不正确'],
            [['content'],'required','message' => '短信内容 不能为空.'],
        ];
    }
}
