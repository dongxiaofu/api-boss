<?php

namespace App\Model;


class Session extends BaseModel
{
    protected $hidden = ['company_id', 'created_at', 'updated_at'];

//    public function getNameAttribute(){
//        return $this->id;
//    }
}
