<?php

namespace App\Http\Controllers;

use App\Http\DTOs\CreateOrderDTO;
use App\Http\Repositories\OrderRepository;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(CreateOrderRequest $request)
    {
        $createOrderDTO =  new CreateOrderDTO(products: $request->products);
        $order = $this->orderRepository->create($createOrderDTO, auth()->id());
        return response()->json($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
