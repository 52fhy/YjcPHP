<?php
namespace YJC\Payment\Alipay;
use YJC\Payment\Alipay\Utils\AlipayNotify;
use YJC\Payment\Alipay\Utils\AlipaySubmit;

/**
 *
 * @author YJC
 *        
 */
class BacthTrans{
    
    const API_GETWAY = 'https://mapi.alipay.com/gateway.do?';
    
    private $alipay_config;
    
    public function __construct($alipay_config){
        $this->alipay_config = $alipay_config;
    }
    
    /**
     * 批量付款到支付宝账户有密接口
     * ok
     */
    public function trans(){
    
        /**************************请求参数**************************/
    
        //服务器异步通知页面路径
        $notify_url = "web/order/alipayBatchTransNotify";
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
    
        //付款账号
        $email = trim($this->alipay_config['seller_email']);
        //必填
    
        //付款账户名
        $account_name = 'cs';
        //必填，个人支付宝账号是真实姓名公司支付宝账号是公司名称
    
        //付款当天日期
        $pay_date = date('Ymd');
        //必填，格式：年[4位]月[2位]日[2位]，如：20100801
    
        //批次号
        $batch_no = date('Ymd'). rand(1000,999);
        //必填，格式：当天日期[8位]+序列号[3至16位]，如：201008010000001
    
        //付款总金额
        $batch_fee = 0.01;
        //必填，即参数detail_data的值中所有金额的总和
    
        //付款笔数
        $batch_num = 1;
        //必填，即参数detail_data的值中，“|”字符出现的数量加1，最大支持1000笔（即“|”字符出现的数量999个）
    
        //付款详细数据
        $detail_data = '1001^18271260223^余建材^0.01^无';
        //必填，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....
    
    
        /************************************************************/
    
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "batch_trans_notify",
            "partner" => trim($this->alipay_config['partner']),
            "notify_url"	=> $notify_url,
            "email"	=> $email,
            "account_name"	=> $account_name,
            "pay_date"	=> $pay_date,
            "batch_no"	=> $batch_no,
            "batch_fee"	=> $batch_fee,
            "batch_num"	=> $batch_num,
            "detail_data"	=> $detail_data,
            "_input_charset"	=> trim(strtolower($this->alipay_config['input_charset']))
        );
    
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config, self::API_GETWAY);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }
    
    /**
     * 批量付款到支付宝账户有密接口 异步通知
     */
    public function notify(){
    
        //基本同 无密退款、有密退款异步通知
         
        $msg = __CLASS__ . '::' . __FUNCTION__ . "\t" . json_encode($_POST);
    
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
    
        if($verify_result) {//验证成功
    
            //批量付款数据中转账成功的详细信息
    
            $success_details = $_POST['success_details'];
    
            //批量付款数据中转账失败的详细信息
            $fail_details = $_POST['fail_details'];
             
            try{
                //实际可以根据该状态对订单状态进行转账成功操作。订单号可以由交易号或者批次号查找生成
            }catch(\Exception $e){
                $msg .= "\t" . 'fail' . "\t" . $e->getMessage();
                echo "fail";
            };
    
            echo "success";		//请不要修改或删除
            $msg .= "\t" . 'success';
    
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        }
        else {
            //验证失败
            echo "fail";
            $msg .= "\t" . 'fail';
        }
    
        logResult($msg);
    }
}
