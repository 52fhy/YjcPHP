<?php

namespace YJC;


/**
 * a simple controller with AOP support
 */

class Application
{

    private $before_filter = array();
    private $after_filter  = array();

    protected function before_filter($filter, $condition = array())
    {
        $this->set_filter("before_filter", $filter, $condition);
    }

    protected function after_filter($filter, $condition = array())
    {
        $this->set_filter("after_filter", $filter, $condition);
    }

    private function set_filter($var, $filter, $condition)
    {
        if (!is_callable(array($this, $filter)))
        {
             throw new \BizException(\ErrorInfo::$err_inner);
        }
        $this->{$var}[$filter] = $condition;
    }

    private function check_filter($method, $condition)
    {
        if($condition[0] == '*' && is_array(@$condition['exclude'])){
            if(in_array($method,$condition['exclude'])){
                return false;
            }else{
                return true;
            }
        }else if($condition[0]  == '*' && !isset($condition['exclude'])){
            return true;
        }

        return in_array($method, $condition);
    }

    public function run($method)
    {
		if( !in_array($method, get_class_methods(get_class($this))) )
        {
            throw new \BizException(\ErrorInfo::$err_bad_request);
        }
		/*
        if(!is_callable(array($this, $method)))
        {
            throw new \BizException(\ErrorInfo::$no_method);
        }
		*/

        // 校验前置过滤
        foreach($this->before_filter as $filter=> $condition )
        {
            if (!$this->check_filter($method, $condition))
            {
                continue;
            }
            $this->$filter();
        }

        return $this->$method();

        foreach($this->after_filter as $filter => $condition)
         {
            if (!$this->check_filter($method, $condition))
            {
                continue;
            }
            $this->$filter();
        }

    }

}
