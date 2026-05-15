<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\Locales;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DomainsSeeder extends Seeder
{
    /**
     * Industrial domains with multilingual copy and optional parent slug for hierarchy.
     *
     * @return array<int, array{slug: string, parent_slug: string|null, sort_order: int, icon: string|null, translations: array<string, array{name: string, description: string, slug?: string}>}>
     */
    private function definitions(): array
    {
        return [
            [
                'slug' => 'footwear',
                'parent_slug' => null,
                'sort_order' => 10,
                'icon' => 'boot',
                'translations' => [
                    'en' => ['name' => 'Footwear', 'description' => 'Closed footwear from upper closing through bottoming, finishing, and packing for retail-ready pairs.'],
                    'pt' => ['name' => 'Calçado', 'description' => 'Calçado fechado desde o fecho do cabedal até ao solado, acabamentos e embalagem para pares prontos para retalho.'],
                    'fr' => ['name' => 'Chaussure', 'description' => 'Chaussures fermées de la fermeture de tige au montage semelle, finitions et conditionnement.'],
                    'de' => ['name' => 'Schuhwerk', 'description' => 'Geschlossenes Schuhwerk von der Schaftfertigung über Bottoming und Finish bis zur verpackungsfertigen Paarware.'],
                    'it' => ['name' => 'Calzature', 'description' => 'Calzature chiuse dalla chiusura tomaia a montaggio suola, finiture e confezionamento.'],
                    'es' => ['name' => 'Calzado', 'description' => 'Calzado cerrado desde el cierre del patrón hasta el montaje de suela, acabados y empaquetado.'],
                ],
            ],
            [
                'slug' => 'footwear-construction',
                'parent_slug' => 'footwear',
                'sort_order' => 11,
                'icon' => 'hammer',
                'translations' => [
                    'en' => ['name' => 'Footwear Construction', 'description' => 'Industrial construction workflow from upper assembly through lasting, bottoming, bonding, and inline quality release.'],
                    'pt' => ['name' => 'Construção de Calçado', 'description' => 'Fluxo industrial de construção desde o fecho do cabedal até moldação, montagem de fundo, colagem e libertação de qualidade em linha.'],
                    'fr' => ['name' => 'Construction de Chaussure', 'description' => 'Flux industriel de construction de la tige au montage sur forme, bottoming, collage et libération qualité en ligne.'],
                    'de' => ['name' => 'Schuhkonstruktion', 'description' => 'Industrieller Konstruktionsfluss von Schaftmontage über Aufziehen, Bottoming, Verklebung bis zur Inline-Qualitätsfreigabe.'],
                    'it' => ['name' => 'Costruzione Calzaturiera', 'description' => 'Flusso industriale dalla costruzione tomaia al montaggio su forma, fondo, incollaggio e rilascio qualità in linea.'],
                    'es' => ['name' => 'Construcción de Calzado', 'description' => 'Flujo industrial desde ensamblaje del corte hasta montado, fondo, pegado y liberación de calidad en línea.'],
                ],
            ],
            [
                'slug' => 'leather-goods',
                'parent_slug' => null,
                'sort_order' => 20,
                'icon' => 'briefcase',
                'translations' => [
                    'en' => ['name' => 'Leather goods', 'description' => 'Small leather goods and soft accessories: panels, folding, reinforcement, and edge treatments before assembly.'],
                    'pt' => ['name' => 'Marroquinaria', 'description' => 'Artigos de pele e acessórios leves: painéis, dobras, reforços e tratamentos de bordo antes da montagem.', 'slug' => 'marroquinaria'],
                    'fr' => ['name' => 'Maroquinerie', 'description' => 'Maroquinerie et accessoires souples : panneaux, plis, renforts et finitions de tranche avant montage.'],
                    'de' => ['name' => 'Lederwaren', 'description' => 'Lederwaren und leichte Accessoires: Zuschnitte, Falzen, Verstärkungen und Kantenbearbeitung vor der Montage.'],
                    'it' => ['name' => 'Pelletteria', 'description' => 'Pelletteria e accessori morbidi: pannelli, pieghe, rinforzi e finiture del bordo prima del montaggio.'],
                    'es' => ['name' => 'Marroquinería', 'description' => 'Marroquinería y complementos ligeros: paneles, pliegues, refuerzos y acabados de canto antes del montaje.'],
                ],
            ],
            [
                'slug' => 'belts',
                'parent_slug' => null,
                'sort_order' => 30,
                'icon' => 'link',
                'translations' => [
                    'en' => ['name' => 'Belts', 'description' => 'Strap goods: splitting to gauge, edge creasing, tip shaping, buckle assembly, and hole punching with wear tolerances.'],
                    'pt' => ['name' => 'Cintos', 'description' => 'Artigos em tira: desbaste à espessura, frisagem, afinação de pontas, montagem de fivela e furação com tolerâncias de uso.'],
                    'fr' => ['name' => 'Ceintures', 'description' => 'Articles en lanière : épaississement contrôlé, rainage, façonnage des embouts, boucle et perforations avec tolérances port.'],
                    'de' => ['name' => 'Gürtel', 'description' => 'Riemenware: Ausdünnen auf Maß, Kantenpressung, Spitzenform, Schließenmontage und Lochbild mit Tragetoleranzen.'],
                    'it' => ['name' => 'Cinture', 'description' => 'Articoli a striscia: riduzione spessore, filettatura, sagomatura punte, montaggio fibbia e forature con tolleranze d’uso.'],
                    'es' => ['name' => 'Cinturones', 'description' => 'Artículos de tira: rebaje a espesor, fileteado, punta y cola, hebilla y perforado con tolerancias de uso.'],
                ],
            ],
            [
                'slug' => 'design',
                'parent_slug' => null,
                'sort_order' => 40,
                'icon' => 'pencil-ruler',
                'translations' => [
                    'en' => ['name' => 'Design', 'description' => 'Product intent: last selection, line architecture, hardware spec, colorways, and tolerance budgets shared with pattern and sourcing.'],
                    'pt' => ['name' => 'Design', 'description' => 'Intenção de produto: forma, arquitetura de linha, especificação de ferragens, colorways e orçamento de tolerâncias para malha e compras.'],
                    'fr' => ['name' => 'Design', 'description' => 'Intention produit : forme, architecture de ligne, quincaillerie, coloris et budget de tolérances pour patronage et sourcing.'],
                    'de' => ['name' => 'Design', 'description' => 'Produktintention: Leistenwahl, Linienführung, Beschlagspezifikation, Colorways und Toleranzbudget für Schnitt und Einkauf.'],
                    'it' => ['name' => 'Design', 'description' => 'Intento prodotto: forma, linea, ferramenta, varianti colore e budget tolleranze per cartamodello e acquisti.'],
                    'es' => ['name' => 'Diseño', 'description' => 'Intención de producto: horma, línea, herrajes, colorways y presupuesto de tolerancias para patronaje y compras.'],
                ],
            ],
            [
                'slug' => 'pattern-making',
                'parent_slug' => 'design',
                'sort_order' => 41,
                'icon' => 'scissors',
                'translations' => [
                    'en' => ['name' => 'Pattern making', 'description' => '2D development on the last: mean form, allowances, notches, and size-rule stacks feeding cutting room and closing.'],
                    'pt' => ['name' => 'Modelagem', 'description' => 'Desenvolvimento 2D na forma: forma média, folgas, entalhes e escalas de tamanho para corte e fecho.'],
                    'fr' => ['name' => 'Patronage', 'description' => 'Développement 2D sur forme : forme moyenne, valeurs de couture, repères et gradation pour coupe et fermeture.'],
                    'de' => ['name' => 'Schnitttechnik', 'description' => '2D-Entwicklung auf der Leiste: Mittelform, Zugaben, Kerben und Größenstaffel für Zuschneiderei und Schließerei.'],
                    'it' => ['name' => 'Modellistica', 'description' => 'Sviluppo 2D sulla forma: forma media, margini, tacche e scala taglie per taglio e chiusura.'],
                    'es' => ['name' => 'Patronaje', 'description' => 'Desarrollo 2D sobre horma: forma media, márgenes, muescas y escalado para corte y cerrado.'],
                ],
            ],
            [
                'slug' => 'cad-cam',
                'parent_slug' => 'design',
                'sort_order' => 42,
                'icon' => 'monitor',
                'translations' => [
                    'en' => ['name' => 'CAD / CAM', 'description' => 'Digital patterns, nesting, cutter paths, and machine post-processors bridging engineering data to shop-floor equipment.'],
                    'pt' => ['name' => 'CAD / CAM', 'description' => 'Malhas digitais, nesting, percursos de corte e pós-processadores que ligam dados de engenharia ao equipamento de chão de fábrica.'],
                    'fr' => ['name' => 'CAO / FAO', 'description' => 'Patrons numériques, placement, parcours de coupe et post-processeurs reliant la donnée technique aux machines.'],
                    'de' => ['name' => 'CAD / CAM', 'description' => 'Digitale Schnitte, Nesten, Fräs- und Schnittbahnen und Postprozessoren zwischen Konstruktion und Maschinen.'],
                    'it' => ['name' => 'CAD / CAM', 'description' => 'Cartamodelli digitali, nesting, percorsi taglio e post-processori verso le macchine di reparto.'],
                    'es' => ['name' => 'CAD / CAM', 'description' => 'Patrones digitales, anidamiento, trayectorias y postprocesadores hacia el parque de máquinas.'],
                ],
            ],
            [
                'slug' => 'materials',
                'parent_slug' => null,
                'sort_order' => 50,
                'icon' => 'layers',
                'translations' => [
                    'en' => ['name' => 'Materials', 'description' => 'Upper and bottom components: textiles, foams, boards, adhesives, and reinforcement stacks with test evidence.'],
                    'pt' => ['name' => 'Materiais', 'description' => 'Componentes de cabedal e solas: têxteis, espumas, cartões, colas e pacotes de reforço com evidência de ensaio.'],
                    'fr' => ['name' => 'Matériaux', 'description' => 'Composants tige et semelle : textiles, mousses, cartons, adhésifs et empilements de renfort avec preuves d’essai.'],
                    'de' => ['name' => 'Materialien', 'description' => 'Schaft- und Laufkomponenten: Textilien, Schäume, Einlagen, Klebstoffe und Verstärkungsstapel mit Prüfnachweis.'],
                    'it' => ['name' => 'Materiali', 'description' => 'Componenti tomaia e fondo: tessuti, schiume, carte, adesivi e sandwich di rinforzo con prove di laboratorio.'],
                    'es' => ['name' => 'Materiales', 'description' => 'Componentes de piel y suela: textiles, espumas, cartones, adhesivos y refuerzos con evidencia de ensayo.'],
                ],
            ],
            [
                'slug' => 'leather',
                'parent_slug' => 'materials',
                'sort_order' => 51,
                'icon' => 'texture',
                'translations' => [
                    'en' => ['name' => 'Leather', 'description' => 'Tanned hides and crust selection: yield planning, substance tolerances, cutting direction, and finish compatibility with adhesives.'],
                    'pt' => ['name' => 'Couro', 'description' => 'Peles curtidas e seleção de crus: planeamento de rendimento, tolerâncias de espessura, sentido de corte e compatibilidade de acabamento com colas.'],
                    'fr' => ['name' => 'Cuir', 'description' => 'Peaux tannées et choix de croûte : rendement, substance, sens de coupe et compatibilité colle/finition.'],
                    'de' => ['name' => 'Leder', 'description' => 'Gerbte Haut und Krustenauswahl: Ertrag, Dicke, Schnittrichtung und Verklebbarkeit der Oberfläche.'],
                    'it' => ['name' => 'Pelle', 'description' => 'Pelli conciate e scelta crosta: resa, spessore, direzione di taglio e compatibilità finitura/colla.'],
                    'es' => ['name' => 'Cuero', 'description' => 'Pieles curtidas y selección de crusta: rendimiento, espesor, dirección de corte y compatibilidad acabado/adhesivo.'],
                ],
            ],
            [
                'slug' => 'machinery',
                'parent_slug' => null,
                'sort_order' => 60,
                'icon' => 'cog',
                'translations' => [
                    'en' => ['name' => 'Machinery', 'description' => 'Presses, stitchers, skivers, conveyors, and CNC cutters with setup sheets, guarding, and preventive maintenance windows.'],
                    'pt' => ['name' => 'Maquinaria', 'description' => 'Prensas, máquinas de costura, rebaixadoras, transportadores e CNC com fichas de regulação, proteções e janelas de manutenção preventiva.'],
                    'fr' => ['name' => 'Machines', 'description' => 'Presses, machines à coudre, trancheuses, convoyeurs et découpe numérique avec fiches de réglage et entretien.'],
                    'de' => ['name' => 'Maschinen', 'description' => 'Pressen, Steppmaschinen, Splitmaschinen, Förderer und CNC-Schneider mit Rüstblättern und Wartungsfenstern.'],
                    'it' => ['name' => 'Macchinari', 'description' => 'Presse, macchine per cucire, skiving, trasportatori e CNC con schede di messa in sicurezza e manutenzione.'],
                    'es' => ['name' => 'Maquinaria', 'description' => 'Prensas, máquinas de coser, rebaixadoras, transportadores y CNC con hojas de puesta a punto y mantenimiento.'],
                ],
            ],
            [
                'slug' => 'production',
                'parent_slug' => null,
                'sort_order' => 70,
                'icon' => 'factory',
                'translations' => [
                    'en' => ['name' => 'Production', 'description' => 'Line rhythm: WIP buffers, takt, changeovers, and handoffs between cutting, closing, lasting, and bottoming cells.'],
                    'pt' => ['name' => 'Produção', 'description' => 'Ritmo de linha: stocks de semi-acabados, takt, mudanças de série e passagens entre corte, fecho, moldação e solagem.'],
                    'fr' => ['name' => 'Production', 'description' => 'Rythme de ligne : encours, takt, changements de série et transferts coupe, fermeture, montage et pose semelle.'],
                    'de' => ['name' => 'Produktion', 'description' => 'Linienrhythmus: Zwischenlager, Takt, Rüsten und Übergaben zwischen Zuschneiden, Schließen, Montage und Bottoming.'],
                    'it' => ['name' => 'Produzione', 'description' => 'Ritmo di linea: WIP, takt, cambi serie e passaggi tra taglio, chiusura, montaggio su forma e fondo.'],
                    'es' => ['name' => 'Producción', 'description' => 'Ritmo de línea: WIP, takt, cambios de serie y entregas entre corte, cerrado, horma y solado.'],
                ],
            ],
            [
                'slug' => 'finishing',
                'parent_slug' => 'production',
                'sort_order' => 71,
                'icon' => 'sparkles',
                'translations' => [
                    'en' => ['name' => 'Finishing', 'description' => 'Surface and edge work after assembly: cleaning, creams, wax burnish, edge ink, and final QC before boxing.'],
                    'pt' => ['name' => 'Acabamentos', 'description' => 'Trabalhos de superfície e bordo após montagem: limpeza, cremes, brunimento com cera, tinta de bordo e CQ final antes da caixa.'],
                    'fr' => ['name' => 'Finitions', 'description' => 'Surface et tranche après montage : nettoyage, crèmes, brunissage, encre de tranche et contrôle final avant carton.'],
                    'de' => ['name' => 'Veredelung', 'description' => 'Oberfläche und Kante nach Montage: Reinigung, Cremes, Wachspolitur, Kantenfarbe und Endkontrolle vor Verpackung.'],
                    'it' => ['name' => 'Finitura', 'description' => 'Superficie e bordo dopo montaggio: pulizia, creme, lucidatura cera, inchiostro bordo e controllo finale.'],
                    'es' => ['name' => 'Acabado', 'description' => 'Superficie y canto tras el montaje: limpieza, cremas, brillo con cera, tinta de canto y QC final antes del embalaje.'],
                ],
            ],
            [
                'slug' => 'hardware',
                'parent_slug' => 'materials',
                'sort_order' => 52,
                'icon' => 'wrench',
                'translations' => [
                    'en' => ['name' => 'Hardware', 'description' => 'Eyelets, hooks, rivets, buckles, zips, and shanks with pull-out specs and corrosion class for the intended climate.'],
                    'pt' => ['name' => 'Ferragens', 'description' => 'Ilhós, ganchos, rebites, fechos, fechos de correr e entressolas com especificações de arranque e classe de corrosão para o clima alvo.'],
                    'fr' => ['name' => 'Quincaillerie', 'description' => 'Œillets, crochets, rivets, boucles, fermetures et tirettes avec résistance à l’arrachement et classe de corrosion.'],
                    'de' => ['name' => 'Beschläge', 'description' => 'Ösen, Haken, Nieten, Schließen, Reißverschlüsse und Stege mit Ausreißwerten und Korrosionsklasse.'],
                    'it' => ['name' => 'Ferramenta', 'description' => 'Occhielli, ganci, rivetti, fibbie, lampo e tiranti con prove di estrazione e classe di corrosione.'],
                    'es' => ['name' => 'Herrajes', 'description' => 'Ojetes, ganchos, remaches, hebillas, cremalleras y tiradores con especificación de arranque y clase de corrosión.'],
                ],
            ],
            [
                'slug' => 'quality-control',
                'parent_slug' => 'production',
                'sort_order' => 72,
                'icon' => 'clipboard-check',
                'translations' => [
                    'en' => ['name' => 'Quality control', 'description' => 'Inline checks, AQL sampling, gauge R&R, corrective actions, and traceability from batch codes to customer claims.'],
                    'pt' => ['name' => 'Controlo de qualidade', 'description' => 'Controlos em linha, amostragem AQL, R&R de instrumentos, ações corretivas e rastreabilidade do lote à reclamação de cliente.'],
                    'fr' => ['name' => 'Contrôle qualité', 'description' => 'Contrôles en ligne, AQL, R&R, actions correctives et traçabilité lot vers réclamation client.'],
                    'de' => ['name' => 'Qualitätskontrolle', 'description' => 'Inline-Prüfungen, AQL, Messmittel-R&R, Korrekturmaßnahmen und Rückverfolgbarkeit bis zum Reklamationsfall.'],
                    'it' => ['name' => 'Controllo qualità', 'description' => 'Controlli in linea, AQL, R&R strumenti, azioni correttive e tracciabilità lotto–reclamo.'],
                    'es' => ['name' => 'Control de calidad', 'description' => 'Controles en línea, AQL, R&R de gages, acciones correctivas y trazabilidad del lote al reclamo.'],
                ],
            ],
        ];
    }

    /**
     * @param  array<string, string|array{name: string, description: string, slug?: string}>  $tr
     */
    private function translationSlug(array $row, string $localeCode, array $tr): string
    {
        if (isset($tr['slug']) && is_string($tr['slug']) && $tr['slug'] !== '') {
            return $tr['slug'];
        }

        if ($localeCode === 'en') {
            return $row['slug'];
        }

        $fromName = Str::slug($tr['name']);

        return $fromName !== '' ? $fromName : $row['slug'];
    }

    public function run(): void
    {
        $languages = Language::query()->whereIn('code', Locales::codes())->get()->keyBy('code');

        DB::transaction(function () use ($languages): void {
            $idBySlug = [];

            foreach ($this->definitions() as $row) {
                $domain = Domain::query()->updateOrCreate(
                    ['slug' => $row['slug']],
                    [
                        'parent_id' => null,
                        'icon' => $row['icon'],
                        'sort_order' => $row['sort_order'],
                        'is_active' => true,
                    ],
                );
                $idBySlug[$row['slug']] = $domain->id;
            }

            foreach ($this->definitions() as $row) {
                $childId = $idBySlug[$row['slug']] ?? null;
                if ($childId === null) {
                    continue;
                }
                $parentId = $row['parent_slug'] !== null
                    ? ($idBySlug[$row['parent_slug']] ?? null)
                    : null;
                Domain::query()->whereKey($childId)->update(['parent_id' => $parentId]);
            }

            foreach ($this->definitions() as $row) {
                $domainId = $idBySlug[$row['slug']] ?? null;
                if ($domainId === null) {
                    continue;
                }
                foreach ($row['translations'] as $code => $tr) {
                    $language = $languages->get($code);
                    if ($language === null) {
                        continue;
                    }
                    DomainTranslation::query()->updateOrCreate(
                        [
                            'domain_id' => $domainId,
                            'language_id' => $language->id,
                        ],
                        [
                            'name' => $tr['name'],
                            'description' => $tr['description'],
                            'slug' => $this->translationSlug($row, $code, $tr),
                        ],
                    );
                }
            }
        });
    }
}
