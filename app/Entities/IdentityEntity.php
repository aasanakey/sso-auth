<?php

namespace App\Entities;

use App\Models\User; // Your User model
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use OpenIDConnect\Claims\Traits\WithClaims;
use OpenIDConnect\Interfaces\IdentityEntityInterface;

class IdentityEntity implements IdentityEntityInterface
{
    use EntityTrait, WithClaims;

    /**
     * The user associated with this identity.
     */
    protected User $user;

    /**
     * The identity repository creates this entity and provides the user identifier.
     *
     * @param mixed $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
        $this->user = User::findOrFail($identifier);
    }

    /**
     * When building the id_token, this entity's claims are collected.
     * Add standard and custom claims here.
     */
    public function getClaims(): array
    {
        return [
            'sub' => $this->user->id, // Subject Identifier
            'email' => $this->user->email,
            'name' => $this->user->name,
            // Add more standard claims (profile, picture, etc.) as needed
            // Based on requested scopes (e.g., 'profile' scope can map to 'name', 'family_name', 'picture' etc.)
            'email_verified' => $this->user->email_verified_at ? true : false,
        ];
    }
}