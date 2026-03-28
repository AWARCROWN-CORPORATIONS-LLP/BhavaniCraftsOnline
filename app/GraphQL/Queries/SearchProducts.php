<?php

namespace App\GraphQL\Queries;

use App\Models\Product;

class SearchProducts
{
    public function __invoke($_, array $args)
    {
        $query = Product::with(['images', 'category'])
            ->where('listed_status', 'Listed');

        if (isset($args['q']) && !empty($args['q'])) {
            $q = $args['q'];
            $query->where(function ($sq) use ($q) {
                $sq->where('product_name', 'LIKE', "%{$q}%")
                   ->orWhere('short_description', 'LIKE', "%{$q}%")
                   ->orWhere('material_type', 'LIKE', "%{$q}%")
                   ->orWhere('festival_use', 'LIKE', "%{$q}%");
            });
        }

        if (isset($args['category']) && !empty($args['category'])) {
            $query->where('category_id', $args['category']);
        }

        return $query;
    }
}
