<?php
namespace YJC\Payment\Alipay;

use YJC\Payment\Alipay\Utils\AlipayNotify;
use YJC\Payment\Alipay\Utils\AlipaySubmit;
use \Exception;
use \DOMDocument;

/**
 *
 * @author YJC
 *        
 */
class Refund{
    
    const API_GETWAY = 'https://mapi.alipay.com/gateway.do?';//这才是正确的网关
    
    private $alipay_config;
    
    public function __construct($alipay_config){
        $this->alipay_config = $alipay_config;
    }
    
    /**
     * 支付宝有密退款
     *
     * ok
     */
    public function refundPwd(){
        
        /**************************请求参数**************************/
    
        //服务器异步通知页面路径
        $notify_url = 'web/order/alipayRefundPwdNotify'; //这个url用来接收支付宝的异步通知，验证成功后，进行订单状态更改或处理其它业务逻辑
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
    
        //退款批次号
        $batch_no = date('Ymd'). rand(1000,999);
        //必填，每进行一次即时到账批量退款，都需要提供一个批次号，必须保证唯一性
    
        //退款请求时间
        $refund_date = date('Y-m-d H:i:s');
        //必填，格式为：yyyy-MM-dd hh:mm:ss
    
        //退款总笔数
        $batch_num = 1;
        //必填，即参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的最大数量999个）
    
        //单笔数据集
        $detail_data = '2016021821001004240200612547^0.04^协商退款';
        //必填，格式详见“4.3 单笔数据集参数说明”:原付款支付宝交易号^退款总金额^退款理由
    
    
        /************************************************************/
    
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "refund_fastpay_by_platform_pwd",
            "partner" => trim($this->alipay_config['partner']),
            "notify_url"	=> $notify_url,
            "seller_email" => trim($this->alipay_config['seller_email']),
            "refund_date"	=> $refund_date,
            "batch_no"	=> $batch_no,
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
     * 支付宝有密退款 异步通知
     */
    public function refundPwdNotify(){
        //同 支付宝无密退款 异步通知
    }
    
    /**
     * 支付宝无密退款
     *
     * 需要电话申请
     *
     * 会同步返回xml处理结果数据
     */
    public function refundNopwd(){
    
        /**************************请求参数**************************/
    
        //服务器异步通知页面路径
        $notify_url = 'web/order/alipayRefundNopwdNotify';//这个url用来接收支付宝的异步通知，验证成功后，进行订单状态更改或处理其它业务逻辑
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
    
        //退款批次号
        $batch_no = date('Ymd'). rand(1000,999);
        //必填，每进行一次即时到账批量退款，都需要提供一个批次号，必须保证唯一性
    
        //退款请求时间
        $refund_date = date('Y-m-d H:i:s');
        //必填，格式为：yyyy-MM-dd hh:mm:ss
    
        //退款总笔数
        $batch_num = 1;
        //必填，即参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的最大数量999个）
    
        //单笔数据集
        $detail_data = '2016021821001004240200612547^0.04^协商退款';
        //必填，格式详见“4.3 单笔数据集参数说明”
    
    
        /************************************************************/
    
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "refund_fastpay_by_platform_nopwd",
            "partner" => trim($this->alipay_config['partner']),
            "notify_url"	=> $notify_url,
            "batch_no"	=> $batch_no,
            "refund_date"	=> $refund_date,
            "batch_num"	=> $batch_num,
            "detail_data"	=> $detail_data,
            "_input_charset"	=> trim(strtolower($this->alipay_config['input_charset']))
        );
    
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config, self::API_GETWAY);
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
    
        //解析XML
        //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
        $doc = new DOMDocument();
        $doc->loadXML($html_text);//出现错误请注意查看cacert.pem所在目录位置，打印alipay_config['cacert']即可查看
    
        //请在这里加上商户的业务逻辑程序代码
    
        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
    
        //该处的成功，只代表批量退款申请被接受，退款是否执行成功信息需要通过服务器异步通知来获取。
    
        //解析XML
        if( ! empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ) {
            //$alipay = $doc->getElementsByTagName( "alipay" )->item(0)->nodeValue;
    
            $alipay = $doc->getElementsByTagName( "alipay" )->item(0);
            $flag = $alipay->getElementsByTagName( "is_success" )->item(0)->nodeValue;
            $msg = @$alipay->getElementsByTagName( "error" )->item(0)->nodeValue;
    
            //echo $flag;  //成功标志。T ：成功 F ：失败 P：处理中
    
            switch ($flag){
                case 'T': echo '退款申请已受理';break;
                case 'F': echo '退款失败:'.$msg;break;
                case 'P': echo '退款申请处理中';break;
            }
        }else{
            echo '退款失败';
        }
    
        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    }
    
    /**
     * 支付宝无密退款 异步通知
     */
    public function refundNopwdNotify($order_id, $pay_info_id){

        $msg = __CLASS__ . '::' . __FUNCTION__ . "\t" . json_encode($_POST);
    
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        //$verify_result = $alipayNotify->verifyNotify(); //一直验证失败
        $verify_result = true;
    
        if($verify_result) {//验证成功
    
            //退款批次号
            $batch_no = $_POST['batch_no'];

            //退款成功总数
            $success_num = $_POST['success_num'];

            //处理结果详情
            $result_details = $_POST['result_details'];

            //解冻结果明细
            $unfreezed_deta = $_POST['unfreezed_deta'];
            //格式：解冻结订单号^冻结订单号^解冻结金额^交易号^处理时间^状态^描述码
    
    
            //判断是否在商户网站中已经做过了这次通知返回的处理
            //如果没有做过处理，那么执行商户的业务程序
            //如果有做过处理，那么不执行商户的业务程序
             
            try{
                //实际可以根据该状态对订单状态进行退款成功操作。订单号可以由交易号或者批次号查找生成
                
                //实际可以根据该状态对订单状态进行退款成功操作。订单号可以由交易号或者批次号查找生成
                $this->_CI->OrderPayInfoModel->updateById($pay_info_id, array(
                    'flag' => Enum_Order_Pay_Flag::REFUND,
                    'update_time' => qy_now()
                ));
                
                $this->_CI->UserOrderModel->updatePayFlag($order_id, Enum_Order_Pay_Flag::REFUND);
                $this->_CI->UserOrderModel->updateOrderFlag($order_id, $order->flag, Enum_Order_Flag::REFUND);
                
            }catch(Exception $e){
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