<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 100, 10000);
        $taxAmount = $subtotal * 0.1;
        $discountAmount = $this->faker->randomFloat(2, 0, $subtotal * 0.2);
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        $paidAmount = $this->faker->randomFloat(2, 0, $totalAmount);
        $balanceAmount = $totalAmount - $paidAmount;

        return [
            'invoice_number' => 'INV' . $this->faker->unique()->numerify('#######'),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'invoice_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'balance_amount' => $balanceAmount,
            'status' => $balanceAmount > 0 ? 'pending' : 'paid',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

