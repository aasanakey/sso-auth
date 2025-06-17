<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "owner_id" => $this->owner_id,
            "owner_type" => $this->owner_type,
            "provider" => $this->provider,
            "redirect_uris" => $this->redirect_uris,
            "grant_types" => $this->grant_types,
            "secret" => $this->whenHas("secret",$this->secret),
            //"plainSecret" => $this->plainSecret,
            "confidential" => $this->confidential(),
            "revoked" => $this->revoked,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "user" => $this->whenHas("user",$this->user),
            "owner" => $this->whenHas('owner',$this->owner),
            "auth_codes" => $this->whenHas('authCodes',$this->authCodes),
            "tokens" => $this->whenHas('tokens',$this->tokens),

        ];
    }
}
