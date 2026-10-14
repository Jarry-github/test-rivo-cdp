<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SegmentController extends Controller
{
    public function query(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conditions' => ['required', 'array', 'min:1', 'max:10'],
            'conditions.*' => ['required', 'array'],
            'conditions.*.event' => [
                'required',
                'string',
                'max:100',
            ],
            'conditions.*.property' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_]+$/',
                'max:100',
            ],
            'conditions.*.operator' => [
                'required',
                Rule::in(['=', '!=', '>', '>=', '<', '<=']),
            ],
            'conditions.*.value' => ['required'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $conditions = $validated['conditions'];
        $limit = $validated['limit'] ?? 50;

        $query = DB::table('customers');

        foreach ($conditions as $condition) {
            $eventName = $condition['event'];
            $property = $condition['property'];
            $operator = $condition['operator'];
            $value = $condition['value'];

            $jsonPath = '$."' . $property . '"';

            $query->whereExists(function ($subquery) use (
                $eventName,
                $operator,
                $value,
                $jsonPath
            ) {
                $subquery
                    ->selectRaw('1')
                    ->from('events')
                    ->whereColumn(
                        'events.customer_id',
                        'customers.id'
                    )
                    ->where('events.event_name', $eventName);

                if (is_numeric($value)) {
                    $subquery->whereRaw(
                        "CAST(JSON_UNQUOTE(JSON_EXTRACT(events.properties, ?)) AS DECIMAL(20,6)) {$operator} ?",
                        [$jsonPath, $value]
                    );
                } else {
                    $subquery->whereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(events.properties, ?)) {$operator} ?",
                        [$jsonPath, $value]
                    );
                }
            });
        }

        $customers = $query
            ->select('customers.id', 'customers.email', 'customers.name')
            ->orderBy('customers.id')
            ->limit($limit)
            ->get();

        return response()->json([
            'data' => $customers,
            'meta' => [
                'count' => $customers->count(),
                'limit' => $limit,
                'conditions' => $conditions,
            ],
        ]);
    }
}