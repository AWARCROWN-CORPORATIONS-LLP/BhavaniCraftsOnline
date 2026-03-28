<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class NewProductsSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = 1;

        $products = [
            [
                'category' => 'Divine Brass Idols',
                'name' => 'Dancing Nataraja Brass Statue',
                'telugu_name' => 'నటరాజ విగ్రహం',
                'price' => 7500, 'mrp' => 9999, 'material' => 'Pure Brass',
                'desc' => 'Symbol of cosmic dance and creation. Masterfully crafted with intricate detail.'
            ],
            [
                'category' => 'Pooja Mandir Essentials',
                'name' => 'Hand-Carved Wooden Mandapam',
                'telugu_name' => 'చెక్క మండపం',
                'price' => 12000, 'mrp' => 15000, 'material' => 'Mango Wood',
                'desc' => 'Traditional design with beautiful carvings, perfect for any home shrine.'
            ],
            [
                'category' => 'Festive Decoration Artifacts',
                'name' => 'Traditional Brass Urli with Bells',
                'telugu_name' => 'గంటలతో కూడిన ఇత్తడి ఉర్లీ',
                'price' => 3500, 'mrp' => 4500, 'material' => 'Brass',
                'desc' => 'Fill with water and flowers for an elegant festive welcome.'
            ],
            [
                'category' => 'Spiritual Incense & Aromas',
                'name' => 'Mysore Sandalwood Incense Sticks',
                'telugu_name' => 'మైసూర్ గంధపు అగర్బత్తీలు',
                'price' => 250, 'mrp' => 350, 'material' => 'Sandalwood',
                'desc' => 'Authentic Mysore sandalwood fragrance for a divine atmosphere.'
            ],
            [
                'category' => 'Custom Handcrafted Gifts',
                'name' => 'Personalized Brass Name Plate',
                'telugu_name' => 'వ్యక్తిగతీకరించిన ఇత్తడి పేరు బోర్డు',
                'price' => 1800, 'mrp' => 2500, 'material' => 'Brass',
                'desc' => 'Custom engraved name plate with traditional borders.'
            ],
            [
                'category' => 'Wall Hanging Artifacts',
                'name' => 'Goddess Durga Brass Wall Plaque',
                'telugu_name' => 'దుర్గాదేవి వాల్ ప్లేట్',
                'price' => 4200, 'mrp' => 5500, 'material' => 'Brass',
                'desc' => 'Powerful representation of Goddess Durga for wall protection.'
            ],
            [
                'category' => 'Handwoven Silk Accessories',
                'name' => 'Traditional Silk Pooja Vastra',
                'telugu_name' => 'పట్టు పూజ వస్త్రం',
                'price' => 1200, 'mrp' => 1800, 'material' => 'Handloom Silk',
                'desc' => 'Fine handwoven silk cloth for sacred rituals.'
            ],
        ];

        foreach ($products as $p) {
            $cat = Category::where('name', $p['category'])->first();
            if (!$cat) continue;

            $product = Product::create([
                'product_name' => $p['name'],
                'telugu_name' => $p['telugu_name'],
                'price' => $p['price'],
                'mrp' => $p['mrp'],
                'discount_percent' => round((($p['mrp'] - $p['price']) / $p['mrp']) * 100),
                'gst_rate' => 12,
                'short_description' => $p['desc'],
                'full_description' => $p['desc'] . ' Handcrafted by generational artisans for your modern sanctuary.',
                'category_id' => $cat->id,
                'material_type' => $p['material'],
                'festival_use' => 'All Festivals',
                'made_type' => 'Handmade',
                'customizable' => ($p['category'] == 'Custom Handcrafted Gifts'),
                'requires_shipping' => true,
                'replacement_available' => true,
                'replacement_conditions' => 'Replacement within 7 days if damaged.',
                'product_code' => 'NAM-' . strtoupper(Str::random(6)),
                'listed_status' => true,
                'stock' => 25,
                'user_id' => $adminId,
                'slug' => Str::slug($p['name']) . '-' . uniqid(),
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => 'products/default.png',
                'is_main' => true
            ]);
        }
    }
}
