<?php
/**
 * 基础数据库操作类
 *
 * PHP Version 5
 * 基础数据库操作类
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/16 下午11:03
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models;

use yii\db\ActiveRecord;

/**
 * Base
 *
 * @category Channel
 * @package  Base
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class Base extends ActiveRecord
{

    /**
     * 获取数据列表
     *
     * @param array  $cond      查询条件
     * @param string $field     查询字段
     * @param string $order     排序
     * @param string $and_where 多条件
     *
     * @return array
     */
    public function getList($cond = array(), $field = '*', $order = '', $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->orderBy($order)
                ->asArray()
                ->all();
        }
        return $list;
    }

    /**
     * 分页数据列表
     *
     * @param array  $cond      查询条件
     * @param string $field     查询字段
     * @param string $order     排序
     * @param int    $page      第几页
     * @param int    $size      每页多少条
     * @param string $and_where 多条件
     *
     * @return array
     */
    public function getPageList($cond = array(), $field = '*', $order = '', $page = 1, $size = 10, $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->orderBy($order)
                ->offset(($page - 1) * $size)
                ->limit($size)
                ->asArray()
                ->all();
        }
        return $list;
    }

    /**
     * 统计总数
     *
     * @param array  $cond      查询条件
     * @param string $and_where 多条件
     *
     * @return int
     */
    public function getCount($cond = array(), $and_where = '')
    {
        $num = 0;
        if ($cond || $and_where) {
            $num = $this->find()->where($cond)->andWhere($and_where)->count();
        }
        return $num;
    }

    /**
     * 获取一条信息
     *
     * @param array  $cond    查询条件
     * @param bool   $asArray 是否返回数组
     * @param string $field   查询字段
     * @param string $order   排序
     *
     * @return array
     */
    public function getInfo($cond = array(), $asArray = true, $field = '*', $order = '')
    {
        $info = [];
        if ($cond) {
            if ($asArray) {
                $info = $this->find()->select($field)->where($cond)->orderBy($order)->asArray()->one();
            } else {
                $info = $this->find()->select($field)->where($cond)->orderBy($order)->one();
            }

        }
        return $info;

    }

    /**
     * 修改一条记录
     * @param array $data 要修改的数据
     * @param array $cond 条件
     * @return bool
     */
    public function updateInfo($data = array(), $cond = array())
    {
        $re = false;
        if ($cond && $data) {
            $re = $this->updateAll($data, $cond);
        }
        return $re !== false;
    }

    /**
     * 插入一条数据
     * @param array $data 要插入的数据
     * @return bool  返回状态
     */
    public function insertInfo($data = array())
    {
        $re = false;
        if ($data) {
            $model = clone $this;
            foreach ($data as $k => $v) {
                $model->$k = $v;
            }
            $re = $model->save();
        }
        return $re !== false;
    }
}