<?php
namespace YJC\Payment\Alipay;

use YJC\Payment\Alipay\Utils\Config;
use YJC\Payment\Alipay\Utils\Core;
use YJC\Payment\Alipay\Utils\Logger;
use YJC\Payment\Alipay\Utils\Rsa;

/**
 * 
 * 支付宝扫码支付
 *
 * @author YJC
 *
 * @see http://app.alipay.com/market/document.htm?name=saomazhifu#page-19
 *        
 */
class ScanQrcodePay{
    
    const API_GETWAY_DEV = 'https://openapi.alipaydev.com/gateway.do?charset=utf-8';
    const API_GETWAY = 'https://openapi.alipay.com/gateway.do?charset=utf-8';

    const APP_ID = '2016080901722409';
    const APP_ID_DEV = '2016080100141801';

    const VERSION = '1.0';

    private $alipay_config;
    
    public function __construct($alipay_config = array()){
        $this->alipay_config = $alipay_config ? : Config::getConfig();
    }

    
    public function pay($order_id, $amount, $options = array()) {
        
        $notify_url = isset($options['notify_url']) ? $options['notify_url'] : '';

        //商户订单号
        $out_trade_no = $order_id;
        
        $subject = isset($options['title']) ? $options['title'] : '';
        $total_fee = $amount;
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "method" => "alipay.trade.precreate",
            "app_id" => self::APP_ID_DEV,
            "charset"	=> 'utf-8',
            "sign_type"	=> 'RSA',
            "timestamp"	=> date('Y-m-d H:i:s'),
            "notify_url"	=> $notify_url,
            "version"	=> self::VERSION,
            "biz_content" => json_encode(array(
                "out_trade_no" => $out_trade_no,
                "seller_id" => $this->alipay_config['seller_id'],
                "total_amount" => $total_fee,
                "subject"	=> $subject,
            ))
        );
        
        //建立请求
        $html_text = $this->sendPost($parameter);
        return $html_text;
    }

    public function buildPara($para_temp){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = Core::paraFilter2($para_temp);

        //对待签名参数数组排序
        $para_sort = Core::argSort($para_filter);

        //生成签名结果
        $arg  = Core::createLinkstring($para_sort);

        $mysign = Rsa::rsaSign($arg, $this->alipay_config['open_private_key_path']);

        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;

        return $para_sort;
    }

    private function sendPost($para_temp){
        $para_sort = $this->buildPara($para_temp);
        $sResult = Core::getHttpResponsePOST(self::API_GETWAY_DEV, $this->alipay_config['cacert'], $para_sort, '');

        return $sResult;
    }

    /**
     * 本接口提供支付宝支付订单的查询的功能，商户可以通过本接口主动查询订单状态，完成下一步的业务逻辑。
     * @param $out_trade_no
     * @return mixed
     * @see http://app.alipay.com/market/document.htm?name=saomazhifu#page-15
     */
    public function query($out_trade_no){
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "method" => "alipay.trade.query",
            "app_id" => self::APP_ID_DEV,
            "charset"	=> 'utf-8',
            "sign_type"	=> 'RSA',
            "timestamp"	=> date('Y-m-d H:i:s'),
            "version"	=> self::VERSION,
            "biz_content" => json_encode(array(
                "out_trade_no" => $out_trade_no
            ))
        );

        //建立请求
        $html_text = $this->sendPost($parameter);
        return $html_text;
    }

    /**
     * 支付宝扫码支付异步通知
     * @param $callback
     * @return string
     * @throws \Exception
     */
    public function notify($callback){

        try{
            //计算得出通知验证结果
            $verify_result = $this->verifyNotify();

            if(!$verify_result) {//验证成功
                //throw new \Exception('Invalid request payloads.', 400);
            }

            $trade_status = $_POST['trade_status'];

            $handleResult = call_user_func_array($callback, array($_POST, $trade_status));
        }catch (\Exception $e){
            Logger::writeAlipayCallBackLog($e->getMessage());

            $handleResult = false;
        }

        if (is_bool($handleResult) && $handleResult) {
            $response = 'success';
        } else {
            $response = 'fail';
        }

        return $response;
    }

    /**
     * 支付宝扫码支付异步通知验签
     * @see http://app.alipay.com/market/document.htm?name=saomazhifu#page-13
     * @return bool
     */
    private function verifyNotify(){
        if(empty($_POST)) {//判断POST来的数组是否为空
            return false;
        }
        else {

            $data = $_POST;

            //1、在通知返回参数列表中，除去sign、sign_type两个参数外，凡是通知返回回来的参数皆是待验签的参数。
            $para_filter = Core::paraFilter($data);

            //2、将剩下参数进行url_decode, 然后进行字典排序
            $para_sort = Core::argSort($para_filter);

            //3、组成字符串，得到待签名字符串
            $prestr  = Core::createLinkstring($para_sort);

            //4、使用RSA的验签方法，通过签名字符串、签名参数（经过base64解码）及支付宝公钥验证签名。
            $isSign = Rsa::rsaVerify($prestr, $this->alipay_config['open_ali_public_key_path'], $data['sign']);

            Logger::writeAlipayCallBackLog('verifyNotify:'.json_encode($data).'---'. $data['sign']. '---'. $prestr . '---' . $isSign);

            if ($isSign) {
                return true;
            } else {
                return false;
            }

        }
    }

    public function t(){
        $data = '{"fund_bill_list":"[{\"amount\":\"80.00\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","subject":"\u677f\u7259\u6551\u63f4\u8d39\u7528","trade_no":"2016081021001004010200191284","gmt_create":"2016-08-10 11:23:59","notify_type":"trade_status_sync","total_amount":"80.00","out_trade_no":"BY20160416131520536","invoice_amount":"80.00","open_id":"20881062157305106949450820116601","seller_id":"2088102169462665","notify_time":"2016-08-10 11:24:17","trade_status":"TRADE_SUCCESS","gmt_payment":"2016-08-10 11:24:16","seller_email":"xkkdcd8619@sandbox.com","receipt_amount":"80.00","buyer_id":"2088102168705013","app_id":"2016080100141801","notify_id":"00e99b05f2772662fe1bbca09874b53g2u","buyer_logon_id":"vgs***@sandbox.com","sign_type":"RSA","buyer_pay_amount":"80.00","sign":"gQ6jw2Tx2EtA0TgB950GdYSHrp0GD0l31goqq5y4\/n7+ex+RQ7mcvgf7vzTVDLoFVXlgSb2XO3YEeCfALxqA4Uvf5HxEJA7jArBAkoWxKY+1WLQZAqAOa7darNgFzH7+jRl0EsPqCPBbMVVW1\/D7wsCn1AaoPD2aWT8FC4mw5qY=","point_amount":"0.00"}';
        $data = json_decode($data,true);

        $para_filter = Core::paraFilter($data);

        $para_sort = Core::argSort($para_filter);

        Core::dump($para_sort);

        //生成签名结果
        $prestr  = Core::createLinkstring($para_sort);

        Core::dump($prestr);

        Core::dump($data['sign']);

        $isSign = Rsa::rsaVerify($prestr, $this->alipay_config['open_ali_public_key_path'], $data['sign']);
        Core::dump($isSign);

        if ($isSign) {
            return true;
        } else {
            return false;
        }
    }
    
}