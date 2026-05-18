<?php

namespace App\Support;

/**
 * Semantic media blueprints for footwear construction priority concepts.
 *
 * @phpstan-type LocaleMediaCopy array{title: string, alt: string, caption: string, process_stage: string}
 * @phpstan-type MediaSlot array{
 *   slot_id: string,
 *   collection: 'featured'|'gallery',
 *   variant: string,
 *   kind: 'diagram',
 *   semantic_role: string,
 *   locales: array<string, LocaleMediaCopy>
 * }
 */
final class FootwearConstructionMediaAuthority
{
    /** @var list<string> */
    public const array PRIORITY_CONCEPT_KEYS = [
        'lasting',
        'welt',
        'goodyear-welt',
        'strobel-stitch',
        'outsole',
        'toe-puff',
        'heel-counter',
        'upper',
        'heel-seat',
        'shank-reinforcement',
    ];

    public const int MAX_PROCESS_STILLS = 2;

    public const int MAX_PROCESS_VIDEOS = 2;

    /**
     * @return list<MediaSlot>
     */
    public static function mediaSlotsFor(string $conceptKey): array
    {
        $slots = self::blueprints()[$conceptKey] ?? [];

        return array_values($slots);
    }

    public static function isPriorityConcept(string $conceptKey): bool
    {
        return in_array($conceptKey, self::PRIORITY_CONCEPT_KEYS, true);
    }

    /**
     * @return list<string>
     */
    public static function recommendedVisuals(string $conceptKey, string $locale): array
    {
        $rows = self::recommendedByConcept()[$conceptKey] ?? [];
        if ($rows === []) {
            return [];
        }

        return array_values($rows[$locale] ?? $rows['en'] ?? []);
    }

    /**
     * @return array<string, list<MediaSlot>>
     */
    private static function blueprints(): array
    {
        static $cache = null;
        if (is_array($cache)) {
            return $cache;
        }

        $cache = [];
        foreach (self::PRIORITY_CONCEPT_KEYS as $key) {
            $cache[$key] = [
                self::slot(
                    $key,
                    'featured',
                    'featured',
                    TechnicalFootwearDiagramSvg::VARIANT_WORKFLOW,
                    'workflow',
                    self::workflowCopy($key),
                ),
                self::slot(
                    $key,
                    'cutaway',
                    'gallery',
                    TechnicalFootwearDiagramSvg::VARIANT_CUTAWAY,
                    'construction',
                    self::cutawayCopy($key),
                ),
            ];
        }

        return $cache;
    }

    /**
     * @param  array<string, LocaleMediaCopy>  $locales
     * @return MediaSlot
     */
    private static function slot(
        string $conceptKey,
        string $slotSuffix,
        string $collection,
        string $variant,
        string $semanticRole,
        array $locales,
    ): array {
        return [
            'slot_id' => "{$conceptKey}.{$slotSuffix}",
            'collection' => $collection,
            'variant' => $variant,
            'kind' => 'diagram',
            'semantic_role' => $semanticRole,
            'locales' => $locales,
        ];
    }

    /**
     * @return array<string, array<string, list<string>>>
     */
    private static function recommendedByConcept(): array
    {
        return [
            'lasting' => self::rec(
                ['Toe → side → back-part → seat sequence (structured); toe → side → seat on Strobel', 'Seat release forks to roughing (cemented) or rib prep (welt)'],
                ['Biqueira → lateral → traseiro → assento (estruturado); biqueira → lateral → assento Strobel', 'Libertação do assento: rugosagem ou prep nervura'],
                ['Pointe → latéral → arrière → assise (structuré); pointe → latéral → assise Strobel', 'Libération assise: rugosification ou prep nervure'],
                ['Spitze → Seite → Hinterteil → Sitz (strukturiert); Spitze → Seite → Sitz Strobel', 'Sitzfreigabe: Aufrauen oder Rippenvorbereitung'],
                ['Punta → laterale → posteriore → sede (strutturato); punta → laterale → sede Strobel', 'Rilascio sede: rugosatura o prep nervatura'],
                ['Puntera → lateral → trasero → asiento (estructurado); puntera → lateral → asiento Strobel', 'Liberación asiento: rugosado o prep nervio'],
            ),
            'welt' => self::rec(
                ['Welt/channel cross-section with stitch path', 'Perimeter stitch depth control sequence'],
                ['Secção vira/canal com percurso de ponto', 'Sequência de controlo de profundidade perimetral'],
                ['Coupe trépointe/canal avec trajet de point', 'Séquence contrôle profondeur périphérique'],
                ['Rahmen/Kanal-Querschnitt mit Stichpfad', 'Umfangs-Stichtiefenkontrolle'],
                ['Sezione guardolo/canale con percorso punto', 'Sequenza controllo profondità perimetrale'],
                ['Sección cerco/canal con recorrido de puntada', 'Secuencia control profundidad perimetral'],
            ),
            'goodyear-welt' => self::rec(
                ['Rib → holdfast → cork → channel stitch chain', 'Layered Goodyear cross-section'],
                ['Cadeia nervura → retenção → cortiça → costura canal', 'Secção Goodyear em camadas'],
                ['Chaîne nervure → point d’ancrage → liège → couture canal', 'Coupe Goodyear en couches avec verrouillage semelle'],
                ['Kette Rippe → Holdfast → Korkfüllung → Kanalnaht', 'Goodyear-Schichtquerschnitt mit Verriegelungsschritten'],
                ['Catena nervatura → holdfast → sughero → cucitura canale', 'Sezione Goodyear a strati'],
                ['Cadena nervio → anclaje → corcho → costura canal', 'Sección Goodyear en capas'],
            ),
            'strobel-stitch' => self::rec(
                ['Strobel board-to-upper seam checkpoints', 'Handoff to lasting and cemented bottoming'],
                ['Pontos de controlo costura base-cabedal', 'Passagem para moldação e fundo colado'],
                ['Points de contrôle couture base-tige', 'Transfert vers montage et fond collé'],
                ['Strobel-Board-Naht-Kontrollpunkte', 'Übergabe Aufziehen und geklebtes Bottoming'],
                ['Checkpoint cucitura base-tomaia', 'Passaggio a montaggio e fondo cementato'],
                ['Controles costura base-corte', 'Pase a montado y fondo cementado'],
            ),
            'outsole' => self::rec(
                ['Bottoming route fork: cemented press vs welt chain vs Blake through-stitch', 'Perimeter pressure / bond-line zone map by route'],
                ['Sequência prensa colada vs bloqueio vira', 'Mapa pressão perimetral / linha de cola'],
                ['Presse collée vs verrouillage trépointe', 'Carte pression périmétrique / ligne de collage'],
                ['Pressung vs Rahmennäht-Verriegelung', 'Umfangsdruck- / Klebefugen-Karte'],
                ['Pressa cementata vs blocco guardolo', 'Mappa pressione perimetrale / linea incollaggio'],
                ['Prensa cementada vs bloqueo cerco', 'Mapa presión perimetral / línea de pegado'],
            ),
            'toe-puff' => self::rec(
                ['Activation curve vs forepart lasting gate', 'Toe reinforcement cross-section'],
                ['Curva de ativação vs gate moldação frente', 'Secção reforço biqueira'],
                ['Courbe activation vs gate montage avant-pied', 'Coupe renfort pointe'],
                ['Aktivierungskurve vs Vorfuss-Aufzieh-Gate', 'Zehenverstärkungs-Querschnitt'],
                ['Curva attivazione vs gate montaggio avampiede', 'Sezione rinforzo punta'],
                ['Curva activación vs gate montado antepié', 'Sección refuerzo puntera'],
            ),
            'heel-counter' => self::rec(
                ['Counter mold / skive transition diagram', 'Rearfoot capture at seat-lasting'],
                ['Diagrama moldagem / transição rebaixo contraforte', 'Captura retropé na moldação assento'],
                ['Schéma moulage / transition parage contrefort', 'Capture arrière-pied au montage assise'],
                ['Formung / Abschrägungsdiagramm Fersenkappe', 'Hinterfußaufnahme beim Sitzaufziehen'],
                ['Diagramma stampaggio / transizione scarnitura contrafforte', 'Cattura retropiede al montaggio sede'],
                ['Diagrama moldeado / transición rebajado contrafuerte', 'Captura retropié en montado asiento'],
            ),
            'upper' => self::rec(
                ['Panel closing workflow with reinforcement nodes', 'Upper anatomy / seam-class map'],
                ['Fluxo fecho com nós de reforço', 'Mapa anatomia / classe de costura'],
                ['Flux piquage avec nœuds de renfort', 'Carte anatomie / classe de couture'],
                ['Schließworkflow mit Verstärkungsknoten', 'Anatomie- / Nahtklassenkarte'],
                ['Flusso chiusura con nodi rinforzo', 'Mappa anatomia / classe cucitura'],
                ['Flujo aparado con nodos de refuerzo', 'Mapa anatomía / clase de costura'],
            ),
            'heel-seat' => self::rec(
                ['Contour match at seat release gates press map and rib land', 'Rearfoot platform load-transfer vs heel-slip risk'],
                ['Sequência correspondência contorno e nivelamento', 'Diagrama transferência carga plataforma retropé'],
                ['Séquence correspondance contour et nivellement', 'Schéma transfert charge plateforme arrière-pied'],
                ['Konturabgleich- und Nivelliersequenz', 'Lastübertragung Hinterfußplattform'],
                ['Sequenza match contorno e livellamento', 'Diagramma trasferimento carico piattaforma retropiede'],
                ['Secuencia ajuste contorno y nivelado', 'Diagrama transferencia carga plataforma retropié'],
            ),
            'shank-reinforcement' => self::rec(
                ['Shank seat in waist / footbed stack', 'Torsion gate before sockliner lay-down'],
                ['Assento alma na cintura / pacote palmilha', 'Gate torção antes forro palmilha'],
                ['Siège cambrion dans empilement première', 'Gate torsion avant pose première propreté'],
                ['Gelenkfeder-Sitz in Taillen-/Fußbett-Stack', 'Torsionsgate vor Decksohle'],
                ['Sede cambrione in stack soletta', 'Gate torsione prima sottopiede'],
                ['Asiento cambrillón en paquete plantilla', 'Gate torsión antes plantilla acabado'],
            ),
            'insole-board' => self::rec(
                ['Board flatness and rib-land planarity before rib attach', 'Channel depth map reference section'],
                ['Planicidade do cartão e assento da nervura antes da aplicação', 'Secção de referência do mapa de profundidade de canal'],
                ['Planéité première et assise nervure avant pose', 'Coupe de référence carte profondeur rainure'],
                ['Plattenebenheit und Rippenland vor Rippenanbringung', 'Referenzschnitt Kanaltiefenkarte'],
                ['Planarità cartone e appoggio nervatura prima applicazione', 'Sezione riferimento mappa profondità canale'],
                ['Planicidad del cartón y asiento del nervio antes de aplicación', 'Sección de referencia del mapa de profundidad de canal'],
            ),
            'gemming-rib' => self::rec(
                ['Rib height vs holdfast bite map', 'Gemming lock before cork fill release'],
                ['Altura da nervura vs mapa de mordida holdfast', 'Fecho de gemming antes da libertação da cortiça'],
                ['Hauteur nervure vs carte de prise holdfast', 'Verrouillage gemmage avant liège'],
                ['Rippenhöhe vs Holdfast-Aufnahmekarte', 'Gemming-Schluss vor Korkfreigabe'],
                ['Altezza nervatura vs mappa presa holdfast', 'Chiusura gemming prima riempimento sughero'],
                ['Altura del nervio vs mapa de mordida holdfast', 'Cierre gemming antes de liberación de corcho'],
            ),
            'welt-channel' => self::rec(
                ['Groove depth and wall-angle vs stitch crown', 'Waist curve channel alignment check'],
                ['Profundidade do sulco e ângulo da parede vs coroa do ponto', 'Verificação de alinhamento do canal na cintura'],
                ['Profondeur rainure et angle paroi vs couronne point', 'Contrôle alignement canal au cambrion'],
                ['Nuttiefe und Wandwinkel vs Stichkrone', 'Kanalausrichtung Tailleenkurve'],
                ['Profondità scanalatura e angolo parete vs corona punto', 'Controllo allineamento canale in vita'],
                ['Profundidad de surco y ángulo de pared vs corona de puntada', 'Control de alineación de canal en cintura'],
            ),
            'filler-cork' => self::rec(
                ['Cork density map and void-pocket audit', 'Waist cavity before edge-ink read'],
                ['Mapa de densidade da cortiça e auditoria de bolsas vazias', 'Cavidade da cintura antes da tinta de bordo'],
                ['Carte densité liège et audit poches vides', 'Cavité cambrion avant encre de tranche'],
                ['Korkdichtekarte und Hohlraum-Audit', 'Taillenhohlraum vor Kantenfarb-Lesung'],
                ['Mappa densità sughero e audit tasche vuote', 'Cavità vita prima lettura inchiostro bordo'],
                ['Mapa de densidad de corcho y auditoría de bolsas vacías', 'Cavidad de cintura antes de tinta de canto'],
            ),
            'bond-line' => self::rec(
                ['Zone-coded void morphology after press', 'Peel path vs spread/activation dwell log'],
                ['Morfologia de vazios por zona após prensa', 'Percurso de peel vs registo de permanência'],
                ['Morphologie vides par zone après presse', 'Trajet pelage vs journal maintien activation'],
                ['Zonenkodierte Hohlstellen-Morphologie nach Presse', 'Peel-Pfad vs Aktivierungs-Verweilprotokoll'],
                ['Morfologia vuoti per zona dopo pressa', 'Percorso peel vs registro permanenza attivazione'],
                ['Morfología de vacíos por zona tras prensa', 'Trayectoria de peel vs registro de permanencia'],
            ),
            'sidewall-trimming' => self::rec(
                ['Flash trim before edge ink', 'Clean sidewall land vs masked cold peel'],
                ['Recorte de flash antes da tinta de bordo', 'Continuidade do assento lateral vs mascaramento de lift'],
                ['Parage flash avant encre tranche', 'Continuité assise flanc vs masquage soulèvement collage'],
                ['Blitzbeschnitt vor Kantenfarbe', 'Seitenwand-Land-Kontinuität vs Klebelösungs-Maskierung'],
                ['Rifilo flash prima inchiostro bordo', 'Continuità appoggio parete vs mascheramento sollevamento colla'],
                ['Recorte de flash antes de tinta de canto', 'Continuidad asiento lateral vs enmascaramiento de lift'],
            ),
            'edge-ink-build' => self::rec(
                ['Ink build and waist symmetry read', 'Channel-lip color step vs crown alignment'],
                ['Construção de camadas de tinta vs leitura de simetria da cintura', 'Degrau de cor no lábio do canal sinaliza desalinhamento'],
                ['Montage couches encre vs lecture symétrie cambrion', 'Rupture teinte bord rainure signale couronne'],
                ['Farbschichtaufbau vs Taillensymmetrie-Lesung', 'Farbsprung Nutlippe signalisiert Kronenfehlstellung'],
                ['Costruzione strati inchiostro vs lettura simmetria vita', 'Gradino colore labbro canale segnala corona'],
                ['Construcción de capas de tinta vs lectura de simetría de cintura', 'Salto de color en labio de canal señala corona'],
            ),
        ];
    }

    /**
     * @param  list<string>  $en
     * @param  list<string>  $pt
     * @param  list<string>  $fr
     * @param  list<string>  $de
     * @param  list<string>  $it
     * @param  list<string>  $es
     * @return array<string, list<string>>
     */
    private static function rec(array $en, array $pt, array $fr, array $de, array $it, array $es): array
    {
        return compact('en', 'pt', 'fr', 'de', 'it', 'es');
    }

    /**
     * @return array<string, LocaleMediaCopy>
     */
    private static function workflowCopy(string $key): array
    {
        return match ($key) {
            'lasting' => self::loc(
                'Lasting station sequence',
                'Schematic: toe, side, back-part (structured lines), and seat pull before bottoming fork',
                'Lasting sequence through seat release to cemented roughing or welt prep.',
                'Lasting line',
                'Sequência de postos de moldação',
                'Esquema: biqueira, lateral, traseiro (linhas estruturadas) e assento antes do desvio de fundo',
                'Moldação até libertação do assento: rota colada ou prep de vira.',
                'Linha de moldação',
                'Séquence postes montage sur forme',
                'Schéma : traction pointe, latéral et assise avant transfert fond',
                'Montage jusqu’à libération d’assise : route collée ou trépointe.',
                'Montage-Linie',
                'Aufzieh-Stationsequenz',
                'Schema: Spitzen-, Seiten- und Sitzstation vor Bottoming-Übergabe',
                'Aufziehen bis Sitzfreigabe: Kleben oder Rahmen.',
                'Aufziehlinie',
                'Sequenza stazioni montaggio su forma',
                'Schema: trazione punta, laterale e sede prima del passaggio al fondo',
                'Montaggio fino al rilascio sede: cementato o guardolo.',
                'Linea montaggio',
                'Secuencia estaciones de montado',
                'Esquema: tracción puntera, lateral y asiento antes del pase a fondo',
                'Flujo de montado — anclaje antepié hasta bifurcación de ruta en liberación de asiento (cementado o cerco).',
                'Línea de montado',
            ),
            'welt' => self::loc(
                'Welt stitch workflow',
                'Schematic: channel prep, holdfast lock, and perimeter stitch path',
                'Welt preparation through outsole lock on stitched routes.',
                'Welt bench',
                'Fluxo de costura de vira',
                'Esquema: prep de canal, bloqueio de retenção e percurso de ponto perimetral',
                'Preparação de vira até bloqueio da sola em rotas costuradas.',
                'Bancada de vira',
                'Flux couture trépointe',
                'Schéma : préparation canal, verrouillage holdfast, trajet périphérique',
                'Préparation trépointe jusqu’au verrouillage semelle.',
                'Atelier trépointe',
                'Rahmennaht-Workflow',
                'Schema: Rippenvorbereitung, Holdfast-Verriegelung, Umfangsstichpfad',
                'Rahmenleiste: Rippe und Holdfast-Aufnahme bis Kanalnaht und Sohlenverriegelung — Penetrationskarte als Freigabe-Gate.',
                'Rahmenbank',
                'Flusso cucitura guardolo',
                'Schema: prep canale, blocco holdfast, percorso perimetrale',
                'Preparazione guardolo fino al blocco suola.',
                'Banco guardolo',
                'Flujo costura de cerco',
                'Esquema: prep canal, bloqueo anclaje, recorrido perimetral',
                'Preparación de cerco hasta bloqueo de suela.',
                'Banco de cerco',
            ),
            'goodyear-welt' => self::loc(
                'Goodyear welt chain',
                'Schematic: rib attach, gemming, holdfast, cork fill, channel stitch',
                'Goodyear route from rib prep through holdfast bite gate, cork fill, and waist profile to channel lock.',
                'Welt prep',
                'Cadeia Goodyear',
                'Esquema: nervura, gemming, retenção, cortiça, costura em canal',
                'Rota Goodyear da prep de nervura na palmilha ao bloqueio oculto da sola.',
                'Prep de vira',
                'Chaîne Goodyear',
                'Schéma : pose nervure, gemmage, point d’ancrage, liège, couture canal',
                'Route Goodyear : nervure, prise d’ancrage, liège et profilage cambrion jusqu’au verrouillage semelle.',
                'Préparation trépointe',
                'Goodyear-Kette',
                'Schema: Rippenanbringung, Gemming, Holdfast, Kork, Kanalnaht',
                'Goodyear-Route von Rippe über Holdfast-Aufnahme, Kork und Taillenprofil bis Kanalverriegelung.',
                'Rahmenvorbereitung',
                'Catena Goodyear',
                'Schema: nervatura, gemming, holdfast, sughero, cucitura canale',
                'Route Goodyear da nervatura a blocco suola via holdfast, sughero e profilo vita.',
                'Prep guardolo',
                'Cadena Goodyear',
                'Esquema: nervio, gemming, anclaje, corcho, costura canal',
                'Ruta Goodyear desde prep de nervio hasta bloqueo de suela.',
                'Prep de cerco',
            ),
            'strobel-stitch' => self::loc(
                'Strobel to bottoming handoff',
                'Schematic: board stitch, lasting entry, cemented bottoming route',
                'Lightweight route from Strobel closure to sole attachment.',
                'Strobel line',
                'Passagem Strobel para fundo',
                'Esquema: costura base, entrada em moldação, rota colada',
                'Rota leve do fecho Strobel à fixação de sola.',
                'Linha Strobel',
                'Transfert Strobel vers fond',
                'Schéma : couture base, entrée montage, route collée',
                'Route légère de la fermeture Strobel à la fixation semelle.',
                'Ligne Strobel',
                'Strobel-zu-Bottoming-Übergabe',
                'Schema: Boardnaht, Aufzieheinstieg, geklebte Route',
                'Leichtbau-Route von Strobel bis Sohlenanbindung.',
                'Strobel-Linie',
                'Passaggio Strobel al fondo',
                'Schema: cucitura base, ingresso montaggio, route cementata',
                'Route leggera da chiusura Strobel a fissaggio suola.',
                'Linea Strobel',
                'Pase Strobel a fondo',
                'Esquema: costura base, entrada montado, ruta cementada',
                'Ruta ligera desde cierre Strobel a fijación de suela.',
                'Línea Strobel',
            ),
            'outsole' => self::loc(
                'Outsole attachment routes',
                'Schematic: cemented press chain and welt channel-stitch lock',
                'Dual-route bottoming — activation/press vs stitched lock.',
                'Bottoming',
                'Rotas de fixação de sola',
                'Esquema: cadeia de prensa colada e bloqueio por costura em canal',
                'Fundo com dupla rota — ativação/prensa versus bloqueio costurado.',
                'Montagem de fundo',
                'Routes fixation semelle',
                'Schéma : presse collée et verrouillage couture canal',
                'Montage de fond double route — pressage vs couture.',
                'Montage de fond',
                'Sohlenanbindungs-Routen',
                'Schema: Pressung und Kanalnaht-Verriegelung',
                'Duale Bottoming-Route — Pressen vs genähte Verriegelung.',
                'Bottoming',
                'Route fissaggio suola',
                'Schema: pressa cementata e blocco cucitura canale',
                'Fondo doppia route — pressa vs blocco cucito.',
                'Fondo',
                'Rutas fijación suela',
                'Esquema: prensa cementada y bloqueo costura canal',
                'Fondo doble ruta — prensado vs bloqueo cosido.',
                'Fondo',
            ),
            'toe-puff' => self::loc(
                'Toe puff process chain',
                'Schematic: heat activation, forepart lasting, toe-spring check',
                'Reinforcement timing before lasting pulls forepart volume.',
                'Forepart prep',
                'Cadeia da ponteira',
                'Esquema: ativação térmica, moldação da frente, controlo toe spring',
                'Momento do reforço antes da tração de volume na frente.',
                'Prep da frente',
                'Chaîne ponteira',
                'Schéma : activation thermique, montage avant-pied, contrôle toe spring',
                'Calage renfort avant traction du volume avant-pied.',
                'Préparation avant-pied',
                'Zehenverstärkungs-Kette',
                'Schema: Wärmeaktivierung, Vorfussaufziehen, Toe-Spring-Check',
                'Verstärkungszeitpunkt vor Vorfussvolumen.',
                'Vorfussvorbereitung',
                'Catena puntale',
                'Schema: attivazione termica, montaggio avampiede, controllo toe spring',
                'Tempistica rinforzo prima del volume avampiede.',
                'Prep avampiede',
                'Cadena puntera',
                'Esquema: activación térmica, montado antepié, control toe spring',
                'Momento del refuerzo antes del volumen de antepié.',
                'Prep antepié',
            ),
            'heel-counter' => self::loc(
                'Heel counter workflow',
                'Schematic: edge skive, mold activation, seat-lasting capture',
                'Rearfoot lock sequence before bottoming load transfer.',
                'Back-part',
                'Fluxo do contraforte',
                'Esquema: rebaixo, moldagem, captura na moldação do assento',
                'Sequência de bloqueio do retropé antes da transferência de carga ao fundo.',
                'Traseiro',
                'Flux contrefort',
                'Schéma : parage, moulage, capture montage assise',
                'Verrouillage arrière-pied avant transfert de charge fond.',
                'Atelier arrière',
                'Fersenkappen-Workflow',
                'Schema: Abschrägung, Formung, Sitzaufzieh-Aufnahme',
                'Hinterfußverriegelung vor Bottoming-Lastübertragung.',
                'Hinterkappenbereich',
                'Flusso contrafforte',
                'Schema: scarnitura, stampaggio, cattura montaggio sede',
                'Blocco retropiede prima del trasferimento carico al fondo.',
                'Reparto tallone',
                'Flujo contrafuerte',
                'Esquema: rebajado, moldeado, captura montado asiento',
                'Bloqueo retropié antes de transferencia de carga al fondo.',
                'Área trasero',
            ),
            'upper' => self::loc(
                'Upper assembly workflow',
                'Schematic: closing sequence with reinforcement and lasting release',
                'Panel alignment and seam-class gates before pull operations.',
                'Closing',
                'Fluxo de fecho do cabedal',
                'Esquema: sequência de fecho com reforços e libertação para moldação',
                'Alinhamento de painéis e gates de classe de costura antes das trações.',
                'Fecho',
                'Flux assemblage tige',
                'Schéma : séquence piquage, renforts, libération montage',
                'Alignement panneaux et gates couture avant montage.',
                'Piquage',
                'Schaftmontage-Workflow',
                'Schema: Schließfolge, Verstärkungen, Aufziehfreigabe',
                'Panelausrichtung und Nahtklassen vor Aufziehen.',
                'Schließerei',
                'Flusso assemblaggio tomaia',
                'Schema: chiusura, rinforzi, rilascio montaggio',
                'Allineamento pannelli e gate cuciture prima del montaggio.',
                'Giunteria',
                'Flujo ensamblaje corte',
                'Esquema: cierre, refuerzos, liberación montado',
                'Alineación paneles y gates de costura antes del montado.',
                'Aparado',
            ),
            'heel-seat' => self::loc(
                'Heel-seat prep sequence',
                'Schematic: contour match, leveling, attachment readiness gate',
                'Rearfoot platform alignment before sole lock.',
                'Heel room',
                'Sequência de prep do assento do calcanhar',
                'Esquema: correspondência de contorno, nivelamento, gate de fixação',
                'Alinhamento da plataforma do retropé antes do bloqueio da sola.',
                'Sala do calcanhar',
                'Séquence préparation assise talon',
                'Schéma : correspondance contour, nivellement, gate fixation',
                'Alignement plateforme arrière-pied avant verrouillage semelle.',
                'Atelier talon',
                'Fersensitz-Vorbereitungssequenz',
                'Schema: Konturabgleich, Nivellierung, Anbindungsgate',
                'Hinterfußplattform vor Sohlenverriegelung.',
                'Absatzbereich',
                'Sequenza prep sede tallone',
                'Schema: match contorno, livellamento, gate fissaggio',
                'Allineamento piattaforma retropiede prima blocco suola.',
                'Reparto tacchi',
                'Secuencia prep asiento talón',
                'Esquema: ajuste contorno, nivelado, gate de fijación',
                'Alineación plataforma retropié antes de bloqueo de suela.',
                'Área de talón',
            ),
            'shank-reinforcement' => self::loc(
                'Shank placement workflow',
                'Schematic: waist-zone seat, torsion check, internal footbed stack',
                'Structural waist reinforcement before wear trials.',
                'Internal assembly',
                'Fluxo de colocação da alma',
                'Esquema: assento na cintura, controlo de torção, pacote de palmilha interno',
                'Reforço estrutural da cintura antes dos ensaios de uso.',
                'Montagem interna',
                'Flux pose cambrion',
                'Schéma : siège cambrion, contrôle torsion, empilement première',
                'Renfort structurel cambrion avant essais de port.',
                'Montage interne',
                'Gelenkfeder-Platzierungsworkflow',
                'Schema: Taillensitz, Torsionsprüfung, Fußbett-Stack',
                'Strukturelle Taillenverstärkung vor Trageversuchen.',
                'Innenmontage',
                'Flusso posa cambrione',
                'Schema: sede vita, controllo torsione, stack soletta',
                'Rinforzo strutturale vita prima dei wear test.',
                'Assemblaggio interno',
                'Flujo colocación cambrillón',
                'Esquema: asiento cintura, control torsión, paquete plantilla',
                'Refuerzo estructural de cintura antes de pruebas de uso.',
                'Ensamblaje interno',
            ),
            default => self::loc(
                'Process schematic',
                'Industrial workflow schematic for '.$key,
                'Technical process visualization.',
                'Production',
                'Esquema de processo',
                'Esquema industrial de fluxo para '.$key,
                'Visualização técnica do processo.',
                'Produção',
                'Schéma de processus',
                'Schéma industriel pour '.$key,
                'Visualisation technique du processus.',
                'Production',
                'Prozessschema',
                'Industrielles Workflow-Schema für '.$key,
                'Technische Prozessvisualisierung.',
                'Produktion',
                'Schema di processo',
                'Schema workflow industriale per '.$key,
                'Visualizzazione tecnica del processo.',
                'Produzione',
                'Esquema de proceso',
                'Esquema industrial para '.$key,
                'Visualización técnica del proceso.',
                'Producción',
            ),
        };
    }

    /**
     * @return array<string, LocaleMediaCopy>
     */
    private static function cutawayCopy(string $key): array
    {
        return match ($key) {
            'lasting' => self::loc(
                'Forepart tension section',
                'Cross-section: upper margin tuck and toe-spring geometry at toe station',
                'How forepart pull preloads waist and seat stations.',
                'Toe lasting',
                'Secção de tensão da frente',
                'Corte: dobra da margem e geometria de toe spring no posto da biqueira',
                'Como a tração da frente pré-carrega cintura e assento.',
                'Moldação da biqueira',
                'Coupe tension avant-pied',
                'Coupe : rabat de marge et géométrie toe spring au poste pointe',
                'Précharge du tirage avant-pied sur cambrion et assise.',
                'Montage pointe',
                'Vorfuss-Spannungsquerschnitt',
                'Querschnitt: Randfalte und Toe-Spring am Spitzenposten',
                'Wie Spitzenzug Taillee und Sitz vorbelastet.',
                'Spitzenaufziehen',
                'Sezione tensione avampiede',
                'Sezione: piega margine e geometria toe spring alla stazione punta',
                'Come il tiro punta precarica vita e sede.',
                'Montaggio punta',
                'Sección tensión antepié',
                'Sección: pliegue margen y geometría toe spring en estación puntera',
                'Cómo la tracción de puntera precarga cintura y asiento.',
                'Montado puntera',
            ),
            default => self::workflowCopy($key),
        };
    }

    /**
     * @return array<string, LocaleMediaCopy>
     */
    private static function loc(
        string $enTitle,
        string $enAlt,
        string $enCaption,
        string $enStage,
        string $ptTitle,
        string $ptAlt,
        string $ptCaption,
        string $ptStage,
        string $frTitle,
        string $frAlt,
        string $frCaption,
        string $frStage,
        string $deTitle,
        string $deAlt,
        string $deCaption,
        string $deStage,
        string $itTitle,
        string $itAlt,
        string $itCaption,
        string $itStage,
        string $esTitle,
        string $esAlt,
        string $esCaption,
        string $esStage,
    ): array {
        return [
            'en' => ['title' => $enTitle, 'alt' => $enAlt, 'caption' => $enCaption, 'process_stage' => $enStage],
            'pt' => ['title' => $ptTitle, 'alt' => $ptAlt, 'caption' => $ptCaption, 'process_stage' => $ptStage],
            'fr' => ['title' => $frTitle, 'alt' => $frAlt, 'caption' => $frCaption, 'process_stage' => $frStage],
            'de' => ['title' => $deTitle, 'alt' => $deAlt, 'caption' => $deCaption, 'process_stage' => $deStage],
            'it' => ['title' => $itTitle, 'alt' => $itAlt, 'caption' => $itCaption, 'process_stage' => $itStage],
            'es' => ['title' => $esTitle, 'alt' => $esAlt, 'caption' => $esCaption, 'process_stage' => $esStage],
        ];
    }

}
