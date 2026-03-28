<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

$adminId = 1;

// Define categories
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
        'telugu_name' => 'లక్ష్మీ దేవీ వెండి విగ్రహం', // Placeholder, using telugu for "Lakshmi Brass Idol"
        'price' => 4500, 'mrp' => 5999, 'material' => 'Pure Brass',
        'desc' => 'Exquisitely handcrafted brass idol of Goddess Lakshmi, the deity of wealth and prosperity. Perfect for your home altar or as a premium gift.'
    ],
    [
        'category' => 'Brass Pooja Items',
        'name' => 'Divine Lord Ganesha Brass Murti',
        'telugu_name' => 'గణేశుని ఇత్తడి విగ్రహం',
        'price' => 3800, 'mrp' => 4999, 'material' => 'Pure Brass',
        'desc' => 'Intricately detailed Ganesha idol in a sitting posture. Brings wisdom and removes obstacles from your spiritual journey.'
    ],
    [
        'category' => 'Brass Pooja Items',
        'name' => 'Auspicious Lord Vishnu Brass Idol',
        'telugu_name' => 'విష్ణువు వెండి విగ్రహం',
        'price' => 5200, 'mrp' => 6999, 'material' => 'Pure Brass',
        'desc' => 'Majestic representation of Lord Vishnu. Hand-polished with a golden finish for timeless elegance.'
    ],
    [
        'category' => 'Brass Pooja Items',
        'name' => 'Brass Hanuman Chalisa Figurine',
        'telugu_name' => 'హనుమంతుని విగ్రహం',
        'price' => 2500, 'mrp' => 3500, 'material' => 'Pure Brass',
        'desc' => 'Symbol of strength and devotion. This Hanuman idol is perfect for small pooja spaces.'
    ],
    [
        'category' => 'Brass Pooja Items',
        'name' => 'Antique Brass Hand Bell with Nandi',
        'telugu_name' => 'నంది ఇత్తడి గంట',
        'price' => 1200, 'mrp' => 1799, 'material' => 'Antiqued Brass',
        'desc' => 'Clear, resonance-rich sound. Features a beautifully carved Nandi handle for traditional appeal.'
    ],
    [
        'category' => 'Brass Pooja Items',
        'name' => 'Peacock Design Brass Aarti Plate',
        'telugu_name' => 'నెమలి డిజైన్ హారతి ప్లేట్',
        'price' => 1800, 'mrp' => 2499, 'material' => 'Pure Brass',
        'desc' => 'Ornate aarti plate with peacock motifs, adding a touch of royalty to your daily rituals.'
    ],

    // Wooden Pooja Items
    [
        'category' => 'Wooden Pooja Items',
        'name' => 'Premium Carved Teak Wood Pooja Mandir',
        'telugu_name' => 'తేకు చెక్క పూజ మందిరం',
        'price' => 15000, 'mrp' => 22000, 'material' => 'Teak Wood',
        'desc' => 'Elegant wall-mountable or floor-standing temple made from seasoned teak wood with intricate floral carvings.'
    ],
    [
        'category' => 'Wooden Pooja Items',
        'name' => 'Floral Engraved Mango Wood Incense Holder',
        'telugu_name' => 'చెక్క అగర్బత్తీల హోల్డర్',
        'price' => 450, 'mrp' => 699, 'material' => 'Mango Wood',
        'desc' => 'Handcrafted wooden box to hold and burn incense safely while storing up to 50 sticks.'
    ],
    [
        'category' => 'Wooden Pooja Items',
        'name' => 'Rosewood Holy Book Stand (Rehal)',
        'telugu_name' => 'రోజ్వుడ్ పుస్తక స్టాండ్',
        'price' => 950, 'mrp' => 1499, 'material' => 'Rosewood',
        'desc' => 'Sturdy and foldable stand for Bhagavad Gita or any sacred scripture. Rich natural wood grain finish.'
    ],
    [
        'category' => 'Wooden Pooja Items',
        'name' => 'Traditional Teak Wood Simhasanam',
        'telugu_name' => 'తేకు చెక్క సింహాసనం',
        'price' => 3200, 'mrp' => 4500, 'material' => 'Teak Wood',
        'desc' => 'Throne for your beloved deity, elevated and carved flawlessly by master artisans.'
    ],

    // Pooja Thalis
    [
        'category' => 'Pooja Thalis',
        'name' => 'Silver Plated Designer Pooja Thali Set',
        'telugu_name' => 'సిల్వర్ ప్లేటెడ్ పూజ థాలి సెట్',
        'price' => 5500, 'mrp' => 7999, 'material' => 'Silver Plated Brass',
        'desc' => '9-piece set including thali, bowls, diya, and spoon. Coated with pure silver for a premium look.'
    ],
    [
        'category' => 'Pooja Thalis',
        'name' => 'Royal Meenakari Work Pooja Thali',
        'telugu_name' => 'మీనాకారి పూజ థాలి',
        'price' => 2800, 'mrp' => 3999, 'material' => 'Steel with Meenakari Enamel',
        'desc' => 'Vibrant colors and intricate enamel work make this thali perfect for festivals like Diwali and weddings.'
    ],
    [
        'category' => 'Pooja Thalis',
        'name' => 'Traditional Pure Copper Pooja Thali Set',
        'telugu_name' => 'రాగి పూజ థాలి సెట్',
        'price' => 1600, 'mrp' => 2199, 'material' => 'Pure Copper',
        'desc' => 'Ayurvedic significance coupled with traditional design. Ideal for daily offerings.'
    ],
    [
        'category' => 'Pooja Thalis',
        'name' => 'Engraved Brass Panch Aarti Thali',
        'telugu_name' => 'ఇత్తడి పంచ హారతి ప్లేట్',
        'price' => 2200, 'mrp' => 2999, 'material' => 'Engraved Brass',
        'desc' => 'Includes 5 fixed lamps and a handle for safe and easy performance of the Pancha Aarti ritual.'
    ],

    // Traditional Diyas
    [
        'category' => 'Traditional Diyas',
        'name' => 'Brass & Glass Akhand Diya (Large)',
        'telugu_name' => 'అఖండ దీపం',
        'price' => 1400, 'mrp' => 1999, 'material' => 'Brass & Borosilicate Glass',
        'desc' => 'Designed to burn for long hours without extinguishing. Heat-resistant glass cover keeps the flame steady.'
    ],
    [
        'category' => 'Traditional Diyas',
        'name' => 'Hanging Peacock Brass Diya with Chain',
        'telugu_name' => 'వేలాడే నెమలి దీపం',
        'price' => 2600, 'mrp' => 3500, 'material' => 'Solid Brass',
        'desc' => 'Handcrafted hanging lamp with a majestic peacock top and 5 wicks. Perfect for temple entrances.'
    ],
    [
        'category' => 'Traditional Diyas',
        'name' => 'Set of 2 Designer Kuber Brass Diyas',
        'telugu_name' => 'కుబేర దీపాలు (2 సెట్)',
        'price' => 650, 'mrp' => 999, 'material' => 'Polished Brass',
        'desc' => 'Elegant flower-shaped lamps to invite wealth and prosperity into your home.'
    ],
    [
        'category' => 'Traditional Diyas',
        'name' => 'Terracotta Tulsi Pot Diya',
        'telugu_name' => 'తులసి కోట మట్టి దీపం',
        'price' => 350, 'mrp' => 500, 'material' => 'Natural Terracotta',
        'desc' => 'Eco-friendly and traditional, shaped like a Tulsi Vrindavan. Hand-painted by rural artisans.'
    ],
    [
        'category' => 'Traditional Diyas',
        'name' => 'Brass Kamatchi Amman Vilakku',
        'telugu_name' => 'కామాక్షి అమ్మన్ విలక్కు',
        'price' => 4500, 'mrp' => 6000, 'material' => 'Pure Brass',
        'desc' => 'Solemn and powerful oil lamp depicting Goddess Kamatchi, widely used in South Indian homes.'
    ],
    [
        'category' => 'Traditional Diyas',
        'name' => 'Panch Mukhi Brass Diya (Five-Flame)',
        'telugu_name' => 'పంచముఖి దీపం',
        'price' => 950, 'mrp' => 1299, 'material' => 'Brass',
        'desc' => 'Traditional lamp with five wick slots, representing the five elements of nature.'
    ],

    // Spiritual Accessories
    [
        'category' => 'Spiritual Accessories',
        'name' => 'Original Mysore Sandalwood Japa Mala',
        'telugu_name' => 'చందనం జపమాల',
        'price' => 2200, 'mrp' => 3000, 'material' => 'Mysore Sandalwood',
        'desc' => '108+1 beads made from authentic sandalwood. Known for its cooling effect and divine fragrance.'
    ],
    [
        'category' => 'Spiritual Accessories',
        'name' => 'Panch Mukhi Rudraksha Japa Mala',
        'telugu_name' => 'రుద్రాక్ష జపమాల',
        'price' => 850, 'mrp' => 1200, 'material' => 'Natural Rudraksha Beads',
        'desc' => 'Sacred seeds related to Lord Shiva. Ideal for chanting and meditation.'
    ],
    [
        'category' => 'Spiritual Accessories',
        'name' => 'Pure Crystal (Sphatik) Japa Mala',
        'telugu_name' => 'స్ఫటిక జపమాల',
        'price' => 1800, 'mrp' => 2500, 'material' => 'Natural Crystal Quartz',
        'desc' => 'Clear and cool crystal beads. Boosts concentration and serenity during spiritual practice.'
    ],
    [
        'category' => 'Spiritual Accessories',
        'name' => 'Heavy Gauge Pure Copper Kalash',
        'telugu_name' => 'రాగి కలశం',
        'price' => 1100, 'mrp' => 1599, 'material' => 'Copper',
        'desc' => 'Traditional pot for storing holy water or for use in abhishekam and rituals.'
    ],
    [
        'category' => 'Spiritual Accessories',
        'name' => 'Artisan Brass Kamandalu',
        'telugu_name' => 'ఇత్తడి కమండలం',
        'price' => 1950, 'mrp' => 2800, 'material' => 'Brass',
        'desc' => 'Spouted pot traditionally used by sages and during vedic ceremonies.'
    ],

    // Pooja Samagri & Decor
    [
        'category' => 'Pooja Samagri & Decor',
        'name' => 'Handcrafted Artificial Marigold Toran',
        'telugu_name' => 'బంతిపూల తోరణం',
        'price' => 300, 'mrp' => 450, 'material' => 'Polyester/Silk',
        'desc' => 'Everlasting festive garland for doors and walls. High-quality fabric for a realistic look.'
    ],
    [
        'category' => 'Pooja Samagri & Decor',
        'name' => 'Ornate Brass Kumkum Box (Choupala)',
        'telugu_name' => 'ఇత్తడి కుంకుమ భరిణ',
        'price' => 1450, 'mrp' => 2100, 'material' => 'Brass',
        'desc' => 'Multiple compartments for Haldi, Kumkum, Chandan, and Akshat. Features a parrot handle.'
    ],
    [
        'category' => 'Pooja Samagri & Decor',
        'name' => 'Embroidered Velvet Puja Aasan (Set of 3)',
        'telugu_name' => 'వెల్వెట్ పూజ ఆసనం',
        'price' => 550, 'mrp' => 799, 'material' => 'Velvet & Gold Thread',
        'desc' => 'Soft, premium cloths for placing idols and thalis. Protects your altar from scratches.'
    ],
    [
        'category' => 'Pooja Samagri & Decor',
        'name' => 'Square Brass Chaurang (Pooja Stool)',
        'telugu_name' => 'ఇత్తడి చౌరంగీ',
        'price' => 2800, 'mrp' => 3999, 'material' => 'Brass & Wood Base',
        'desc' => 'Elevated platform for deities. Features lion-leg design and intricate top embossing.'
    ],
    [
        'category' => 'Pooja Samagri & Decor',
        'name' => 'Floral Brass Rangoli Stencil Set',
        'telugu_name' => 'రంగువల్లి స్టెన్సిల్',
        'price' => 650, 'mrp' => 999, 'material' => 'Brass',
        'desc' => 'Create divine patterns effortlessly with these reusable brass stencils.'
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
    ]);

    $imageToUse = 'products/brass_pooja_set.png';
    if ($p['category'] == 'Wooden Pooja Items') $imageToUse = 'products/teak_mandir.png';
    if ($p['category'] == 'Pooja Thalis') $imageToUse = 'products/silver_thali.png';
    if ($p['category'] == 'Traditional Diyas') $imageToUse = 'products/designer_diyas.png';
    if ($p['category'] == 'Spiritual Accessories') $imageToUse = 'products/spiritual_malas.png';
    if ($p['category'] == 'Pooja Samagri & Decor') $imageToUse = 'products/pooja_decor.png';

    ProductImage::create([
        'product_id' => $product->id,
        'image_url' => $imageToUse,
        'is_main' => true
    ]);
}

echo "30 Products seeded successfully!";
