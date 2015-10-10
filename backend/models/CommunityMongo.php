<?php
/**
 * 小区操作处理类
 *
 * PHP Version 5
 * Mongodb 小区操作处理类
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/10/9 下午5:14
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models;
use yii\mongodb\ActiveRecord;

class CommunityMongo extends ActiveRecord
{
    /**
     * 表名
     */
    public static $table;

    /**
     * 构造函数
     * @param array $table 表名
     */
    public function __construct($table = '')
    {
        self::$table=$table;
    }
    public function rules()
    {
        return [
            [['community_id','name','lng','lat','loc','province','city','area','address','create_time','status'], 'safe']
        ];
    }
    /**
     * 定义表名
     * @return string
     */
    public static function collectionName()
    {
        return self::$table;
    }
    public function attributes()
    {
        return ['_id','community_id','name','lng','lat','loc','province','city','area','address','create_time','status'];
    }

    /**
     * 小区搜索
     *
     * @param string $keywords 搜索关键字
     * @param int    $limit    返回条数
     *
     * @return array
     */
    public function search($keywords, $limit = 6)
    {
        if (empty($keywords)) return [];
//        var_dump($this);
//        $list = $this->find()->limit($limit)->all();
//        var_dump($list);
//        echo $keywords;
        //$list = $this->find()->select(['name','lng','lat'])->where(['LIKE', 'name', "^".$keywords])->all();
        $list = $this->find()->select(['community_id', 'name', 'province_id', 'city_id','address', 'lng','lat'])->where(['LIKE', 'name', "$keywords"])->limit($limit)->asArray()->all();
        //var_dump($list);
        return $list;
    }
    public function getData($limit = 10){
        $list = $this->find()->select(['name', 'address', 'lng','lat'])->limit($limit)->asArray()->all();
        return $list;
    }
    /**
     * 往mongodb里插入一条小区数据
     * @param array $data 小区信息
     * @return bool
     */
    public function addOne($data)
    {
        foreach ($data as $k=>$v) {
            $this->$k = $v;
        }
        $re = $this->insert();
        if ($re) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 小区信息实时修改

     * @param string $community_id 小区id
     *
     * @param array $info 小区信息
     *
     * @return bool
     */
    public function editOne($community_id, $info)
    {
        if (empty($community_id)) {
            return false;
        }
//var_dump($info);
        //echo $this->find()->where(['shop_id'=>1577])->createCommand()->sql;
        $table = self::$table;

        $model = $this->find()->where(['community_id'=>$community_id])->one();
        self::$table = $table;
        // var_dump($model);//exit();
//        $data = [
//            'name'=>$info['name'],
//            'area'=>$info['area'],
//            'address'=>$info['address'],
//            'lng'=>$info['lng'],
//            'lat'=>$info['lat'],
//            'status'=>$info['status'],
//
//            'loc'=>[$info['lng'],$info['lat']]
//        ];
        //$clone = clone $model;
        foreach ($info as $k=>$v) {
            $model->$k = $v;
        }
        //var_dump($clone);//exit();
//
        // echo self::$table;exit();
        $re = $model->update();
        return $re;
    }
    /**
     * 检测商家是否在mongodb里
     */
    public function checkExist($community_id)
    {
        if (empty($community_id)) {
            return false;
        }
        $count = $this->find()->where(['community_id'=>$community_id])->count();
        return $count;
    }
}