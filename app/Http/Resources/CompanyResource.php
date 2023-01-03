<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,   
            'name' => $this->name,  
            'email' => $this->email,  
            'country' => $this->country,  
            'phone_number' => $this->phone_number,  
            'description' => $this->description,  
            'owner' => $this->owner,
            'service_offered' => $this->service,
            'created_at' => $this->created_at ? date('F d, Y',strtotime($this->created_at)): null,
            'updated_at' => $this->updated_at ? date('F d, Y',strtotime($this->updated_at)): null
        ];
    }
}
