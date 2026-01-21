<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\WalletTransaction;

class WalletService
{
    public function credit(Customer $customer, $amount, $source, $description = null ,$order_id = null)
    {
         $customer->increment('wallet_balance', $amount);
    
         $customer->refresh();
       
        WalletTransaction::create([
            'order_id' => $order_id,
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
