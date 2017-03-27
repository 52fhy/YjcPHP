<?php
namespace YJC\Payment\Alipay;

use YJC\Payment\Alipay\Utils\AlipayNotify;
use YJC\Payment\Alipay\Utils\AlipaySubmit;
use YJC\Payment\Alipay\Utils\Config;

/**
 * 
 * 支付宝手机Wap支付
 *
 * @author YJC
 * 
 * 注意：新版的wap支付已经和之前(WS_WAP_PAYWAP.zip版本)的接口不同了，网关也发生了变化
 * 
 * 可以参考demo:https://doc.open.alipay.com/doc2/detail?treeId=54&articleId=103419&docType=1
 * 
 * 详见https://doc.open.alipay.com/doc2/detail.htm?spm=0.0.0.0.i3Xee7&treeId=60&articleId=103693&docType=1
 *        
 */
class Wap{
    
    const API_GETWAY = 'https://mapi.alipay.com/gateway.do?';
    
    private $alipay_config;
    
    public function __construct($alipay_config = array()){
        $this->alipay_config = $alipay_config ? : Config::getConfig();
    }

    
    public function pay($order_id, $amount, $options = array()) {
        
        //支付类型
        $payment_type = "1";
        
        $notify_url = isset($options['notify_url']) ? $options['notify_url'] : '';
        
        //页面跳转同步通知页面路径
        $return_url = isset($options['return_url']) ? $options['return_url'] : '';

        //商户订单号
        $out_trade_no = $order_id;
        
        $subject = isset($options['title']) ? $options['title'] : '';
        $total_fee = $amount;

        //可空, 商品展示地址
        $show_url = isset($options['show_url']) ? $options['show_url'] : '';
        
        //选填   订单描述
        $body = isset($options['body']) ? $options['body'] : '';
        //选填         //超时时间
        $it_b_pay = '';
        //选填         //钱包token
        $extern_token = '';

        //是否尝试掉起支付宝APP原生支付
        $use_app = isset($options['use_app']) ? 'Y' : '';
        
        /************************************************************/
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.create.direct.pay.by.user",
            "partner" => trim($this->alipay_config['partner']),
            "seller_id" => trim($this->alipay_config['seller_id']),
            "payment_type"	=> $payment_type,
            "notify_url"	=> $notify_url,
            "return_url"	=> $return_url,
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "show_url"	=> $show_url,
            "body"	=> $body,
            'app_pay' => $use_app,
            "it_b_pay"	=> $it_b_pay,
            "extern_token"	=> $extern_token,
            "_input_charset"	=> trim(strtolower($this->alipay_config['input_charset']))
        );
        
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config, self::API_GETWAY);
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '');
        echo $html_text;
    }
    
    public function notify($callback){
        
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        if(!$verify_result) {//验证成功
            throw new \Exception('Invalid request payloads.', 400);
        }

        $trade_status = $_POST['trade_status'];

        $handleResult = call_user_func_array($callback, array($_POST, $trade_status));

        if (is_bool($handleResult) && $handleResult) {
            $response = 'success';
        } else {
            $response = 'fail';
        }

        return $response;
    }
    
}