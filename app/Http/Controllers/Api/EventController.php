<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => ['sometimes', 'required', 'uuid'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'event_name' => ['required', 'string', 'max:100'],
            'properties' => ['sometimes', 'nullable', 'array'],
            'occurred_at' => ['sometimes', 'nullable', 'date'],
        ]);

        $email = strtolower(trim($validated['email']));
        $eventId = $validated['event_id'] ?? null;

        // Si cet identifiant a déjà été reçu, ne pas créer un doublon.
        if ($eventId !== null) {
            $existingEvent = Event::where('event_id', $eventId)->first();

            if ($existingEvent) {
                return $this->duplicateResponse($existingEvent);
            }
        }

        try {
            $event = DB::transaction(function () use ($validated, $email, $eventId) {
                $customer = Customer::firstOrCreate(
                    ['email' => $email],
                    ['name' => $validated['name'] ?? null]
                );

                if (
                    array_key_exists('name', $validated)
                    && $validated['name'] !== null
                    && trim($validated['name']) !== ''
                    && $customer->name !== trim($validated['name'])
                ) {
                    $customer->name = trim($validated['name']);
                    $customer->save();
                }

                return Event::create([
                    'event_id' => $eventId,
                    'customer_id' => $customer->id,
                    'event_name' => $validated['event_name'],
                    'properties' => $validated['properties'] ?? null,
                    'occurred_at' => $validated['occurred_at'] ?? now(),
                ]);
            });
        } catch (QueryException $exception) {
            // Gérer le cas où deux requêtes arrivent simultanément
            // avec le même event_id et que la contrainte unique intervient.
            if ($eventId !== null) {
                $existingEvent = Event::where('event_id', $eventId)->first();

                if ($existingEvent) {
                    return $this->duplicateResponse($existingEvent);
                }
            }

            throw $exception;
        }

        return response()->json([
            'message' => 'Événement enregistré avec succès.',
            'duplicate' => false,
            'data' => $this->eventData($event),
        ], 201);
    }

    private function duplicateResponse(Event $event): JsonResponse
    {
        return response()->json([
            'message' => 'Cet événement a déjà été reçu.',
            'duplicate' => true,
            'data' => $this->eventData($event),
        ], 200);
    }

    private function eventData(Event $event): array
    {
        return [
            'event_id' => $event->event_id,
            'id' => $event->id,
            'customer_id' => $event->customer_id,
            'event_name' => $event->event_name,
            'properties' => $event->properties,
            'occurred_at' => $event->occurred_at,
        ];
    }
}