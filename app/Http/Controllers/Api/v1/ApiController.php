<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponses;
    use AuthorizesRequests;

    protected $policyClass;

    public function include(string $relationship) : bool
    {
        $param = request()->input("include");
        if(!isset($param)){
            return false;
        }

        $includeValues = explode(",", strtolower($param));

        return in_array($relationship, $includeValues);
    }

    public function isAble($ability, $targetModel) {
        return $this->authorize($ability, [$targetModel, $this->policyClass]);
    }
}
