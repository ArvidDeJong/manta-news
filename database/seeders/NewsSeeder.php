<?php

namespace Darvis\MantaNews\Database\Seeders;

use Darvis\MantaNews\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        $this->command->info('News items seeded successfully!');
    }
}
