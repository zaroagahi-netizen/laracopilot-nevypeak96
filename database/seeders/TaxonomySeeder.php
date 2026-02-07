<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    public function run()
    {
        // Age Group Taxonomy
        $ageGroupTaxonomy = Taxonomy::create([
            'name' => 'Age Group',
            'slug' => 'age-group',
            'description' => 'Product categorization by age ranges',
        ]);

        $ageGroups = [
            ['slug' => '0-12-months', 'name' => ['tr' => '0-12 Ay', 'ku-latn' => '0-12 Meh', 'en' => '0-12 Months'], 'sort' => 1],
            ['slug' => '1-2-years', 'name' => ['tr' => '1-2 Yaş', 'ku-latn' => '1-2 Sal', 'en' => '1-2 Years'], 'sort' => 2],
            ['slug' => '3-4-years', 'name' => ['tr' => '3-4 Yaş', 'ku-latn' => '3-4 Sal', 'en' => '3-4 Years'], 'sort' => 3],
            ['slug' => '5-6-years', 'name' => ['tr' => '5-6 Yaş', 'ku-latn' => '5-6 Sal', 'en' => '5-6 Years'], 'sort' => 4],
            ['slug' => '7-9-years', 'name' => ['tr' => '7-9 Yaş', 'ku-latn' => '7-9 Sal', 'en' => '7-9 Years'], 'sort' => 5],
            ['slug' => '10-12-years', 'name' => ['tr' => '10-12 Yaş', 'ku-latn' => '10-12 Sal', 'en' => '10-12 Years'], 'sort' => 6],
        ];

        foreach ($ageGroups as $group) {
            Term::create([
                'taxonomy_id' => $ageGroupTaxonomy->id,
                'slug' => $group['slug'],
                'name' => $group['name'],
                'sort_order' => $group['sort'],
            ]);
        }

        // Development Area Taxonomy
        $developmentTaxonomy = Taxonomy::create([
            'name' => 'Development Area',
            'slug' => 'development-area',
            'description' => 'Child development focus areas',
        ]);

        $developmentAreas = [
            [
                'slug' => 'montessori',
                'name' => ['tr' => 'Montessori', 'ku-latn' => 'Montessori', 'en' => 'Montessori'],
                'description' => ['tr' => 'Montessori eğitim prensiplerine uygun', 'ku-latn' => 'Li gorî prensîbên perwerdehiya Montessori', 'en' => 'Following Montessori education principles'],
                'sort' => 1
            ],
            [
                'slug' => 'fine-motor-skills',
                'name' => ['tr' => 'İnce Motor Becerileri', 'ku-latn' => 'Jêhatîbûnên Motora Hûr', 'en' => 'Fine Motor Skills'],
                'description' => ['tr' => 'El ve parmak koordinasyonu', 'ku-latn' => 'Hevahengiya destan û tilîyan', 'en' => 'Hand and finger coordination'],
                'sort' => 2
            ],
            [
                'slug' => 'gross-motor-skills',
                'name' => ['tr' => 'Kaba Motor Becerileri', 'ku-latn' => 'Jêhatîbûnên Motora Girsî', 'en' => 'Gross Motor Skills'],
                'description' => ['tr' => 'Büyük kas grupları ve denge', 'ku-latn' => 'Komên masûlkeyên mezin û hevsengî', 'en' => 'Large muscle groups and balance'],
                'sort' => 3
            ],
            [
                'slug' => 'cognitive',
                'name' => ['tr' => 'Bilişsel Gelişim', 'ku-latn' => 'Pêşketina Zanistî', 'en' => 'Cognitive Development'],
                'description' => ['tr' => 'Düşünme ve problem çözme', 'ku-latn' => 'Ramanan û çareserkirina pirsgirêkan', 'en' => 'Thinking and problem solving'],
                'sort' => 4
            ],
            [
                'slug' => 'language',
                'name' => ['tr' => 'Dil Gelişimi', 'ku-latn' => 'Pêşketina Zimanî', 'en' => 'Language Development'],
                'description' => ['tr' => 'Konuşma ve iletişim becerileri', 'ku-latn' => 'Jêhatîbûnên axaftin û ragihandinê', 'en' => 'Speech and communication skills'],
                'sort' => 5
            ],
            [
                'slug' => 'social-emotional',
                'name' => ['tr' => 'Sosyal-Duygusal', 'ku-latn' => 'Civakî-Hestî', 'en' => 'Social-Emotional'],
                'description' => ['tr' => 'Duygusal zeka ve sosyal beceriler', 'ku-latn' => 'Jêrîbûna hestî û jêhatîbûnên civakî', 'en' => 'Emotional intelligence and social skills'],
                'sort' => 6
            ],
            [
                'slug' => 'creativity',
                'name' => ['tr' => 'Yaratıcılık', 'ku-latn' => 'Afirînerî', 'en' => 'Creativity'],
                'description' => ['tr' => 'Sanat ve hayal gücü', 'ku-latn' => 'Huner û hêza xeyalê', 'en' => 'Art and imagination'],
                'sort' => 7
            ],
            [
                'slug' => 'sensory',
                'name' => ['tr' => 'Duyusal Gelişim', 'ku-latn' => 'Pêşketina Hestkirinê', 'en' => 'Sensory Development'],
                'description' => ['tr' => 'Beş duyunun gelişimi', 'ku-latn' => 'Pêşketina pênc hestan', 'en' => 'Development of five senses'],
                'sort' => 8
            ],
        ];

        foreach ($developmentAreas as $area) {
            Term::create([
                'taxonomy_id' => $developmentTaxonomy->id,
                'slug' => $area['slug'],
                'name' => $area['name'],
                'description' => $area['description'] ?? null,
                'sort_order' => $area['sort'],
            ]);
        }
    }
}