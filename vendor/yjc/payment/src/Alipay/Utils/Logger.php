<?php

namespace YJC\Payment\Alipay\Utils;

class Logger
{
    const TYPE_BIZ_ACCESS = 'biz_access';
    const TYPE_BIZ_PROCESS = 'biz_process';
    const TYPE_BIZ_ERROR = 'biz_error';
    const TYPE_SQL_INSERT = 'sql_insert';
    const TYPE_SQL_UPDATE = 'sql_update';
    const TYPE_SQL_EXECUTE = 'sql_execute';
    const TYPE_SQL_ERROR = 'sql_error';
    const TYPE_LOGIN_COST = 'login_cost';
    const TYPE_DB_CONNECT = 'db_connect';
    const TYPE_EXCEPTION = 'exception';
    const TYPE_PDO = 'pdo_connect';
    const TYPE_REG_SRC = 'reg_src';
    const TYPE_MDF_SRC = 'mdf_src';
    const TYPE_ALL_COST = 'all_cost';
    const TYPE_RESULT = 'result';
    const TYPE_OTHER = 'other';
    const TYPE_FEEDBACK = 'feedback';
    const TYPE_CRASH = 'crash';
    const TYPE_JOB_TASK = 'job_task';
    const TYPE_SMS = 'sms';
    const TYPE_WECHAT = 'wechat';
    const TYPE_ALIPAY_CALLBACK = 'alipay';

    const LOG_PATH = '/logs/';

    static private function getLogFile($log_type)
    {/*{{{*/
        $dir = ALIPAY_SDK_PATH . self::LOG_PATH;
        
        if(!file_exists($dir)){
            @mkdir($dir);
        }
        
        $data = date('Ymd');
        $file = $dir . $log_type . '.log.' . $data;

        return $file;
    }/*}}}*/

    static public function writeLog($log_type, $msg, $prefix = true)
    {/*{{{*/
        /** if (@$_SERVER['DOCUMENT_ROOT'] == '') {
         * return false; // 命令行下运行
         * }
         **/

        $file = self::getLogFile($log_type);
        if (is_array($msg)) {
            $msg = var_export($msg, true);
        }

        $ip = !isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? @$_SERVER['REMOTE_ADDR'] : @$_SERVER['HTTP_X_FORWARDED_FOR'];

        if (empty($ip)) {
            $ip = '127.0.0.1';
        }

        if ($prefix) {
            $msg = $ip . "\t" . date('Y-m-d H:i:s') . "\t" . $msg . PHP_EOL;
        } else {
            $msg .= PHP_EOL;
        }
        //echo $msg;
        return error_log($msg, 3, $file);
    }/*}}}*/

    static public function writeBizAccessLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_BIZ_ACCESS, $msg);
    }/*}}}*/

    static public function writeBizProcessLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_BIZ_PROCESS, $msg);
    }/*}}}*/

    static public function writeBizErrorLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_BIZ_ERROR, $msg);
    }/*}}}*/

    static public function writeSqlInsertLog($sql, $value = false)
    {/*{{{*/
        $msg = $sql;
        if ($value) {
            $msg = array('sql' => $sql, 'value' => $value);
        }
        return self::writeLog(self::TYPE_SQL_INSERT, $msg);
    }/*}}}*/

    static public function writeSqlUpdateLog($sql, $value = false)
    {/*{{{*/
        $msg = $sql;
        if ($value) {
            $msg = array('sql' => $sql, 'value' => $value);
        }
        return self::writeLog(self::TYPE_SQL_UPDATE, $msg);
    }/*}}}*/

    static public function writeSqlExecuteLog($sql, $value = false)
    {/*{{{*/
        $msg = $sql;
        if ($value) {
            $msg = array('sql' => $sql, 'value' => $value);
        }
        return self::writeLog(self::TYPE_SQL_EXECUTE, $msg);
    }/*}}}*/

    static public function writeSqlErrorLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_SQL_ERROR, $msg);
    }/*}}}*/

    static public function writeLoginCostLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_LOGIN_COST, $msg);
    }/*}}}*/

    static public function writeDbConnectLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_DB_CONNECT, $msg);
    }/*}}}*/

    static public function writeExceptionLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_EXCEPTION, $msg);
    }/*}}}*/

    static public function writeOtherLog($msg)
    {/*{{{*/
      //  echo "\n" . $msg . "\n";

        return self::writeLog(self::TYPE_OTHER, $msg, false);
    }/*}}}*/

    static public function writePDOLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_PDO, $msg);
    }/*}}}*/

    static public function writeRegSrcLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_REG_SRC, $msg);
    }/*}}}*/

    static public function writeMdfSrcLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_MDF_SRC, $msg);
    }/*}}}*/

    static public function writeAllCostLog($msg)
    {/*{{{*/
        return self::writeLog(self::TYPE_ALL_COST, $msg);
    }/*}}}*/

    static public function writeResultLog($msg)
    {
        return self::writeLog(self::TYPE_RESULT, $msg);
    }

    static public function writeFeedbackLog($msg)
    {
        return self::writeLog(self::TYPE_FEEDBACK, $msg);
    }

    static public function writeCrashLog($msg)
    {
        return self::writeLog(self::TYPE_CRASH, $msg);
    }

    static public function writeJobTaskLog($msg)
    {
        return self::writeLog(self::TYPE_JOB_TASK, $msg);
    }

    static public function writeSMSLog($msg)
    {
        return self::writeLog(self::TYPE_SMS, $msg);
    }

    static public function writeAlipayCallBackLog($msg)
    {
        return self::writeLog(self::TYPE_ALIPAY_CALLBACK, $msg);
    }

    static public function writeWechatLog($msg)
    {
        return self::writeLog(self::TYPE_WECHAT, $msg);
    }
}

?>
