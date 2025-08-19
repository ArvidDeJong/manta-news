<?php

namespace Darvis\MantaNews\Console\Commands;

use Darvis\MantaNews\Database\Seeders\NewsSeeder;
use Darvis\MantaNews\Models\News;
use Illuminate\Console\Command;

class SeedNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta-news:seed
                            {--force : Force seeding even if news items already exist}
                            {--fresh : Delete existing news items before seeding}
                            {--with-navigation : Also seed navigation items for news management}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with sample news items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌱 Seeding Manta News...');
        $this->newLine();

        // Check if news items already exist
        $existingCount = News::count();

        if ($existingCount > 0 && !$this->option('force') && !$this->option('fresh')) {
            $this->warn("⚠️  Found {$existingCount} existing news items.");

            if (!$this->confirm('Do you want to continue seeding? This will add more items.', false)) {
                $this->info('Seeding cancelled.');
                return self::SUCCESS;
            }
        }

        // Handle fresh option
        if ($this->option('fresh')) {
            if ($this->confirm('This will delete ALL existing news items. Are you sure?', false)) {
                $this->info('🗑️  Deleting existing news items...');
                News::truncate();
                $this->line('   ✅ Existing news items deleted');
            } else {
                $this->info('Fresh seeding cancelled.');
                return self::SUCCESS;
            }
        }

        // Run the seeder
        $this->info('📰 Creating sample news items...');

        try {
            $this->seedNewsItems();

            $totalCount = News::count();
            $this->newLine();
            $this->info("🎉 News seeding completed successfully!");
            $this->line("   📊 Total news items in database: {$totalCount}");
        } catch (\Exception $e) {
            $this->error('❌ Error during seeding: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Seed navigation if requested
        if ($this->option('with-navigation')) {
            $this->seedNavigation();
        }

        $this->newLine();
        $this->comment('💡 Tips:');
        $this->line('• Use --fresh to start with a clean slate');
        $this->line('• Use --force to skip confirmation prompts');
        $this->line('• Use --with-navigation to also seed navigation items');
        $this->line('• Check your news management interface to see the seeded items');

        return self::SUCCESS;
    }

    /**
     * Seed the news items into the database
     */
    private function seedNewsItems(): void
    {
        $newsItems = [
            [
                'title' => 'Welkom bij ons nieuwe nieuwssysteem',
                'slug' => 'welkom-bij-ons-nieuwe-nieuwssysteem',
                'summary' => 'We introduceren ons gloednieuwe nieuwssysteem met moderne functionaliteiten.',
                'excerpt' => 'Ontdek alle nieuwe mogelijkheden van ons vernieuwde nieuwsplatform.',
                'content' => '<p>We zijn trots om ons nieuwe nieuwssysteem te presenteren. Dit systeem biedt een moderne en gebruiksvriendelijke interface voor het beheren en lezen van nieuws.</p><p>Enkele hoogtepunten:</p><ul><li>Responsive design</li><li>Categorieën en tags</li><li>SEO-geoptimaliseerd</li><li>Meertalige ondersteuning</li></ul>',
                'author' => 'Redactie',
                'active' => true,
                'sort' => 1,
            ],
            [
                'title' => 'Nieuwe functionaliteiten toegevoegd',
                'slug' => 'nieuwe-functionaliteiten-toegevoegd',
                'summary' => 'Ontdek de nieuwste functies die we hebben toegevoegd aan het platform.',
                'excerpt' => 'Een overzicht van alle nieuwe mogelijkheden en verbeteringen.',
                'content' => '<p>We hebben verschillende nieuwe functionaliteiten toegevoegd om uw ervaring te verbeteren:</p><p><strong>Verbeterde zoekfunctie:</strong> Vind sneller wat u zoekt met onze geavanceerde zoekfilters.</p><p><strong>Betere categorisering:</strong> Nieuws is nu beter georganiseerd in logische categorieën.</p><p><strong>Sociale media integratie:</strong> Deel artikelen eenvoudig op uw favoriete sociale platforms.</p>',
                'author' => 'Ontwikkelteam',
                'active' => true,
                'sort' => 2,
            ],
            [
                'title' => 'Tips voor effectief nieuwsbeheer',
                'slug' => 'tips-voor-effectief-nieuwsbeheer',
                'summary' => 'Praktische tips om het meeste uit uw nieuwssysteem te halen.',
                'excerpt' => 'Leer hoe u efficiënt nieuws kunt beheren en publiceren.',
                'content' => '<p>Hier zijn enkele tips om effectief gebruik te maken van het nieuwssysteem:</p><ol><li><strong>Gebruik duidelijke titels:</strong> Zorg ervoor dat uw titels informatief en aantrekkelijk zijn.</li><li><strong>Optimaliseer voor SEO:</strong> Vul altijd de SEO-titel en beschrijving in.</li><li><strong>Gebruik afbeeldingen:</strong> Visuele content verhoogt de betrokkenheid.</li><li><strong>Plan uw publicaties:</strong> Gebruik de planning functie voor tijdige publicatie.</li></ol>',
                'author' => 'Content Manager',
                'active' => true,
                'sort' => 3,
            ],
            [
                'title' => 'Aankomende updates en verbeteringen',
                'slug' => 'aankomende-updates-en-verbeteringen',
                'summary' => 'Een vooruitblik op wat er nog meer komt in toekomstige updates.',
                'excerpt' => 'Blijf op de hoogte van onze roadmap en aankomende features.',
                'content' => '<p>We werken continu aan verbeteringen. Hier is wat u kunt verwachten:</p><p><strong>Q1 2024:</strong></p><ul><li>Geavanceerde analytics</li><li>Automatische content suggesties</li><li>Verbeterde mobile app</li></ul><p><strong>Q2 2024:</strong></p><ul><li>AI-powered content optimization</li><li>Enhanced collaboration tools</li><li>Advanced scheduling options</li></ul>',
                'author' => 'Product Manager',
                'active' => false, // Draft artikel
                'sort' => 4,
            ],
        ];

        foreach ($newsItems as $item) {
            // Voeg standaard velden toe
            $item['locale'] = 'nl';
            $item['host'] = request()->getHost() ?? 'localhost';
            $item['company_id'] = 1; // Standaard company ID
            $item['seo_title'] = $item['seo_title'] ?? $item['title'];
            $item['seo_description'] = $item['seo_description'] ?? $item['summary'];
            $item['tags'] = 'nieuws,update,systeem';
            $item['created_at'] = now()->subDays(rand(1, 30));
            $item['updated_at'] = $item['created_at'];

            News::create($item);
        }

        $this->info('News items seeded successfully!');
    }

    /**
     * Seed navigation items by calling the manta:seed-navigation command
     */
    private function seedNavigation(): void
    {
        $this->newLine();
        $this->info('🧭 Seeding navigation items...');

        try {
            // First, call the general manta:seed-navigation command from manta-laravel-flux-cms
            $exitCode = $this->call('manta:seed-navigation', [
                '--force' => true // Always force navigation seeding
            ]);

            if ($exitCode === 0) {
                $this->info('   ✅ General navigation items seeded successfully.');
            } else {
                $this->warn('   ⚠️  General navigation seeding completed with warnings.');
            }

            // Then seed news-specific navigation items
            $this->seedNewsNavigation();
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Navigation seeding failed: ' . $e->getMessage());
            $this->warn('   💡 You can manually run "php artisan manta:seed-navigation" later.');
        }
    }

    /**
     * Seed news-specific navigation items
     */
    private function seedNewsNavigation(): void
    {
        $this->info('📰 Seeding news navigation items...');

        try {
            // Check if MantaNav model exists
            if (!class_exists('\Manta\FluxCMS\Models\MantaNav')) {
                $this->warn('   ⚠️  MantaNav model not found. Skipping news navigation seeding.');
                return;
            }

            $newsNavItems = [
                [
                    'title' => 'Nieuws',
                    'route' => 'news.list',
                    'sort' => 10,
                    'type' => 'content',
                    'description' => 'Beheer nieuwsartikelen'
                ],
                [
                    'title' => 'Nieuws Categorieën',
                    'route' => 'news.cat.list',
                    'sort' => 11,
                    'type' => 'content',
                    'description' => 'Beheer nieuwscategorieën'
                ]
            ];

            $MantaNav = '\Manta\FluxCMS\Models\MantaNav';
            $created = 0;
            $existing = 0;

            foreach ($newsNavItems as $item) {
                // Controleer of het navigatie-item al bestaat
                $existingNav = $MantaNav::where('route', $item['route'])
                    ->where('locale', 'nl')
                    ->first();

                if (!$existingNav) {
                    $MantaNav::create([
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

                    $this->info("   ✅ News navigatie item '{$item['title']}' aangemaakt.");
                    $created++;
                } else {
                    $this->info("   ℹ️  News navigatie item '{$item['title']}' bestaat al.");
                    $existing++;
                }
            }

            $this->info("   📊 {$created} items aangemaakt, {$existing} items bestonden al.");
        } catch (\Exception $e) {
            $this->warn('   ⚠️  News navigation seeding failed: ' . $e->getMessage());
            $this->warn('   💡 This may be due to missing MantaNav model or database table.');
        }
    }
}
