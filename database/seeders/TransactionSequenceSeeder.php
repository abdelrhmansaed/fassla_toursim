<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = Transaction::orderBy('created_at')->get();
        $sequence = 1;

        foreach ($transactions as $transaction) {
            $transaction->sequence = $sequence++;
            $transaction->save();
        }
    }
}
