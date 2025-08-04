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

    /**
     * Add a user to the group.
     *
     * @param User $user
     * @return void
     */
    public function addUser(User $user)
    {
        $this->users()->attach($user);
    }

    /**
     * Remove a user from the group.
     *
     * @param User $user
     * @return void
     */
    public function removeUser(User $user)
    {
        $this->users()->detach($user);
    }
}
