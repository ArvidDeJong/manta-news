<?php

namespace Darvis\MantaNews\Database\Seeders;

use Illuminate\Database\Seeder;
use Manta\FluxCMS\Models\MantaNav;

class NewsNavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsNavItems = [
            [
                'title' => 'Nieuws',
                'route' => 'news.list',
                'sort' => 10,
                'type' => 'module',
                'description' => 'Beheer nieuwsartikelen'
            ],
            [
                'title' => 'Nieuws Categorieën',
                'route' => 'news.cat.list',
                'sort' => 11,
                'type' => 'module',
                'description' => 'Beheer nieuwscategorieën'
            ]
        ];

        foreach ($newsNavItems as $item) {
            // Controleer of het navigatie-item al bestaat
            $existingNav = MantaNav::where('route', $item['route'])
                ->where('locale', 'nl')
                ->first();

            if (!$existingNav) {
                MantaNav::create([
                    'created_by' => 'News Seeder',
                    'updated_by' => null,
                    'deleted_by' => null,
                    'company_id' => 1, // Default company
                    'host' => request()->getHost() ?? 'localhost',
                    'pid' => null,
                    'locale' => 'nl',
                    'active' => true,
                    'sort' => $item['sort'],
                    'title' => $item['title'],
                    'route' => $item['route'],
                    'url' => null,
                    'type' => $item['type'],
                    'rights' => null,
                    'data' => json_encode([
                        'description' => $item['description'],
                        'icon' => 'newspaper',
                        'module' => 'manta-news'
                    ]),
                ]);

                if (isset($this->command)) {
                    $this->command->info("News navigatie item '{$item['title']}' aangemaakt.");
                }
            } else {
                if (isset($this->command)) {
                    $this->command->info("News navigatie item '{$item['title']}' bestaat al.");
                }
            }
        }
    }
}
