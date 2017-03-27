<?php

namespace YJC;
use \PDO;
use \PDOException;
use \Exception;

class PDODriver
{
    private $_dbh = null;
    private $_db_encoding = 'utf8';

    public function __construct($db_host, $db_user, $db_pass, $db_name, $db_port = '3306')
    {
    	$persistent = empty($_SERVER['argv'][0]) ? true : false;
        $t1 = microtime(true);
        $this->_dbh = new PDO("mysql:host={$db_host};dbname={$db_name};port={$db_port}", $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => $persistent));
        $t2 = round((microtime(true)- $t1)*1000, 3);
        if($t2 > 1000)
        {
            $log_msg = ' - ' . $t2 . ' - ' . $db_host;
            Logger::writePDOLog($log_msg);
        }
        $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_dbh->query("SET NAMES '{$this->_db_encoding}'");
        $this->_dbh->query("SET character_set_client=binary");
    }

    public function query($sql, $values=array())
    {
        $this->_dbh->query("SET NAMES '{$this->_db_encoding}'");
        $this->log($sql, $values);
        try
        {
            $sth = $this->_dbh->prepare($sql);
            $i = 0;
            foreach($values as $value)
            {
                $sth->bindValue(++$i, $value);
            }
            if($sth->execute())
            {
                $result = $sth->fetch(PDO::FETCH_ASSOC);
                if($result) return $result;
            }
        }
        catch (PDOException $e)
        {
            $this->processError($sql, $e, $values);
        }

        return false;
    }

    public function querys($sql, $values=array())
    {
        $this->_dbh->query("SET NAMES '{$this->_db_encoding}'");
        
		$this->log($sql, $values);
        try {
            $sth = $this->_dbh->prepare($sql);
            $i = 0;
            foreach($values as $value)
            {
                $sth->bindValue(++$i, $value);
            }
            if($sth->execute())
            {
                return $sth->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        catch (PDOException $e)
        {
            $this->processError($sql, $e, $values);
        }
    }

    public function exeNoQuery($sql, $values=array())
    {
        $this->_dbh->query("SET NAMES '{$this->_db_encoding}'");
        $this->log($sql, $values);
        try {
            $sth = $this->_dbh->prepare($sql);
            $i = 0;
            foreach($values as $value)
            {
                $sth->bindValue(++$i, $value);
            }
            return $sth->execute();
        }
        catch (PDOException $e)
        {
            $this->processError($sql, $e, $values);
        }
    }

    public function execute($sql, $values=array())
    {
        $this->_dbh->query("SET NAMES '{$this->_db_encoding}'");
        $this->log($sql, $values);
        try {
            $sth = $this->_dbh->prepare($sql);
            $i = 0;
            foreach($values as $value)
            {
                $sth->bindValue(++$i, $value);
            }
            $sth->execute();
            return $sth->rowCount();
        }
        catch (PDOException $e)
        {
            $this->processError($sql, $e, $values);
        }
    }

    public function processError($sql, $e, $values=array())
    {
        $msg['sql'] = $sql;
        $msg['values'] = var_export($values, true);
        $msg['message'] = $e->getMessage();
        $msg['TraceAsString'] = $e->getTraceAsString();
        Logger::writeSqlErrorLog($msg);
		throw new Exception($e->getMessage());
    }

    public function setTransactionLevel($level)
    {
        $sql_set = "SET transaction isolation level $level; ";
        return self::execute($sql_set);
    }

    public function beginTrans()
    {
        $this->_dbh->beginTransaction();
    }

    public function commit()
    {
        return $this->_dbh->commit();
    }

    public function rollback()
    {
        return $this->_dbh->rollback();
    }

    public function getLastInsertID()
    {
        return (int)$this->_dbh->lastInsertId();
    }

    private function log($sql, $values=array())
    {
        $op = strtolower(substr(trim($sql), 0, 6));
        if($op == 'insert')
        {
            Logger::writeSqlInsertLog($sql, $values);
        }
        elseif($op == 'update')
        {
            Logger::writeSqlUpdateLog($sql, $values);
        }
        else
        {
            Logger::writeSqlExecuteLog($sql, $values);
        }
    }

}

?>
