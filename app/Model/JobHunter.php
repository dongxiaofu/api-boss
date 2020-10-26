<?php

namespace App\Model;


class JobHunter extends BaseModel
{
    protected $hidden = ['company_id', 'created_at', 'updated_at'];

    protected $table = 'job_hunter';
}
