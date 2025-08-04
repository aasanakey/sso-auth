<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function social_accounts()
    {
        return $this->hasMany(SocialAccount::class,'user_id','id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class,'user_groups','user_id','group_id');
    }

    /**
     * Add a group to the user.
     *
     * @param string $name
     * @return void
     */
    public function addGroup(string $name)
    {
        $group = Group::firstOrCreate(['name' => $name]);
        $this->groups()->attach($group);
    }

    /**
     * Remove a group from the user.
     *
     * @param string $name
     * @return void
     */
    public function removeGroup(string $name)
    {
        $group = Group::where('name', $name)->first();
        if($group){
            $this->groups()->detach($group);
        }
    }
}
