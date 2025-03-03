<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_transaction()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $transaction->id,
                     'amount' => $transaction->amount,
                     'status' => $transaction->status,
                 ]);
    }

    public function test_admin_can_view_any_transaction()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_other_users_transactions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(403); // Forbidden
    }
}
