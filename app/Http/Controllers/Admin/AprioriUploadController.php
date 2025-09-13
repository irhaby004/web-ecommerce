<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AprioriUploadController extends Controller
{
    public function index()
    {
        return view('admin.apriori_upload', [
            'page_title_lang' => 'Apriori Analysis'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dataset' => 'required|file|mimes:csv,txt,xlsx',
            'minsup' => 'required|numeric|min:1|max:100',
            'minconf' => 'required|numeric|min:1|max:100',
        ]);

        $file = $request->file('dataset');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('dataset', $filename);
        $filePath = storage_path('app/dataset/' . $filename);

        $transactions = $this->loadTransactions($filePath);
        $totalTransactions = count($transactions);

        $minSupCount = ceil(($request->minsup / 100) * $totalTransactions);
        $minConf = $request->minconf;

        try {
            [$frequentItemsets, $rules] = $this->apriori($transactions, $minSupCount, $minConf);

            $groupedItemsets = [];
            foreach ($frequentItemsets as $itemset => $data) {
                $size = substr_count($itemset, ',') + 1;
                $groupedItemsets[$size][] = [
                    'items' => $itemset,
                    'support' => $data['support'],
                    'count' => $data['count']
                ];
            }

            return redirect()->route('admin.apriori.index')
                ->with('page_title_lang', 'Apriori Analysis')
                ->with('output', 'Proses Apriori selesai! (disimpan di session, bukan DB)')
                ->with('saved', true)
                ->with('minsup', $request->minsup)
                ->with('minconf', $request->minconf)
                ->with('totalTransactions', $totalTransactions)
                ->with('groupedItemsets', $groupedItemsets)
                ->with('frequentItemsets', $frequentItemsets)
                ->with('rules', $rules);
        } catch (\Exception $e) {
            return redirect()->route('admin.apriori.index')
                ->with('output', "Terjadi kesalahan: " . $e->getMessage())
                ->with('saved', false);
        }
    }

    private function loadTransactions($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $transactions = [];

        if ($extension === 'xlsx') {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($sheet->toArray() as $row) {
                $cleaned = array_filter(array_map('trim', $row));
                if (!empty($cleaned)) {
                    $transactions[] = $cleaned;
                }
            }
        } else {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $items = array_map('trim', explode(',', $line));
                $items = array_values(array_filter($items, fn($v) => $v !== ''));
                if (!empty($items)) {
                    $transactions[] = $items;
                }
            }
        }

        return $transactions;
    }

    private function apriori($transactions, $minSupCount, $minConf)
    {
        $frequentItemsets = [];
        $rules = [];
        $totalTransactions = count($transactions);

        $itemCount = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction as $item) {
                $itemCount[$item] = ($itemCount[$item] ?? 0) + 1;
            }
        }

        $currentItemsets = [];
        foreach ($itemCount as $item => $count) {
            if ($count >= $minSupCount) {
                $frequentItemsets[$item] = [
                    'support' => round(($count / $totalTransactions) * 100, 2),
                    'count' => $count
                ];
                $currentItemsets[] = [$item];
            }
        }

        $k = 2;
        while (!empty($currentItemsets)) {
            $candidates = $this->generateCandidates($currentItemsets, $k);
            if (empty($candidates)) break;

            $candidateCount = [];
            foreach ($transactions as $transaction) {
                foreach ($candidates as $cand) {
                    if (count(array_intersect($cand, $transaction)) === $k) {
                        $key = implode(',', $cand);
                        $candidateCount[$key] = ($candidateCount[$key] ?? 0) + 1;
                    }
                }
            }

            $newItemsets = [];
            foreach ($candidateCount as $key => $count) {
                if ($count >= $minSupCount) {
                    $frequentItemsets[$key] = [
                        'support' => round(($count / $totalTransactions) * 100, 2),
                        'count' => $count
                    ];
                    $newItemsets[] = explode(',', $key);
                }
            }

            $currentItemsets = $newItemsets;
            $k++;
        }

        foreach ($frequentItemsets as $itemsetKey => $data) {
            $items = explode(',', $itemsetKey);
            if (count($items) === 2) {
                $supportAB = $data['support'];
                foreach ($items as $i) {
                    $antecedent = [$i];
                    $consequent = array_values(array_diff($items, [$i]));
                    $supportA = $frequentItemsets[$i]['support'] ?? null;
                    if ($supportA) {
                        $confidence = round(($supportAB / $supportA) * 100, 2);
                        if ($confidence >= $minConf) {
                            $rules[] = [
                                'antecedent' => $antecedent,
                                'consequent' => $consequent,
                                'support' => $supportAB,
                                'confidence' => $confidence
                            ];
                        }
                    }
                }
            }
        }

        return [$frequentItemsets, $rules];
    }

    private function generateCandidates($itemsets, $k)
    {
        $candidates = [];
        $n = count($itemsets);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $merged = array_unique(array_merge($itemsets[$i], $itemsets[$j]));
                sort($merged);
                if (count($merged) === $k) {
                    $candidates[] = $merged;
                }
            }
        }
        $uniq = [];
        foreach ($candidates as $c) {
            $key = implode(',', $c);
            $uniq[$key] = $c;
        }
        return array_values($uniq);
    }
}
