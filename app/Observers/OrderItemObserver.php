<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\Product;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     * Increase stock when order item is created (incoming inventory).
     */
    public function created(OrderItem $orderItem): void
    {
        $this->increaseStock($orderItem->product, $orderItem->quantity);
        $this->updateOrderTotal($orderItem);
    }

    /**
     * Handle the OrderItem "updated" event.
     * Adjust stock based on quantity changes.
     */
    public function updated(OrderItem $orderItem): void
    {
        if ($orderItem->wasChanged('quantity')) {
            $oldQuantity = $orderItem->getOriginal('quantity') ?? 0;
            $newQuantity = $orderItem->quantity;
            $difference = $newQuantity - $oldQuantity;
            
            if ($difference > 0) {
                $this->increaseStock($orderItem->product, $difference);
            } elseif ($difference < 0) {
                $this->decreaseStock($orderItem->product, abs($difference));
            }
        }
        
        $this->updateOrderTotal($orderItem);
    }

    /**
     * Handle the OrderItem "deleted" event.
     * Decrease stock when order item is deleted (remove incoming inventory).
     */
    public function deleted(OrderItem $orderItem): void
    {
        $this->decreaseStock($orderItem->product, $orderItem->quantity);
        $this->updateOrderTotal($orderItem);
    }

    /**
     * Handle the OrderItem "restored" event.
     * Increase stock when order item is restored.
     */
    public function restored(OrderItem $orderItem): void
    {
        $this->increaseStock($orderItem->product, $orderItem->quantity);
        $this->updateOrderTotal($orderItem);
    }

    /**
     * Increase product stock by given quantity.
     */
    private function increaseStock(Product $product, int $quantity): void
    {
        $product->increment('stock_quantity', $quantity);
    }

    /**
     * Decrease product stock by given quantity.
     */
    private function decreaseStock(Product $product, int $quantity): void
    {
        $product->decrement('stock_quantity', $quantity);
    }

    /**
     * Update the order's total amount.
     */
    private function updateOrderTotal(OrderItem $orderItem): void
    {
        if ($orderItem->order) {
            $orderItem->order->calculateTotal();
        }
    }
}
