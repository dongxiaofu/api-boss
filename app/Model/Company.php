<?php

namespace App\Model;


class Company extends BaseModel
{
    //
    protected $primaryKey = 'company_id';
//    protected $table = 'company';
    protected $hidden = ['company_id', 'created_at', 'updated_at'];
}
