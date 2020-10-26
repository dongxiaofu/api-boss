<?php

namespace App\Model;


class Job extends BaseModel
{
    protected $table = 'job';
    //
    protected $primaryKey = 'job_id';
    protected $hidden = ['created_at'];

    protected $appends = ['code'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

//    protected $fillable = ['job_id'];

    public function getCodeAttribute()
    {
        return $this->job_id;
    }

//    public function getCompanyAttribute($key)
//    {
////        return $this->company;
//    }

//    public function getUpdatedAtAttribute()
//    {
//        return date('Y-m-d H:i:s', strtotime($this->updated_at));
//    }

    public function company()
    {
//        return $this->belongsTo(Company::class);
        return $this->hasOne(Company::class, 'company_id');
    }
}
