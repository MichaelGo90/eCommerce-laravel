<?php

namespace App\Http\Controllers;

use App\Http\DTOs\ListProductsDTO;
use App\Http\Repositories\ProductRepository;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\ListProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;

class ProductController
{
    public function __construct(public ProductRepository $productRepository)
    {
    }

    public function index(ListProductsRequest $request)
    {
        $listProductsDTO = new ListProductsDTO(
            page: $request->input('page', 1),
            perPage: $request->input('per_page', 10),
            name: $request->input('name'),
            minPrice: $request->input('min_price'),
            maxPrice: $request->input('max_price')
        );
        return ProductResource::collection($this->productRepository->list($listProductsDTO));
    }

    public function create(CreateProductRequest $request)
    {
        $product = $this->productRepository->create($request->validated());
        return new ProductResource($product);
    }

    public function show(int $id)
    {
        $product = $this->productRepository->find($id);
        return new ProductResource($product);
    }

    public function update(int $id, UpdateProductRequest $request)
    {
        $product = $this->productRepository->update($id, $request->validated());
        return new ProductResource($product);
    }
}
