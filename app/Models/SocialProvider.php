<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'driver',
        'client_id',
        'client_secret',
        'redirect_uri',
    ];

    public function social_accounts()
    {
        return $this->hasMany(SocialAccount::class,'provider_id','id');
    }
}
