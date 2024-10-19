<?php

namespace App\Http\Repositories;

use App\Http\DTOs\ListProductsDTO;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductRepository
{
    public function __construct(public Product $productModel)
    {
    }

    public function list(ListProductsDTO $listProductsDTO)
    {
        $cacheKey = $this->generateCacheKey($listProductsDTO);
        return Cache::remember($cacheKey, now()->addHours(2), function () use ($listProductsDTO) {

            $skip = ($listProductsDTO->page - 1) * $listProductsDTO->perPage;
            $name = $listProductsDTO->name;
            $minPrice = $listProductsDTO->minPrice ?? 0;
            $maxPrice = $listProductsDTO->maxPrice ?? PHP_INT_MAX;
            $hasPriceFilter = $minPrice >= 0 || $maxPrice;
            return $this->productModel
                ->when($name, function ($query, $name) {
                    return $query->where('name', 'like', "%$name%");
                })
                ->when($hasPriceFilter, function ($query) use ($minPrice, $maxPrice) {
                    return $query->whereBetween('price', [$minPrice, $maxPrice]);
                })
                ->skip($skip)
                ->simplePaginate($listProductsDTO->perPage);
        });
    }

    public function create(array $data): Product
    {
        return $this->productModel->create($data);
    }

    public function find(int $id): Product
    {
        return $this->productModel->findOrFail($id);
    }

    public function update(int $id, mixed $validated): Product
    {
        $product = $this->productModel->findOrFail($id);
        $product->update($validated);
        return $product;
    }

    protected function generateCacheKey(ListProductsDTO $listProductsDTO): string
    {
        return 'products_' . md5(json_encode([
                'page' => $listProductsDTO->page,
                'perPage' => $listProductsDTO->perPage,
                'name' => $listProductsDTO->name,
                'minPrice' => $listProductsDTO->minPrice,
                'maxPrice' => $listProductsDTO->maxPrice
            ]));
    }

    public function validateOrderItems(array $items): void
    {
        $itemIds = array_column($items, 'id');
        $itemsDB = $this->productModel->find($itemIds);;
        foreach ($itemsDB as $item) {
            $product = $this->productModel->find($item['id']);
            if ($product->quantity < $item['quantity']) {
                abort(400, 'Product ' . $product->name . ' has only ' . $product->quantity . ' items in stock');
            }
        }
    }

    public function getByIds($ids)
    {
        return $this->productModel->find($ids);
    }
}
