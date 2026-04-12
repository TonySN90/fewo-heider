<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageEntry;
use App\Models\PageEntryBlock;
use App\Models\PageGroup;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('domain', 'muster-fewo.de')
            ->orWhere('slug', 'muster-fewo')
            ->first();

        if (! $tenant) {
            return;
        }

        // ── Gruppe ────────────────────────────────────────────────────────────

        $group = PageGroup::firstOrCreate(
            ['tenant_id' => $tenant->id, 'slug' => 'ruegen-erleben'],
            [
                'title' => 'Rügen entdecken',
                'nav_label' => 'Rügen erleben',
                'description' => 'Entdecken Sie die schönsten Ecken der Insel – von Kreidefelsen und Buchenwäldern bis zu historischen Schlössern und familienfreundlichen Ausflügen.',
                'is_visible' => true,
                'sort_order' => 1,
            ]
        );

        // ── Kategorien ────────────────────────────────────────────────────────

        $categories = [
            [
                'title' => 'Wandern',
                'slug' => 'wandern',
                'description' => 'Atemberaubende Wanderwege entlang der Kreideküste, durch UNESCO-Buchenwälder und am Hochufer des Nationalparks Jasmund.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Radfahren',
                'slug' => 'radfahren',
                'description' => 'Rügen ist ein Paradies für Radfahrer – flache Strecken, gut ausgebaute Radwege und herrliche Küstenlandschaften auf 275 km Rundweg.',
                'sort_order' => 2,
            ],
            [
                'title' => 'Ausflugsziele',
                'slug' => 'ausflugsziele',
                'description' => 'Von der eleganten Seebrücke in Sellin bis zum wilden Kap Arkona – die schönsten Orte und Ausflugsziele der Insel Rügen.',
                'sort_order' => 3,
            ],
            [
                'title' => 'Sehenswürdigkeiten',
                'slug' => 'sehenswuerdigkeiten',
                'description' => 'Der Königsstuhl, die Störtebeker Festspiele, die Leuchttürme von Kap Arkona – Rügens historische und natürliche Highlights.',
                'sort_order' => 4,
            ],
            [
                'title' => 'Schlösser & Parks',
                'slug' => 'schloesser-parks',
                'description' => 'Das Jagdschloss Granitz, die klassizistische „Weiße Stadt" Putbus und prächtige Parkanlagen – Rügens fürstliches Erbe.',
                'sort_order' => 5,
            ],
            [
                'title' => 'Familie',
                'slug' => 'familie',
                'description' => 'Dinosaurierland, Rasender Roland, Karls Erlebnis-Dorf und kilometerlange Sandstrände – Rügen begeistert Klein und Groß.',
                'sort_order' => 6,
            ],
        ];

        $pages = [];
        foreach ($categories as $data) {
            $pages[$data['slug']] = Page::firstOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => $data['slug']],
                [
                    'page_group_id' => $group->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'is_visible' => true,
                ]
            );
            // page_group_id bei bestehenden Pages nachrüsten
            if ($pages[$data['slug']]->page_group_id !== $group->id) {
                $pages[$data['slug']]->update(['page_group_id' => $group->id]);
            }
        }

        // Restliche Pages ohne group_id zuordnen
        Page::where('tenant_id', $tenant->id)
            ->whereNull('page_group_id')
            ->update(['page_group_id' => $group->id]);

        // ── Einträge & Blöcke: WANDERN ────────────────────────────────────────

        $wandern = $pages['wandern'];

        $this->entry($wandern, 'Hochuferweg Jasmund', 'hochuferweg-jasmund', 1, [
            ['heading', 'Die schönsten Wanderwege der Insel'],
            ['text', 'Einer der schönsten Wanderwege Deutschlands führt durch den Nationalpark Jasmund entlang der weißen Kreidefelsen. Der Weg verbindet Lohme und Sassnitz und bietet immer wieder spektakuläre Ausblicke auf die Ostsee. UNESCO-geschützte Buchenwälder säumen den Pfad.'],
            ['heading', 'Highlights'],
            ['text', "Highlights\n- Königsstuhl (118 m) – bekanntester Kreidefelsen Rügens\n- Wissower Klinken & Victoriasicht\n- Ernst-Moritz-Arndt-Sicht\n- UNESCO-Buchenwälder"],
            ['badge', 'Leicht – Moderat', 'green'],
            ['badge', '12,6 km · ca. 3,5 Std.', 'blue'],
        ]);

        $this->entry($wandern, 'Schmachter See Rundweg', 'schmachter-see-rundweg', 2, [
            ['text', 'Eine herrliche Rundwanderung ab Binz rund um den naturgeschützten Schmachter See. Der Weg führt durch ruhige Wälder und Felder, vorbei am Jagdschloss Granitz und durch den romantischen Kurpark von Binz. Ideal für Familien und weniger geübte Wanderer.'],
            ['heading', 'Highlights'],
            ['text', "Highlights\n- Jagdschloss Granitz mit Aussichtsturm\n- Naturschutzgebiet Schmachter See\n- Kurpark Binz mit Rosengarten\n- Strandabschnitte bei Binz"],
            ['badge', 'Leicht', 'green'],
            ['badge', '12–13 km · ca. 3 Std.', 'blue'],
        ]);

        $this->entry($wandern, 'Schaabe Strandwanderung', 'schaabe-strandwanderung', 3, [
            ['text', 'Entlang des längsten Strandes Rügens – der Schaabe – verläuft dieser traumhafte Küstenwanderweg zwischen Juliusruh und Glowe. Die bis zu 2 km breite Sandbank bietet endlose Weite, frische Ostseeluft und herrliche Ausblicke auf die Tromper Wiek.'],
            ['heading', 'Highlights'],
            ['text', "Highlights\n- 12 km Sandstrand – längster Rügens\n- Naturlandschaft Tromper Wiek\n- Blick auf Kap Arkona\n- Ruhige, unberührte Natur"],
            ['badge', 'Leicht', 'green'],
            ['badge', 'ca. 12 km · ca. 3 Std.', 'blue'],
        ]);

        $this->entry($wandern, 'Kreidefelsenpfad Stubbenkammer', 'kreidefelsenpfad-stubbenkammer', 4, [
            ['text', 'Dieser anspruchsvollere Küstenpfad zwischen Sassnitz und Lohme führt direkt an der Abbruchkante der Kreidefelsen entlang. Dramatische Ausblicke, das Rauschen der Ostsee tief unten und der Kontrast zwischen leuchtendem Weiß und tiefem Blau machen diesen Weg unvergesslich.'],
            ['heading', 'Highlights'],
            ['text', "Highlights\n- Königsstuhl & Nationalpark-Zentrum\n- Wissower Klinken\n- Victoriasicht & Arndt-Sicht\n- Dramatische Klippenabschnitte"],
            ['badge', 'Moderat', 'orange'],
            ['badge', '8–12 km · 3–4 Std.', 'blue'],
        ]);

        // ── Einträge & Blöcke: RADFAHREN ──────────────────────────────────────

        $radfahren = $pages['radfahren'];

        $this->entry($radfahren, 'Rügen-Rundweg', 'ruegen-rundweg', 1, [
            ['heading', 'Rügen per Rad entdecken'],
            ['text', 'Die Königsdisziplin unter Rügens Radtouren: Der Rundweg umrundet die gesamte Insel und führt durch alle typischen Landschaften – Kreideküste, Boddenlandschaften, Bäderarchitektur und stille Dörfer. Die Route ist in fünf komfortable Tagesetappen aufgeteilt und ideal für einen Wochenurlaub auf dem Fahrrad.'],
            ['text', 'Gesamtlänge: 275 km · 5 Etappen · Schwierigkeit: Leicht – überwiegend flach'],
        ]);

        $this->entry($radfahren, 'Ostseeküstenradweg', 'ostseekuestenradweg', 2, [
            ['text', 'Der Ostseeküstenradweg (EuroVelo 10) ist einer der bekanntesten Fernradwege Europas. Der Rügen-Abschnitt führt entlang traumhafter Küstenabschnitte, vorbei an Steilküsten und durch Hansestädte. Gut ausgeschildert, flach und für alle Fitnesslevel geeignet.'],
            ['text', 'Rügen-Abschnitt: 278 km · Schwierigkeit: Leicht'],
        ]);

        $this->entry($radfahren, 'Schmachter See Runde', 'schmachter-see-runde', 3, [
            ['text', 'Ein perfekter Halbtagesausflug ab Binz: Durch den Wald am Schmachter See, vorbei am Jagdschloss Granitz und zurück über Felder und Wiesen. Die Strecke kombiniert Natur und Kultur auf einem kompakten, gut befahrbaren Kurs – ideal für Familien mit Kindern.'],
            ['text', 'Länge: 12 km · Dauer: ca. 1,5 Std. · Schwierigkeit: Leicht'],
        ]);

        $this->entry($radfahren, 'Radweg nach Putbus', 'radweg-putbus', 4, [
            ['text', 'Von Gustow durch ruhige Felder und Wälder in die klassizistische „Weiße Stadt" Putbus – vorbei an gepflegten Dörfern und durch unberührte Landschaft. In Putbus wartet der einzigartige Circus-Platz und der Schlosspark auf eine Pause. Rückfahrt bequem mit dem Rasenden Roland.'],
            ['text', 'Länge: 19 km · Dauer: 1,5–2 Std. · Schwierigkeit: Moderat'],
        ]);

        // ── Einträge & Blöcke: AUSFLUGSZIELE ─────────────────────────────────

        $ausflugsziele = $pages['ausflugsziele'];

        $this->entry($ausflugsziele, 'Ostseebad Binz', 'ostseebad-binz', 1, [
            ['heading', 'Die schönsten Orte der Insel'],
            ['text', 'Binz ist das größte und bekannteste Seebad Rügens. Die 3,2 km lange, lindengesäumte Strandpromenade mit ihren weißen Bädervillen im Stil der Jahrhundertwende verbreitet ein unvergleichliches Flair. Die 370 m lange Seebrücke mit Restaurant und Tauchgondel lädt zum Bummeln und Staunen ein. Zahlreiche Restaurants, Cafés und Boutiquen machen Binz zum geselligen Mittelpunkt der Insel.'],
            ['text', 'Entfernung: ca. 3 km von der Ferienwohnung'],
        ]);

        $this->entry($ausflugsziele, 'Ostseebad Sellin', 'ostseebad-sellin', 2, [
            ['text', 'Sellin besticht durch sein elegantes Ambiente und die 394 m lange Seebrücke – die längste auf Rügen. Mit Restaurant, Tauchgondel und Bootsanleger ist sie ein Erlebnis für sich. Die typische weiße Bäderarchitektur, hübsche Villen und der weitläufige Strand machen Sellin zu einem der schönsten Seebäder der Insel.'],
            ['text', 'Entfernung: ca. 15 km'],
        ]);

        $this->entry($ausflugsziele, 'Kap Arkona', 'kap-arkona', 3, [
            ['text', 'Die nördlichste Spitze Rügens ist ein dramatisches Naturspektakel: 45 m hohe Kreidefelsen fallen steil ins Meer. Drei historische Leuchttürme – darunter der von Schinkel entworfene Backsteinturm von 1827 – überragen die Landschaft. Die benachbarte slawische Tempelburg Jaromarsburg aus dem 6. Jahrhundert erzählt von der vorchristlichen Geschichte der Insel.'],
            ['text', 'Entfernung: ca. 45 km'],
        ]);

        $this->entry($ausflugsziele, 'Sassnitz – Hafenstadt mit Flair', 'sassnitz', 4, [
            ['text', 'Sassnitz ist die nördlichste Stadt Rügens und bietet mit ihrem historischen Fischerhafen und dem Stadthafen echtes maritimes Flair. Eine 500 m lange Steinmole mit Leuchtturm lädt zum Spaziergang ein. Fähren in die skandinavischen Länder legen hier ab, und das U-Boot-Museum HMS Otus sorgt für ein besonderes Erlebnis.'],
            ['text', 'Entfernung: ca. 25 km'],
        ]);

        $this->entry($ausflugsziele, 'Putbus – Die Weiße Stadt', 'putbus', 5, [
            ['text', 'Putbus wurde 1810 von Fürst Malte zu Putbus als klassizistische Residenzstadt geplant. Das Herzstück ist der einzigartige Circus – ein kreisrunder Platz mit weißen Häusern, von dem acht Alleen sternförmig abgehen. Ein 21 m hoher Obelisk mit Fürstenkrone krönt das Zentrum. Der weitläufige Schlosspark lädt zu Spaziergängen ein.'],
            ['text', 'Entfernung: ca. 20 km'],
        ]);

        // ── Einträge & Blöcke: SEHENSWÜRDIGKEITEN ────────────────────────────

        $sehens = $pages['sehenswuerdigkeiten'];

        $this->entry($sehens, 'Der Königsstuhl', 'koenigsstuhl', 1, [
            ['heading', 'Highlights einer außergewöhnlichen Insel'],
            ['text', 'Mit 118 m ist der Königsstuhl der höchste Kreidefelsen Deutschlands und das bekannteste Wahrzeichen Rügens. Gemalt von Caspar David Friedrich, inspiriert von der Romantik – der Anblick der schneeweißen Felswände über dem tiefen Blau der Ostsee ist unvergesslich. Das Nationalpark-Zentrum Königsstuhl bietet Ausstellungen zur Entstehungsgeschichte der Kreide.'],
            ['text', 'Seit 2023 ergänzt der „Königsweg" – eine 185 m lange Aussichtsplattform, die scheinbar über den Felsen schwebt – das Erlebnis.'],
            ['text', 'Öffnungszeiten: tägl. 10–17 Uhr · Eintritt: ab 12 € Erw. / 6 € Kinder · Adresse: Stubbenkammer, 18546 Sassnitz'],
        ]);

        $this->entry($sehens, 'Störtebeker Festspiele', 'stoertebeker-festspiele', 2, [
            ['text', 'Deutschlands größtes Open-Air-Theater findet seit 1993 alljährlich in Ralswiek am Ufer des Großen Jasmunder Boddens statt. Die spektakulären Inszenierungen rund um den legendären Piraten Klaus Störtebeker verbinden atemberaubende Bühnenbilder mit Livemusik, Pyrotechnik und Kampfchoreografien.'],
            ['text', 'Bis zu 8.500 Zuschauer erleben unter freiem Himmel ein einzigartiges Theaterspektakel – ein Highlight für die ganze Familie.'],
            ['text', 'Spielzeit: Ende Juni – Anfang September · Ort: Ralswiek am Bodden'],
        ]);

        $this->entry($sehens, 'Leuchttürme Kap Arkona', 'leuchttuerme-kap-arkona', 3, [
            ['text', 'Am nördlichsten Punkt Rügens stehen drei historische Türme nebeneinander: Der Schinkelturm (1827) aus rotem Backstein – ein Meisterwerk des klassizistischen Ingenieurbaus – dient heute als Standesamt. Der neuere Leuchtturm (1902) sendet sein Feuer 44 km weit über die Ostsee. Der Peilturm (1927) bietet von seiner Plattform einen atemberaubenden Panoramablick.'],
            ['text', 'Adresse: Kap Arkona, Putgarten · Entfernung: ca. 45 km von Binz'],
        ]);

        $this->entry($sehens, 'Jaromarsburg', 'jaromarsburg', 4, [
            ['text', 'Slawische Tempelburg aus dem 6. Jahrhundert am Kap Arkona – Kultstätte des Gottes Svantevit. 1168 von dänischen Kreuzrittern zerstört, heute beeindruckende Überreste in dramatischer Küstenlage.'],
        ]);

        $this->entry($sehens, 'U-Boot HMS Otus', 'u-boot-hms-otus', 5, [
            ['text', 'Begehbares britisches U-Boot im Hafen Sassnitz. Authentische Ausstattung, enge Gänge und Kammern sowie Führungen zur Geschichte des Falklandkriegs machen es zu einem einzigartigen Erlebnis.'],
            ['text', 'Ganzjährig geöffnet · Sassnitz, ca. 25 km'],
        ]);

        $this->entry($sehens, 'Rasender Roland', 'rasender-roland-sehens', 6, [
            ['text', 'Die historische Schmalspurbahn „Rügensche Bäderbahn" fährt seit über 100 Jahren auf 24 km zwischen Putbus und Göhren – ein nostalgisches Erlebnis mit Dampflok-Charme und herrlichen Landschaftspanoramen.'],
        ]);

        $this->entry($sehens, 'UNESCO-Buchenwälder', 'unesco-buchenwaelder', 7, [
            ['text', 'Die alten Buchenwälder des Nationalparks Jasmund gehören seit 2011 zum UNESCO-Weltnaturerbe. Majestätische Bäume, deren Wurzeln bis an die Kreideküste reichen – ein einzigartiges Naturerlebnis.'],
        ]);

        // ── Einträge & Blöcke: SCHLÖSSER & PARKS ─────────────────────────────

        $schloesser = $pages['schloesser-parks'];

        $this->entry($schloesser, 'Jagdschloss Granitz', 'jagdschloss-granitz', 1, [
            ['heading', 'Fürstliches Erbe der Insel'],
            ['text', 'Inmitten des Granitzer Waldes thront das Jagdschloss wie eine norditalienische Renaissance-Villa. Fürst Malte zu Putbus ließ es als repräsentatives Jagddomizil erbauen. Das Herzstück ist die spektakuläre gusseiserne Wendeltreppe im 38 m hohen Zentralturm – ein Meisterwerk des Eisenkunstgusses des 19. Jahrhunderts. Von der Turmspitze reicht der Blick über die gesamte Insel und die Ostsee.'],
            ['text', 'Rund 150.000 Besucher jährlich machen es zum meistbesuchten Museum in Mecklenburg-Vorpommern.'],
            ['text', 'Entfernung: ca. 10 km von Binz · Turmhöhe: 38 m · Stil: Norditalienische Renaissance'],
        ]);

        $this->entry($schloesser, 'Putbus – Die Weiße Stadt', 'putbus-weisse-stadt', 2, [
            ['text', 'Fürst Malte zu Putbus erschuf 1810 eine klassizistische Residenzstadt nach dem Vorbild von Bath. Der einzigartige Circus-Platz mit dem 21 m hohen Obelisk und der Schlosspark zählen zu den schönsten Stadtensembles Norddeutschlands.'],
            ['text', 'Gegründet: 1810 · Entfernung: ca. 20 km'],
        ]);

        $this->entry($schloesser, 'Schlosspark Putbus', 'schlosspark-putbus', 3, [
            ['text', 'Einer der frühesten englischen Landschaftsparks in Norddeutschland. Weitläufige Rasenflächen, alte Bäume, Teiche und verschlungene Wege laden zu Spaziergängen ein – ein ruhiger Gegenpol zum Badetreiben an der Küste.'],
            ['text', 'Angelegt ab 1804'],
        ]);

        $this->entry($schloesser, 'Schloss Ralswiek', 'schloss-ralswiek', 4, [
            ['text', 'Das Schloss am Großen Jasmunder Bodden ist heute als Bühne der Störtebeker Festspiele weltbekannt. Die erhöhte Lage mit Blick auf den Bodden schafft eine einzigartige Kulisse für das größte Open-Air-Theater Deutschlands.'],
            ['text', 'Erbaut: 1891'],
        ]);

        $this->entry($schloesser, 'Nationalpark Jasmund', 'nationalpark-jasmund', 5, [
            ['text', 'Rügens kleinster, aber berühmtester Nationalpark beherbergt die weißen Kreidefelsen und uralte Buchenwälder. Seit 2011 UNESCO-Weltnaturerbe – ein einzigartiges Naturparadies an der Ostseeküste.'],
        ]);

        $this->entry($schloesser, 'Kurpark Binz', 'kurpark-binz', 6, [
            ['text', 'Direkt hinter der Strandpromenade erstreckt sich der gepflegte Kurpark von Binz mit altem Baumbestand, Rosengarten und Konzertmuschel. Perfekt für einen entspannten Morgenspaziergang oder eine Pause zwischen Strand und Erkundung.'],
        ]);

        // ── Einträge & Blöcke: FAMILIE ────────────────────────────────────────

        $familie = $pages['familie'];

        $this->entry($familie, 'Dinosaurierland Glowe', 'dinosaurierland-glowe', 1, [
            ['heading', 'Unvergessliche Erlebnisse für Klein und Groß'],
            ['text', 'Auf einem 1,5 km langen Erlebnispfad begegnen Kinder über 120 lebensgroßen Dinosaurier-Modellen in natürlicher Umgebung. Ob riesiger T-Rex oder imposanter Brachiosaurus – die detailgetreuen Nachbauten begeistern Kinder und Erwachsene gleichermaßen. Eine interaktive Fossilien-Ausgrabungsstätte rundet das prähistorische Erlebnis ab.'],
            ['text', "120+ Dino-Modelle · 1,5 km Erlebnispfad · Fossilien ausgraben\nÖffnungszeiten: Apr–Okt tägl. 10–17 Uhr · Entfernung: Glowe, ca. 20 km · Altersempfehlung: ab 3 Jahren"],
        ]);

        $this->entry($familie, 'Karls Erlebnis-Dorf Zirkow', 'karls-erlebnis-dorf', 2, [
            ['text', 'Direkt in unserer Nachbarschaft befindet sich das beliebte Karls Erlebnis-Dorf! Mit über 40 Attraktionen – vom Riesentrampolin über Strohparcours und Kinderbauernhof bis hin zu Ponyreiten – ist hier für jeden etwas dabei. Das Tobeland bietet auch bei schlechtem Wetter viel Spaß. Das Erdbeerpflücken ist ein Highlight der Saison.'],
            ['text', "40+ Attraktionen · Freier Eintritt · Schlechtwetter geeignet\nStandort: Zirkow – direkt nebenan · Kostenloser Eintritt"],
        ]);

        $this->entry($familie, 'Rasender Roland', 'rasender-roland', 3, [
            ['text', 'Die historische Schmalspurbahn „Rügensche Bäderbahn" fährt seit über 100 Jahren über die Insel. Auf 24,2 km zwischen Putbus und Göhren schnauft die Dampflok durch Wälder, Wiesen und Küstendörfer. Für Kinder ist die Fahrt im alten Waggon ein unvergessliches Abenteuer – für Erwachsene ein Stück lebendige Eisenbahngeschichte.'],
            ['text', '24 km Strecke · Historische Dampflok · ca. 1 Std. Fahrt · Start: Putbus, ca. 20 km'],
        ]);

        $this->entry($familie, 'U-Boot HMS Otus', 'u-boot-hms-otus-familie', 4, [
            ['text', 'Im Hafen von Sassnitz liegt ein echtes britisches U-Boot der Oberon-Klasse, das von Besuchern begehbar ist. Enge Gänge, authentische Ausstattung und originalgetreue Geräuschkulisse tauchen Groß und Klein in die Welt unter Wasser ein. Geführte Touren erklären spannend die Geschichte des Fahrzeugs aus dem Falklandkrieg.'],
            ['text', "Begehbares U-Boot · Geführte Touren · Ganzjährig geöffnet\nEintritt: 7 € Erw. / 3 € Kinder · Sassnitz, ca. 25 km · Empfohlen ab 6 Jahren"],
        ]);

        $this->entry($familie, 'Familienstrände auf Rügen', 'familienstraende', 5, [
            ['heading', 'Die besten Familienstrände'],
            ['text', 'Strand Schaabe: 12 km feinsandiger Strand mit flachem Wasser – ideal für Kleinkinder. Ruhig, naturbelassen und einer der längsten Strände Deutschlands.'],
            ['text', 'Strand Binz: 3,2 km Promenadenstrand mit allem Komfort – Strandkörbe, Restaurants, Spielplätze und die berühmte Seebrücke – 3 km von unserer Ferienwohnung.'],
            ['text', 'Strand Sellin: Ruhigerer Strand mit flachem Einstieg und der beeindruckenden Seebrücke. Besonders schön für Abendausflüge mit Blick auf den Sonnenuntergang.'],
            ['text', 'Wassersport & Bootstouren: Kanu, Stand-Up-Paddling, Segelkurse und Bootsausflüge entlang der Kreideküste – für aktive Familien gibt es reichlich Angebote auf und im Wasser.'],
        ]);
    }

    // ── Hilfsmethode ──────────────────────────────────────────────────────────

    private function entry(Page $page, string $title, string $slug, int $order, array $blocks): void
    {
        $entry = PageEntry::firstOrCreate(
            ['page_id' => $page->id, 'slug' => $slug],
            [
                'title' => $title,
                'sort_order' => $order,
            ]
        );

        // Nur anlegen wenn noch keine Blöcke vorhanden
        if ($entry->wasRecentlyCreated) {
            foreach ($blocks as $i => $block) {
                [$type, $content] = $block;
                $color = $block[2] ?? null;
                PageEntryBlock::create([
                    'page_entry_id' => $entry->id,
                    'type' => $type,
                    'content' => $content,
                    'color' => $color,
                    'sort_order' => $i + 1,
                ]);
            }
        }
    }
}
