<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function customers(): JsonResponse
    {
        $customers = Customer::query()
            ->withCount('events')
            ->orderByDesc('id')
            ->paginate(20);

        return response()->json($customers);
    }

    public function events(): JsonResponse
    {
        $events = Event::query()
            ->with('customer:id,email,name')
            ->orderByDesc('occurred_at')
            ->orderByDesc('id')
            ->paginate(20);

        return response()->json($events);
    }
}
