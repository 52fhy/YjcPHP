<?php
/**
 * Created by PhpStorm.
 * @author: YJC
 * @date: 2017/3/30
 */

namespace Yjc\Helper;


use YJC\Http;

class Amap
{

    const KEY = '';

    //日志文件名
    const LOG_NORMAL = 'Amap';
    const LOG_ERROR = 'AmapError';

    private static $_info       =   array();
    private static $_curl_info       =   array();
    private static $module;

    /**
     * 产品介绍
     * 天气查询是一个简单的HTTP接口，根据用户输入的adcode，查询目标区域当前/未来的天气情况。
     * @see http://lbs.amap.com/api/webservice/guide/api/weatherinfo
     * @return bool|mixed
     */
    public static function gertWeatherInfo($city = '110101'){
        self::$module = __CLASS__ .'\\' .__FUNCTION__;

        $url = 'http://restapi.amap.com/v3/weather/weatherInfo';

        $data = array(
            'city' => $city,
        );

        $res = self::request($url, $data);

        $result = json_decode($res,TRUE);

        if(empty($result) || $result['status'] != 1){//1成功

            self::log(json_encode($data) . '---'. $res, self::LOG_ERROR);
            return false;
        }

        return $result;
    }

    /**
     * 指定线路交通态势
     * @see http://lbs.amap.com/api/webservice/guide/api/trafficstatus#circle
     * @param string $road_name
     * @param string $adcode
     * @return bool|mixed
     */
    public static function gertRoadTraffic($road_name = '北环大道', $adcode = '440300'){
        self::$module = __CLASS__ .'\\' .__FUNCTION__;

        $url = 'http://restapi.amap.com/v3/traffic/status/road';

        $data = array(
            'name' => $road_name,
            'adcode' => $adcode,
        );

        $res = self::request($url, $data);

        $result = json_decode($res,TRUE);

        if(empty($result) || $result['status'] != 1){//1成功

            self::log(json_encode($data) . '---'. $res, self::LOG_ERROR);
            return false;
        }

        return $result;
    }

    /** request
     * @param $url
     * @param $data
     * @return mixed
     */
    private static function request($url, $data, $method = 'GET'){
        self::$_info['start']  =  microtime(TRUE);

        $common_param = array(
            'output'=>'JSON',
            'key'=> self::KEY,
        );

        //合并参数
        $data = array_merge($data, $common_param);

        if($method == 'GET'){
            $url = $url. '?'. http_build_query($data, '&');
            $data = [];
        }

        $http = new Http();
        $body = $http->request($url, $method, $data)->getBody();


        $res = json_encode(json_decode($body, true));

        $msg = urldecode($url) . '---' . json_encode($data) . '---'. $res;
        self::log($msg);

        return $res;
    }

    /**
     * log
     * @param $message
     */
    public static function log($message, $filename = self::LOG_NORMAL){

        self::$_info['end']  =  microtime(TRUE);
        $runtime = number_format(self::$_info['end'] - self::$_info['start'], 4);

        $time=date("y-m/d");

        if(defined('APP_PATH')){
            $path = APP_PATH."Logs/http/{$time}";
        }elseif (defined('LOGS_PATH')){
            $path = LOGS_PATH."/http/{$time}";
        }else{
            $path = sys_get_temp_dir() . "/Logs/http/{$time}";
        }

        if(!file_exists($path)){
            @mkdir($path, 0777, true);
        }

        //信息截取
        if($filename == self::LOG_NORMAL && strlen($message) >= 1024 * 5){
            $message = substr($message, 0, 1024 * 5) . ' ... msg too long';
        }

//        if($filename == self::LOG_ERROR && self::$_curl_info['http_code'] != 200){
//            $message = $message . '---'. json_encode(self::$_curl_info);
//        }

        self::$module = str_replace('\\', '/', self::$module);
        $message = date('Y-m-d H:i:s'). ' ' .self::$module .'---'. $message .'---'.$runtime. ' s' .'---'. PHP_EOL;

        @error_log($message, 3, $path . "/{$filename}.log");
    }

}