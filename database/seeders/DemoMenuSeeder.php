<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoMenuSeeder extends Seeder
{
    /**
     * بيانات تجريبية لمعاينة واجهة المنيو على /menu/1.
     */
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'demo-owner@menusnap.test'],
            [
                'name' => 'مالك تجريبي',
                'password' => Hash::make('password'),
            ],
        );

        Restaurant::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => $owner->id,
                'name' => 'برجر هاوس',
                'slug' => 'burger-house',
                'whatsapp_number' => '962795105700',
                'logo' => null,
                'is_active' => true,
                'order_method' => 'whatsapp',
            ],
        );

        Product::query()->where('restaurant_id', 1)->delete();
        Category::query()->where('restaurant_id', 1)->delete();

        $mains = Category::create([
            'restaurant_id' => 1,
            'name' => 'وجبات وساندويش',
            'sort_order' => 1,
        ]);

        $sides = Category::create([
            'restaurant_id' => 1,
            'name' => 'مقبلات ومشروبات',
            'sort_order' => 2,
        ]);

        foreach ([
            ['category_id' => $mains->id, 'name' => 'برجر كلاسيك', 'description' => 'لحم بلدي، جبنة شيدر، خس، مخلل، صوص المنزل.', 'price' => '4.50'],
            ['category_id' => $mains->id, 'name' => 'برجر مشروم', 'description' => 'فطر سوتيه، جبنة سويسرية، مايونيز بالثوم المعتق.', 'price' => '5.50'],
            ['category_id' => $mains->id, 'name' => 'تشيز برجر بالهالابينو', 'description' => 'نكهة حارة متوازنة مع صوص الباربكيو.', 'price' => '5.00'],
            ['category_id' => $mains->id, 'name' => 'دجاج كرسبي سبايسي', 'description' => 'كتف دجاج مقرمش، مخللات، سلطات كرنب.', 'price' => '4.00'],
            ['category_id' => $sides->id, 'name' => 'بطاطس وجبنة وفطر', 'description' => 'بطاطس مقلية مع جبنة وفطر مهروس ومزينة بالأعشاب.', 'price' => '2.50'],
            ['category_id' => $sides->id, 'name' => 'حلقات بصل', 'description' => 'مقرمشة من الخارج، طرية من الداخل.', 'price' => '2.00'],
            ['category_id' => $sides->id, 'name' => 'ليمون نعناع مثلج', 'description' => 'منعش، يُنصح مع الوجبات الحارة.', 'price' => '1.50'],
        ] as $row) {
            Product::create([
                'restaurant_id' => 1,
                'category_id' => $row['category_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'image' => null,
                'is_available' => true,
            ]);
        }
    }
}
