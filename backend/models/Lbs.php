<?php
/**
 * LBS服务
 *
 * PHP Version 5
 * LBS定位信息
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/11 下午1:59
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */

/**
 * 获取附近的商家列表 带距离
 * @param float  $lng      经度
 * @param float  $lat      纬度
 * @param int    $distance 最大距离
 * @param int    $num      返回条数
 * @param string $table    查询的表
 * @return array
 */
namespace backend\models;

use common\helpers\Common;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use common\helpers\CurlHelper;

class Lbs extends ActiveRecord
{
    /**
     * 定义表名
     * @return string
     */
    public static function collectionName()
    {
        return 'user_poi_log';
    }

    /**
     * 获取附近的商家列表 带距离
     * @param float  $lng      经度
     * @param float  $lat      纬度
     * @param int    $distance 最大距离
     * @param string $table    查询的表
     * @return array
     */
    public function getNearByShop($lng, $lat, $distance, $table = 'location')
    {
        $list = [];
        $connection = \Yii::$app->mongodb;
        $db = $connection->getDatabase('shop');
        $maxDistance = $distance/6371;
        $options = [
            'geoNear'=>$table,
            'near'=>[$lng, $lat],
          //  'num'=>$num,
            //'limit'=>30,
            'spherical'=>true,
            'maxDistance'=>$maxDistance,
            'distanceMultiplier'=>6371,
            'query'=>['status'=>'2']
        ];
        $near = $db->executeCommand($options);
        if (!empty($near['results'])) {
            foreach ($near['results'] as $k => $v) {
                $list[$k] = [
                    'shop_id' => ArrayHelper::getValue($v, 'obj.shop_id', 0),
                    'shop_name' => ArrayHelper::getValue($v, 'obj.shop_name', ''),
                    'username' => ArrayHelper::getValue($v, 'obj.username', ''),
                    'city' => ArrayHelper::getValue($v, 'obj.city', 0),
                    'dis'=>Common::getDistance($v['dis']),

                ];
                $is_server = 1;
                $max_dis = ArrayHelper::getValue($v, 'obj.max_dis', 2);//获取最大服务距离
                //如果超过最大服务距离则不配送
                if ($v['dis'] > $max_dis) {
                    $is_server = 0;
                }
                //$list[$k]['dis'] = $dis;
                $list[$k]['max_dis'] = $max_dis;
                $list[$k]['is_server'] = $is_server;

            }
            return $list;
        }
        return [];
        //near :, num :10,spherical : true, maxDistance : 2/6371, distanceMultiplier: 6371
    }

    /**
     * 获取附近的商家带分页的
     * @param float  $lng      经度
     * @param float  $lat      纬度
     * @param int    $distance 距离
     * @param int    $num      返回条数
     * @param int    $page     第几页
     * @param string $table    查询表名
     * @return array
     */
    public function getNearShopPage($lng, $lat, $distance, $num, $page = 1, $table = 'location')
    {
        $maxDistance = $distance/6371;
        if ($page < 1 || !is_int($page)) {
            $page = 1;
        }
        $skip = ($page-1)*$num;
        $ops = [
            ['$geoNear' =>[
                'near'=>[$lng, $lat],

                'distanceField'=>"dis",
                'maxDistance'=>$maxDistance,
                //'query'=>['status'=>"2"],
                'distanceMultiplier'=>6371,
                'query'=>['status'=>'2'],
                'spherical'=>true,
                'uniqueDocs'=>false,
            ],
            ],
            ['$skip'=>$skip],
            ['$limit'=>$num],
            //'$skip'=>1,
            // '$limit'=>3,
        ];

        $res = \Yii::$app->mongodb->getCollection($table)->aggregate($ops);
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $list[$k] = [
                    'shop_id' => ArrayHelper::getValue($v, 'shop_id', 0),
                    'shop_name' => ArrayHelper::getValue($v, 'shop_name', ''),
                    'username' => ArrayHelper::getValue($v, 'username', ''),
                    'city' => ArrayHelper::getValue($v, 'city', 0),
                    'dis'=>Common::getDistance($v['dis']),

                ];
                $is_server = 1;
                $max_dis = ArrayHelper::getValue($v, 'max_dis', 2);//获取最大服务距离
                //如果超过最大服务距离则不配送
                if ($v['dis'] > $max_dis) {
                    $is_server = 0;
                }
                //$list[$k]['dis'] = $dis;
                $list[$k]['max_dis'] = $max_dis;
                $list[$k]['is_server'] = $is_server;

            }
            return $list;
        }
        return [];
    }

    /**
     * 插入用户位置信息
     * @param string $mobile 手机号
     * @param float  $lng    经度
     * @param float  $lat    纬度
     * @param string $poi    所在位置
     * @return bool
     */
    public function insertPoiLog($mobile, $lng, $lat, $poi)
    {
        $data = [];
        if (!empty($mobile) && !empty($lng) && !empty($lat)) {
            $data = ['mobile'=>$mobile, 'lng'=>$lng, 'lat'=>$lat, 'poi'=>$poi];
        }
        if (!empty($data)) {
            $re = \Yii::$app->mongodb->getCollection('user_poi_log')
                ->insert($data);
            return $re;
        }
        return false;

    }
    public function getPointByAddress($address)
    {
        if (!empty($address)) {
            $baiduUrl = \Yii::$app->params['baiduUrl'];
            $ak = \Yii::$app->params['ak'];
            $url = $baiduUrl.'?address='.$address.'&ak='.$ak.'&output=json';
            $res = CurlHelper::get($url);
            return $res;
        }
        return false;
    }
    public function getSuggest($keywords, $region='北京')
    {
        if (!empty($keywords)) {
            $baiduUrl = \Yii::$app->params['suggestUrl'];
            $ak = \Yii::$app->params['qqak'];
            $params =
                [
                    'region'=>$region,
                    'keyword'=>$keywords,
                    'key'=>$ak,
                    'output'=>'json',
                ];
            $query = http_build_query($params);
            $url = $baiduUrl .'?'. $query;
           // exit();
            $res = CurlHelper::get($url);

            return $res;
        }
        return false;
    }

}
