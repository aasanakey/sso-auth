<?php

namespace App\Models\Passport;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'grant_types' => 'array',
        'scopes' => 'array',
        'redirect' => 'array',
        'personal_access_client' => 'bool',
        'password_client' => 'bool',
        'revoked' => 'bool',
    ];

    /** 
     * Indicates if the IDs are auto-incrementing. 
     * 
     * @var bool 
     */
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
        /*
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });

        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });*/
    }

        /**
     * Interact with the client's redirect URIs.
     */
    protected function redirectUris(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes): array => match (true) {
                ! empty($value) => $this->fromJson($value),
                ! empty($attributes['redirect']) => explode(',', $attributes['redirect']),
                default => [],
            },
        );
    }
}
