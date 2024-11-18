<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Services\TourService;
use Illuminate\Http\Request;

class TourController extends Controller
{
    protected $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tours = $this->tourService->getTours($request, $perPage);
        return response()->json($tours, 200);
    }

    public function store(StoreTourRequest $request)
    {
        $tour = $this->tourService->createTour($request->validated());
        return response()->json(new TourResource($tour), 201);
    }

    public function show(Tour $tour)
    {
        $tourData = $this->tourService->getTour($tour);
        return new TourResource($tourData);
    }

    public function update(UpdateTourRequest $request, Tour $tour)
    {
        $updatedTour = $this->tourService->updateTour($tour, $request->validated());
        return response()->json($updatedTour, 200);
    }

    public function destroy(Tour $tour)
    {
        $this->tourService->deleteTour($tour);
        return response()->json(null, 204);
    }
}
