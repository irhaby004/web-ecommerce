<?php

namespace App\Services;

class Apriori
{
  private int $minSup;
  private int $minConf;
  private int $totalTransactions;
  private array $rules = [];
  private string $delimiter = ',';

  public function setMinSup(int $minSup): void
  {
    $this->minSup = $minSup;
  }

  public function setMinConf(int $minConf): void
  {
    $this->minConf = $minConf;
  }

  public function setDelimiter(string $delimiter): void
  {
    $this->delimiter = $delimiter;
  }

  public function process(string $filePath): void
  {
    if (!file_exists($filePath)) {
      throw new \Exception("File not found: $filePath");
    }

    $transactions = array_map(function ($line) {
      return array_map('trim', explode($this->delimiter, $line));
    }, file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

    $this->totalTransactions = count($transactions);

    // Hitung frekuensi tiap item
    $itemCount = [];
    foreach ($transactions as $transaction) {
      foreach ($transaction as $item) {
        if (!isset($itemCount[$item])) {
          $itemCount[$item] = 0;
        }
        $itemCount[$item]++;
      }
    }

    // Generate rules
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

              $support = ($countAB / $this->totalTransactions) * 100;
              $confidence = ($countAB / $itemCount[$A]) * 100;

              if ($support >= $this->minSup && $confidence >= $this->minConf) {
                $this->rules[] = [
                  'A' => $A,
                  'B' => $B,
                  'support' => round($support, 2),
                  'confidence' => round($confidence, 2),
                ];
              }
            }
          }
        }
      }
    }
  }

  public function getRules(): array
  {
    return $this->rules;
  }

  public function printRules(): string
  {
    $output = '';
    foreach ($this->rules as $rule) {
      $output .= "{$rule['A']} -> {$rule['B']} | Support: {$rule['support']}% | Confidence: {$rule['confidence']}%\n";
    }
    return $output;
  }
}
