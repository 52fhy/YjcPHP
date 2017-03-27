<?php

namespace YJC;

use \Memcached;
use \Memcache;
use \BizException;

class MemCacheDriver
{
    private $_memcache;
    private $_prefix;
    private static $ins = null;
    private $client_type;

    public static function getInstance()
    {/*{{{*/
        if (!self::$ins)
        {
            $conf = ConfigLoader::getServerConfig('MEMCACHE_CONF');
            $cls = __CLASS__;
            self::$ins = new $cls($conf);
        }
        return self::$ins;
    }/*}}}*/

    private function __construct($conf)
    {/*{{{*/
        
        $this->client_type = extension_loaded('memcached') ? "Memcached" : (extension_loaded('memcache') ? "Memcache" : FALSE);
        
        if($this->client_type == 'Memcached'){
            $this->_memcache = new Memcached();
            $this->_memcache->setOption(Memcached::OPT_COMPRESSION, false);
            $this->_memcache->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
            $this->_memcache->addServer($conf['host'], $conf['port']);
            $this->_memcache->setSaslAuthData($conf['user'], $conf['pass']);
        }else if($this->client_type == 'Memcache'){
            $this->_memcache = new Memcache();
            if(!$this->_memcache->addServer('127.0.0.1' , 11211)){
                throw new BizException('ERROR: Could not connect to the server named 127.0.0.1 by memcache client.');
            }
        }else{
            throw new BizException('ERROR: memcached or memcache client not found.');
        }
        
        $this->_prefix = RUN_ONLINE ? 'ol' : 'test';
    }/*}}}*/

    /*
     * string MemCacheDriver::get ( string key )
     * array MemCacheDriver::get ( array keys )
     */
    public function get($key)
    {/*{{{*/
        $key = $this->genKey($key);
        return $this->_memcache->get($key);
    }/*}}}*/

    public function add($key, $value, $expire=3600)
    {/*{{{*/
        $key = $this->genKey($key);
        if($this->client_type == 'Memcache') return $this->_memcache->add($key, $value, MEMCACHE_COMPRESSED,  $expire);
        return $this->_memcache->add($key, $value, $expire);
    }/*}}}*/

    public function set($key, $value, $expire=3600)
    {/*{{{*/
        $key = $this->genKey($key);
        if($this->client_type == 'Memcache') return $this->_memcache->set($key, $value, MEMCACHE_COMPRESSED,  $expire);
        return $this->_memcache->set($key, $value, $expire);
    }/*}}}*/

    public function replace($key, $value, $expire=3600)
    {/*{{{*/
        $key = $this->genKey($key);
        if($this->client_type == 'Memcache') return $this->_memcache->replace($key, $value, MEMCACHE_COMPRESSED,  $expire);
        return $this->_memcache->replace($key, $value, $expire);
    }/*}}}*/

    public function delete($key)
    {/*{{{*/
        $key = $this->genKey($key);
        return $this->_memcache->delete($key);
    }/*}}}*/

    public function flush()
    {/*{{{*/
        return $this->_memcache->flush();
    }/*}}}*/

    public function close()
    {/*{{{*/
        return $this->_memcache->close();
    }/*}}}*/

    public function increment($key)
    {/*{{{*/
        $key = $this->genKey($key);
        return $this->_memcache->increment($key);
    }/*}}}*/

    public function decrement($key)
    {/*{{{*/
        $key = $this->genKey($key);
        return $this->_memcache->decrement($key);
    }/*}}}*/

    private function genKey($key)
    {/*{{{*/
        return "{$this->_prefix}:$key";
    }/*}}}*/
}