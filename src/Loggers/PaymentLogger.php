<?php

namespace Tfo\AdvancedLog\Loggers;

use Illuminate\Support\Facades\Log;
use Monolog\Level;

/**
 * Logs payment transactions and processing details
 * 
 * @example
 * // Log successful payment
 * (new PaymentLogger('success', 99.99, 'stripe'))->log([
 *     'transaction_id' => 'tx_123',
 *     'customer_id' => 'cus_456'
 * ]);
 * 
 * @example
 * // Log failed payment
 * (new PaymentLogger('failed', 199.99, 'paypal'))->log([
 *     'error' => 'Insufficient funds',
 *     'payment_method' => 'credit_card'
 * ]);
 */
class PaymentLogger extends BaseLogger
{
    public function __construct(
        private string $status,
        private float $amount,
        private string $provider,
        private string $currency = 'BRL'
    ) {
    }

    public function log(array $context = []): void
    {
        $paymentContext = [
            'status' => $this->status,
            'amount' => $this->amount,
            'provider' => $this->provider,
            'currency' => $this->currency,
            'formatted_amount' => $this->formatAmount(),
            'transaction_date' => now()->format('Y-m-d H:i:s.u')
        ];

        Log::log(
            $this->getLogLevel()->name,
            "Payment {$this->status}: {$this->formatAmount()}",
            $this->mergeContext(array_merge($paymentContext, $context))
        );
    }

    private function formatAmount(): string
    {
        return number_format($this->amount, 2) . " {$this->currency}";
    }

    private function getLogLevel(): Level
    {
        return match ($this->status) {
            'failed', 'error', 'declined' => self::ERROR,
            'pending', 'processing' => self::INFO,
            'success', 'completed' => self::INFO,
            'refunded', 'cancelled' => self::WARNING,
            default => self::INFO
        };
    }
}