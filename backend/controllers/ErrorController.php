<?php
/**
 * 错误
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  BaseController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/3 下午5:53
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\controllers;

use yii\web\Controller;

/**
 * ErrorController
 *
 * @category Channel
 * @package  ErrorController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ErrorController extends Controller
{
    /**
     * 错误页面
     *
     * @return array
     */
    public function actionIndex()
    {
        $message = '页面不存在';
        $arr = array('code' => 404, 'content' => $message);
        echo json_encode($arr);
    }
}
