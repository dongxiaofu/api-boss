<?php

namespace App\Model;


class User extends BaseModel
{
    protected $hidden = ['company_id', 'created_at', 'updated_at', 'password'];

//    public function getNameAttribute(){
//        return $this->id;
//    }
}
