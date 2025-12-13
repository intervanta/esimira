<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\WalletTransaction;

class WalletService
{
    public function credit(Customer $customer, $amount, $source, $description = null)
    {
        $customer->wallet_balance += $amount;
        $customer->save();

        WalletTransaction::create([
            'customer_id' => $customer->id,
            'type' => 'credit',
            'source' => $source,
            'amount' => $amount,
            'balance_after' => $customer->wallet_balance,
            'description' => $description,
        ]);
    }

    public function debit(Customer $customer, $amount, $source, $description = null)
    {
        if ($customer->wallet_balance < $amount) {
            return false;
        }

        $customer->wallet_balance -= $amount;
        $customer->save();

        WalletTransaction::create([
            'customer_id' => $customer->id,
            'type' => 'debit',
            'source' => $source,
            'amount' => $amount,
            'balance_after' => $customer->wallet_balance,
            'description' => $description,
        ]);

        return true;
    }
}
