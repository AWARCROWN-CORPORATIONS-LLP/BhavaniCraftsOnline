<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = 1;

        // Ensure categories exist (similar to CategorySeeder but with slugs matching the script)
        $poojaCategories = [
            'Brass Pooja Items' => 'brass-pooja-items',
            'Wooden Pooja Items' => 'wooden-pooja-items',
            'Pooja Thalis' => 'pooja-thalis',
            'Traditional Diyas' => 'traditional-diyas',
            'Spiritual Accessories' => 'spiritual-accessories',
            'Pooja Samagri & Decor' => 'pooja-samagri-decor',
        ];

        $categoryIds = [];
        foreach ($poojaCategories as $name => $slug) {
            $cat = Category::firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'parent_id' => null,
                'icon_url' => 'icons/' . $slug . '.png'
            ]);
            $categoryIds[$name] = $cat->id;
        }

        $products = [
            // Brass Pooja Items
            [
                'category' => 'Brass Pooja Items',
                'name' => 'Handcrafted Goddess Lakshmi Brass Idol',
                'telugu_name' => 'లక్ష్మీ దేవీ విగ్రహం',
                'price' => 4500, 'mrp' => 5999, 'material' => 'Pure Brass',
                'desc' => 'Exquisitely handcrafted brass idol of Goddess Lakshmi, the deity of wealth and prosperity.'
            ],
            [
                'category' => 'Brass Pooja Items',
                'name' => 'Divine Lord Ganesha Brass Murti',
                'telugu_name' => 'గణేశుని ఇత్తడి విగ్రహం',
                'price' => 3800, 'mrp' => 4999, 'material' => 'Pure Brass',
                'desc' => 'Intricately detailed Ganesha idol in a sitting posture. Brings wisdom and removes obstacles.'
            ],
            [
                'category' => 'Brass Pooja Items',
                'name' => 'Auspicious Lord Vishnu Brass Idol',
                'telugu_name' => 'విష్ణువు విగ్రహం',
                'price' => 5200, 'mrp' => 6999, 'material' => 'Pure Brass',
                'desc' => 'Majestic representation of Lord Vishnu. Hand-polished with a golden finish.'
            ],
            // Wooden Pooja Items
            [
                'category' => 'Wooden Pooja Items',
                'name' => 'Premium Carved Teak Wood Pooja Mandir',
                'telugu_name' => 'తేకు చెక్క పూజ మందిరం',
                'price' => 15000, 'mrp' => 22000, 'material' => 'Teak Wood',
                'desc' => 'Elegant temple made from seasoned teak wood with intricate floral carvings.'
            ],
            [
                'category' => 'Wooden Pooja Items',
                'name' => 'Rosewood Holy Book Stand',
                'telugu_name' => 'రోజ్వుడ్ పుస్తక స్టాండ్',
                'price' => 950, 'mrp' => 1499, 'material' => 'Rosewood',
                'desc' => 'Sturdy and foldable stand for Bhagavad Gita or any sacred scripture.'
            ],
            // Pooja Thalis
            [
                'category' => 'Pooja Thalis',
                'name' => 'Silver Plated Designer Pooja Thali Set',
                'telugu_name' => 'సిల్వర్ ప్లేటెడ్ పూజ థాలి సెట్',
                'price' => 5500, 'mrp' => 7999, 'material' => 'Silver Plated Brass',
                'desc' => '9-piece set including thali, bowls, diya, and spoon.'
            ],
            // Traditional Diyas
            [
                'category' => 'Traditional Diyas',
                'name' => 'Brass & Glass Akhand Diya',
                'telugu_name' => 'అఖండ దీపం',
                'price' => 1400, 'mrp' => 1999, 'material' => 'Brass & Borosilicate Glass',
                'desc' => 'Designed to burn for long hours without extinguishing.'
            ],
            [
                'category' => 'Traditional Diyas',
                'name' => 'Hanging Peacock Brass Diya',
                'telugu_name' => 'వేలాడే నెమలి దీపం',
                'price' => 2600, 'mrp' => 3500, 'material' => 'Solid Brass',
                'desc' => 'Handcrafted hanging lamp with a majestic peacock top.'
            ],
        ];

        foreach ($products as $p) {
            $product = Product::create([
                'product_name' => $p['name'],
                'telugu_name' => $p['telugu_name'],
                'price' => $p['price'],
                'mrp' => $p['mrp'],
                'discount_percent' => round((($p['mrp'] - $p['price']) / $p['mrp']) * 100),
                'gst_rate' => 12,
                'short_description' => $p['desc'],
                'full_description' => $p['desc'] . ' This product is made of ' . $p['material'] . ' and is handcrafted for premium quality.',
                'category_id' => $categoryIds[$p['category']],
                'material_type' => $p['material'],
                'festival_use' => 'All Festivals',
                'made_type' => 'Handmade',
                'customizable' => false,
                'requires_shipping' => true,
                'replacement_available' => true,
                'replacement_conditions' => 'Replacement within 7 days if damaged during transit.',
                'product_code' => 'PJA-' . strtoupper(Str::random(6)),
                'listed_status' => true,
                'stock' => 50,
                'user_id' => $adminId,
                'slug' => Str::slug($p['name']),
            ]);

            $imageToUse = 'products/default.png';
            if ($p['category'] == 'Brass Pooja Items') $imageToUse = 'products/brass_pooja_set.png';
            if ($p['category'] == 'Wooden Pooja Items') $imageToUse = 'products/teak_mandir.png';
            if ($p['category'] == 'Pooja Thalis') $imageToUse = 'products/silver_thali.png';
            if ($p['category'] == 'Traditional Diyas') $imageToUse = 'products/designer_diyas.png';

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imageToUse,
                'is_main' => true
            ]);
        }
    }
}
