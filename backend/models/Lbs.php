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
use yii\mongodb\Query;

class Lbs extends ActiveRecord
{
    public $database = 'shop';
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
            $baiduUrl = \Yii::$app->params['baiduUrl'].'geocoder/v2/';
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
                    'region_fix'=>1,
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
    public function convertToBaidu($lng, $lat, $type = 3)
    {
        $baiduUrl = \Yii::$app->params['baiduUrl'].'geoconv/v1/';
        $ak = \Yii::$app->params['ak'];
        $params =
            [
                'coords'=>$lng.','.$lat,
                'from'=>$type,
                'to'=>5,
                'ak'=>$ak,
            ];
        $query = http_build_query($params);
        $url = $baiduUrl .'?'. $query;
        $res = CurlHelper::get($url);
        $location = [];
        if (isset($res['status']) && $res['status'] == 0) {
            $location = ArrayHelper::getValue($res, 'result.0', []);
        }
        return $location;

    }

    /**
     * 根据坐标和商家id 判断这个位置是否在商家的服务范围之内
     * @param float  $lng     经度
     * @param float  $lat     纬度
     * @param string $shop_id 商家id
     * @return bool
     */
    public function checkAddress($lng, $lat, $shop_id)
    {

        $query = new Query();

        $info = $query->from('location')->where(['shop_id'=>$shop_id])->one();
        //var_dump($info);
        if (empty($info)) {
            return false;
        }
        $connection = \Yii::$app->mongodb;
        $db = $connection->getDatabase('shop');
        $max_dis = ArrayHelper::getValue($info, 'max_dis', 3);
        $maxDistance = $max_dis/6371;
        $options = [
            'geoNear'=>'location',
            'near'=>[$lng, $lat],
            //  'num'=>$num,
            //'limit'=>30,
            'spherical'=>true,
            'maxDistance'=>$maxDistance,
            'distanceMultiplier'=>6371,
            'query'=>['shop_id'=>$shop_id, 'status'=>'2']
        ];
        $near = $db->executeCommand($options);
        //var_dump($near['results']);
        if (!empty($near['results'])) {
            return true;
        } else {
            return false;
        }
    }
    public function getPoi($lng, $lat, $type='poi')
    {
        $baiduUrl = \Yii::$app->params['baiduUrl'].'geocoder/v2/';
        $ak = \Yii::$app->params['ak'];
        $poi = ($type == 'poi') ? 1 : 0;
        $params =
            [
                'location'=>$lat.','.$lng,
                'output'=>'json',
                'pois'=>$poi,
                'ak'=>$ak,
            ];
        $query = http_build_query($params);

        $url = $baiduUrl .'?'. $query;
        $res = CurlHelper::get($url);
        if ($poi == 1) {
            if (isset($res['result']['pois'])) {
                return ArrayHelper::getValue($res['result'], 'pois.0.name', '');
                // return $res['result']['pois'][0];
            } elseif (isset($res['result']['formatted_address'])) {
                return ArrayHelper::getValue($res['result'], 'formatted_address', '');
            }
        } else {
            if (isset($res['result']['addressComponent'])) {
                return ArrayHelper::getValue($res['result'], 'addressComponent.city', '北京市');
                // return $res['result']['pois'][0];
            } elseif (isset($res['result']['pois'])) {
                return ArrayHelper::getValue($res['result'], 'addressComponent.street', '');
            }
        }

        //var_dump($res);
    }
    public function getNearUser($lng, $lat, $distance, $table = 'user_social')
    {
        $list = [];
        $connection = \Yii::$app->mongodb;
        $db = $connection->getDatabase($this->database);
        $maxDistance = $distance/6371;
        $options = [
            'geoNear'=>$table,
            'near'=>[$lng, $lat],
            //  'num'=>$num,
            //'limit'=>30,
            'spherical'=>true,
            'maxDistance'=>$maxDistance,
            'distanceMultiplier'=>6371,
            'query'=>['status'=>2, 'audit_status'=>2, 'is_deleted'=>2]
        ];
        $near = $db->executeCommand($options);
        if (!empty($near['results'])) {
            foreach ($near['results'] as $k => $v) {
                $list[$k] = [
                    'uid' => ArrayHelper::getValue($v, 'obj.uid', 0),
                    'mobile' => ArrayHelper::getValue($v, 'obj.mobile', ''),
                    'name' => ArrayHelper::getValue($v, 'obj.name', ''),
                    'province_id' => ArrayHelper::getValue($v, 'obj.province_id', 0),
                    'dis'=>Common::getDistance($v['dis']),
                ];
            }
            return $list;
        }
        return [];
    }
    public function addUserService($data)
    {
        if (!empty($data) && is_array($data)) {

        }
    }
    public function getNearCommunity($lng, $lat, $distance)
    {
        $list = [];
        $connection = \Yii::$app->mongodb;
        $db = $connection->getDatabase($this->database);
        $maxDistance = $distance/6371;
        $table = Common::getCommunityTable(1);
        $options = [
            'geoNear'=>$table,
            'near'=>[$lng, $lat],
            //  'num'=>$num,
            //'limit'=>30,
            'spherical'=>true,
            'maxDistance'=>$maxDistance,
            'distanceMultiplier'=>6371,
            //'query'=>['status'=>2, 'audit_status'=>2, 'is_deleted'=>2]
        ];
        $near = $db->executeCommand($options);
        //var_dump($near['results']);exit();
        if (!empty($near['results'])) {
            foreach ($near['results'] as $k => $v) {
                $list[$k] = [
                    'community_id' => ArrayHelper::getValue($v, 'obj.community_id', 0),
                    'name' => ArrayHelper::getValue($v, 'obj.name', ''),
                    //'name' => ArrayHelper::getValue($v, 'obj.name', ''),
                    'province_id' => ArrayHelper::getValue($v, 'obj.province_id', 0),
                    'dis'=>Common::getDistance($v['dis']),
                ];
            }
            return $list;
        }
        return [];
    }
    public function getCity($lng, $lat)
    {
        $baiduUrl = \Yii::$app->params['baiduUrl'].'geocoder/v2/';
        $ak = \Yii::$app->params['ak'];
        $params =
            [
                'location'=>$lat.','.$lng,
                'output'=>'json',
                'pois'=>1,
                'ak'=>$ak,
            ];
        $query = http_build_query($params);

        $url = $baiduUrl .'?'. $query;
        $res = CurlHelper::get($url);
        if (isset($res['result']['pois'])) {
            return ArrayHelper::getValue($res['result'], 'pois.0.name', '');
            // return $res['result']['pois'][0];
        } elseif (isset($res['result']['pois'])) {
            return ArrayHelper::getValue($res['result'], 'addressComponent.street', '');
        }

        $ak = \Yii::$app->params['ak'];
        $url = 'http://api.map.baidu.com/geocoder/v2/?ak='.$ak.'&location='.$lat.','.$lng.'&output=json&pois=0';
        $response = $this->_curl_get($url);
        return json_decode($response, true);
    }

}
