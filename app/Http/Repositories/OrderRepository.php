<?php

namespace App\Http\Repositories;

use App\Events\OrderPlaced;
use App\Events\OrderPlacedEvent;
use App\Http\DTOs\CreateOrderDTO;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function __construct(public Order $order, public ProductRepository $productRepository)
    {
    }

    /**
     * @throws \Exception
     */
    public function create(CreateOrderDTO $data, $userId): Order|null
    {
        try {
            DB::transaction(function () use ($data, $userId) {
                /** @TODO  move this to a service but keep design simple for now */
                $this->productRepository->validateOrderItems($data->products);
                $dbProducts = $this->productRepository->getByIds(array_column($data->products, 'id'));
                $orderItems = collect($data->products)->map(function ($product) use ($dbProducts) {
                    return [
                        'id' => $product['id'],
                        'price' => $dbProducts->where('id', $product['id'])->first()->price,
                        'quantity' => $product['quantity']
                    ];
                })->toArray();
                $total = array_reduce($orderItems, function ($carry, $product) use ($dbProducts) {
                    return $carry + ($dbProducts->where('id', $product['id'])->first()->price * $product['quantity']);
                }, 0);
                $order = $this->order->create([
                    'user_id' => $userId,
                    'total' => $total
                ]);
                foreach ($orderItems as $product) {
                    $order->products()->attach($product['id'], [
                        'quantity' => $product['quantity'],
                        'price' => $product['price'] // this should save the price for history prices
                    ]);
                }
                DB::commit();
                event(new OrderPlaced($order));
                return $order;
            });
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error creating order '. $e->getMessage());
        }
        return null;
    }
}
