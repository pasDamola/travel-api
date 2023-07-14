<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tour;
use App\Models\Travel;
use App\Http\Resources\TourResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TourController extends Controller
{
    //
    public function index(Travel $travel) 
    {
       // $travel->tours === Tour::where('travel_id', $travel->id);
       $tours =  $travel->tours()
                     ->orderBy('starting_date')
                     ->paginate();

        return TourResource::collection($tours);
    }
}
