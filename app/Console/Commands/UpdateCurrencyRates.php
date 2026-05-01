<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Update currency exchange rates from Frankfurter API';

    public function handle()
    {
        $this->info('Updating currency rates...');

        $response = Http::get('https://api.frankfurter.app/latest?from=GBP');

        if ($response->successful()) {
            $rates = $response->json()['rates'];
            $rates['GBP'] = 1.0;

            $currencies = Currency::all();

            foreach ($currencies as $currency) {
                if (isset($rates[$currency->code])) {
                    $currency->update([
                        'exchange_rate' => $rates[$currency->code]
                    ]);
                    $this->info("Updated {$currency->code} to {$rates[$currency->code]}");
                    
                    // Clear cache
                    Cache::forget('currency_' . $currency->code);
                }
            }

            Cache::forget('currency_GBP');
            $this->info('All rates updated successfully.');
        } else {
            $this->error('Failed to fetch rates from API.');
        }
    }
}
