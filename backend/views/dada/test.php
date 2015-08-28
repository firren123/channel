<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Channel
 * @filename  test.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/24 下午3:43
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
?>
<table>
    //token	string	是	访问令牌（access_token)
    $data['token'] = $this->_access_token;
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    //timestamp	int	是	时间戳,以秒计算时间，即unix-timestamp
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    $data['timestamp'] = $time;
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    //signature	string	是	加密签名 详情见消息体签名算法
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    $data['signature'] = $signature;
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    //origin_id	string	是	第三方对接平台订单id
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    $data['origin_id'] = $time;
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    //city_name	string	否	订单所在城市名 称(如上海就填”上海“，勿带上“市”
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    $data['city_name'] = $city_name ='上海';
    //city_code	string	是	订单所在城市的 code(如上海就填 "021")
    $data['city_code'] = 021;
    //pay_for_supplier_fee	float	是	付给商家的费用, 如果无需支付,传0
    $data['pay_for_supplier_fee'] = 0;
    //fetch_from_receiver_fee	float	是	向客户收取的费 用,如果无,传0
    $data['fetch_from_receiver_fee'] = 0;
    //deliver_fee	float	是	第三方平台补贴的运费金额,金额由达达与第三方接入平台接入前共同讨论决定。如果无,传0
    $data['deliver_fee'] = 0;
    //create_time	int	是	第三方平台的原始订单创建时间戳
    $data['create_time'] = $time;
    //info	string	是	订单详细信息或者订单备注
    $data['info'] = 010;
    //cargo_type	int	是	订单商品类型 1、餐饮 2、饮 料 3、鲜花 4、票 务 5、其他 8、印刷品 9、便利店 10、学校餐饮 11、校园便利 12、生鲜 13、水果
    $data['cargo_type'] = 13;
    //cargo_weight	float	是	订单商品重量,如果无,传为1
    $data['cargo_weight'] = 1;
    //cargo_price	float	是	订单商品价格
    $data['cargo_price'] = 11.11;
    //cargo_num	int	是	商品份数
    $data['cargo_num'] = 12;
    //is_prepay	int	是	是否需要垫付 1:是 0:否
    $data['is_prepay'] = 0;
    //expected_fetch_time	int	是	期望取货时间,如果无,传0,传0的情况下，默认取当前时间10分钟之后为期望取货时间
    $data['expected_fetch_time'] = 0;
    //expected_finish_time	int	是	期望送达时间,如果无,传0
    $data['expected_finish_time'] = 0;
    //supplier_id	string	是	发货人id
    $data['supplier_id'] = 1;
    //supplier_name	string	是	发货人姓名,平台名-商家名
    $data['supplier_name'] = '平台名-商家名';
    //supplier_address	string	是	发货地址
    $data['supplier_address'] = '发货地址';
    //supplier_phone	string	是	发货人手机号
    $data['supplier_phone'] = '15010325343';
    //supplier_tel	string	是	发货人座机,不需要传区号，手机号和座机号 二者至少有一个
    $data['supplier_tel'] = '010-88888888';
    //supplier_lat	float	是	发货人(商家)纬度,如果无,传0.(坐标系为高德地图坐标系，又称火星坐标)
    $data['supplier_lat'] = 0;
    //supplier_lng	float	是	发货人(商家)经度,如果无,传0.(坐标系为高德地图坐标系，又称火星坐标)
    $data['supplier_lng'] = 0;
    //invoice_title	string	是	发票抬头(个人填 “个人”,公司填 公司名称),如果无,传空值
    $data['invoice_title'] = '';
    //receiver_name	string	是	收货人姓名,如果无,传空值
    $data['receiver_name'] = '收货人姓名';
    //receiver_address	string	是	收货人地址
    $data['receiver_address'] = '收货人地址';
    //receiver_phone	string	是	收货人手机
    $data['receiver_phone'] = '15010325343';
    //receiver_tel	string	是	收货人座机, 不需要传区号，手机号和座机号 二者至少有一个
    $data['receiver_tel'] = '010-88888888';
    //receiver_lat	float	是	收货人地址纬度, 如果无,传0.(坐标系为高德地图坐标系，又称火星坐标)
    $data['receiver_lat'] = 0;
    //receiver_lng	float	是	收货人地址经度, 如果无,传0.(坐标系为高德地图坐标系，又称火星坐标)
    $data['receiver_lng'] = 0;
    //callback	string	是	回调URL
    $data['callback'] = 'http://baidu.com';
    <tr>
        <th></th>
        <th><input type="text" name="xxx"/></th>

    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>