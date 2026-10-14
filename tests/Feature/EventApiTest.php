<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_customer_and_records_an_event(): void
    {
        $payload = [
            'email' => 'client.test@example.com',
            'name' => 'Client Test',
            'event_name' => 'purchase',
            'properties' => [
                'amount' => 150000,
                'currency' => 'MGA',
                'product' => 'Abonnement Premium',
            ],
        ];

        $response = $this->postJson('/api/events', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.event_name', 'purchase')
            ->assertJsonPath('data.customer_id', 1)
            ->assertJsonPath('data.properties.amount', 150000);

        $this->assertDatabaseHas('customers', [
            'email' => 'client.test@example.com',
            'name' => 'Client Test',
        ]);

        $this->assertDatabaseHas('events', [
            'customer_id' => 1,
            'event_name' => 'purchase',
        ]);

        $this->assertSame(1, Customer::count());
        $this->assertSame(1, Event::count());
    }

    public function test_it_returns_customer_profile_and_purchase_statistics(): void
    {
        $customer = Customer::create([
            'email' => 'stats@example.com',
            'name' => 'Client Statistiques',
        ]);

        Event::create([
            'customer_id' => $customer->id,
            'event_name' => 'purchase',
            'properties' => [
                'amount' => 150000,
                'currency' => 'MGA',
            ],
            'occurred_at' => now()->subHour(),
        ]);

        Event::create([
            'customer_id' => $customer->id,
            'event_name' => 'purchase',
            'properties' => [
                'amount' => 250000,
                'currency' => 'MGA',
            ],
            'occurred_at' => now(),
        ]);

        $response = $this->getJson(
            '/api/customers/' . $customer->id
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $customer->id)
            ->assertJsonPath(
                'data.statistics.purchase_count',
                2
            )
            ->assertJsonPath(
                'data.statistics.total_spent',
                400000
            )
            ->assertJsonCount(2, 'data.latest_events');
    }

    public function test_it_finds_customers_matching_a_segment(): void
    {
        $customer = Customer::create([
            'email' => 'segment@example.com',
            'name' => 'Client Segment',
        ]);

        Event::create([
            'customer_id' => $customer->id,
            'event_name' => 'purchase',
            'properties' => [
                'amount' => 150000,
                'currency' => 'MGA',
            ],
            'occurred_at' => now(),
        ]);

        $response = $this->postJson('/api/segments/query', [
    'conditions' => [
        [
            'event' => 'purchase',
            'property' => 'amount',
            'operator' => '>',
            'value' => 100000,
        ],
    ],
    'limit' => 50,
]);

        $response
            ->assertOk()
            ->assertJsonPath('meta.count', 1)
            ->assertJsonPath(
                'data.0.email',
                'segment@example.com'
            );
    }


    public function test_it_does_not_create_duplicate_events_with_the_same_event_id(): void
    {
        $eventId = (string) \Illuminate\Support\Str::uuid();

        $payload = [
            'event_id' => $eventId,
            'email' => 'dedup@example.com',
            'name' => 'Client Deduplication',
            'event_name' => 'purchase',
            'properties' => [
                'amount' => 150000,
                'currency' => 'MGA',
            ],
        ];

        $firstResponse = $this->postJson('/api/events', $payload);

        $firstResponse
            ->assertCreated()
            ->assertJsonPath('duplicate', false)
            ->assertJsonPath('data.event_id', $eventId);

        $secondResponse = $this->postJson('/api/events', $payload);

        $secondResponse
            ->assertOk()
            ->assertJsonPath('duplicate', true)
            ->assertJsonPath('data.event_id', $eventId);

        $this->assertSame(
            1,
            Event::where('event_id', $eventId)->count()
        );

        $this->assertSame(1, Customer::where(
            'email',
            'dedup@example.com'
        )->count());
    }


}
