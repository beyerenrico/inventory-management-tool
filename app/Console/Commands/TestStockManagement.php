<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestStockManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-stock-management';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test automatic stock management functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $product = \App\Models\Product::first();
        $customer = \App\Models\Customer::first();

        if (!$product || !$customer) {
            $this->error('Please make sure you have at least one product and one customer.');
            return;
        }

        $this->info('Testing automatic stock management...');
        $this->info("Product: {$product->name}");
        $this->info("Stock before: {$product->stock_quantity}");

        // Create a test order
        $order = \App\Models\Order::create([
            'order_number' => 'TEST-' . now()->format('His'),
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'order_date' => now(),
            'status' => 'pending'
        ]);

        // Add order item - this should automatically increase stock
        $orderItem = $order->orderItems()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => $product->price,
            'total_price' => 10 * $product->price
        ]);

        $product->refresh();
        $this->info("Stock after adding 10 units: {$product->stock_quantity}");
        $this->info("Order total: €{$order->fresh()->total_amount}");
        $this->info('✅ Stock management test completed successfully!');
    }
}
