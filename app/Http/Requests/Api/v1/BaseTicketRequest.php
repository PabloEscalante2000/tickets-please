<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{

    public function mappedAttributes() {
        $attributeMap = [
            "data.attributes.title" => "title",
            "data.attributes.description" => "description",
            "data.attributes.status" => "status",
            "data.relationships.author.data.id" => "user_id",
            "data.attributes.createdAt" => "created_at",
            "data.attributes.updatedAt" => "updated_at"
        ];

        $attributesToUpdate = [];

        foreach($attributeMap as $key => $attribute){
            if($this->has($key)){
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    public function messages(){
        return [
            "data.attributes.status" => "The data.attributes.status is invalid. Please use A, C, H or X"
        ];
    }
}
