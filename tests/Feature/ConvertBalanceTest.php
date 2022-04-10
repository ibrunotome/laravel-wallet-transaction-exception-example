<?php

use App\Enums\BalanceAs;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('can convert balance successfully', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $user
        ->getWallet(BalanceAs::Worker->value)
        ->depositFloat('10');

    actingAs($user)
        ->postJson(route('balance.convert'), [
            'amount' => 5,
        ])
        ->assertJson(['message' => trans('user.convert_balance.success')]);

    expect($user->getWallet(BalanceAs::Worker->value)->balanceFloat)->toBe(number_format(5.0, 8));
    expect($user->getWallet(BalanceAs::Creator->value)->balanceFloat)->toBe(number_format(5.0, 8));
});

it('cannot convert balance because insuficient worker balance', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $user
        ->getWallet(BalanceAs::Worker->value)
        ->depositFloat('10');

    actingAs($user)
        ->postJson(route('balance.convert'), [
            'amount' => 11,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['amount' => 'The amount must not be greater than 10.00']);

    expect($user->getWallet(BalanceAs::Worker->value)->balanceFloat)->toBe(number_format(10.0, 8));
    expect($user->getWallet(BalanceAs::Creator->value)->balanceFloat)->toBe(number_format(.0, 8));
});
