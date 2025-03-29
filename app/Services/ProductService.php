<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends BaseService
{
    public function __construct(protected ProductRepository $productRepository)
    {
        parent::__construct($productRepository);
    }

    public function getProductsByIds(array $productIds):Collection
    {
       return $this->repository->whereIn('id',$productIds)->get();
    }
}
