<?php

namespace App\GraphQL\Queries;

use App\Models\Product;

class RelatedProducts
{
    public function __invoke($rootValue, array $args)
    {
        /** @var \App\Models\Product $product */
        $product = $rootValue;
        $take = $args['take'] ?? 4;

        return Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('listed_status', 'Listed')
            ->take($take)
            ->get();
    }
}
