<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\VendorOrder;
use App\values\OrderStatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorOrderFactory extends Factory
{
    protected $model = VendorOrder::class;

    public function definition()
    {
        return [
            'ulid' => Str::ulid(),
            'vendor_id' => Vendor::factory(),
            'order_id' => Order::factory(),
            'tax_rate' => $this->faker->randomFloat(2, 0, 20), // Percentage as decimal
            'tax_total' => $this->faker->numberBetween(100, 1000), // In cents
            'total' => $this->faker->numberBetween(1000, 10000), // In cents
            'currency_id' => Currency::factory(),
            'status' => $this->faker->randomElement(OrderStatusTypes::cases()),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterMaking(function (VendorOrder $vendorOrder) {
            // Any after-making configurations
        })->afterCreating(function (VendorOrder $vendorOrder) {
            // Any after-create configurations
        });
    }

    /**
     * Set the order status to a specific value.
     */
    public function status(string $status)
    {
        return $this->state(function (array $attributes) use ($status) {
            return [
                'status' => $status,
            ];
        });
    }
}
