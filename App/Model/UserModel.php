<?php
/**
 * Created by PhpStorm.
 * User: YJC
 * Date: 2016/6/11 011
 * Time: 17:27
 */

namespace App\Model;

use YJC\Model;

class UserModel extends Model
{
   protected $table = 'user';

    public function getUser($id){
        return $this->get('*', array(
            'id' => $id
        ));
    }
}