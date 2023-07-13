<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Travel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    //
    public function index()
    {
        return Travel::where('is_public', true)->get();
    }
}