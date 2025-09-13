<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateAprioriRules extends Command
{
    protected $signature = 'apriori:generate {--minSupport=0.1} {--minConfidence=0.5}';
    protected $description = 'Generate association rules from transactions using Apriori algorithm';

    public function handle()
    {
        $minSupport = (float) $this->option('minSupport');
        $minConfidence = (float) $this->option('minConfidence');

        // Ambil semua transaksi (array of arrays)
        $transactions = [];
        $transactionData = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('transaction_items.transaction_code', 'products.name')
            ->get()
            ->groupBy('transaction_code');

        foreach ($transactionData as $items) {
            $transactions[] = $items->pluck('name')->toArray();
        }

        $totalTransactions = count($transactions);
        if ($totalTransactions === 0) {
            $this->error('Tidak ada data transaksi.');
            return;
        }

        // Hitung frekuensi item tunggal
        $itemCount = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction as $item) {
                if (!isset($itemCount[$item])) {
                    $itemCount[$item] = 0;
                }
                $itemCount[$item]++;
            }
        }

        $rules = [];
        foreach ($transactions as $transaction) {
            if (count($transaction) >= 2) {
                for ($i = 0; $i < count($transaction); $i++) {
                    for ($j = 0; $j < count($transaction); $j++) {
                        if ($i != $j) {
                            $A = $transaction[$i];
                            $B = $transaction[$j];

                            $countAB = 0;
                            foreach ($transactions as $t) {
                                if (in_array($A, $t) && in_array($B, $t)) {
                                    $countAB++;
                                }
                            }

                            $support = $countAB / $totalTransactions;
                            $confidence = $countAB / $itemCount[$A];

                            if ($support >= $minSupport && $confidence >= $minConfidence) {
                                $rules[] = [
                                    'product_a' => $A,
                                    'product_b' => $B,
                                    'support' => round($support, 4),
                                    'confidence' => round($confidence, 4),
                                ];
                                // tambahkan aturan kebalikan (B -> A)
                                $rules[] = [
                                    'product_a' => $B,
                                    'product_b' => $A,
                                    'support' => round($support, 4),
                                    'confidence' => round($confidence, 4),
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Simpan hasil ke DB
        DB::table('association_rules')->truncate();
        DB::table('association_rules')->insert($rules);

        $this->info("Berhasil membuat " . count($rules) . " association rules.");
    }
}
