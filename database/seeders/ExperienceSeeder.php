<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Person;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tristan = Person::where(['first_name' => 'Tristan', 'last_name' => 'Jahier'])->firstOrFail();

        Experience::firstOrCreate([
            'title' => 'Freelance developer',
            'person_id' => $tristan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan.',
            'company' => null,
            'location' => null,
            'started_at' => '2011-04-01',
            'ended_at' => null,
        ]);

        Experience::firstOrCreate([
            'title' => 'GUI developer for Blood Bowl 2 (internship)',
            'person_id' => $tristan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan. Aenean turpis dolor, aliquam a ultricies non, lobortis sed nunc. Cras venenatis sodales dictum. In elementum sem eu eros tincidunt, condimentum tempor lacus condimentum. Donec blandit augue vel pretium hendrerit. Praesent porta id velit a congue. Fusce fermentum turpis lectus. Duis et lorem in risus euismod aliquet. Integer iaculis pellentesque erat, laoreet consectetur tellus congue nec. Integer ornare, sapien gravida malesuada efficitur, quam est euismod nibh, at facilisis odio purus non justo. Praesent laoreet justo velit, sed elementum lectus rhoncus ac. Nam sodales nisl risus. Quisque pellentesque pulvinar velit in egestas.',
            'company' => 'Cyanide Studio',
            'location' => 'Nanterre, France',
            'started_at' => '2014-03-01',
            'ended_at' => '2014-08-01',
        ]);

        Experience::firstOrCreate([
            'title' => 'Senior full-stack PHP developer',
            'person_id' => $tristan->getKey(),
        ], [
            'description' => trim(<<<STRING_DELIMITER
PHP, Laravel, JavaScript (et TypeScript), MySQL, Docker.\n
Conception, création, maintenance et amélioration continues de nombreux outils internes à l’entreprise afin de répondre à ses besoins de visualisation de données, de centralisation et de nettoyage de données, de gestion d'équipe, et d'intégration avec divers outils externes.\n
Plus particulièrement :
- une application de synchronisation unilatérale avec Zoho CRM (base de données miroir de plus de 100 millions de lignes, pour un accès plus rapide que via l’API).
- une application de gestion pour organiser le personnel opérationnel en équipes et sous-équipes (système arborescent), avec un historique des mouvements d’équipes et des changements structurels entièrement explorable et modifiable.
STRING_DELIMITER),
            'company' => 'Selectra',
            'location' => 'Paris, France',
            'started_at' => '2015-02-01',
            'ended_at' => '2025-07-01',
        ]);

        $drustan = Person::where(['first_name' => 'Drustan', 'last_name' => 'Jaegger'])->firstOrFail();

        Experience::firstOrCreate([
            'title' => 'Junior Spy',
            'person_id' => $drustan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan.',
            'company' => '<redacted>',
            'location' => 'Brittany, France',
            'started_at' => '2005-06-01',
            'ended_at' => '2025-06-01',
        ]);

        Experience::firstOrCreate([
            'title' => 'Senior Lead Spy',
            'person_id' => $drustan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan.',
            'company' => '<redacted>',
            'location' => 'Earth, Solar System',
            'started_at' => '2025-07-01',
            'ended_at' => null,
        ]);
    }
}
