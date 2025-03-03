<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Transfer;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_initiate_transfer()
    {
        $sender = User::factory()->create(['balance' => 1000]);
        $receiver = User::factory()->create();

        $response = $this->actingAs($sender)->postJson('/api/transfers', [
            'receiver_id' => $receiver->id,
            'amount' => 500,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['transfer_id']);
    }

    public function test_transfer_fails_due_to_insufficient_balance()
    {
        $sender = User::factory()->create(['balance' => 100]);
        $receiver = User::factory()->create();

        $response = $this->actingAs($sender)->postJson('/api/transfers', [
            'receiver_id' => $receiver->id,
            'amount' => 500,
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Insufficient balance']);
    }

    public function test_transfer_is_added_to_queue()
    {
        $this->expectsJobs(\App\Jobs\ProcessTransfer::class);

        $sender = User::factory()->create(['balance' => 1000]);
        $receiver = User::factory()->create();

        $this->actingAs($sender)->postJson('/api/transfers', [
            'receiver_id' => $receiver->id,
            'amount' => 500,
        ]);
    }
}
