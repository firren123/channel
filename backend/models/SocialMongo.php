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
 * @time      15/9/18 下午2:08 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models;

use yii\mongodb\ActiveRecord;

class SocialMongo extends ActiveRecord
{
    /**
     * 定义表名
     * @return string
     */
    public static function collectionName()
    {
        return 'user_social';
    }
//    public function attributes()
//    {
//        return ['_id','community_id','username','shop_name','lng','lat','loc'];
//    }
    public function rules()
    {
        return [
            [['uid', 'mobile', 'name', 'province_id', 'lng', 'lat', 'loc', 'user_name', 'user_card', 'user_sex', 'user_age', 'user_home', 'audit_status', 'status', 'is_deleted', 'create_time'], 'safe'],
        ];
    }
    public function attributes()
    {
        return ['_id', 'uid', 'mobile', 'name', 'province_id', 'lng', 'lat', 'loc', 'user_name', 'user_card', 'user_sex', 'user_age', 'user_home', 'audit_status', 'status', 'is_deleted', 'create_time'];
    }


    public function getData($limit = 10){
        $list = $this->find()->select(['shop_name','position_x','position_y'])->asArray()->all();
        return $list;
    }

    /**
     * 往mongodb里插入一条商家数据
     * @param array $data 商家信息
     * @return bool
     */
    public function addOne($data)
    {
        if (!empty($data) && is_array($data)) {
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
        return false;

    }

    /**
     * 商家信息实时修改

     * @param string $mobile 用户手机号
     *
     * @param array $info    商家信息
     *
     * @return bool
     *
     */
    public function editOne($mobile, $info)
    {
        if (empty($mobile)) {
            return false;
        }
        $model = $this->find()->where(['mobile'=>$mobile])->one();
        // var_dump($model);exit();
//        $data = [
//            'shop_name'=>$info['shop_name'],
//            'position_x'=>$info['position_x'],
//            'position_y'=>$info['position_y'],
//            'loc'=>[$info['position_x'],$info['position_y']]
//        ];
        foreach ($info as $k=>$v) {
            $model->$k = $v;
        }
//
        $re = $model->save();
        return $re;
    }
    /**
     * 检测商家是否在mongodb里
     * @param int $shop_id
     */
    public function checkExist($mobile)
    {
        if (empty($mobile)) {
            return false;
        }
        $count = $this->find()->where(['mobile'=>$mobile])->count();
        return $count;
    }
    public function syncUserService($data)
    {
        $re = \Yii::$app->mongodb->getCollection(self::collectionName())->remove();
        if (!empty($data) && is_array($data)) {
            $re = \Yii::$app->mongodb->getCollection(self::collectionName())->batchInsert($data);
            if(empty($re)){
                echo "数据信息写入失败";
                return false;
            }
            echo "数据信息写入成功";
        } else {
            \Yii::trace('无数据!');
        }
        $this->createIndex();
    }
    private function createIndex()
    {
        $re = \Yii::$app->mongodb->getCollection(self::collectionName())->createIndex(['loc'=>'2d']);
        if(empty($re)){
            \Yii::error('创建2d索引失败!');
        }else{
            \Yii::trace('创建2d索引成功!');
        }

    }
}