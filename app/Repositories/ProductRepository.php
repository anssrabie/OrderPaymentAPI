<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(protected Product $product)
    {
        parent::__construct($product);
    }
}
