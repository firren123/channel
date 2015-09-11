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
 * @time      15/8/13 下午5:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\models;

use yii\sphinx\ActiveRecord;

class Shop extends ActiveRecord
{
    public static function indexName()
    {
        return 'shoprt';
    }

    public function getList($keywords, $map)
    {
        //$list = \Yii::$app->sphinx->createCommand("SELECT * FROM `shop` WHERE status=1")->queryAll();
        //$list = $this->find()->where(['status'=>2,'city'=>1])->asArray()->all();
//        $this->id = 1;
//        $this->province = 2;
//        $this->city = 2;
//        $this->status=2;
//        $this->shop_name ='鸿喜族养生会馆';
//        $this->insert();


        // $list = $this->find()->where('city=2')->match($words)->limit(2)->asArray()->all();
        //$list = $this->find()->where('city=2')->limit(6)->asArray()->all();
        $list = $this->find()->where($map)->match($keywords)->asArray()->all();
        //$list = $this->find()->where('city=2')->limit(2)->asArray()->all();
        var_dump($list);
    }
    public function getListPage()
    {

    }
    public function getShop()
    {
        $words = '北京';
        $list = $this->find()->match('11529')->limit(2)->asArray()->all();
        var_dump($list);
    }
}