<?php
namespace YJC\Payment\Alipay;

use YJC\Payment\Alipay\Utils\AlipayNotify;
use YJC\Payment\Alipay\Utils\AlipaySubmit;
use YJC\Payment\Alipay\Utils\Config;
/**
 * 
 * 支付宝即时到账，适用于PC端
 *
 * @author YJC
 *        
 */
class Direct{
    
    const API_GETWAY = 'https://mapi.alipay.com/gateway.do?';
    
    private $alipay_config;

    public function __construct($alipay_config = array()){
        $this->alipay_config = $alipay_config ? : Config::getConfig();
    }
    
    public function pay($order_id, $amount, $options = array()) {
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = isset($options['notify_url']) ? $options['notify_url'] : '';
    
        //页面跳转同步通知页面路径
        $return_url = isset($options['return_url']) ? $options['return_url'] : '';
    
        //商户订单号
        $out_trade_no = $order_id;
        //商户网站订单系统中唯一订单号，必填
    
        //订单名称
        $subject = isset($options['title']) ? $options['title'] : '';
        //必填
    
        //付款金额
        $total_fee = $amount;
        //必填
    
        //订单描述
    
        $body = isset($options['body']) ? $options['body'] : '';
        //商品展示地址
        $show_url = isset($options['show_url']) ? $options['show_url'] : '';
    
        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数
    
        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
    
        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($this->alipay_config['partner']),
            "seller_email" => trim($this->alipay_config['seller_email']),
            "payment_type" => $payment_type,
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($this->alipay_config['input_charset']))
        );
    
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config, self::API_GETWAY);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "");
        echo $html_text;
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
    
    public function back($callback) {
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        
        //业务逻辑：
        
        is_callable($callback) && $callback($_GET);
        
        if ($verify_result) {
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
        
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
        
            //交易状态
            $trade_status = $_GET['trade_status'];
        
            if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                
                //判断订单状态，如果未支付，更改状态为支付
                
                //使用try...catch结构捕捉数据库事务操作
                
                //成功则：跳转到该订单页或者其它页面
                //失败则：展示失败原因
            } else {
                //
            }
        
            //redirect('web/order/paInfo/' . $order_id);
        
        } else {
            //$this->showErrorPage();
        }
    }
}