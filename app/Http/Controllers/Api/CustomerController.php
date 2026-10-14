<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $customer = Customer::query()
            ->with([
                'events' => function ($query) {
                    $query->latest('occurred_at')
                        ->latest('id')
                        ->limit(10);
                },
            ])
            ->withCount([
                'events as purchase_count' => function ($query) {
                    $query->where('event_name', 'purchase');
                },
            ])         
            ->find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Client introuvable.',
            ], 404);
        }

        $totalSpent = $customer->events()
		    ->where('event_name', 'purchase')
		    ->selectRaw(
		        "COALESCE(SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(properties, '$.amount')) AS DECIMAL(15,2))), 0) AS total"
		    )
		    ->value('total');

        return response()->json([
            'data' => [
                'id' => $customer->id,
                'email' => $customer->email,
                'name' => $customer->name,
                'created_at' => $customer->created_at,
                'statistics' => [
                    'purchase_count' => $customer->purchase_count,
                    'total_spent' => (float) $totalSpent,
                ],
                'latest_events' => $customer->events,
            ],
        ]);
    }
}
