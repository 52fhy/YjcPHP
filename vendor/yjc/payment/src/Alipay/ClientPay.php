<?php
namespace YJC\Payment\Alipay;

use YJC\Payment\Alipay\Utils\AlipayNotify;
use YJC\Payment\Alipay\Utils\AlipaySubmit;
use YJC\Payment\Alipay\Utils\Config;

/**
 * 客户端支付
 * 
 * 1、服务端负责传递必要参数给客户端
 * 2、服务端处理notify
 * @author YJC
 *        
 */
class ClientPay{
    
    const API_GETWAY = 'https://mapi.alipay.com/gateway.do?';
    
    private $alipay_config;

    public function __construct($alipay_config = array()){
        $this->alipay_config = $alipay_config ? : Config::getConfig();
    }
    
    public function getPayInfo($order_id, $amount) {
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = 'web/order/alipayWebNotify';
        
        //页面跳转同步通知页面路径
        $return_url = 'web/order/alipayWebReturn';
        
        //商户订单号
        $out_trade_no = md5($order_id);
        //商户网站订单系统中唯一订单号，必填
        
        //订单名称
        $subject = '板牙救援费用';
        //必填
        
        //付款金额
        $total_fee = $amount;
        //必填
        
        //订单描述
        $body = '板牙救援费用';

        
        /************************************************************/
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "mobile.securitypay.pay",
            "partner" => trim($this->alipay_config['partner']),
            "seller_id" => trim($this->alipay_config['seller_id']),
            "payment_type" => $payment_type,
            "notify_url" => $notify_url,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            'out_context' => '',//商户业务扩展参数，json格式
            "_input_charset" => trim(strtolower($this->alipay_config['input_charset']))
        );
        
        $this->alipay_config['sign_type'] = 'RSA'; //签名类型，目前仅支持RSA
        
        $alipaySubmit = new AlipaySubmit($this->alipay_config, self::API_GETWAY);
        $para_sort = $alipaySubmit->buildRequestPara($parameter);
        
        $info = array_merge($this->alipay_config, $para_sort);

        return $info;
    }
    
    public function notify($callback) {
        $msg = __CLASS__ . '::' . __FUNCTION__ . "\t" . json_encode($_POST);
        
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        //   $verify_result = true;
        if ($verify_result) {
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
        
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
        
            //交易状态
            $trade_status = $_POST['trade_status'];
        
            if ($trade_status == 'TRADE_FINISHED') {
        
            } else if ($trade_status == 'TRADE_SUCCESS') {
                
                //以下是业务逻辑
                //判断订单状态，如果未支付，更改状态为支付
                
                //使用try...catch结构捕捉数据库事务操作
                
                //成功则：echo "success";
                //失败则：echo "fail";
                
                is_callable($callback) && $callback($_POST);

            }
        } else {
            echo "fail";
            $msg .= "\t" . 'fail';
        }
        
        Logger::writeAlipayCallBackLog($msg);
    }
    
}