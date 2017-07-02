<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2017/7/2 002
 * Time: 10:55
 */

namespace YJC;


class SingleLinkList
{

    private $num;
    private $name;

    private $next = null;

    public function __construct($num = '', $name = '')
    {
        $this->num = $num;
        $this->name = $name;
    }

    public function showList($head){
        $cur = $head;
        while ($cur->next != null){
            echo 'num:'. $cur->next->num. '---name:'.$cur->next->name. PHP_EOL;
            $cur = $cur->next;
        }
    }

    public function add($head, $obj){
        $cur = $head;

        while ($cur->next != null){
            if($cur->next->num > $obj->num){
                break;
            }
            $cur = $cur->next;
        }

        $obj->next = $cur->next;//让新的追加进去
        $cur->next = $obj;
    }

    public function del($head, $num){
        $cur = $head;
        while ($cur->next != null){
            if($cur->next->num == $num){
                break;
            }
            $cur = $cur->next;
        }

        if($cur->next != null){
            $cur->next = $cur->next->next;
        }else{
            echo $num. '不存在！无法删除'. PHP_EOL;
        }
    }

    public function update($head, $obj){
        $cur = $head;
        while ($cur->next != null){
            if($cur->next->num == $obj->num){
                break;
            }
            $cur = $cur->next;
        }

        if($cur->next == null){
            echo $obj->num. '不存在！无法更新'. PHP_EOL;
        }else{
            $cur->next->name = $obj->name;
        }
    }
}