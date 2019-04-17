<?php

namespace App\Observers;

use App\Models\Payment\{Payment, PaymentType, PaymentMethod};
use App\Models\Client\Client;

class PaymentsObserver
{
    public function creating(Payment $payment)
    {
        if (
            $payment->type === PaymentType::PAYMENT &&
            $payment->method === PaymentMethod::CASH &&
            $payment->entity_type === Client::class
        ) {
            $payment->bill_number = Payment::query()
                ->where('type', PaymentType::PAYMENT)
                ->where('method', PaymentMethod::CASH)
                ->whereRaw(sprintf("%s = YEAR(NOW())", date('Y', strtotime($payment->date))))
                ->max('bill_number') + 1;
        }
    }
}