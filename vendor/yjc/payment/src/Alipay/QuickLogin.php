<?php
namespace YJC\Payment\Alipay;
use YJC\Payment\Alipay\Utils\AlipayNotify;
use YJC\Payment\Alipay\Utils\AlipaySubmit;
use YJC\Payment\Alipay\Utils\Config;


/**
 * 支付宝快捷登录
 * 
 * 接入支付宝登录功能，可以让开发者的网站在用户登录支付宝账号后，获得用户的支付宝UID，实现账户体系打通后快速登录。
 *
 * @see https://doc.open.alipay.com/doc2/detail.htm?spm=0.0.0.0.MeMpaD&treeId=65&articleId=103570&docType=1
 * @author YJC
 *        
 */
class QuickLogin{
    
    const API_GETWAY = 'https://mapi.alipay.com/gateway.do?';
    
    private $alipay_config;
    
    public function __construct($alipay_config = array()){
        $this->alipay_config = $alipay_config ? : getConfig();
    }
    
    
    public function login(){
        //目标服务地址
        $target_service = "user.auth.quick.login";
        //必填
        //必填，页面跳转同步通知页面路径
        $return_url = "web/order/alipayQuickLoginReturn";
        //需http://格式的完整路径，不允许加?id=123这类自定义参数         //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数         //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
    
    
        /************************************************************/
    
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.auth.authorize",
            "partner" => trim($this->alipay_config['partner']),
            "target_service"	=> $target_service,
            "return_url"	=> $return_url,
            "anti_phishing_key"	=> $anti_phishing_key,
            "exter_invoke_ip"	=> $exter_invoke_ip,
            "_input_charset"	=> trim(strtolower($this->alipay_config['input_charset']))
        );
    
        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config, self::API_GETWAY);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }
    
    public function notify() {
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        
        if($verify_result) {//验证成功
        	//请在这里加上商户的业务逻辑程序代码
        	
        	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        
        	//支付宝用户号 	$user_id = $_GET['user_id'];
        
        	//授权令牌
        	$token = $_GET['token'];
        
        
        	//判断是否在商户网站中已经做过了这次通知返回的处理
        		//如果没有做过处理，那么执行商户的业务程序
        		//如果有做过处理，那么不执行商户的业务程序
        		
        	echo "验证成功<br />";
        
        	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        	
        	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }
                
    }
}
