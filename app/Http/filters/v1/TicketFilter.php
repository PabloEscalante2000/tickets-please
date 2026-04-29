<?php

namespace App\Http\filters\v1;

class TicketFilter extends QueryFilter{
    public function include($value){
        return $this->builder->with($value);
    }
    public function status($value) {
        return $this->builder->where("status", explode(",", $value)); 
    }
    public function title($value) {
        $likeStr = str_replace("*", "%", $value);
        return $this->builder->where("title", "like", $likeStr);
    }
}