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
 * @time      15/10/12 下午5:15
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace console\controllers;

use backend\models\Lbs;
use common\helpers\Common;
use common\helpers\CurlHelper;
use console\models\i500m\UserAddress;
use frontend\models\Shop;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class DataController extends Controller
{
//539 伊珂星钻  809   伊珂星钻 免费领珍珠吊坠一个  116.46678256986, 39.912227681302
//621 大茗苑  815 大茗苑 免费领坦洋工夫红茶一包  116.33301724383,39.893182711847
//630 龙稻   827  龙稻  到店免费领龙腾一品稻花香  116.47297796827,39.900711538139
//632 五常大米  829  五常大米 到店免费领五常大米一袋500g  116.37173701671,39.987548962057
//638 明明诚益  833 明明诚益  到店免费领取眼镜盒一个   116.46575185412,39.926411318541
//648 草原领头羊   845 草原领头羊 到店扫码免费送羊肉串3串 116.46575185412,39.926411318541
//635 济民康泰大药房  838  济民康泰大药房 到店领5元代金券
//628 华鼎中国兰州牛肉面   823 华鼎中国兰州牛肉面 到店免费送德国埃丝伯爵清啤酒一听(500ml)
//639 女子水晶乐坊音乐文创基地  835  女子水晶乐坊音乐文创基地 到店免费品尝水晶饮品伯爵茶一杯
//
//623 德式自酿鲜啤   817  德式自酿鲜啤俱乐部 免费领德式自酿鲜啤
    public $shop_list = [
        ['shop_id'=>539, 'name'=>'伊珂星钻','address'=>'北京市朝阳区建外SOHO东区三号楼3106室', 'sample_id'=>809, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>621, 'name'=>'大茗苑', 'address'=>'北京市西城区马连道9—1甲17号','sample_id'=>815, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>630, 'name'=>'龙稻', 'address'=>'北京市朝阳区东柏街10号院4号楼1层12号','sample_id'=>827, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>632, 'name'=>'五常大米', 'address'=>'北京市朝阳区大郊亭中街珠江帝景C区107号','sample_id'=>829, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>638, 'name'=>'明明诚益', 'address'=>'京市海淀区花园路1—31号底商','sample_id'=>833, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],

        ['shop_id'=>648, 'name'=>'草原领头羊', 'address'=>'北京市朝阳区关东店1号楼2栋4号、5号','sample_id'=>845, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],

        ['shop_id'=>635, 'name'=>'济民康泰大药房','address'=>'北京市海淀区建西苑南里10号楼1层4单元01商业8', 'sample_id'=>838, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>628, 'name'=>'华鼎中国兰州牛肉面', 'address'=>'北京市朝阳区望京街与阜安西路交叉路口望京SOHO塔2','sample_id'=>823, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>639, 'name'=>'女子水晶乐坊音乐文创基地', 'address'=>'北京市朝阳区广渠路唐家村五号院', 'sample_id'=>835, 'sample_name'=>'伊珂星钻 免费领珍珠吊坠一个'],
        ['shop_id'=>623, 'name'=>'德式自酿鲜啤','address'=>'北京市朝阳区十里河中心街116号', 'sample_id'=>817, 'sample_name'=>'德式自酿鲜啤俱乐部 免费领德式自酿鲜啤'],
    ];
    public $pageSize = 1000;
    public function actionGetCoordinate()
    {
        $model = new UserAddress();
        //$map = ['!=','lng', 0];
        $count = $model->find()->count();

        //echo $query->createCommand()->sql;
        $page = 1;
        $pageCount = ceil($count/$this->pageSize);
        $lbs = new Lbs();
        while ($page <= $pageCount) {
            $list = $model->find()->select('id,mobile,address,search_address,lng,lat')
                //->where($map)
                ->offset(($page - 1) * $this->pageSize)
                ->limit($this->pageSize)
                ->asArray()
                ->all();
            if (!empty($list)) {
                foreach ($list as $k => $v) {

                    if ($v['lng'] == 0 || $v['lat'] == 0) {
                        $data = [];
                        if (!empty($v['search_address'])) {
                            $search_address = explode(' ', $v['search_address']);
                            $address_info = $search_address[0];

                        } elseif (!empty($v['address'])) {
                            $address_info = $v['address'];
                        }
                        try {
                            $res = $lbs->getPointByAddress($address_info);
                            if (!empty($res['result'])) {
                                $data = ArrayHelper::getValue($res, 'result.location', []);
                            }
                            if (!empty($data)) {
                                $address = $model->findOne($v['id']);
                                $address->lng = number_format($data['lng'], 6);
                                $address->lat = number_format($data['lat'], 6);
                                $address->save();
                            } else {
                                file_put_contents('/tmp/user_point.log', "用户名为：".$v['mobile']." 搜索地址:" .$v['search_address']." 详细地址:" .$v['address']."\n", FILE_APPEND);
                            }
                        } catch(Exception $e) {

                        }

                    }
                }
                echo '第'.$page."页用户坐标写入成功!\n";
            }

            $page++;

        }


    }
    public function  actionGetUser($max_dis = 3, $shop_id = 777, $lng=0, $lat=0, $sample_id=0)
    {
//        if (!empty($lng) && !empty($lat)) {
//
//        } elseif (!empty($shop_id) && empty($lng) && empty($lat)) {
//            $info = \Yii::$app->db_500m->createCommand('select xpoint,ypoint from yp_supplier_location where id='.$shop_id)->queryOne();
//        }
        if (!empty($shop_id) && !empty($lng) && !empty($lat)) {
            //$info = \Yii::$app->db_500m->createCommand('select xpoint,ypoint from yp_supplier_location where id='.$shop_id)->queryOne();
            $shop_info = [
                ['shop_id'=>$shop_id, 'name'=>'','address'=>'', 'sample_id'=>$sample_id, 'lng'=>$lng, 'lat'=>$lat],
            ];
        } else {
            //根据商家地址获取坐标
            $lbs = new Lbs();
            foreach ($this->shop_list as $key => $val) {
                $res = $lbs->getPointByAddress($val['address']);
                if (!empty($res['result'])) {
                    $data = ArrayHelper::getValue($res, 'result.location', []);
                    $this->shop_list[$key]['lng'] = ArrayHelper::getValue($data, 'lng', 0);
                    $this->shop_list[$key]['lat'] = ArrayHelper::getValue($data, 'lat', 0);
                }
            }
//            var_dump($this->shop_list);
//            exit();
            $shop_info = $this->shop_list;
        }
        if (!empty($shop_info)) {
//            $info = \Yii::$app->db_500m->createCommand('select lng,lat from user_address')->queryAll();
            $model = new UserAddress();
            //$map = ['!=','lng', 0];
            $count = $model->find()->count();
            //echo $query->createCommand()->sql;
            $page = 1;
            $pageCount = ceil($count/$this->pageSize);
            while ($page <= $pageCount) {
                $list = $model->find()->select('id,mobile,address,search_address,lng,lat')
                    //->where($map)
                    ->offset(($page - 1) * $this->pageSize)
                    ->limit($this->pageSize)
                    ->asArray()
                    ->all();
                //var_dump($list);
                if (!empty($list)) {
                    $near_users = [];
                    foreach ($list as $k => $v) {
                        //循环用户的时候 判断用户是否在所有商家的派送范围内

                            if (!empty($v['lng']) && !empty($v['lat'])) {
                                foreach ($shop_info as $key => $val) {
                                    $dis = Common::geoDistance($val['lng'],$val['lat'],$v['lng'],$v['lat']);
                                    //var_dump($dis);
                                    $dis = $dis/1000;
                                    //var_dump($dis);
                                    if ($dis <= $max_dis) {
                                        $near_users[] = [
                                            'user_id'=>$v['id'],
                                            'shop_id'=>$val['shop_id'],
                                            'mobile'=>$v['mobile'],
                                            'sample_id'=>$val['sample_id'],
                                            'address'=>$v['address'],
                                            'search_address'=>$v['search_address'],
//                                            'lng'=>number_format($v['lng'], 6),
//                                            'lat'=>number_format($v['lat'], 6),
                                            'lng'=>$v['lng'],
                                            'lat'=>$v['lat'],
                                            'dis'=>$dis,
                                        ];
                                    }
                                }
                            }


                    }
                    //var_dump($near_users[0]);//exit();
                    if (!empty($near_users)) {
                        $re = \Yii::$app->local->createCommand()
                            ->batchInsert('near_user',['user_id', 'shop_id', 'mobile', 'sample_id','address', 'search_address', 'lng', 'lat', 'dis'], $near_users)->execute();
                       // var_dump($re);
                    }

                }

                echo '第'.$page."页用户坐标判断成功!\n";

                $page++;

            }
        }
        //var_dump($info);
    }
    public function actionTest()
    {
//        $lbs = new Lbs();
//        $res = $lbs->getPointByAddress('润坤大厦');
//        var_dump($res);
//        $data['mobile'] = 18618359358;
//        $data['content'] = 'asdfasdf测试能';
//        echo $url = \Yii::$app->params['channelUrl'].'enter/sms-add';
//        $re = CurlHelper::post($url, $data);
//        var_dump($re);
        var_dump(\Yii::$app->db_500m);
        $lng1 = 116.527747;
        $lat1 = 39.921224;
        $lng2 = 116.51666;
        $lat2 = 39.899727;
        $re = Common::geoDistance($lng1,$lat1,$lng2,$lat2);
        var_dump($re/1000);
    }
    public function actionSendMail()
    {
        $list = \Yii::$app->db_500m->createCommand("select * from near_user")->queryAll();
        $url = \Yii::$app->params['channelUrl'].'enter/sms-add';
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $content = "手机号为123的用户可以免费领取";
                $data['mobile'] = $v['mobile'];
                $data['content'] = $content;
                $re = CurlHelper::post($url, $data);
            }
        }
    }

}