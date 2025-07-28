<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
        
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_groups','group_id','user_id');
    }
}
