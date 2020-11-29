<?php

namespace App\Model;


class Message extends BaseModel
{
    protected $hidden = ['company_id', 'updated_at'];

//    public function getNameAttribute(){
//        return $this->id;
//    }
}
