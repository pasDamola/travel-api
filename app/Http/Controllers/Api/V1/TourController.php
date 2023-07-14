<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Travel;
use App\Models\Tour;
use App\Http\Resources\TourResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TourController extends Controller
{
    //
    public function index(Travel $travel, Request $request) 
    {
       // data validation for query params
       // You can also extract the validation using the request command - php artisan make:request ToursListequest

        $request->validate([
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'dateFrom' => 'date',
            'dateTo' => 'date',
            'sortBy' => Rule::in(['price']),
            'sortOrder' => Rule::in(['asc', 'desc'])
        ], [
            'sortBy' => "The 'sortBy' paramter accepts only 'price' value",
            'sortOrder' => "The 'sortOrder' paramter accepts only 'asc' or 'desc' value"
        ]);

       // $travel->tours === Tour::where('travel_id', $travel->id);
       $tours =  Tour::where('travel_id', $travel->id)
                     ->when($request->priceFrom, function ($query) use ($request) {
                         $query->where('price', '>=', $request->priceFrom * 100);
                     })
                     ->when($request->priceTo, function ($query) use ($request) {
                        $query->where('price', '<=', $request->priceFrom * 100);
                    })
                    ->when($request->dateFrom, function ($query) use ($request) {
                        $query->where('starting_date', '>=', $request->dateFrom);
                    })
                    ->when($request->dateTo, function ($query) use ($request) {
                        $query->where('starting_date', '<=', $request->dateTo);
                    })
                    ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                        $query->where($request->sortBy, $request->sortOrder);
                    })
                     ->orderBy('starting_date')
                     ->paginate();

        return TourResource::collection($tours);
    }
}
