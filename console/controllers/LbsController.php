<?php
/**
 * Lbs数据同步
 *
 * PHP Version 5
 * Lbs数据同步
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/9/18 上午10:57 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace console\controllers;

use backend\models\i500_social\UserService;
use backend\models\SocialMongo;
use console\models\i500m\Product;
use yii\console\Controller;

/**
 * Lbs
 *
 * @category Channel
 * @package  Lbs
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class LbsController extends Controller
{
    public function actionSyncUser()
    {
        $user_model = new UserService();
        $select = ['uid', 'mobile', 'name', 'province_id', 'lng', 'lat', 'user_name', 'user_card', 'user_sex', 'user_age', 'user_home', 'audit_status', 'status', 'is_deleted', 'create_time'];
        $list = UserService::find()->select($select)->asArray()->all();
        $mongo = new SocialMongo();

        if (!empty($list)) {
            foreach($list as $k=>$val){
                //$item[$k]['shop_id'] = intval($val['shop_id']);
                $list[$k]['lng'] = floatval($val['lng']);
                $list[$k]['lat'] = floatval($val['lat']);
                $list[$k]['loc'] = [floatval($val['lng']),floatval($val['lat'])];
                $list[$k]['audit_status'] = intval($val['audit_status']);
                $list[$k]['status'] = intval($val['status']);
                $list[$k]['is_deleted'] = intval($val['is_deleted']);

            }
            $mongo->syncUserService($list);
        } else {
            echo "无数据/n";
        }


        return true;
    }
}