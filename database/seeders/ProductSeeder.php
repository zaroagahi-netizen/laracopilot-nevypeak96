<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Term;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => ['tr' => 'Ahşap Renkli Bloklar', 'ku-latn' => 'Blokên Darîn ên Rengîn', 'en' => 'Wooden Colored Blocks'],
                'description' => ['tr' => 'Montessori prensiplerine uygun, doğal ahşap bloklar. İnce motor becerileri ve renk tanıma geliştirir.', 'ku-latn' => 'Li gorî prensîbên Montessori, blokên darîn ên xwezayî. Jêhatîbûnên motora hûr û nasîna rengan pêş dixin.', 'en' => 'Natural wooden blocks following Montessori principles. Develops fine motor skills and color recognition.'],
                'sku' => 'WB-001',
                'price' => 299.99,
                'type' => 'physical',
                'stock' => 25,
                'min_age' => 2,
                'max_age' => 6,
                'is_new' => true,
                'age_group' => '1-2-years',
                'development' => ['montessori', 'fine-motor-skills', 'cognitive'],
            ],
            [
                'name' => ['tr' => 'Kürtçe Alfabe E-Kitabı', 'ku-latn' => 'E-Pirtûka Alfabeya Kurdî', 'en' => 'Kurdish Alphabet E-Book'],
                'description' => ['tr' => 'İnteraktif dijital kitap. Kürtçe alfabe öğretimi, sesli telaffuz ve eğlenceli aktiviteler.', 'ku-latn' => 'Pirtûka dîjîtal a înteraktîf. Hînkirina alfabeya Kurdî, dengdana dengî û çalakîyên kêfxweş.', 'en' => 'Interactive digital book. Kurdish alphabet teaching, audio pronunciation and fun activities.'],
                'sku' => 'DG-001',
                'price' => 49.99,
                'type' => 'digital',
                'stock' => 0,
                'min_age' => 3,
                'max_age' => 7,
                'is_new' => true,
                'age_group' => '3-4-years',
                'development' => ['language', 'cognitive'],
            ],
            [
                'name' => ['tr' => 'Denge Rengîn Oyuncak Davul', 'ku-latn' => 'Deholê Lîstikê ya Dengê Rengîn', 'en' => 'Colorful Sound Toy Drum'],
                'description' => ['tr' => 'Müzik ve ritim duygusu geliştiren renkli davul. El-göz koordinasyonu için ideal.', 'ku-latn' => 'Dehol a rengîn ku hestê mûzîk û rîtmê pêş dixe. Ji bo hevahengiya destan-çavan îdeal e.', 'en' => 'Colorful drum developing music and rhythm sense. Ideal for hand-eye coordination.'],
                'sku' => 'TO-001',
                'price' => 189.99,
                'compare_at_price' => 249.99,
                'type' => 'physical',
                'stock' => 3,
                'min_age' => 1,
                'max_age' => 5,
                'age_group' => '1-2-years',
                'development' => ['fine-motor-skills', 'creativity', 'sensory'],
            ],
            [
                'name' => ['tr' => 'Montessori Sayı Çubukları', 'ku-latn' => 'Çûkan Jimaran ên Montessori', 'en' => 'Montessori Number Rods'],
                'description' => ['tr' => 'Matematiksel düşünme ve sayı kavramı için ahşap çubuklar. 1-10 arası sayı öğretimi.', 'ku-latn' => 'Çûkan darîn ji bo ramanîna matematîkî û têgihiştina jimaran. Hînkirina jimaran ji 1-10.', 'en' => 'Wooden rods for mathematical thinking and number concept. Number teaching from 1-10.'],
                'sku' => 'MT-002',
                'price' => 449.99,
                'type' => 'physical',
                'stock' => 15,
                'min_age' => 3,
                'max_age' => 8,
                'is_new' => false,
                'age_group' => '3-4-years',
                'development' => ['montessori', 'cognitive'],
            ],
            [
                'name' => ['tr' => 'İsme Özel Ahşap Puzzle', 'ku-latn' => 'Pûzel a Darîn a Taybet bi Navê', 'en' => 'Personalized Wooden Puzzle'],
                'description' => ['tr' => 'Çocuğunuzun ismi ile özel yapılmış ahşap puzzle. Problem çözme ve harfleri tanıma.', 'ku-latn' => 'Pûzela darîn ku bi navê zarokê we tê çêkirin. Çareserkirina pirsgirêk û nasîna tîpan.', 'en' => 'Custom-made wooden puzzle with your child\'s name. Problem solving and letter recognition.'],
                'sku' => 'CP-001',
                'price' => 349.99,
                'type' => 'physical',
                'stock' => 8,
                'min_age' => 2,
                'max_age' => 6,
                'is_customizable' => true,
                'age_group' => '1-2-years',
                'development' => ['fine-motor-skills', 'cognitive', 'language'],
            ],
            [
                'name' => ['tr' => 'Kürtçe Masallar PDF Seti', 'ku-latn' => 'Komê PDF a Çîrokên Kurdî', 'en' => 'Kurdish Fairy Tales PDF Set'],
                'description' => ['tr' => '20 geleneksel Kürt masalı. İllüstrasyonlu, sesli anlatım ve aktivite sayfaları.', 'ku-latn' => '20 çîrokên kevneşopî yên Kurdî. Bi wêneyan, vegotin û rûpelên çalakîyan.', 'en' => '20 traditional Kurdish tales. Illustrated, audio narration and activity pages.'],
                'sku' => 'DG-002',
                'price' => 99.99,
                'compare_at_price' => 149.99,
                'type' => 'digital',
                'stock' => 0,
                'min_age' => 4,
                'max_age' => 10,
                'is_new' => true,
                'age_group' => '5-6-years',
                'development' => ['language', 'social-emotional', 'creativity'],
            ],
            [
                'name' => ['tr' => 'Denge Dengbêjî Eğitim Seti', 'ku-latn' => 'Komê Perwerdehiya Dengbêjîyê', 'en' => 'Dengbêj Education Set'],
                'description' => ['tr' => 'Kürt müziği ve dengbêjlik geleneğini öğreten kapsamlı set. Video dersler ve notalar.', 'ku-latn' => 'Komeke giştî ku mûzîka Kurdî û kevneşopîya dengbêjîyê dihîne. Waneyên vîdyoyî û noter.', 'en' => 'Comprehensive set teaching Kurdish music and dengbêj tradition. Video lessons and notes.'],
                'sku' => 'DG-003',
                'price' => 199.99,
                'type' => 'digital',
                'stock' => 0,
                'min_age' => 7,
                'max_age' => 15,
                'age_group' => '7-9-years',
                'development' => ['language', 'creativity', 'social-emotional'],
            ],
            [
                'name' => ['tr' => 'Denge Bal Balon Seti', 'ku-latn' => 'Komê Balonên Bal', 'en' => 'Honey Balloon Set'],
                'description' => ['tr' => 'Renkli balonlar ve pompa. Dış mekan aktiviteleri için harika. Nefes kontrolü geliştirir.', 'ku-latn' => 'Balon û pompeyên rengîn. Ji bo çalakîyên derveyî balkêş in. Kontrola nefesê pêş dixe.', 'en' => 'Colorful balloons and pump. Great for outdoor activities. Develops breath control.'],
                'sku' => 'TO-002',
                'price' => 79.99,
                'type' => 'physical',
                'stock' => 0,
                'min_age' => 3,
                'max_age' => 10,
                'age_group' => '3-4-years',
                'development' => ['gross-motor-skills', 'sensory'],
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'sku' => $data['sku'],
                'price' => $data['price'],
                'compare_at_price' => $data['compare_at_price'] ?? null,
                'type' => $data['type'],
                'stock_quantity' => $data['stock'] ?? 0,
                'min_age' => $data['min_age'] ?? null,
                'max_age' => $data['max_age'] ?? null,
                'is_new' => $data['is_new'] ?? false,
                'is_customizable' => $data['is_customizable'] ?? false,
                'active' => true,
            ]);

            // Attach age group
            if (isset($data['age_group'])) {
                $ageGroupTerm = Term::byTaxonomy('age-group')
                    ->where('slug', $data['age_group'])
                    ->first();
                if ($ageGroupTerm) {
                    $product->terms()->attach($ageGroupTerm->id);
                }
            }

            // Attach development areas
            if (isset($data['development'])) {
                foreach ($data['development'] as $devSlug) {
                    $devTerm = Term::byTaxonomy('development-area')
                        ->where('slug', $devSlug)
                        ->first();
                    if ($devTerm) {
                        $product->terms()->attach($devTerm->id);
                    }
                }
            }
        }
    }
}