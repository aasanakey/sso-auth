<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'provider_id',
        'provider_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function provider()
    {
        return $this->belongsTo(SocialProvider::class,'provider_id','id');
    }
}
