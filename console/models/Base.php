<?php
/**
 * Base库
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
namespace console\models;

use yii\db\ActiveRecord;

/**
 * Base
 *
 * @category Channel
 * @package  Base
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class Base extends ActiveRecord
{
    /**
     * 列表
     *
     * @param array  $cond      条件
     * @param string $field     查询字段
     * @param string $order     排序
     * @param string $and_where 附加条件
     *
     * @return array|\yii\db\ActiveRecord[]
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
     * 带分页的列表
     *
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $order     排序
     * @param int    $page      页码
     * @param int    $size      每页显示条数
     * @param string $and_where 附加条件
     *
     * @return array|\yii\db\ActiveRecord[]
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
                ->offset(($page-1) * $size)
                ->limit($size)
                ->asArray()
                ->all();
        }
        return $list;
    }

    /**
     * 获取数量
     *
     * @param array  $cond      条件
     * @param string $and_where 附加条件
     *
     * @return int|string
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
     * 详情
     *
     * @param array  $cond    条件
     * @param bool   $asArray 结果返回是否是数组
     * @param string $field   字段
     * @param string $order   排序
     *
     * @return array|null|ActiveRecord
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
     * 修改
     *
     * @param array $data 数据
     * @param array $cond 条件
     *
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
     * 添加
     *
     * @param array $data 数据
     *
     * @return bool
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



    /**
     * 查询表，返回分页数据
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where    arr_where
     * @param string $str_andwhere 字符串where条件
     * @param array  $arr_order    arr_order
     * @param string $str_field    str_field
     * @param int    $int_offset   int_offset If not set or less than 0, it means starting from the beginning
     * @param int    $int_limit    int_limit If not set or less than 0, it means no limit
     *
     * @return array
     */
    public function getList2(
        $arr_where,
        $str_andwhere = '',
        $arr_order = array(),
        $str_field = '*',
        $int_offset = -1,
        $int_limit = -1
    ) {
        $arr = $this->find()
            ->select($str_field)
            ->where($arr_where)
            ->andWhere($str_andwhere)
            ->orderBy($arr_order)
            ->offset($int_offset)
            ->limit($int_limit)
            ->asArray()
            ->all();
        return $arr;
    }

    /**
     * 查询条件的记录总数
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where    arr_where
     * @param string $str_andwhere 字符串where条件
     * @param string $str_field    str_field
     *
     * @return array
     */
    public function getListCount($arr_where, $str_andwhere = '', $str_field = 'count(*) as num')
    {
        $arr = $this->find()
            ->select($str_field)
            ->where($arr_where)
            ->andWhere($str_andwhere)
            ->asArray()
            ->one();
        if (!$arr) {
            $arr = array();
        }
        return $arr;
    }

    /**
     * 修改1条记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where    查询条件
     * @param string $str_andwhere 字符串where条件
     * @param array  $arr_set      set的数据
     *
     * @return array array('result'=>0/1,'data'=>array(),'msg'=>'')
     */
    public function updateOneRecord($arr_where, $str_andwhere = '', $arr_set = array())
    {
        $active_record = $this->find()
            ->where($arr_where)
            ->andWhere($str_andwhere)
            ->one();
        foreach ($arr_set as $key => $value) {
            $active_record->$key = $value;
        }
        try {
            $result = $active_record->update();
            if ($result === false) {
                return array('result' => 0, 'data' => array(), 'msg' => 'failed');
            } else {
                return array('result' => 1, 'data' => array(), 'msg' => '');
            }
        } catch (\Exception $e) {
            return array('result' => 0, 'data' => array(), 'msg' => $e->getMessage());
        }
    }

    /**
     * Insert 1条记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr_field_value 新记录的数据
     *
     * @return array array('result'=>0/1,'data'=>array(),'msg'=>'')
     */
    public function insertOneRecord($arr_field_value)
    {
        foreach ($arr_field_value as $key => $value) {
            $this->$key = $value;
        }
        try {
            $result = $this->insert();
            if ($result === false) {
                return array('result' => 0, 'data' => array(), 'msg' => 'failed');
            } else {
                return array('result' => 1, 'data' => array('new_id' => $this->id), 'msg' => '');
            }
        } catch (\Exception $e) {
            return array('result' => 0, 'data' => array(), 'msg' => $e->getMessage());
        }
    }

    /**
     * 删除1条记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where    查询条件
     * @param string $str_andwhere 字符串where条件
     *
     * @return array array('result'=>0/1,'data'=>array(),'msg'=>'')
     */
    public function delOneRecord($arr_where, $str_andwhere = '')
    {
        $active_record = $this->find()
            ->where($arr_where)
            ->andWhere($str_andwhere)
            ->one();
        try {
            $result = $active_record->delete();
            if ($result === false) {
                return array('result' => 0, 'data' => array(), 'msg' => 'failed');
            } else {
                return array('result' => 1, 'data' => array(), 'msg' => '');
            }
        } catch (\Exception $e) {
            return array('result' => 0, 'data' => array(), 'msg' => $e->getMessage());
        }
    }

    /**
     * 获取一条记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where    where条件
     * @param string $str_andwhere 字符串where条件
     * @param string $str_field    字段
     *
     * @return array
     */
    public function getOneRecord($arr_where, $str_andwhere = '', $str_field = '*')
    {
        $arr = $this->find()
            ->select($str_field)
            ->where($arr_where)
            ->andWhere($str_andwhere)
            ->asArray()
            ->one();
        if (!$arr) {
            $arr = array();
        }
        return $arr;
    }

    /**
     * 查询表，返回分页数据
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where          arr_where
     * @param array  $arr_where_param    where绑定数组
     * @param string $str_andwhere       字符串where条件
     * @param array  $arr_andwhere_param andwhere绑定数组
     * @param array  $arr_order          arr_order
     * @param string $str_field          str_field
     * @param int    $int_offset         If not set or less than 0, it means starting from the beginning
     * @param int    $int_limit          If not set or less than 0, it means no limit
     *
     * @return array
     */
    public function getRecordList(
        $arr_where,
        $arr_where_param = array(),
        $str_andwhere = '',
        $arr_andwhere_param = array(),
        $arr_order = array(),
        $str_field = '*',
        $int_offset = -1,
        $int_limit = -1
    ) {
        $arr = $this->find()
            ->select($str_field)
            ->where($arr_where, $arr_where_param)
            ->andWhere($str_andwhere, $arr_andwhere_param)
            ->orderBy($arr_order)
            ->offset($int_offset)
            ->limit($int_limit)
            ->asArray()
            ->all();
        return $arr;
    }

    /**
     * 查询表，返回分页数据
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where          arr_where
     * @param array  $arr_where_param    where绑定数组
     * @param string $str_andwhere       字符串where条件
     * @param array  $arr_andwhere_param andwhere绑定数组
     * @param string $str_field          str_field
     *
     * @return array
     */
    public function getRecordListCount(
        $arr_where,
        $arr_where_param = array(),
        $str_andwhere = '',
        $arr_andwhere_param = array(),
        $str_field = 'count(*) as num'
    ) {
        $arr = $this->find()
            ->select($str_field)
            ->where($arr_where, $arr_where_param)
            ->andWhere($str_andwhere, $arr_andwhere_param)
            ->asArray()
            ->one();
        if (!$arr) {
            $arr = array();
        }
        return $arr;
    }

    /**
     * 根据查询条件获取一个字段的值
     *
     * @param array  $map   查询条件
     * @param string $field 查询的名称
     *
     * @return bool|string   返回值
     */
    public function getFieldName($map, $field)
    {
        if (empty($map) || empty($field)) {
            return false;
        }
        return $this->find()->select($field)->where($map)->scalar();
    }
}
