<?php

namespace App\Actions;

use App\Enums\BalanceAs;
use App\Enums\EventType;
use App\Models\User;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;

class ConvertBalanceAction
{
    /**
     * @throws \Throwable
     */
    public function execute(User $user, string $amount): void
    {
        app(DatabaseServiceInterface::class)->transaction(function () use ($user, $amount) {
            $this->transaction($user, $amount);
            $this->activityLog($user, $amount);
        });
    }

    /**
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    private function transaction(User $user, string $amount): void
    {
        $workerWallet = $user->getWallet(BalanceAs::Worker->value);
        $creatorWallet = $user->getWallet(BalanceAs::Creator->value);

        $workerWallet->transferFloat($creatorWallet, $amount, ['description' => 'Convert Balance']);
    }

    private function activityLog(User $user, string $amount): void
    {
        activity()
            ->by($user)
            ->on($user)
            ->event(EventType::Transaction->value)
            ->log("Converted \${$amount} from worker to creator balance.");
    }
}
