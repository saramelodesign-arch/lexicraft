<?php

use App\Support\Editorial\WorkflowStatus;
use Illuminate\Support\Str;

/**
 * Footwear Construction deep-production dataset.
 *
 * @return array{concepts: list<array<string, mixed>>, relations: list<array{from: string, to: string, type: string}>}
 */
return (function (): array {
    $locales = ['en', 'pt', 'fr', 'de', 'it', 'es'];

    $shortTemplates = [
        'en' => ':term is a footwear construction term used to :focus.',
        'pt' => ':term é um termo de construção de calçado usado para :focus.',
        'fr' => ':term est un terme de construction de chaussure utilisé pour :focus.',
        'de' => ':term ist ein Begriff der Schuhkonstruktion für :focus.',
        'it' => ':term è un termine di costruzione calzaturiera usato per :focus.',
        'es' => ':term es un término de construcción de calzado usado para :focus.',
    ];

    $fullTemplates = [
        'en' => 'In footwear construction, :term is controlled during :stage. Engineering teams define material stack, machine setup, and tolerance windows so the operation remains stable across size runs, shift changes, and inline quality audits.',
        'pt' => 'Na construção de calçado, :term é controlado durante :stage. As equipas de engenharia definem pilha de materiais, regulação de máquina e janelas de tolerância para manter estabilidade entre tamanhos, turnos e auditorias em linha.',
        'fr' => 'En construction de chaussure, :term est piloté pendant :stage. Les équipes techniques définissent l’empilement matière, le réglage machine et les tolérances pour garder une exécution stable sur tailles, équipes et audits ligne.',
        'de' => 'In der Schuhkonstruktion wird :term während :stage gesteuert. Technikteams definieren Materialaufbau, Maschineneinstellung und Toleranzfenster, damit der Ablauf über Größen, Schichten und Inline-Audits stabil bleibt.',
        'it' => 'Nella costruzione calzaturiera, :term viene controllato durante :stage. I team tecnici definiscono stack materiali, setup macchina e finestre di tolleranza per garantire stabilità tra taglie, turni e audit in linea.',
        'es' => 'En construcción de calzado, :term se controla durante :stage. Los equipos técnicos definen stack de materiales, ajuste de máquina y ventanas de tolerancia para mantener estabilidad entre tallas, turnos y auditorías en línea.',
    ];

    $exampleTemplates = [
        'en' => 'The line supervisor reviewed :term settings during pre-production to keep fit, shape, and bond quality aligned with the technical package.',
        'pt' => 'O supervisor de linha reviu os parâmetros de :term no pré-arranque para manter ajuste, forma e qualidade de união conforme a ficha técnica.',
        'fr' => 'Le chef de ligne a revu les réglages de :term en pré-série pour maintenir chaussant, forme et qualité d’assemblage selon le dossier technique.',
        'de' => 'Die Linienleitung prüfte die Einstellungen für :term im Vorserienlauf, um Passform, Formbild und Verbundqualität gemäß Technischer Spezifikation zu halten.',
        'it' => 'Il responsabile linea ha verificato i parametri di :term in pre-serie per mantenere calzata, forma e qualità di unione secondo scheda tecnica.',
        'es' => 'El supervisor de línea revisó los parámetros de :term en pre-serie para mantener ajuste, forma y calidad de unión según la ficha técnica.',
    ];

    $coreConceptKeys = [
        'lasting', 'shoe-last', 'upper', 'vamp', 'quarter', 'lining', 'toe-puff', 'heel-counter', 'counter-molding',
        'toe-box', 'foxing', 'welt', 'outsole', 'midsole', 'insole', 'shank', 'rand', 'heel-seat', 'toe-spring',
        'ball-girth', 'vamp-break', 'lasting-allowance', 'side-lasting', 'toe-lasting', 'back-part-lasting',
        'seat-lasting', 'strobel-stitch', 'strobel-board', 'insole-board', 'rib-attaching', 'channeling',
        'lockstitch-seam', 'edge-folding', 'skiving', 'roughing', 'primer-coat', 'cementing', 'heat-activation',
        'sole-pressing', 'bond-line',
    ];

    $coreShortPatterns = [
        'en' => [
            ':term is a core footwear-construction control point with direct impact on fit, geometry, and assembly stability.',
            ':term defines a critical step in industrial shoe manufacturing where process variation quickly becomes product variation.',
            ':term is a technical anchor in footwear production used to stabilize quality, repeatability, and downstream performance.',
        ],
        'pt' => [
            ':term é um ponto de controlo central da construção de calçado com impacto direto no calce, na geometria e na estabilidade da montagem.',
            ':term define uma etapa crítica no fabrico industrial de calçado onde a variação de processo se transforma rapidamente em variação de produto.',
            ':term é uma âncora técnica da produção de calçado para estabilizar qualidade, repetibilidade e desempenho a jusante.',
        ],
        'fr' => [
            ':term est un point de contrôle central en construction de chaussure avec impact direct sur chaussant, géométrie et stabilité d’assemblage.',
            ':term définit une étape critique du process industriel chaussure où la variabilité process devient rapidement variabilité produit.',
            ':term est un ancrage technique de production chaussure pour stabiliser qualité, répétabilité et performance aval.',
        ],
        'de' => [
            ':term ist ein zentraler Kontrollpunkt der Schuhkonstruktion mit direktem Einfluss auf Passform, Geometrie und Montagestabilität.',
            ':term bezeichnet einen kritischen Schritt der industriellen Schuhfertigung, bei dem Prozessstreuung schnell zu Produktstreuung wird.',
            ':term ist ein technischer Anker der Schuhproduktion zur Stabilisierung von Qualität, Reproduzierbarkeit und nachgelagerter Leistung.',
        ],
        'it' => [
            ':term è un punto di controllo centrale della costruzione calzaturiera con impatto diretto su calzata, geometria e stabilità di assemblaggio.',
            ':term definisce una fase critica della produzione industriale calzaturiera dove la variazione di processo diventa rapidamente variazione di prodotto.',
            ':term è un ancoraggio tecnico della produzione calzaturiera per stabilizzare qualità, ripetibilità e performance a valle.',
        ],
        'es' => [
            ':term es un punto de control central de la construcción de calzado con impacto directo en ajuste, geometría y estabilidad de ensamblaje.',
            ':term define una etapa crítica de la fabricación industrial de calzado donde la variación de proceso se convierte rápidamente en variación de producto.',
            ':term es un ancla técnica de producción de calzado para estabilizar calidad, repetibilidad y desempeño aguas abajo.',
        ],
    ];

    $coreFullPatterns = [
        'en' => [
            'In industrial footwear manufacturing, :term is managed as a controlled operation with explicit setup windows, in-line checks, and containment rules to keep geometry, comfort targets, and assembly yield stable across size runs.',
            ':term acts as a process-critical interface between design intent and factory execution; when settings drift, defects propagate into lasting, bottoming, and final quality release. Mature teams therefore track it with measurable criteria and shift-level reaction plans.',
            ':term is treated as a production discipline rather than a single task, combining machine setup, operator method, and audit thresholds so the concept remains repeatable under real takt pressure.',
        ],
        'pt' => [
            'No fabrico industrial de calçado, :term é gerido como operação controlada com janelas de regulação, verificações em linha e regras de contenção para manter estabilidade geométrica, conforto e rendimento de montagem.',
            ':term funciona como interface crítica entre intenção de design e execução fabril; quando os parâmetros derivam, os defeitos propagam-se para moldação, montagem de fundo e libertação final de qualidade.',
            ':term é tratado como disciplina de produção, combinando setup de máquina, método operacional e limites de auditoria para garantir repetibilidade sob pressão real de takt.',
        ],
        'fr' => [
            'En production industrielle chaussure, :term est piloté comme opération contrôlée avec fenêtres de réglage, contrôles en ligne et règles de contention pour stabiliser géométrie, confort et rendement d’assemblage.',
            ':term est une interface critique entre intention design et exécution atelier; quand les paramètres dérivent, les défauts se propagent vers montage sur forme, fond et libération qualité finale.',
            ':term est géré comme discipline de production combinant réglage machine, méthode opérateur et seuils d’audit pour maintenir la répétabilité sous contrainte de cadence.',
        ],
        'de' => [
            'In der industriellen Schuhfertigung wird :term als kontrollierter Prozessschritt mit klaren Einstellfenstern, Inline-Prüfungen und Containment-Regeln geführt, um Geometrie, Komfortziele und Montageausbeute stabil zu halten.',
            ':term bildet eine kritische Schnittstelle zwischen Designabsicht und Werksausführung; bei Parameterdrift propagieren Fehler in Aufziehen, Bottoming und Endfreigabe.',
            ':term wird als Produktionsdisziplin verstanden, die Maschinensetup, Bedienmethode und Auditschwellen kombiniert, damit Wiederholbarkeit unter realem Takt erhalten bleibt.',
        ],
        'it' => [
            'Nella produzione industriale calzaturiera, :term è gestito come operazione controllata con finestre di settaggio, controlli in linea e regole di contenimento per mantenere stabilità di geometria, comfort e resa di assemblaggio.',
            ':term è un’interfaccia critica tra intento di design ed esecuzione di fabbrica; quando i parametri deragliano, i difetti si propagano in montaggio su forma, fondo e rilascio qualità finale.',
            ':term viene trattato come disciplina produttiva che combina setup macchina, metodo operatore e soglie di audit per garantire ripetibilità sotto reale pressione di takt.',
        ],
        'es' => [
            'En fabricación industrial de calzado, :term se gestiona como operación controlada con ventanas de ajuste, controles en línea y reglas de contención para mantener estable geometría, confort y rendimiento de ensamblaje.',
            ':term actúa como interfaz crítica entre intención de diseño y ejecución de planta; cuando los parámetros derivan, los defectos se propagan a montado, fondo y liberación final de calidad.',
            ':term se trata como disciplina de producción que combina setup de máquina, método operativo y umbrales de auditoría para sostener repetibilidad bajo presión real de takt.',
        ],
    ];

    $coreExamplePatterns = [
        'en' => [
            'Process engineering opened a containment ticket when :term drift at :stage produced repeatable asymmetry on left-right pairs before final release.',
            'The shift lead adjusted :term parameters at :stage after inline checks linked micro-variation to downstream rework and reduced first-pass yield.',
            'Training notes for :stage flagged :term as a red-point operation after audit data showed direct correlation with fit complaints and edge failures.',
            'During hourly audit at :stage, operators logged :term deviation and triggered immediate corrective setup to protect bond and geometry consistency.',
        ],
        'pt' => [
            'A engenharia de processo abriu contenção quando a deriva de :term em :stage gerou assimetria repetitiva em pares esquerdo-direito antes da libertação final.',
            'O líder de turno ajustou parâmetros de :term em :stage após verificações em linha ligarem microvariações a retrabalho e queda de first-pass yield.',
            'As notas de formação de :stage marcaram :term como operação crítica depois de dados de auditoria mostrarem correlação direta com queixas de calce e falhas de bordo.',
            'Na auditoria horária de :stage, os operadores registaram desvio em :term e ativaram correção imediata de setup para proteger consistência de colagem e geometria.',
        ],
        'fr' => [
            'L’ingénierie process a ouvert une contention quand la dérive de :term sur :stage a généré une asymétrie répétable des paires gauche-droite avant libération finale.',
            'Le chef d’équipe a ajusté les paramètres :term sur :stage après que les contrôles ligne aient relié la micro-variation au retouche et à la baisse du first-pass yield.',
            'Les notes de formation de :stage ont classé :term en opération critique après corrélation audit avec plaintes de chaussant et défauts de rive.',
            'Lors de l’audit horaire sur :stage, les opérateurs ont journalisé une dérive :term et déclenché une correction immédiate de réglage pour protéger collage et géométrie.',
        ],
        'de' => [
            'Die Prozesstechnik eröffnete ein Containment, nachdem :term-Drift in :stage reproduzierbare Links-Rechts-Asymmetrien vor der Endfreigabe erzeugte.',
            'Die Schichtleitung justierte :term-Parameter in :stage, nachdem Inline-Prüfungen Mikroabweichungen mit Nacharbeit und sinkender First-Pass-Quote verknüpften.',
            'Schulungsunterlagen für :stage kennzeichneten :term als Rotpunkt-Operation, da Auditdaten eine direkte Korrelation mit Passformreklamationen und Kantenfehlern zeigten.',
            'Im stündlichen Audit von :stage protokollierten Bediener eine :term-Abweichung und lösten sofortige Setup-Korrektur zum Schutz von Verbund- und Geometriekonstanz aus.',
        ],
        'it' => [
            'L’ingegneria di processo ha aperto contenimento quando la deriva di :term in :stage ha generato asimmetria ripetibile tra paia sinistro-destro prima del rilascio finale.',
            'Il capoturno ha regolato i parametri di :term in :stage dopo che i controlli in linea hanno collegato micro-variazioni a rilavorazioni e calo del first-pass yield.',
            'Le note di training di :stage hanno classificato :term come operazione critica dopo che i dati audit hanno mostrato correlazione diretta con reclami di calzata e difetti bordo.',
            'Durante l’audit orario in :stage, gli operatori hanno registrato deriva su :term e attivato correzione immediata setup per proteggere coerenza di legame e geometria.',
        ],
        'es' => [
            'Ingeniería de proceso abrió contención cuando la deriva de :term en :stage generó asimetría repetible en pares izquierdo-derecho antes de la liberación final.',
            'El jefe de turno ajustó parámetros de :term en :stage tras controles en línea que vincularon microvariación con retrabajo y caída de first-pass yield.',
            'Las notas de formación de :stage marcaron :term como operación crítica al mostrar los datos de auditoría correlación directa con quejas de ajuste y fallos de canto.',
            'Durante la auditoría horaria en :stage, operarios registraron desviación en :term y activaron corrección inmediata de setup para proteger consistencia de unión y geometría.',
        ],
    ];

    $coreTerminologyStatusByKey = [
        'lasting' => 'validated',
        'shoe-last' => 'validated',
        'upper' => 'validated',
        'vamp' => 'reviewed',
        'quarter' => 'reviewed',
        'lining' => 'reviewed',
        'toe-puff' => 'validated',
        'heel-counter' => 'validated',
        'counter-molding' => 'reviewed',
        'toe-box' => 'reviewed',
        'foxing' => 'reviewed',
        'welt' => 'validated',
        'outsole' => 'validated',
        'midsole' => 'validated',
        'insole' => 'validated',
        'shank' => 'validated',
        'rand' => 'reviewed',
        'heel-seat' => 'validated',
        'toe-spring' => 'reviewed',
        'ball-girth' => 'reviewed',
        'vamp-break' => 'reviewed',
        'lasting-allowance' => 'reviewed',
        'side-lasting' => 'validated',
        'toe-lasting' => 'validated',
        'back-part-lasting' => 'validated',
        'seat-lasting' => 'validated',
        'strobel-stitch' => 'validated',
        'strobel-board' => 'reviewed',
        'insole-board' => 'reviewed',
        'rib-attaching' => 'reviewed',
        'channeling' => 'reviewed',
        'lockstitch-seam' => 'validated',
        'edge-folding' => 'reviewed',
        'skiving' => 'validated',
        'roughing' => 'validated',
        'primer-coat' => 'validated',
        'cementing' => 'validated',
        'heat-activation' => 'validated',
        'sole-pressing' => 'validated',
        'bond-line' => 'validated',
    ];

    $coreEditorialNotesByKey = [
        'lasting' => 'Lasting-line hub: route fork at release (Strobel-cement vs welt prep) depends on margin stress and symmetry logged at seat handoff.',
        'side-lasting' => 'Lasting-chain station 2 after toe; next post is back-part (structured) or seat (many Strobel lines); twist gauge gates transfer.',
        'seat-lasting' => 'Terminal lasting gate: Goodyear release routes to rib prep; cemented release must reach roughing-primer inside open-time—contour mismatch returns to counter room, not seat rework.',
        'sole-pressing' => 'Irreversible cemented lock after tunnel: cold heel-seat quadrants contain here or become field peel—heel dwell often exceeds forepart on cupsole waist cavities.',
        'toe-lasting' => 'First lasting-chain station anchoring forepart volume and toe-spring before side and seat pulls.',
        'rib-attaching' => 'Welt-prep entry on insole board; rib bond quality gates channel cut and holdfast penetration.',
        'channeling' => 'Groove prep on insole board before gemming: depth and wall angle set holdfast and channel-stitch penetration limits.',
        'sidewall-trimming' => 'Post-lock flash trim before edge build: perimeter geometry must be clean or edge-ink read masks bond lift at sidewall.',
        'insole-board' => 'Rigid lasting platform on welted routes: flatness and rib land geometry gate rib attach and channel depth maps.',
        'gemming-rib' => 'Structural rib formed after attach: height and lock integrity gate holdfast bite—shallow rib cannot be recovered at channel stitch.',
        'welt-channel' => 'Machined groove geometry gates stitch sink and resole access; wandering depth propagates as waist crown failure.',
        'filler-cork' => 'Goodyear cavity fill after holdfast: density map and void audit precede waist bench and edge-ink read.',
        'bond-line' => 'Physical trace of spread–activation–press discipline; zone-coded void morphology separates process drift from compound failure.',
        'counter-molding' => 'Hot counter form after flat back-part stack; contour mismatch returns to reinforcement room, not seat rework.',
        'edge-ink-build' => 'Perimeter edge build after sole lock: layer cure and waist symmetry read confirm bench profile before shipment audit.',
        'roughing' => 'Cemented entry after seat release: shallow roughing starves primer wetting; over-roughing collapses margins—topography audit blocks primer queue.',
        'primer-coat' => 'Surface-energy gate between roughing and spread: under-dry traps solvent; over-dry delivers dead tack at tunnel—climate band governs flash-off.',
        'cementing' => 'Cemented bottoming step 1 of 3: spread completes before tunnel queue; open-time clock starts here.',
        'heat-activation' => 'Tunnel step 2/3: IR energy profile must match stack—temperature drift shows as cold heel-seat peel or scorched forepart before press.',
        'holdfast-stitch' => 'Goodyear mechanical lock after gemming: penetration failure or skipped lock loops block cork fill and predict channel reopening at waist.',
        'channel-stitching' => 'Terminal welt lock: crown above groove lip or shallow bite cannot be pressed back—selected when resole access outweighs cement throughput.',
        'lockstitch-seam' => 'Pre-lasting closing gate: SPI/thread drift at throat-quarter propagates to grin under pull on every bottoming route.',
        'waist-shaping' => 'Goodyear bench profile between cork fill and channel stitch: voids under waist drive flex squeak and asymmetric edge-ink read.',
        'shank-reinforcement' => 'Waist-window structural seat: longitudinal drift relocates gait hinge; proud shank bridges air in cemented press or blocks cork pour on Goodyear.',
        'strobel-board' => 'Strobel assembly foundation before board-to-upper stitch and lasting entry.',
        'bottoming' => 'Post-lasting handoff zone; route at release selects cemented press chain or welt prep chain.',
        'shoe-last' => 'Master geometric reference used across grading, pattern release, and bottom tooling alignment.',
        'upper' => 'Core assembly envelope controlling visual quality and fit readiness before lasting.',
        'vamp' => 'Forepart comfort-break control node; monitor crease behavior by material family.',
        'quarter' => 'Rearfoot stabilization shell; balance check required before volume release.',
        'toe-puff' => 'Thermo-mechanical forepart gate: activation curve must match leather family before toe-lasting or profile collapses under pull.',
        'heel-counter' => 'Rearfoot lock component; mismatch quickly drives heel slip and collar distortion.',
        'welt' => 'Serviceability-critical interface for stitched constructions and resoling longevity.',
        'outsole' => 'Primary ground-contact system with durability and traction authority implications.',
        'insole' => 'Foot-interface anchor affecting lasting capture and internal volume perception.',
        'midsole' => 'Load-management layer governing cushioning curve and platform stability.',
        'strobel-stitch' => 'Board-to-upper closure before pull; feed-sync and seam gap at toe-spring radius gate lasting entry; lightweight cemented branch diverges from board-lasted at upper closure.',
        'foxing' => 'Perimeter reinforcement and visual continuity node in sidewall-heavy constructions.',
        'lining' => 'Internal comfort/friction layer with direct impact on wear stability.',
        'heel-seat' => 'Rearfoot platform reference: contour match and planarity at seat release gate cemented press maps and welt rib land.',
        'shank' => 'Torsional-stability element controlling waist behavior in heeled and structured builds.',
        'goodyear-welt' => 'Welted bottoming route: rib prep through channel stitch; diverges from Blake at lasting release.',
        'blake-stitch' => 'Through-stitch bottoming after lasting; no cork cavity—selected when resole bench is not required.',
        'cemented-construction' => 'Adhesive bottoming route after lasting; open-time and press-map gates replace stitch penetration.',
        'board-lasted-construction' => 'Rigid insole-board lasting architecture; bottoming may still fork to cemented or welt prep.',
        'stitchdown-construction' => 'Turned-out margin perimeter stitch; field-service edge without welt rib prep.',
        'cupsole-cementing' => 'Cemented sub-route into pre-formed cup cavity; heel-pocket dwell split on press map.',
        'back-part-lasting' => 'Lasting-chain station 3 of 4 on structured lines (after side, before seat); sets counter wrap before seat capture.',
    ];

    /** Native editorial notes for priority workflow concepts (indexed per locale in search). */
    $localizedEditorialNotesByKey = [
        'lasting' => [
            'en' => 'Lasting-line hub: seat release commits bottoming route—Strobel-cement vs welt prep depends on margin stress and symmetry logged at handoff.',
            'de' => 'Aufzieh-Hub: Sitzfreigabe legt die Bottoming-Route fest—Strobel-Kleben vs Rahmenvorbereitung nach Randspannung und Symmetrie am Übergabeprotokoll.',
            'fr' => 'Hub montage: la libération d’assise engage la route de fond—Strobel collé ou prep trépointe selon tension de marge et symétrie au transfert.',
            'it' => 'Hub montaggio: il rilascio sede impegna la filiera fondo—Strobel cementato o prep guardolo in base a tensione margine e simmetria al passaggio.',
            'pt' => 'Hub de moldação: a libertação do assento fixa a rota de fundo—Strobel colado ou prep de vira conforme tensão de margem e simetria na passagem.',
            'es' => 'Hub de montado: la liberación de asiento fija la ruta de fondo—Strobel cementado o prep de cerco según tensión de margen y simetría en el traspaso.',
        ],
        'bottoming' => [
            'en' => 'Post-lasting handoff: release ticket selects cemented press chain or welt prep chain—route mismatch cannot be corrected after spread or rib attach.',
            'de' => 'Übergabe nach Aufziehen: Freigabeschein wählt Klebepresskette oder Rahmenvorbereitung—Routenfehler ist nach Auftrag bzw. Rippenanbringung nicht korrigierbar.',
            'fr' => 'Transfert après montage: la fiche de libération choisit chaîne presse collée ou prep trépointe—erreur de route non rattrapable après dépôt ou pose nervure.',
            'it' => 'Passaggio post-montaggio: la scheda di rilascio seleziona filiera pressa cementata o prep guardolo—errore di route non recuperabile dopo spalmatura o nervatura.',
            'pt' => 'Passagem pós-moldação: a ficha de libertação escolhe cadeia de prensa colada ou prep de vira—erro de rota irreversível após aplicação ou nervura.',
            'es' => 'Traspaso post-montado: la ficha de liberación elige cadena de prensa cementada o prep de cerco—error de ruta irreversible tras extendido o nervio.',
        ],
        'seat-lasting' => [
            'en' => 'Terminal lasting gate: Goodyear release to rib prep; cemented release must enter roughing-primer inside open-time—contour mismatch returns to counter room, not seat rework.',
            'de' => 'Abschluss-Gate Aufziehen: Goodyear-Freigabe in Rippenvorbereitung; Kleberoute muss Aufrauen-Primer innerhalb der Offenzeit—Konturfehler ins Kappenroom, keine Sitz-Nacharbeit.',
            'fr' => 'Poste terminal montage: fiche Goodyear vers nervure/gemmage; fiche collée vers rugosification-primaire dans le temps ouvert—défaut de contour retour contrefort, pas reprise assise.',
            'it' => 'Gate terminale montaggio: rilascio Goodyear a prep nervatura; rilascio cementato a rugosatura-primer entro latenza—disallineamento contorno torna al contrafforte, non al sede.',
            'pt' => 'Gate terminal de moldação: libertação Goodyear para prep de nervura; libertação colada para rugosagem-primário no tempo aberto—desvio de contorno volta ao contraforte, não ao assento.',
            'es' => 'Gate terminal de montado: liberación Goodyear a prep de nervio; liberación cementada a rugosado-imprimación en tiempo abierto—desajuste de contorno vuelve al contrafuerte, no al asiento.',
        ],
        'roughing' => [
            'en' => 'Cemented entry after seat release: shallow profile starves primer wetting; over-profile collapses margins—topography audit blocks primer queue.',
            'de' => 'Einstieg geklebte Route nach Sitzfreigabe: flaches Aufrauen verhungert Primerbenetzung; zu tief kollabiert Randgeometrie—Topografie-Audit sperrt Primer-Warteschlange.',
            'fr' => 'Entrée fond collé après libération d’assise: rugosification insuffisante affame le primaire; surplus dégrade la marge—audit topographie bloque la file primaire.',
            'it' => 'Ingresso fondo cementato dopo rilascio sede: rugosatura superficiale affama il primer; eccesso collassa il margine—audit topografia blocca coda primer.',
            'pt' => 'Entrada de fundo colado após libertação do assento: rugosagem rasa priva molhabilidade do primário; excesso colapsa a margem—auditoria topográfica bloqueia fila de primário.',
            'es' => 'Entrada de fondo cementado tras liberación de asiento: rugosado superficial priva humectación del primer; exceso colapsa el margen—auditoría topográfica bloquea cola de imprimación.',
        ],
        'primer-coat' => [
            'en' => 'Surface-energy gate between roughing and spread: under-dry traps solvent; over-dry kills tack before spread—climate band sets flash-off envelope.',
            'de' => 'Oberflächenenergie-Gate zwischen Aufrauen und Auftrag: Untertrocknung bindet Lösungsmittel; Übertrocknung liefert toten Tack—Klimaband steuert Ausdampfzeit.',
            'fr' => 'Gate énergie de surface entre rugosification et dépôt: sous-séchage piège solvant; sur-séchage tue le tack avant dépôt—bande climat fixe le flash-off.',
            'it' => 'Gate energia superficiale tra rugosatura e spalmatura: sotto-asciugatura intrappola solvente; sovra-asciugatura uccide il tack prima della spalmatura—fascia climatica definisce flash-off.',
            'pt' => 'Gate de energia superficial entre rugosagem e aplicação: subseco retém solvente; sobreseco mata o tack antes da aplicação—faixa climática define tempo de flash-off.',
            'es' => 'Gate de energía superficial entre rugosado y extendido: subsecado retiene disolvente; sobresecado mata el tack antes del extendido—banda climática fija el flash-off.',
        ],
        'cementing' => [
            'en' => 'Cemented bottoming step 1 of 3: spread mass and transfer latency start the open-time clock before tunnel queue.',
            'de' => 'Geklebtes Bottoming Schritt 1/3: Auftragsmasse und Transferlatenz starten die Offenzeit-Uhr vor der Tunnel-Warteschlange.',
            'fr' => 'Fond collé étape 1/3: grammage de dépôt et latence de transfert démarrent le compteur temps ouvert avant file tunnel.',
            'it' => 'Fondo cementato passo 1/3: grammatura di spalmatura e latenza di trasferimento avviano il contatore tempo aperto prima della coda tunnel.',
            'pt' => 'Fundo colado passo 1/3: gramagem de aplicação e latência de transferência iniciam o relógio de tempo aberto antes da fila do túnel.',
            'es' => 'Fondo cementado paso 1/3: gramaje de extendido y latencia de transferencia arrancan el reloj de tiempo abierto antes de la cola del túnel.',
        ],
        'heat-activation' => [
            'en' => 'Tunnel step 2/3: IR profile must match stack—cold drift yields heel-seat peel; hot drift scorches forepart before perimeter wetting.',
            'de' => 'Tunnel Schritt 2/3: IR-Profil muss zum Stack passen—Kaltabweichung ergibt Fersensitz-Peel; Heissabweichung versengt Vorfuss vor Umfangsbenetzung.',
            'fr' => 'Tunnel étape 2/3: profil IR aligné sur l’empilement—dérive froide donne pelage assise talon; dérive chaude brûle l’avant-pied avant mouillage périmétrique.',
            'it' => 'Tunnel passo 2/3: profilo IR allineato allo stack—deriva al freddo produce peel sede tallone; deriva al caldo brucia avampiede prima bagnatura perimetrale.',
            'pt' => 'Túnel passo 2/3: perfil IR alinhado ao pacote—deriva a frio gera peel no assento do calcanhar; deriva a quente queima frente antes da molhabilidade perimetral.',
            'es' => 'Túnel paso 2/3: perfil IR alineado al paquete—deriva en frío produce peel en asiento de talón; deriva en calor quema antepié antes del humectado perimetral.',
        ],
        'sole-pressing' => [
            'en' => 'Irreversible cemented lock after tunnel: cold heel-seat zones contain here or become field peel—heel dwell often exceeds forepart on cupsole cavities.',
            'de' => 'Irreversibles Klebeverriegeln nach Tunnel: kalte Fersensitz-Zonen werden hier eingegrenzt oder werden Feldausblähungen—Fersenverweilzeit oft länger als Vorfuss bei Cupsole-Hohlräumen.',
            'fr' => 'Verrouillage collé irréversible après tunnel: zones froides assise talon à contenir ici ou pelage terrain—maintien talon souvent supérieur à l’avant-pied en cupsole.',
            'it' => 'Blocco cementato irreversibile dopo tunnel: zone fredde sede tallone si contengono qui o diventano peel in campo—permanenza tallone spesso superiore all’avampiede in cupsole.',
            'pt' => 'Bloqueio colado irreversível após túnel: zonas frias do assento do calcanhar contêm-se aqui ou viram peel em campo—permanência do calcanhar costuma exceder a frente em cavidades cupsole.',
            'es' => 'Bloqueo cementado irreversible tras túnel: zonas frías del asiento de talón se contienen aquí o se convierten en peel de campo—permanencia de talón suele superar antepié en cavidades cupsole.',
        ],
        'holdfast-stitch' => [
            'en' => 'Goodyear mechanical lock after gemming: shallow bite or skipped lock loops block cork fill and predict channel reopening at waist.',
            'de' => 'Mechanisches Goodyear-Verriegeln nach Gemming: flache Aufnahme oder ausgelassene Schlingen sperren Korkfüllung und kündigen Kanalwiederöffnung in der Taillee an.',
            'fr' => 'Verrouillage mécanique Goodyear après gemmage: prise faible ou boucles manquantes bloquent le liège et annoncent réouverture de canal au cambrion.',
            'it' => 'Blocco meccanico Goodyear dopo gemming: presa ridotta o salti di chiusura bloccano il sughero e anticipano riapertura canale in vita.',
            'pt' => 'Bloqueio mecânico Goodyear após gemming: mordida rasa ou saltos de fecho bloqueiam a cortiça e antecipam reabertura de canal na cintura.',
            'es' => 'Bloqueo mecánico Goodyear tras gemming: mordida superficial o saltos de cierre bloquean el corcho y anticipan reapertura de canal en cintura.',
        ],
        'channel-stitching' => [
            'en' => 'Terminal welt lock: crown above groove lip or shallow bite cannot be recovered—selected when resole access outweighs cement throughput.',
            'de' => 'Terminales Rahmenverriegeln: Kronenhöhe über Nutlippe oder flache Aufnahme sind nicht nachziehbar—Route wenn Wiederbesohlung wichtiger als Klebedurchsatz ist.',
            'fr' => 'Verrouillage trépointe terminal: couronne au-dessus du bord de rainure ou prise faible non rattrapable—route quand le ressemelage prime sur le débit collé.',
            'it' => 'Blocco guardolo terminale: corona sopra il labbro scanalatura o presa insufficiente non recuperabili—filiere quando la risuolatura supera il throughput cementato.',
            'pt' => 'Bloqueio terminal da vira: coroa acima do lábio do sulco ou mordida rasa sem recuperação—rota quando ressolagem supera throughput colado.',
            'es' => 'Bloqueo terminal de cerco: corona sobre labio de surco o mordida superficial sin recuperación—ruta cuando el resolado supera el throughput cementado.',
        ],
        'lockstitch-seam' => [
            'en' => 'Pre-lasting closing gate: SPI and thread-balance drift at throat-quarter propagates to grin under pull on every bottoming route.',
            'de' => 'Schließ-Gate vor Aufziehen: SPI- und Garnbalance-Drift an Rist-Quartier führt beim Zug zum Aufgehen auf jeder Bottoming-Route.',
            'fr' => 'Gate fermeture avant montage: dérive SPI et équilibre fil gorge-quartier se propage en ouverture au tirage sur toute route de fond.',
            'it' => 'Gate chiusura pre-montaggio: deriva SPI e bilanciamento filo gola-quartiere si propaga in grinning al tiro su ogni filiera fondo.',
            'pt' => 'Gate de fecho pré-moldação: deriva de SPI e equilíbrio de fio garganta-quarto propaga abertura na tração em qualquer rota de fundo.',
            'es' => 'Gate de cierre pre-montado: deriva de SPI y balance de hilo garganta-cuarto se propaga a apertura en tracción en cualquier ruta de fondo.',
        ],
        'waist-shaping' => [
            'en' => 'Goodyear bench between cork fill and channel stitch: voids under waist drive flex squeak and asymmetric edge-ink read.',
            'de' => 'Goodyear-Bank zwischen Korkfüllung und Kanalnaht: Hohlstellen unter der Taillee erzeugen Flex-Knarren und asymmetrische Kantenfarb-Lesung.',
            'fr' => 'Banc Goodyear entre liège et couture canal: poches vides sous cambrion donnent grincement en flexion et lecture encre de tranche asymétrique.',
            'it' => 'Banco Goodyear tra sughero e cucitura in canale: tasche vuote sotto vita generano scricchiolio in flessione e lettura inchiostro bordo asimmetrica.',
            'pt' => 'Bancada Goodyear entre cortiça e costura em canal: vazios sob a cintura originam ranger em flexão e leitura assimétrica de tinta de bordo.',
            'es' => 'Banco Goodyear entre corcho y costura en canal: vacíos bajo cintura generan chirridos en flexión y lectura asimétrica de tinta de canto.',
        ],
        'shank-reinforcement' => [
            'en' => 'Waist-window structural seat: longitudinal drift relocates gait hinge; proud seat bridges air in press or blocks cork pour on Goodyear.',
            'de' => 'Struktursitz im Taillenfenster: Längsdrift verschiebt den Gangknick; überstehender Schanksitz presst Luftbrücken oder blockiert Korkgießen bei Goodyear.',
            'fr' => 'Assise structurelle fenêtre cambrion: dérive longitudinale déplace la charnière de marche; assise haute crée pont d’air en presse ou bloque coulée liège Goodyear.',
            'it' => 'Sede strutturale finestra vita: deriva longitudinale sposta la cerniera del passo; sede sporgente crea ponti d’aria in pressa o blocca colata sughero Goodyear.',
            'pt' => 'Assento estrutural na janela da cintura: deriva longitudinal desloca a articulação da marcha; assento alto cria pontes de ar na prensa ou bloqueia vertido de cortiça Goodyear.',
            'es' => 'Asiento estructural en ventana de cintura: deriva longitudinal desplaza la bisagra de marcha; asiento orgulloso crea puentes de aire en prensa o bloquea vertido de corcho Goodyear.',
        ],
        'goodyear-welt' => [
            'en' => 'Welted route after seat release: rib → holdfast → cork → waist profile → channel lock—each station inherits prior geometry; penetration maps are hard gates.',
            'de' => 'Rahmenroute nach Sitzfreigabe: Rippe → Holdfast → Kork → Taillenprofil → Kanalverriegelung—jeder Posten erbt die Vorlage; Penetrationskarten sind harte Gates.',
            'fr' => 'Route trépointée après libération d’assise: nervure → holdfast → liège → profilage cambrion → verrouillage canal—chaque poste hérite la géométrie amont; cartes de pénétration sont gates durs.',
            'it' => 'Filiere guardolo dopo rilascio sede: nervatura → tenuta → sughero → profilo vita → blocco canale—ogni posto eredita la geometria a monte; mappe penetrazione sono gate rigidi.',
            'pt' => 'Rota com vira após libertação do assento: nervura → retenção → cortiça → perfil da cintura → bloqueio em canal—cada posto herda a geometria anterior; mapas de penetração são gates duros.',
            'es' => 'Ruta de cerco tras liberación de asiento: nervio → anclaje → corcho → perfil de cintura → bloqueo en canal—cada puesto hereda la geometría previa; mapas de penetración son gates duros.',
        ],
        'cemented-construction' => [
            'en' => 'Throughput route after lasting: open-time, tunnel profile, and press map replace stitch recovery—roughing through cure window governs release.',
            'de' => 'Durchsatzroute nach Aufziehen: Offenzeit, Tunnelprofil und Presskarte ersetzen Nahtrückholung—Aufrauen bis Aushärtefenster steuern die Freigabe.',
            'fr' => 'Route débit après montage: temps ouvert, profil tunnel et carte presse remplacent le rattrapage couture—rugosification jusqu’à fenêtre de cure gouvernent la libération.',
            'it' => 'Filiere throughput dopo montaggio: tempo aperto, profilo tunnel e mappa pressa sostituiscono recupero cucitura—rugosatura fino a finestra di cura governano il rilascio.',
            'pt' => 'Rota de throughput após moldação: tempo aberto, perfil de túnel e mapa de prensa substituem recuperação por costura—rugosagem até janela de cura governam a libertação.',
            'es' => 'Ruta de throughput tras montado: tiempo abierto, perfil de túnel y mapa de prensa sustituyen recuperación por costura—rugosado hasta ventana de curado gobiernan la liberación.',
        ],
        'strobel-stitch' => [
            'en' => 'Board-to-upper closure before pull: toe-spring radius gap and feed sync gate lasting entry; lightweight cemented branch diverges at seat release.',
            'de' => 'Board-zu-Schaft-Schließung vor Zug: Spaltmaß Zehenfeder-Radius und Vorschub-Sync gate Aufzieheinstieg; leichte Kleberoute zweigt an Sitzfreigabe ab.',
            'fr' => 'Fermeture base-tige avant traction: jeu au rayon toe spring et synchro avance gate l’entrée montage; branche collée légère bifurque à la libération d’assise.',
            'it' => 'Chiusura base-tomaia prima della trazione: gioco raggio toe spring e sincro avanzamento gate ingresso montaggio; ramo cementato leggero devia al rilascio sede.',
            'pt' => 'Fecho base-cabedal antes da tração: folga no raio do toe spring e sincronismo de avanço gate entrada na moldação; ramo colado leve bifurca na libertação do assento.',
            'es' => 'Cierre base-corte antes de tracción: holgura en radio toe spring y sincronía de avance gate entrada de montado; rama cementada ligera bifurca en liberación de asiento.',
        ],
        'insole-board' => [
            'en' => 'Welted platform: flatness and rib land steer rib attach; warp skews waist channel depth.',
            'de' => 'Rahmen-Brandsohle: Ebenheit und Rippenland steuern die Anbringung; Verzug verzerrt die Kanaltiefe in der Taillee.',
            'fr' => 'Première rigide: planéité et assise nervure avant pose; voile décale la rainure au cambrion.',
            'it' => 'Soletta rigida: planarità e appoggio nervatura; deformazione altera la profondità canale in vita.',
            'pt' => 'Cartão rígido: planicidade e assento da nervura; empeno desvia o sulco na cintura.',
            'es' => 'Cartón rígido: planicidad y asiento del nervio; alabeo desvía el surco en cintura.',
        ],
        'gemming-rib' => [
            'en' => 'Rib after attach: height and lock set holdfast bite; under-formed rib fails at channel lock.',
            'de' => 'Rippe nach Anbringung: Höhe und Schluss setzen Holdfast-Aufnahme; unterformt nicht nachziehbar an der Kanalnaht.',
            'fr' => 'Nervure après pose: hauteur et verrouillage fixent la prise; sous-formée, non rattrapable au canal.',
            'it' => 'Nervatura dopo applicazione: altezza e chiusura definiscono la presa; sottoformata non recupera al canale.',
            'pt' => 'Nervura após aplicação: altura e fecho definem a mordida; subformada falha no bloqueio em canal.',
            'es' => 'Nervio tras aplicación: altura y cierre fijan la mordida; subformado falla en el bloqueo de canal.',
        ],
        'welt-channel' => [
            'en' => 'Board groove: depth, wall angle, and rib alignment set stitch sink; shallow groove fails at sole lock.',
            'de' => 'Brandsohlennut: Tiefe, Wandwinkel und Rippe setzen Stichsenkung; flache Nut scheitert an der Sohlenverriegelung.',
            'fr' => 'Rainure première: profondeur, paroi et nervure fixent l’enfoncement; rainure faible échoue au verrouillage semelle.',
            'it' => 'Scanalatura soletta: profondità, parete e nervatura definiscono affondamento; canale superficiale fallisce al blocco suola.',
            'pt' => 'Sulco no cartão: profundidade, parede e nervura definem afundamento; sulco raso falha no bloqueio da sola.',
            'es' => 'Surco en cartón: profundidad, pared y nervio fijan hundimiento; surco superficial falla en bloqueo de suela.',
        ],
        'filler-cork' => [
            'en' => 'Fill after holdfast: density and void check before waist bench; pockets cause flex squeak and uneven edge ink.',
            'de' => 'Füllung nach Holdfast: Dichte und Hohlraum vor Taillenbank; Lücken erzeugen Knarren und ungleichmäßige Kantenfarbe.',
            'fr' => 'Liège après holdfast: densité et vides avant banc cambrion; poches donnent grincement et encre irrégulière.',
            'it' => 'Riempimento dopo tenuta: densità e vuoti prima del banco vita; tasche causano scricchiolio e bordo irregolare.',
            'pt' => 'Enchimento após retenção: densidade e vazios antes da bancada; bolsas causam ranger e bordo irregular.',
            'es' => 'Relleno tras anclaje: densidad y vacíos antes del banco; bolsas provocan chirridos y canto irregular.',
        ],
        'bond-line' => [
            'en' => 'Post-press interface: zoned voids and peel shape separate process drift from compound failure.',
            'de' => 'Klebefuge nach Presse: Hohlstellen und Peel-Form trennen Prozessabweichung vom Compound.',
            'fr' => 'Interface après presse: vides par zone et pelage distinguent dérive process du compound.',
            'it' => 'Interfaccia post-pressa: vuoti per zona e peel separano deriva di processo dal compound.',
            'pt' => 'Interface pós-prensa: vazios por zona e peel separam deriva de processo do composto.',
            'es' => 'Interfaz post-prensa: vacíos por zona y peel separan deriva de proceso del compuesto.',
        ],
        'counter-molding' => [
            'en' => 'Hot counter molding after flat back-part stack: skive-to-quarter transition must match width grade—contour mismatch returns to counter room, not seat pull.',
            'de' => 'Heissformung Fersenkappe nach flachem Hinterkappen-Stack: Schaerfuebergang muss zur Breitengröße passen—Konturfehler zurück ins Kappenroom, kein Sitz-Zug.',
            'fr' => 'Moulage chaud contrefort après empilement arrière à plat: transition parage-quartier selon grade largeur—défaut contour retour salle contrefort, pas reprise assise.',
            'it' => 'Stampaggio a caldo contrafforte dopo stack posteriore piatto: transizione scarnitura-quartiere per larghezza—disallineamento contorno torna in sala contrafforte, non al tiro sede.',
            'pt' => 'Moldagem a quente do contraforte após pacote traseiro plano: transição de rebaixo-quarto por largura—desvio de contorno volta à sala de contraforte, não à tração do assento.',
            'es' => 'Moldeado en caliente del contrafuerte tras paquete trasero plano: transición rebajado-cuarto por ancho—desajuste de contorno vuelve a sala de contrafuerte, no al tiraje de asiento.',
        ],
        'heel-seat' => [
            'en' => 'Rearfoot at lasting: contour and planarity steer press map and rib land; rock reads as heel slip after lock.',
            'de' => 'Hinterfuß beim Aufziehen: Kontur und Planparität steuern Presskarte und Rippenland; Wippen wird Fersenrutsch nach Verriegelung.',
            'fr' => 'Arrière-pied au montage: contour et planéité pilotent presse et nervure; bascule devient glissement talon.',
            'it' => 'Retropiede al montaggio: contorno e planarità guidano pressa e nervatura; sede instabile diventa scivolamento tallone.',
            'pt' => 'Retropé na moldação: contorno e planicidade orientam prensa e nervura; oscilação vira deslizamento após bloqueio.',
            'es' => 'Retropié en montado: contorno y planicidad orientan prensa y nervio; basculación se lee como deslizamiento tras bloqueo.',
        ],
        'toe-puff' => [
            'en' => 'Forepart reinforcement gate: activation window must match leather family before toe-lasting—under-activated puff collapses profile; over-activation stiffens break line.',
            'de' => 'Vorfuss-Verstärkungs-Gate: Aktivierungsfenster muss zur Lederfamilie passen vor Spitzenaufziehen—unteraktivierte Kappe kollabiert Profil; Überaktivierung versteift Bruchlinie.',
            'fr' => 'Gate renfort avant-pied: fenêtre d’activation alignée sur famille cuir avant montage pointe—sous-activation affaisse profil; sur-activation rigidifie ligne de flexion.',
            'it' => 'Gate rinforzo avampiede: finestra attivazione allineata alla famiglia pelle prima montaggio punta—sotto-attivazione collassa profilo; sovra-attivazione irrigidisce linea di flessione.',
            'pt' => 'Gate de reforço da frente: janela de ativação alinhada à família de couro antes da moldação da biqueira—subativação colapsa perfil; sobre-ativação endurece linha de quebra.',
            'es' => 'Gate de refuerzo de antepié: ventana de activación alineada a familia de cuero antes de montado de puntera—subactivación colapsa perfil; sobreactivación endurece línea de quiebre.',
        ],
        'heel-counter-reinforcement' => [
            'en' => 'Flat back-part stack before molding: reinforcement height per width grade gates counter curvature—excess stiffness lifts collar; insufficient stack collapses heel pocket.',
            'de' => 'Flacher Hinterkappen-Stack vor Formung: Verstärkungshöhe je Breitengröße gate Kappenkrümmung—zu steif hebt Kragen; zu dünn kollabiert Fersenbecken.',
            'fr' => 'Empilement arrière à plat avant moulage: hauteur renfort par grade largeur gate courbure contrefort—rigidité excessive relève col; pile insuffisant affaisse assise talon.',
            'it' => 'Stack posteriore piatto prima stampaggio: altezza rinforzo per larghezza gate curvatura contrafforte—eccesso rigidezza solleva collarino; stack insufficiente collassa sede tallone.',
            'pt' => 'Pacote traseiro plano antes da moldagem: altura do reforço por largura gate curvatura do contraforte—rigidez excessiva levanta colarinho; pacote fino colapsa assento do calcanhar.',
            'es' => 'Paquete trasero plano antes de moldeado: altura de refuerzo por ancho gate curvatura del contrafuerte—exceso de rigidez levanta collar; paquete fino colapsa asiento de talón.',
        ],
        'edge-ink-build' => [
            'en' => 'Perimeter edge after sole lock: cure and waist symmetry confirm profile; color step at channel lip signals crown offset.',
            'de' => 'Umfangskante nach Verriegelung: Aushärtung und Taillensymmetrie bestätigen Profil; Farbsprung an Nutlippe zeigt Kronenversatz.',
            'fr' => 'Tranche après verrouillage: cure et symétrie cambrion valident profil; rupture de teinte au bord rainure signale couronne décalée.',
            'it' => 'Bordo dopo blocco suola: cura e simmetria vita validano profilo; gradino colore sul labbro canale segnala corona disallineata.',
            'pt' => 'Bordo após bloqueio: cura e simetria da cintura validam perfil; degrau de cor no lábio do canal indica coroa desalinhada.',
            'es' => 'Canto tras bloqueo: cura y simetría de cintura validan perfil; salto de color en labio de canal indica corona desalineada.',
        ],
        'rib-attaching' => [
            'en' => 'First welt prep on board: rib bond and land steer channeling; lifted land skews holdfast at waist.',
            'de' => 'Erster Rahmen-Prep: Rippenklebung und Land steuern die Nut; angehobenes Land verzerrt Holdfast in der Taillee.',
            'fr' => 'Premier prep trépointe: collage et assise nervure avant rainurage; assise relevée décale holdfast au cambrion.',
            'it' => 'Primo prep guardolo: legame e appoggio nervatura prima scanalatura; land sollevato compromette holdfast in vita.',
            'pt' => 'Primeiro prep de vira: colagem e assento da nervura antes do sulco; assento alto desvia holdfast na cintura.',
            'es' => 'Primer prep de cerco: pegado y asiento del nervio antes del surco; asiento alto desvía holdfast en cintura.',
        ],
        'channeling' => [
            'en' => 'Groove before gemming: wall angle and depth set holdfast bite and crown; recut drift fails at waist stitch.',
            'de' => 'Nut vor Gemming: Wandwinkel und Tiefe setzen Holdfast und Kronenhöhe; Nachschnitt scheitert an der Taillennaht.',
            'fr' => 'Rainure avant gemmage: paroi et profondeur fixent prise et couronne; reprise erronée échoue au cambrion.',
            'it' => 'Scanalatura prima di gemming: parete e profondità definiscono presa e corona; riapertura errata fallisce in vita.',
            'pt' => 'Sulco antes de gemming: parede e profundidade definem mordida e coroa; retoque errado falha na cintura.',
            'es' => 'Surco antes de gemming: pared y profundidad fijan mordida y corona; retrabajo falla en costura de cintura.',
        ],
        'sidewall-trimming' => [
            'en' => 'Flash trim before edge ink; clean sidewall land avoids false edge read and cold peel at perimeter.',
            'de' => 'Blitzbeschnitt vor Kantenfarbe; sauberes Seitenwand-Land vermeidet falsche Kantenlesung und kaltes Peel.',
            'fr' => 'Parage flash avant encre tranche; flanc propre évite fausse lecture et pelage froid au périmètre.',
            'it' => 'Rifilo flash prima inchiostro bordo; parete pulita evita lettura falsa e peel freddo al perimetro.',
            'pt' => 'Recorte de flash antes da tinta; assento lateral limpo evita leitura falsa e peel frio no perímetro.',
            'es' => 'Recorte de flash antes de tinta; asiento lateral limpio evita lectura falsa y peel frío en perímetro.',
        ],
    ];

    $coreSourceReferenceByKey = [
        'lasting' => 'Lasting control plan with station sequence card (toe → side → back-part → seat on structured lines; toe → side → seat on Strobel) and symmetry audit sheet.',
        'side-lasting' => 'Side-lasting pull-ratio matrix by last family and waist-curve visual gauge.',
        'seat-lasting' => 'Seat-lasting capture checklist linked to heel-seat contour match and leveling gate.',
        'sole-pressing' => 'Outsole attachment press map and perimeter compression signature acceptance sheet.',
        'toe-lasting' => 'Toe-lasting pull card with pincer pressure band and toe-spring symmetry gauge.',
        'rib-attaching' => 'Insole rib bond SOP with perimeter alignment check before channel routing.',
        'channeling' => 'Channel depth and wall-angle route card linked to holdfast penetration map.',
        'roughing' => 'Roughing depth profile card and bond-interface topography audit before primer queue.',
        'primer-coat' => 'Primer coat dry-time envelope sheet by substrate stack and climate band.',
        'cementing' => 'Cement spread-weight log and open-time gate before activation tunnel queue.',
        'heat-activation' => 'Tunnel energy-density profile sheet by material stack and queue dwell limit.',
        'holdfast-stitch' => 'Holdfast bite map and lock-loop completeness gate before cork fill release.',
        'bottoming' => 'Bottoming route card at lasting release (Strobel-cement vs welted prep fork).',
        'shoe-last' => 'Last governance record, grading matrix, and fit-lab dimensional validation baseline.',
        'upper' => 'Upper assembly SOP, seam-class visual standard, and mirror-pair inspection checklist.',
        'vamp' => 'Pattern engineering break-line control protocol and forepart flex validation records.',
        'quarter' => 'Back-part alignment standard and collar-position tolerance chart for closing operations.',
        'toe-puff' => 'Reinforcement activation curve guideline and hot-box durability assessment protocol.',
        'heel-counter' => 'Counter molding setup standard and heel-retention wear-trial evidence set.',
        'welt' => 'Welt/channel stitch penetration criteria and resole-integrity audit framework.',
        'outsole' => 'Compound validation matrix, abrasion-climate correlation report, and tooling compensation log.',
        'insole' => 'Insole dimensional control sheet and lasting margin capture verification procedure.',
        'midsole' => 'Compression-set and density control records for cushioning stack release.',
        'strobel-stitch' => 'Strobel board-to-upper seam capability card with toe-spring radius gap limits before lasting handoff.',
        'heel-counter-reinforcement' => 'Back-part reinforcement stack height matrix by last family and width grade before counter molding.',
        'shank-reinforcement' => 'Shank seat and torsion bench validation record before cork fill or cemented press.',
        'foxing' => 'Foxing overlap and sidewall continuity visual control standard.',
        'lining' => 'Lining friction/moisture compatibility test matrix for internal comfort stability.',
        'heel-seat' => 'Heel-seat contour match and planarity verification checklist before attachment.',
        'shank' => 'Shank placement and torsion response validation procedure by construction family.',
        'goodyear-welt' => 'Goodyear route card with station sequence rib → holdfast → cork → channel stitch.',
        'blake-stitch' => 'Blake through-stitch route card with inline endoscope gate before sole lock.',
        'cemented-construction' => 'Cemented bottoming route governance sheet (roughing through cure window).',
        'board-lasted-construction' => 'Board-lasted lasting architecture sheet and bottoming fork at release.',
        'stitchdown-construction' => 'Stitchdown perimeter stitch SOP with waist edge-guide requirement.',
        'cupsole-cementing' => 'Cupsole press map and heel-pocket vs forepart dwell acceptance protocol.',
        'back-part-lasting' => 'Back-part pull card linked to counter contour match before seat transfer.',
        'channel-stitching' => 'Channel stitch crown-height gate and outsole lock sequence card on welted routes.',
        'lockstitch-seam' => 'Closing seam-class release map with thread-balance and SPI gate before lasting handoff.',
        'waist-shaping' => 'Waist profile control sheet linking cork fill density, shank width, and channel crown-height gate.',
        'strobel-board' => 'Strobel board tension and flatness record before stitch and lasting handoff.',
    ];

    // Expert tier: hand-authored full definitions with high industrial nuance.
    $expertTierFullDefinitions = [
        'upper-assembly' => [
            'en' => 'Upper assembly is where component-level quality becomes product-level risk: lining skew, seam offset, and reinforcement drift all compound before lasting. Mature lines lock this stage with operation cards, in-process torque and SPI checks, and mirror-pair visual controls to prevent asymmetry that cannot be corrected downstream.',
            'pt' => 'O fecho do cabedal é o ponto em que a qualidade de componente vira risco de produto: desvio de forro, deslocamento de costura e deriva de reforços acumulam-se antes da moldação. Linhas maduras controlam esta fase com cartas de operação, verificação de SPI e inspeção de par espelhado para evitar assimetrias irrecuperáveis a jusante.',
            'fr' => 'L’assemblage de tige est le moment où un écart de composant devient un défaut produit: décalage doublure, couture hors axe et dérive des renforts s’additionnent avant montage sur forme. Les ateliers robustes verrouillent cette étape avec gammes détaillées, contrôles SPI et vérification paire miroir pour éviter les défauts non rattrapables ensuite.',
            'de' => 'Die Schaftmontage ist die Stufe, in der Bauteilabweichungen zu Produktfehlern werden: Futterversatz, Nahtabweichung und Verstärkungsdrift summieren sich vor dem Aufziehen. Reife Fertigungen sichern diesen Schritt mit Arbeitsstandards, SPI-Kontrollen und Paar-Spiegelprüfung, um nachgelagerte, nicht korrigierbare Asymmetrien zu vermeiden.',
            'it' => 'L’assemblaggio tomaia è la fase in cui lo scostamento del componente diventa rischio di prodotto: deriva fodera, disallineamento cucitura e rinforzi fuori posizione si sommano prima del montaggio su forma. Le linee mature bloccano questa fase con standard operativi, controlli SPI e verifica coppia speculare per evitare difetti non recuperabili a valle.',
            'es' => 'El ensamblaje del corte es la etapa donde una desviación de componente se convierte en riesgo de producto: desfase de forro, costura fuera de eje y deriva de refuerzos se acumulan antes del montado. Las líneas maduras aseguran esta fase con hojas operativas, control SPI y verificación de par espejo para evitar asimetrías no corregibles aguas abajo.',
        ],
        'bottoming' => [
            'en' => 'Bottom assembly begins after lasting releases the upper: on Strobel-cemented routes the line runs roughing through press and cure; on welted routes it runs insole rib prep, holdfast lock, cork fill, and channel stitch before outsole lock. Bottoming is the handoff zone where lasting tension becomes sole attachment—route selection at release determines which chain the pair enters.',
            'pt' => 'A montagem de fundo começa após a moldação libertar o cabedal: em rotas Strobel coladas a linha executa rugosagem até prensagem e cura; em rotas com vira executa preparação de nervura, bloqueio de retenção, enchimento em cortiça e costura em canal antes do bloqueio da sola. É a zona de passagem em que a tensão da moldação vira fixação de sola—a rota na libertação define a cadeia.',
            'fr' => 'Le montage de fond commence après libération du montage sur forme: sur routes Strobel collées la ligne enchaîne rugosification jusqu’au pressage et cure; sur routes trépointées elle enchaîne préparation nervure, verrouillage holdfast, liège et couture en canal avant verrouillage semelle. C’est la zone de transfert où la tension de montage devient fixation semelle—la route à la libération fixe la chaîne.',
            'de' => 'Die Bodenmontage beginnt nach Sitzfreigabe vom Aufziehen: auf Strobel-Kleberouten laufen Paare Aufrauen → Primer → Auftrag → Aktivierung → Pressung → Aushaertung; auf Rahmenkonstruktionen Rippenauftrag → Holdfast → Korkfuellung → Taillenformung → Kanalnaht vor Sohlenverriegelung. Bodenmontage ist die Uebergabezone, in der Aufziehspannung zur Sohlenanbindung wird—die Route bei Freigabe bestimmt die Kette.',
            'it' => 'Il montaggio fondo inizia dopo il rilascio dal montaggio su forma: sulle route Strobel cementate la linea esegue rugosatura → primer → spalmatura → attivazione → pressa → cura; sulle route guardolo nervatura → holdfast → sughero → modellatura vita → cucitura in canale prima del blocco suola. È la zona di passaggio in cui la tensione di montaggio diventa fissaggio suola—la route al rilascio definisce la catena.',
            'es' => 'El montaje de fondo comienza tras liberar el montado: en rutas Strobel cementadas la línea ejecuta rugosado hasta prensado y curado; en rutas de cerco preparación de nervio, bloqueo de anclaje, relleno de corcho y costura en canal antes del bloqueo de suela. Es la zona de traspaso donde la tensión de montado se convierte en fijación de suela—la ruta en liberación define la cadena.',
        ],
        'toe-lasting' => [
            'en' => 'Toe lasting is the first pull station in the lasting chain: it anchors forepart volume and toe-spring target before side and seat stations add waist and heel-seat tension. Pincer pressure and margin tuck at this step set the baseline for medial-lateral balance—corrections after side pull cannot recover lost toe-spring symmetry.',
            'pt' => 'A moldação da biqueira é o primeiro posto de tração na cadeia de moldação: ancora o volume da frente e o alvo de toe spring antes de os postos lateral e de assento acrescentarem tensão na cintura e no assento do calcanhar. Pressão da pinça e dobra da margem neste passo definem a base do equilíbrio medial-lateral—correções após a tração lateral não recuperam simetria de toe spring perdida.',
            'fr' => 'Le montage de pointe est le premier poste de traction de la chaîne montage: il ancre le volume avant-pied et la cible toe spring avant que les postes latéral et assise ajoutent tension au cambrion et à l’assise talon. Pression pinces et rabat de marge à cette étape fixent la base d’équilibre médio-latéral—aucune correction après tirage latéral ne récupère une symétrie toe spring perdue.',
            'de' => 'Das Spitzenaufziehen ist die erste Zugstation der Aufziehkette: es verankert Vorfussvolumen und Toe-Spring-Ziel, bevor Seiten- und Sitzstationen Taillen- und Fersensitzspannung aufbauen. Zangendruck und Randfalte hier setzen die Basis fuer medial-laterales Gleichgewicht—Korrekturen nach Seitenzug holen verlorene Toe-Spring-Symmetrie nicht ein.',
            'it' => 'Il montaggio punta e la prima stazione di trazione nella catena di montaggio: ancora volume avampiede e target toe spring prima che le stazioni laterale e sede aggiungano tensione in vita e sede tallone. Pressione pinza e piega margine in questo passo definiscono la base del bilanciamento mediale-laterale—correzioni dopo il tiro laterale non recuperano simmetria toe spring persa.',
            'es' => 'El montado de puntera es la primera estación de tracción en la cadena de montado: ancla volumen de antepié y objetivo de toe spring antes de que las estaciones lateral y de asiento añadan tensión en cintura y asiento de talón. Presión de pinza y pliegue de margen en este paso fijan la base del balance medial-lateral—correcciones tras tracción lateral no recuperan simetría de toe spring perdida.',
        ],
        'back-part-lasting' => [
            'en' => 'Back-part lasting is the rearfoot pull station that sets heel-quarter wrap and counter alignment before seat capture on board-lasted and structured chains: operators tension the quarter around the last heel block so molded counter curvature meets the seat gauge, not the toe pincer map. On lines where sequence is toe → side → back-part → seat, errors here return pairs to the counter room; on Strobel-cement lines back-part work may sit earlier in closing, but seat release still audits the same contour match. This station does not fix waist twist—that remains at side lasting.',
            'pt' => 'A moldação traseira é o posto de tração do retropé que define envolvimento do quarto e alinhamento do contraforte antes da captura do assento em cadeias com cartão e estruturadas: os operadores tensionam o quarto no bloco do calcanhar da forma para a curvatura moldada do contraforte corresponder à galga do assento, não ao mapa da pinça da biqueira. Em linhas com sequência biqueira → lateral → traseiro → assento, erros regressam à sala do contraforte; em linhas Strobel coladas o trabalho traseiro pode estar mais cedo no fecho, mas a libertação do assento audita a mesma correspondência de contorno. Este posto não corrige torção da cintura—isso permanece na moldação lateral.',
            'fr' => 'Le montage arrière est le poste de traction arrière-pied qui règle l’enveloppement quartier et l’alignement contrefort avant capture assise sur chaînes montées sur première rigide et structurées: les opérateurs tendent le quartier sur le bloc talon de forme pour que la courbure moulée corresponde à la jauge assise, pas à la carte pinces pointe. Sur lignes pointe → latéral → arrière → assise, les erreurs renvoient à l’atelier contrefort; sur lignes Strobel collées le travail arrière peut être plus tôt au piquage, mais la libération assise audite la même concordance de contour. Ce poste ne corrige pas la vrille cambrion—cela reste au montage latéral.',
            'de' => 'Das Hinterkappen-Aufziehen ist die Rueckfuss-Zugstation, die Quartier-Umschlag und Kappenausrichtung vor Sitzaufnahme auf Brandsohlen- und strukturierten Ketten setzt: Bediener spannen das Quartier am Fersenblock, damit geformte Kappenkruemmung zur Sitzlehre passt, nicht zur Spitzenzangen-Karte. Bei Sequenz Spitze → Seite → Hinterteil → Sitz gehen Fehler in die Kappenabteilung zurueck; auf Strobel-Klebelinien kann Hinterarbeit frueher in der Schliesserei liegen, Sitzfreigabe prueft jedoch dieselbe Konturpassung. Diese Station korrigiert keine Taillenverdrillung—die bleibt beim Seitenaufziehen.',
            'it' => 'Il montaggio posteriore e la stazione di trazione retropiede che imposta avvolgimento quartiere e allineamento contrafforte prima della cattura sede su catene a cartone rigido e strutturate: gli operatori tendono il quartiere sul blocco tallone forma affinche la curvatura stampata combaci con la dima sede, non con la mappa pinza punta. Su linee punta → laterale → posteriore → sede, gli errori tornano al reparto contrafforte; su linee Strobel cementate il lavoro posteriore puo essere prima in chiusura, ma il rilascio sede audita lo stesso match contorno. Questa stazione non corregge torsione vita—resta al montaggio laterale.',
            'es' => 'El montado trasero es la estación de tracción del retropié que fija envolvente del cuarto y alineación del contrafuerte antes de la captura de asiento en cadenas sobre cartón y estructuradas: los operarios tensan el cuarto en el bloque de talón de horma para que la curvatura moldeada coincida con la galga de asiento, no con el mapa de pinza de puntera. En líneas puntera → lateral → trasero → asiento, los errores vuelven a sala de contrafuerte; en líneas Strobel cementadas el trabajo trasero puede ir antes en aparado, pero la liberación de asiento audita la misma concordancia de contorno. Esta estación no corrige torsión de cintura—eso queda en montado lateral.',
        ],
        'rib-attaching' => [
            'en' => 'Rib attaching is the opening step of welt preparation on the insole board: it fixes the rib perimeter that gemming will form into a holdfast-ready anchor. Bond integrity and rib alignment here determine whether downstream channel cutting and holdfast stitch can meet penetration maps at the waist.',
            'pt' => 'A aplicação de nervura é o passo inicial da preparação da vira no cartão de palmilha: fixa o perímetro da nervura que o gemming transformará em ancoragem pronta para retenção. A integridade da união e o alinhamento da nervura determinam se o corte de canal e o ponto de retenção a jusante cumprem mapas de penetração na cintura.',
            'fr' => 'La pose de nervure ouvre la préparation trépointe sur la première rigide: elle fixe le périmètre que le gemmage transformera en ancrage prêt pour holdfast. L’intégrité de collage et l’alignement de nervure déterminent si le fraisage de canal et le point d’ancrage aval satisfont les cartes de pénétration au cambrion.',
            'de' => 'Die Rippenanbringung ist der Einstieg der Rahmenvorbereitung auf der Brandsohle: sie fixiert den Rippenumfang, den Gemming zur Holdfast-faehigen Verankerung formt. Klebeintegritaet und Rippenausrichtung bestimmen, ob nachgelagertes Kanalfraesen und Holdfast-Stich die Penetrationskarte in der Taillee erfuellen.',
            'it' => 'L’applicazione nervatura apre la preparazione guardolo sul cartone soletta: fissa il perimetro che il gemming formerà in ancoraggio pronto per holdfast. Integrità del legame e allineamento nervatura determinano se il taglio canale e il punto di tenuta a valle rispettano le mappe di penetrazione in vita.',
            'es' => 'La aplicación de nervio abre la preparación de cerco en el cartón de plantilla: fija el perímetro que el gemming formará en anclaje listo para retención. La integridad de unión y la alineación del nervio determinan si el corte de canal y la puntada de anclaje aguas abajo cumplen mapas de penetración en cintura.',
        ],
        'roughing' => [
            'en' => 'Roughing is the first cemented-bottoming station after seat release: it profiles the upper margin and sole landing to a controlled bond interface before primer and spread. Depth, pattern, and dust extraction must match the route card—shallow roughing starves primer wetting, while aggressive roughing collapses margin geometry that press maps cannot recover.',
            'pt' => 'A rugosagem é o primeiro posto de fundo colado após libertação do assento: perfila margem do cabedal e assento da sola para uma interface de colagem controlada antes do primário e da aplicação. Profundidade, padrão e extração de pó têm de corresponder à ficha de rota—rugosagem rasa priva molhabilidade do primário; rugosagem agressiva colapsa geometria de margem que a prensa não recupera.',
            'fr' => 'La rugosification est le premier poste de fond collé après libération d’assise: elle profile marge tige et assise semelle en interface de collage maîtrisée avant primaire et dépôt. Profondeur, motif et aspiration poussière doivent suivre la gamme—rugosification insuffisante affame le mouillage primaire; rugosification agressive dégrade la géométrie de marge que la presse ne rattrape pas.',
            'de' => 'Das Aufrauen ist die erste Station der geklebten Route nach Sitzfreigabe: es profiliert Schaftrand und Sohlenauflage zu einer kontrollierten Klebefugen-Oberflaeche vor Primer und Auftrag. Tiefe, Muster und Staubabsaugung muessen zur Route-Card passen—flaches Aufrauen verhungert Primerbenetzung, aggressives Aufrauen kollabiert Randgeometrie, die die Presse nicht heilt.',
            'it' => 'La rugosatura è la prima stazione del fondo cementato dopo il rilascio sede: profila margine tomaia e appoggio suola in un’interfaccia di incollaggio controllata prima di primer e spalmatura. Profondità, pattern ed estrazione polvere devono seguire la scheda rotta—rugosatura superficiale affama il bagnamento primer; rugosatura aggressiva collassa la geometria margine che la pressa non recupera.',
            'es' => 'El rugosado es la primera estación de fondo cementado tras liberación de asiento: perfila margen del corte y asiento de suela en una interfaz de pegado controlada antes de imprimación y extendido. Profundidad, patrón y extracción de polvo deben seguir la hoja de ruta—rugosado superficial priva humectación del primer; rugosado agresivo colapsa geometría de margen que la prensa no recupera.',
        ],
        'primer-coat' => [
            'en' => 'Primer coat sits between roughing and cement spread on cemented routes: it raises surface energy on low-energy compounds and leather stacks so the adhesive film can wet uniformly. Dry-time and reactivation windows are route-governed—under-dry primer traps solvent and weakens the bond line; over-dry primer loses tack before spread and delivers dead interfaces at activation.',
            'pt' => 'O primário de colagem situa-se entre rugosagem e aplicação de cola nas rotas coladas: eleva a energia superficial em compostos e pilhas de couro de baixa energia para o filme adesivo molhar de forma uniforme. Tempo de secagem e janelas de reativação são governados pela rota—primário sub-seco aprisiona solvente e enfraquece a linha de cola; primário sobre-seco perde tack antes da aplicação e entrega interfaces mortas na ativação.',
            'fr' => 'Le primaire d’adhésion se place entre rugosification et dépôt colle sur routes collées: il relève l’énergie de surface sur compounds et empilements cuir basse énergie pour un mouillage uniforme du film adhésif. Temps de séchage et fenêtres de réactivation sont pilotés par gamme—primaire sous-séché piège solvant et affaiblit la ligne de collage; primaire sur-séché perd tack avant dépôt et livre interfaces mortes à l’activation.',
            'de' => 'Der Primerauftrag liegt zwischen Aufrauen und Kleberauftrag auf geklebten Routen: er hebt die Oberflaechenenergie auf Low-Energy-Compounds und Lederstapel, damit der Klebefilm gleichmaessig benetzt. Trockenzeit und Reaktivierungsfenster sind routengefuehrt—untertrockener Primer bindet Loesungsmittel und schwaecht die Klebefuge; uebertrockener Primer verliert Tack vor dem Auftrag und liefert tote Grenzflaechen in der Aktivierung.',
            'it' => 'Il primer adesivo si colloca tra rugosatura e spalmatura sulle route cementate: aumenta l’energia superficiale su compound e stack pelle a bassa energia affinché il film adesivo bagni in modo uniforme. Tempo di asciugatura e finestre di riattivazione sono governati dalla scheda—primer sotto-asciugato intrappola solvente e indebolisce la linea di incollaggio; primer sovra-asciugato perde tack prima della spalmatura e consegna interfacce morte in attivazione.',
            'es' => 'La imprimación de adhesión va entre rugosado y extendido en rutas cementadas: eleva la energía superficial en compuestos y pilas de cuero de baja energía para que la película adhesiva humecte de forma uniforme. Tiempo de secado y ventanas de reactivación se gobiernan por hoja de ruta—primer subsecado atrapa solvente y debilita la línea de pegado; primer sobreesecado pierde tack antes del extendido y entrega interfaces muertas en activación.',
        ],
        'channeling' => [
            'en' => 'Channeling prepares the insole board groove that receives welt-channel geometry before gemming and holdfast lock: wall angle and depth set stitch sink and moisture path for the entire welt-stitch chain. Recut or offset errors at this step propagate into shallow holdfast bite that channel stitching cannot compensate.',
            'pt' => 'O canal de costura prepara o sulco na palmilha que recebe a geometria do canal da vira antes do gemming e do bloqueio de retenção: ângulo e profundidade definem afundamento do ponto e percurso de humidade para toda a cadeia de costura da vira. Erros de reabertura ou offset propagam mordida rasa de retenção que a costura em canal não compensa.',
            'fr' => 'La canalisation prépare la rainure de première qui recevra la géométrie de canal trépointe avant gemmage et verrouillage holdfast: angle de paroi et profondeur fixent enfoncement du point et chemin d’humidité pour toute la chaîne de couture trépointe. Une reprise ou un offset erroné se propage en prise holdfast faible que la couture en canal ne compense pas.',
            'de' => 'Die Nahtkanalierung bereitet die Brandsohlennut vor, die die Rahmenkanalgeometrie vor Gemming und Holdfast-Verriegelung aufnimmt: Wandwinkel und Tiefe setzen Stichsenkung und Feuchtigkeitsweg fuer die gesamte Rahmennahtkette. Nachschnitt- oder Offsetfehler propagieren als flache Holdfast-Aufnahme, die die Kanalnaht nicht ausgleicht.',
            'it' => 'La canalizzazione prepara la scanalatura soletta che riceve la geometria canale guardolo prima di gemming e blocco holdfast: angolo parete e profondità definiscono affondamento punto e percorso umidità per tutta la catena cucitura guardolo. Errori di riapertura o offset si propagano in presa holdfast ridotta che la cucitura in canale non compensa.',
            'es' => 'El canal de costura prepara el surco de plantilla que recibe la geometría de canal de cerco antes de gemming y bloqueo de anclaje: ángulo de pared y profundidad fijan hundimiento de puntada y ruta de humedad para toda la cadena de costura de cerco. Errores de reapertura u offset se propagan en mordida superficial de anclaje que la costura en canal no compensa.',
        ],
        'quality-checkpoint' => [
            'en' => 'A quality checkpoint is not a generic inspection stop; it is a designed gate tied to known failure modes and reaction plans. High-performing teams define measurable triggers (SPI drift, peel threshold, toe spring deviation), immediate containment, and escalation ownership so defects are blocked before value-adding operations continue.',
            'pt' => 'Um ponto de controlo de qualidade não é uma paragem genérica de inspeção; é um gate desenhado para modos de falha conhecidos e planos de reação. Equipas de alto desempenho definem gatilhos mensuráveis (deriva de SPI, limite de peel, desvio de toe spring), contenção imediata e dono de escalonamento antes de continuar a acrescentar valor.',
            'fr' => 'Un point de contrôle qualité n’est pas un arrêt d’inspection générique; c’est une porte conçue sur des modes de défaillance connus avec plan de réaction. Les équipes performantes fixent des seuils mesurables (dérive SPI, seuil pelage, écart toe spring), la contention immédiate et la responsabilité d’escalade.',
            'de' => 'Ein Qualitäts-Prüfpunkt ist kein allgemeiner Kontrollhalt, sondern ein gezieltes Gate für bekannte Fehlerbilder mit Reaktionsplan. Leistungsstarke Teams definieren messbare Auslöser (SPI-Drift, Schälgrenze, Toe-Spring-Abweichung), Sofortcontainment und klare Eskalationsverantwortung vor weiteren Wertschritten.',
            'it' => 'Un punto controllo qualità non è una fermata ispettiva generica; è un gate progettato su failure mode noti con piano di reazione. I team maturi fissano trigger misurabili (deriva SPI, soglia peel, scostamento toe spring), contenimento immediato e ownership di escalation prima di proseguire.',
            'es' => 'Un punto de control de calidad no es una parada genérica de inspección; es una puerta diseñada para modos de fallo conocidos con plan de reacción. Los equipos maduros definen disparadores medibles (deriva SPI, umbral de pelado, desviación de toe spring), contención inmediata y responsable de escalado.',
        ],
        'lasting' => [
            'en' => 'Lasting is the route-governance hub that converts a closed upper into a stable last-shaped shell before bottoming: operators run a controlled pull chain, never a single combined pull. On structured board-lasted lines the sequence is toe → side → back-part → seat; on most Strobel-cement chains rearfoot closure is upstream and the sequence is toe → side → seat. Seat release determines route continuity—cemented pairs must enter roughing inside open-time control, while Goodyear pairs transfer to rib and channel preparation—and the ticket must capture margin stress, collar symmetry, and heel-seat contour because neither press maps nor welt benches can recover locked-in tension errors.',
            'pt' => 'A moldação é o hub fabril que converte um cabedal fechado em volume conformado à forma antes de qualquer construção de sola: os supervisores executam sequência com gates, não uma tração única. Em linhas estruturadas com cartão a ordem é biqueira → lateral → traseiro → assento; em muitas linhas Strobel coladas o retropé fecha-se antes e a cadeia é biqueira → lateral → assento. A libertação do assento fixa o fundo—pares colados entram em rugosagem dentro da janela de tempo aberto, pares Goodyear em prep de nervura—a ficha regista tensão das margens, simetria do colarinho e contorno do assento porque prensa e bancada de vira não desfazem tensão bloqueada aqui.',
            'fr' => 'Le montage sur forme est le nœud de gouvernance route qui transforme une tige fermée en volume calé sur forme avant tout montage de fond: on exécute une chaîne de tirage séquencée, jamais un tirage unique. Sur lignes structurées montées carton, l’ordre est pointe → latéral → arrière → assise; sur la plupart des chaînes Strobel cimentées, l’arrière-pied est fermé en amont et la séquence devient pointe → latéral → assise. La libération d’assise décide la continuité de route—les paires collées doivent entrer en rugosification dans la fenêtre de temps ouvert, les paires Goodyear basculent en préparation nervure/canal—et le ticket doit tracer tension de marge, symétrie de col et contour d’assise, car ni la presse ni l’atelier trépointe ne corrigent une tension déjà verrouillée.',
            'de' => 'Das Aufziehen ist der Route-Governance-Knoten, der einen geschlossenen Schaft vor jedem Bottoming in eine stabile Leistengeometrie ueberfuehrt: gefahren wird eine sequenzierte Zugkette, nie ein Sammelzug. Auf strukturierten Brandsohlen-Linien lautet die Reihenfolge Spitze → Seite → Hinterteil → Sitz; auf den meisten Strobel-Klebelinien ist das Rueckteil vorgelagert geschlossen und die Sequenz lautet Spitze → Seite → Sitz. Die Sitzfreigabe steuert die Routen-Kontinuitaet—geklebte Paare muessen innerhalb des Offenzeitfensters ins Aufrauen, Goodyear-Paare gehen in Rippen- und Kanalvorbereitung—und der Laufzettel muss Randspannung, Kragensymmetrie und Fersensitzkontur dokumentieren, weil weder Presskarte noch Rahmenbank gesperrte Zugfehler heilen.',
            'it' => 'Il montaggio su forma è il nodo di governance della route che converte una tomaia chiusa in volume stabile su forma prima di ogni fondo: si esegue una catena di tiro sequenziata, mai un tiro unico. Sulle linee strutturate a cartone rigido la sequenza è punta → laterale → posteriore → sede; sulla maggior parte delle catene Strobel cementate il retropiede chiude a monte e la sequenza diventa punta → laterale → sede. Il rilascio sede governa la continuità di route—le paia cementate devono entrare in rugosatura entro la finestra di tempo aperto, le paia Goodyear passano a preparazione nervatura/canale—e il ticket deve tracciare tensione margini, simmetria collarino e profilo sede, perché né pressa né banco guardolo recuperano errori di trazione già bloccati.',
            'es' => 'El montado en horma es el hub que convierte un corte cerrado en volumen sobre horma antes de cualquier construcción de suela: los supervisores ejecutan estaciones con gates, no un solo tirón. En líneas estructuradas sobre cartón el orden es puntera → lateral → trasero → asiento; en muchas líneas Strobel cementadas el retropié cierra antes y la cadena es puntera → lateral → asiento. La liberación de asiento fija el fondo—pares cementados a rugosado dentro de la ventana de tiempo abierto, pares Goodyear a prep de nervio—la ficha registra tensión de márgenes, simetría de collar y contorno de asiento porque prensa y banco de cerco no deshacen tensión bloqueada aquí.',
        ],
        'side-lasting' => [
            'en' => 'Side lasting is station two after toe anchoring and the main twist-control gate for route continuity: operators balance medial-lateral pull so the upper follows last roll without collar migration. Structured chains transfer next to back-part pull; many Strobel-cement chains transfer directly to seat capture. Corrections belong at this station, not downstream at heel-seat leveling: stable side margins are what allow consistent waist shaping on welted routes and uniform perimeter wetting on cemented routes.',
            'pt' => 'A moldação lateral é o posto 2 da cadeia após a biqueira: os operadores definem tração medial-lateral para o cabedal seguir o rolar da forma sem torção do colarinho antes do traseiro (linhas estruturadas) ou do assento (muitas linhas Strobel). O medidor espelhado é o gate de torção—correção aqui, não no nivelamento do assento. Margens laterais estáveis permitem modelação da cintura em rotas com vira e contacto uniforme da linha de cola em rotas coladas; tração excessiva não se recupera com dwell extra na prensa.',
            'fr' => 'Le montage latéral est le poste 2 après ancrage pointe et le principal gate de vrille pour la continuité de route: les opérateurs équilibrent la traction médio-latérale afin que la tige suive le roulis de forme sans migration du col. Les chaînes structurées transfèrent ensuite vers l’arrière; beaucoup de chaînes Strobel cimentées transfèrent directement vers l’assise. La correction se fait ici, pas en aval au nivellement d’assise: des marges latérales stables conditionnent le profilage cambrion en route trépointée et le mouillage périmétrique en route collée.',
            'de' => 'Das seitliche Aufziehen ist Station 2 nach Spitzenanker und das zentrale Verdrillungs-Gate fuer Routenkontinuitaet: Bediener balancieren den medial-lateralen Zug, damit der Schaft der Leistenrolle ohne Kragenwanderung folgt. Strukturierte Ketten uebergeben danach an den Hinterteilzug; viele Strobel-Klebeketten gehen direkt in die Sitzaufnahme. Korrekturen gehoeren in diese Station und nicht spaeter in die Fersensitz-Nivellierung: stabile Seitenraender sind Voraussetzung fuer saubere Taillenformung auf rahmengenaehten Routen und gleichmaessige Umfangsbenetzung auf geklebten Routen.',
            'it' => 'Il montaggio laterale è la stazione 2 dopo l\'ancoraggio punta ed è il gate principale di torsione per la continuità di route: gli operatori bilanciano la trazione mediale-laterale affinché la tomaia segua il rollio forma senza migrazione del collarino. Le catene strutturate trasferiscono poi al posteriore; molte catene Strobel cementate passano direttamente alla sede. La correzione va fatta qui, non a valle nel livellamento sede: margini laterali stabili abilitano la modellatura vita nelle route guardolo e una bagnatura perimetrale uniforme nelle route cementate.',
            'es' => 'El montado lateral es la estación 2 tras anclaje de puntera: los operarios fijan tracción medial-lateral para que el corte siga el rolado sin torsión de collar antes del trasero (líneas estructuradas) o del asiento (muchas líneas Strobel). La galga espejo es la puerta de torsión—corrección aquí, no en el nivelado del asiento. Márgenes laterales estables permiten conformado de cintura y contacto uniforme de línea de pegado; la sobreatracción no se recupera con dwell extra en prensa.',
        ],
        'seat-lasting' => [
            'en' => 'Seat lasting is the terminal lasting gate where geometry becomes route commitment: after back-part pull on structured lines or side pull on Strobel lines, the station locks heel-seat margins and validates counter-to-lining alignment plus contour match. Goodyear tickets release to rib and gemming because stitch mechanics will recover the sole later; cemented tickets release to roughing-primer only inside the open-time clock because adhesive throughput replaces stitch recovery. Route selection is not interchangeable—forcing a cemented queue after a welt ticket skips holdfast bite maps, while delaying roughing on a cemented ticket burns tack before spread. The critical risks governed here are functional failures—heel slip, perimeter bond lift, and torsional heel instability—not cosmetic defects.',
            'pt' => 'A moldação do assento é o posto terminal em que a geometria vira compromisso de rota: após o traseiro em linhas estruturadas ou a lateral em linhas Strobel, bloqueia margens do assento e valida alinhamento contraforte-forro e contorno. Fichas Goodyear libertam para nervura e gemming porque a mecânica de costura recupera o fundo; fichas coladas só avançam para rugosagem-primário dentro do relógio de tempo aberto porque a cola substitui essa recuperação. As rotas não são intercambiáveis—atrasar rugosagem numa ficha colada queima tack antes da aplicação. Falhas funcionais: escorregamento, levantamento perimetral da cola, balanço do salto em torção.',
            'fr' => 'Le montage de siège est le poste terminal où la géométrie devient engagement de route: après tirage arrière sur lignes structurées ou tirage latéral sur lignes Strobel, le poste verrouille les marges d’assise et valide alignement contrefort-doublure et concordance de contour. Les fiches Goodyear passent en nervure et gemmage car la couture rétablira le fond; les fiches collées passent en rugosification-primaire uniquement dans le temps ouvert, la colle remplaçant ce rattrapage. Les routes ne sont pas interchangeables—retarder la rugosification sur une fiche collée consume le tack avant dépôt. Risques critiques: glissement talon, décollement périphérique collage, instabilité en torsion.',
            'de' => 'Das Sitzaufziehen ist das terminale Gate, an dem Geometrie zur Routenfestlegung wird: nach Hinterteil- oder Seitenzug verriegelt die Station die Fersensitzraender und validiert Kappen-Futter-Ausrichtung sowie Konturpassung. Goodyear-Scheine gehen in Rippen- und Gemming-Vorbereitung, weil die Nahtmechanik den Boden spaeter traegt; Klebe-Scheine nur innerhalb der Offenzeit ins Aufrauen-Primer, weil Klebstoff diese Rueckholung ersetzt. Routen sind nicht austauschbar—verspaetetes Aufrauen auf Klebe-Scheinen verbrennt Tack vor dem Auftrag. Risiken: Fersenschlupf, Umfangsanhebung der Klebefuge, Torsionsinstabilitaet.',
            'it' => 'Il montaggio sede è il gate terminale in cui la geometria diventa impegno di route: dopo il tiro posteriore o laterale, la stazione blocca i margini sede e valida allineamento contrafforte-fodera e profilo. I ticket Goodyear passano a nervatura e gemming perché la meccanica cucita recupererà il fondo; i ticket cementati a rugosatura-primer solo entro il tempo aperto perché l’adesivo sostituisce quel recupero. Le route non sono intercambiabili—ritardare la rugosatura su un ticket cementato brucia il tack prima della spalmatura. Rischi: slittamento tallone, sollevamento perimetrale colla, instabilità torsionale.',
            'es' => 'El montado de asiento es la estación terminal en que la geometría fija la ruta: tras el trasero o el lateral, bloquea márgenes del asiento y valida alineación contrafuerte-forro y contorno. Fichas Goodyear liberan a nervio y gemming porque la costura recuperará el fondo; fichas cementadas a rugosado-primario solo dentro de la ventana de tiempo abierto porque el adhesivo sustituye esa recuperación. Las rutas no son intercambiables—retrasar rugosado en cementado quema tack antes del extendido. Fallos: deslizamiento, levantamiento perimetral de pegado, balanceo de tacón en torsión.',
        ],
        'shoe-last' => [
            'en' => 'The shoe last is the master geometric reference that synchronizes design intent, grading math, pattern allowances, and tooling constraints. Small last edits in heel pitch or ball girth can cascade into vamp break shift, outsole mismatch, and altered pressure maps, so governance requires controlled revision and revalidation loops.',
            'pt' => 'A forma de calçado é a referência geométrica-mestre que sincroniza intenção de design, matemática de graduação, folgas de modelagem e limites de ferramental. Pequenas alterações em inclinação do salto ou perímetro metatarsal podem deslocar a quebra do vampão, criar desajuste de sola e alterar mapas de pressão.',
            'fr' => 'La forme chaussure est la référence géométrique maîtresse qui synchronise intention design, gradation, marges patron et contraintes d’outillage. De petites modifications de pitch talon ou périmètre métatarsien peuvent déplacer la cassure claque, désaligner semelle et modifier les cartes de pression.',
            'de' => 'Der Schuhleisten ist die zentrale Geometrie-Referenz für Designabsicht, Gradierungslogik, Schnittzugaben und Werkzeuggrenzen. Kleine Änderungen bei Fersenpitch oder Ballenumfang können Knicklinie, Sohlenpassung und Druckbild verschieben; daher sind Leistenrevisionen streng zu steuern und zu revalidieren.',
            'it' => 'La forma calzaturiera è il riferimento geometrico principale che allinea intento design, gradazione, margini modellistici e vincoli tooling. Piccole variazioni di pitch tallone o circonferenza metatarsale possono spostare la piega tomaia, disallineare suola e cambiare mappe di pressione.',
            'es' => 'La horma de calzado es la referencia geométrica maestra que sincroniza intención de diseño, gradación, márgenes de patronaje y límites de herramental. Pequeños cambios en pitch de talón o perímetro metatarsal pueden desplazar el quiebre del empeine, desajustar suela y alterar mapas de presión.',
        ],
        'upper' => [
            'en' => 'The upper is the functional envelope that converts pattern geometry and material behavior into fit, containment, and visual identity. In production, upper quality is controlled through panel alignment, seam class integrity, and reinforcement placement because small deviations amplify during lasting and become visible in final shape.',
            'pt' => 'O cabedal é o invólucro funcional que converte geometria de modelagem e comportamento de materiais em calce, contenção e identidade visual. Na produção, a qualidade do cabedal é controlada por alinhamento de painéis, integridade de classe de costura e posição de reforços, porque pequenos desvios amplificam-se na moldação.',
            'fr' => 'La tige est l’enveloppe fonctionnelle qui transforme géométrie patron et comportement matière en chaussant, maintien et identité visuelle. En production, sa qualité est pilotée par alignement panneaux, intégrité des classes de couture et positionnement des renforts, car les petits écarts s’amplifient au montage sur forme.',
            'de' => 'Der Schaft ist die funktionale Hülle, die Schnittgeometrie und Materialverhalten in Passform, Halt und visuelle Identität überführt. In der Fertigung wird die Schaftqualität über Panelausrichtung, Nahtklassen-Integrität und Verstärkungsposition gesteuert, da kleine Abweichungen beim Aufziehen verstärkt sichtbar werden.',
            'it' => 'La tomaia è l’involucro funzionale che trasforma geometria modellistica e comportamento dei materiali in calzata, contenimento e identità visiva. In produzione la qualità tomaia è controllata tramite allineamento pannelli, integrità classe cuciture e posizionamento rinforzi, perché piccoli scostamenti si amplificano nel montaggio su forma.',
            'es' => 'El corte es la envolvente funcional que transforma geometría de patronaje y comportamiento de materiales en ajuste, contención e identidad visual. En producción su calidad se controla por alineación de paneles, integridad de clase de costura y ubicación de refuerzos, porque pequeñas desviaciones se amplifican en el montado.',
        ],
        'lining' => [
            'en' => 'Lining engineering balances comfort, moisture transfer, and structural interaction with reinforcements and seams. Industrially, lining mismatch in stretch, friction, or bonding compatibility drives heel slip, seam grin, and premature internal abrasion, so selection is validated as part of whole-stack performance.',
            'pt' => 'A engenharia do forro equilibra conforto, transferência de humidade e interação estrutural com reforços e costuras. Industrialmente, incompatibilidades de alongamento, fricção ou colagem no forro provocam escorregamento de calcanhar, abertura de costura e abrasão interna precoce, por isso a seleção é validada no conjunto completo.',
            'fr' => 'L’ingénierie doublure équilibre confort, transfert d’humidité et interaction structurelle avec renforts et coutures. En industrie, une incompatibilité d’allongement, friction ou collage en doublure provoque glissement talon, ouverture de couture et abrasion interne précoce; la sélection est donc validée au niveau de l’empilement complet.',
            'de' => 'Die Futterauslegung balanciert Komfort, Feuchtetransport und strukturelle Wechselwirkung mit Verstärkungen und Nähten. Industriell führen Fehlanpassungen bei Dehnung, Reibung oder Klebkompatibilität zu Fersenschlupf, Nahtaufgehen und frühem Innenabrieb; daher wird das Futter als Teil des Gesamtsystems validiert.',
            'it' => 'L’ingegneria fodera bilancia comfort, trasferimento umidità e interazione strutturale con rinforzi e cuciture. In ambito industriale incompatibilità di allungamento, attrito o incollaggio della fodera causano slittamento tallone, apertura cuciture e abrasione interna precoce, quindi la selezione è validata sull’intero stack.',
            'es' => 'La ingeniería de forro equilibra confort, transferencia de humedad e interacción estructural con refuerzos y costuras. Industrialmente, incompatibilidades de elongación, fricción o pegado del forro provocan deslizamiento de talón, apertura de costura y abrasión interna temprana, por lo que se valida como parte del stack completo.',
        ],
        'foxing' => [
            'en' => 'Foxing controls sidewall reinforcement and visual continuity at the upper-to-sole transition, especially in vulcanized and cupside constructions. Process capability depends on overlap geometry, adhesion route, and cure behavior because foxing defects become immediate cosmetic and durability failures at perimeter flex zones.',
            'pt' => 'O foxing controla reforço de parede lateral e continuidade visual na transição cabedal-sola, sobretudo em construções vulcanizadas e com lateral elevada. A capacidade do processo depende de geometria de sobreposição, rota de adesão e comportamento de cura, porque defeitos de foxing tornam-se falhas imediatas de estética e durabilidade.',
            'fr' => 'Le foxing contrôle le renfort de flanc et la continuité visuelle à la transition tige-semelle, notamment sur constructions vulcanisées et cupside. La capabilité process dépend de la géométrie de recouvrement, de la route d’adhésion et du comportement de cure, car les défauts foxing deviennent des échecs immédiats de finition et de tenue.',
            'de' => 'Foxing steuert Seitenwandverstärkung und visuelle Kontinuität im Übergang Schaft-Sohle, besonders bei vulkanisierten und Cupside-Konstruktionen. Die Prozessfähigkeit hängt von Überlappungsgeometrie, Adhäsionspfad und Aushärteverhalten ab, da Foxing-Fehler sofortige Optik- und Haltbarkeitsausfälle in Flexzonen verursachen.',
            'it' => 'Il foxing controlla rinforzo del fianco e continuità visiva nella transizione tomaia-suola, soprattutto in costruzioni vulcanizzate e cupside. La capabilità processo dipende da geometria sovrapposizione, percorso adesione e comportamento di cura, perché i difetti foxing diventano failure immediate estetiche e di durata.',
            'es' => 'El foxing controla refuerzo de pared lateral y continuidad visual en la transición corte-suela, especialmente en construcciones vulcanizadas y cupside. La capacidad de proceso depende de geometría de solape, ruta de adhesión y comportamiento de curado, porque defectos de foxing se convierten en fallos inmediatos de estética y durabilidad.',
        ],
        'welt' => [
            'en' => 'Welt construction creates a serviceable structural interface between upper and outsole systems, distributing stitch loads and enabling resoling cycles. Industrial welt quality is controlled through channel geometry, stitch penetration consistency, and edge preparation to prevent water path ingress and seam fatigue.',
            'pt' => 'A construção com vira cria uma interface estrutural reparável entre sistemas de cabedal e sola, distribuindo cargas de costura e permitindo ciclos de ressolagem. A qualidade industrial da vira é controlada por geometria do canal, consistência de penetração do ponto e preparação de bordos para evitar entrada de água e fadiga de costura.',
            'fr' => 'La construction trépointe crée une interface structurelle réparable entre tige et systèmes de semelle, répartissant les charges de couture et permettant des cycles de ressemelage. La qualité industrielle du trépointe est pilotée par géométrie de canal, constance de pénétration du point et préparation de rive pour éviter infiltration et fatigue de couture.',
            'de' => 'Die Rahmenleiste schafft eine servicefaehige strukturelle Schnittstelle zwischen Schaft- und Sohlensystem, verteilt Nahtlasten und ermoeglicht Wiederbesohlungszyklen. Industrielle Qualitaet wird ueber Kanalgeometrie, konstante Holdfast- und Kanalnaht-Penetration sowie Kantenvorbereitung gesteuert, um Wassereintritt und Nahtermuedung zu vermeiden.',
            'it' => 'La costruzione a guardolo crea un’interfaccia strutturale manutenzionabile tra tomaia e sistemi suola, distribuendo i carichi di cucitura e consentendo cicli di risuolatura. La qualità industriale del guardolo è controllata da geometria canale, costanza di penetrazione punto e preparazione bordo per evitare ingressi acqua e fatica cucitura.',
            'es' => 'La construcción de cerco crea una interfaz estructural reparable entre corte y sistemas de suela, distribuyendo cargas de costura y permitiendo ciclos de resolado. La calidad industrial del cerco se controla por geometría de canal, consistencia de penetración de puntada y preparación de canto para evitar ingreso de agua y fatiga de costura.',
        ],
        'midsole' => [
            'en' => 'The midsole is the load-management layer that governs cushioning curve, energy transfer, and platform stability above the outsole. In manufacturing, material density drift, compression-set behavior, and geometry tolerance directly affect gait feel and long-term shape retention.',
            'pt' => 'A entressola é a camada de gestão de carga que governa curva de amortecimento, transferência de energia e estabilidade da plataforma acima da sola exterior. No fabrico, deriva de densidade, comportamento de deformação permanente e tolerâncias geométricas afetam diretamente sensação de marcha e retenção de forma ao longo do tempo.',
            'fr' => 'La semelle intermédiaire est la couche de gestion de charge qui pilote courbe d’amorti, transfert d’énergie et stabilité de plateforme au-dessus de la semelle extérieure. En fabrication, dérive de densité, compression permanente et tolérances géométriques impactent directement la sensation de marche et la tenue de forme.',
            'de' => 'Die Zwischensohle ist die Lastmanagement-Schicht, die Dämpfungskurve, Energieübertragung und Plattformstabilität über der Laufsohle steuert. In der Fertigung beeinflussen Dichtedrift, Compression-Set-Verhalten und Geometrietoleranzen direkt das Abrollgefühl und die langfristige Formstabilität.',
            'it' => 'L’intersuola è lo strato di gestione carico che governa curva di ammortizzazione, trasferimento di energia e stabilità piattaforma sopra la suola esterna. In produzione, deriva di densità, comportamento di compression set e tolleranze geometriche influenzano direttamente feeling di camminata e tenuta forma nel tempo.',
            'es' => 'La entresuela es la capa de gestión de carga que gobierna curva de amortiguación, transferencia de energía y estabilidad de plataforma por encima de la suela exterior. En fabricación, deriva de densidad, comportamiento de deformación permanente y tolerancias geométricas afectan directamente sensación de marcha y retención de forma.',
        ],
        'insole' => [
            'en' => 'The insole is the primary foot-interface platform that links comfort, internal support, and lasting reference geometry. Industrial insole performance depends on dimensional stability, bonding compatibility, and thickness uniformity because it anchors upper margins and influences perceived fit volume.',
            'pt' => 'A palmilha é a plataforma principal de interface com o pé que liga conforto, suporte interno e geometria de referência da moldação. O desempenho industrial da palmilha depende de estabilidade dimensional, compatibilidade de colagem e uniformidade de espessura, porque ancora margens do cabedal e influencia o volume percebido de calce.',
            'fr' => 'La semelle intérieure est la plateforme principale d’interface pied reliant confort, support interne et géométrie de référence de montage. Sa performance industrielle dépend de stabilité dimensionnelle, compatibilité de collage et uniformité d’épaisseur, car elle ancre les marges de tige et influence le volume perçu de chaussant.',
            'de' => 'Die Innensohle ist die primäre Fuß-Interface-Plattform und verbindet Komfort, interne Stützung und Referenzgeometrie fürs Aufziehen. Ihre industrielle Leistung hängt von Maßstabilität, Klebkompatibilität und Dickenuniformität ab, da sie Schaftränder verankert und das wahrgenommene Passformvolumen beeinflusst.',
            'it' => 'La soletta è la principale piattaforma di interfaccia piede che collega comfort, supporto interno e geometria di riferimento del montaggio. La performance industriale dipende da stabilità dimensionale, compatibilità incollaggio e uniformità di spessore, perché ancora i margini tomaia e influenza il volume di calzata percepito.',
            'es' => 'La plantilla es la plataforma principal de interfaz con el pie que conecta confort, soporte interno y geometría de referencia del montado. Su desempeño industrial depende de estabilidad dimensional, compatibilidad de pegado y uniformidad de espesor, porque ancla márgenes del corte e influye en el volumen de ajuste percibido.',
        ],
        'heel-seat' => [
            'en' => 'Heel seat geometry defines rearfoot platform alignment for lasting closure, heel attachment, and load transfer into the outsole system. In industrial control, heel seat mismatch creates tilt, rotational instability, and early bond fatigue, so seat flatness and contour compatibility are audited before release.',
            'pt' => 'A geometria do assento do calcanhar define o alinhamento da plataforma do retropé para fecho da moldação, fixação de salto e transferência de carga para o sistema de sola. No controlo industrial, desajustes no assento criam inclinação, instabilidade rotacional e fadiga precoce da união, por isso planicidade e compatibilidade de contorno são auditadas antes da libertação.',
            'fr' => 'La géométrie d’assise talon définit l’alignement de plateforme arrière-pied pour fermeture de montage, fixation talon et transfert de charge vers le système semelle. En contrôle industriel, un mauvais appariement crée bascule, instabilité rotationnelle et fatigue précoce de liaison; planéité et compatibilité de contour sont donc auditées avant libération.',
            'de' => 'Die Fersensitz-Geometrie definiert die Hinterfuß-Plattformausrichtung für Abschluss des Aufziehens, Absatzanbindung und Lastübertragung ins Sohlsystem. Industriell führt Fehlpassung zu Kippung, Rotationsinstabilität und früher Verbundermüdung; daher werden Ebenheit und Konturkompatibilität vor Freigabe auditiert.',
            'it' => 'La geometria della sede tallone definisce l’allineamento della piattaforma retropiede per chiusura montaggio, fissaggio tacco e trasferimento carico al sistema suola. Nel controllo industriale un mismatch della sede genera inclinazione, instabilità rotazionale e fatica precoce del legame; planaritá e compatibilità profilo sono auditate prima del rilascio.',
            'es' => 'La geometría del asiento de talón define alineación de plataforma de retropié para cierre de montado, fijación de tacón y transferencia de carga al sistema de suela. En control industrial, un desajuste del asiento genera inclinación, inestabilidad rotacional y fatiga temprana de unión, por lo que planitud y compatibilidad de contorno se auditan antes de liberar.',
        ],
        'vamp' => [
            'en' => 'The vamp governs forepart fit and visual language at the same time: it carries the flex line, tension vectors, and high-visibility creasing behavior. In practice, vamp engineering is a compromise between comfort break location, material yield, and stable stitching geometry at throat and toe transitions.',
            'pt' => 'O vampão governa simultaneamente o calce da frente e a linguagem visual: suporta linha de flexão, vetores de tração e comportamento de pregas visíveis. Na prática, a engenharia do vampão equilibra localização da quebra de conforto, rendimento de material e geometria estável de costura no peito de pé e na transição da biqueira.',
            'fr' => 'La claque pilote à la fois le chaussant avant et la lecture visuelle: elle porte ligne de flexion, vecteurs de tension et comportement de plis visibles. Son ingénierie est un compromis entre position de cassure de confort, rendement matière et stabilité couture en gorge et pointe.',
            'de' => 'Das Vorderblatt steuert sowohl Vorfußpassform als auch Erscheinungsbild: es trägt Biegelinie, Spannungsvektoren und sichtbares Faltenverhalten. Die Auslegung ist ein Kompromiss aus Komfort-Knicklage, Materialausnutzung und stabiler Nahtgeometrie im Rist- und Spitzenbereich.',
            'it' => 'La porzione anteriore tomaia governa insieme calzata avampiede e linguaggio estetico: ospita linea di flessione, vettori di trazione e piega visibile. L’ingegnerizzazione è un compromesso tra posizione piega comfort, resa materiale e stabilità cucitura in gola e punta.',
            'es' => 'El empeine delantero gobierna a la vez ajuste del antepié y lenguaje visual: concentra línea de flexión, vectores de tracción y comportamiento de arruga visible. Su ingeniería equilibra ubicación de quiebre confortable, rendimiento de material y geometría estable de costura en garganta y puntera.',
        ],
        'quarter' => [
            'en' => 'The quarter is the structural shell of rearfoot control, linking collar comfort to heel containment and lacing response. Quarter balance errors often appear as late-stage asymmetry, heel slip, or throat distortion, so expert teams pair pattern checks with lasted visual gauges before volume release.',
            'pt' => 'O quarto é a casca estrutural de controlo do retropé, ligando conforto do colarinho à contenção do calcanhar e resposta do atacador. Erros de balanceamento surgem como assimetria tardia, escorregamento do calcanhar ou distorção do peito de pé, por isso equipas especialistas combinam verificação de malha e gabaritos visuais após moldação.',
            'fr' => 'Le quartier est la coque structurelle du contrôle arrière-pied, reliant confort du col à maintien talon et réponse du laçage. Les erreurs d’équilibrage se révèlent tardivement par asymétrie, glissement talon ou déformation de gorge; d’où contrôle patron + jauge visuelle sur forme.',
            'de' => 'Das Quartier bildet die strukturelle Hülle der Rückfußführung und verbindet Schaftrandkomfort mit Fersenhalt und Schnürreaktion. Balancefehler zeigen sich oft spät als Asymmetrie, Fersenschlupf oder Ristverzug; daher sind Schnittprüfung und Leisten-Sichtlehre vor Serienfreigabe essenziell.',
            'it' => 'Il quartiere è la struttura di controllo del retropiede: collega comfort collarino, contenimento tallone e risposta allacciatura. Errori di bilanciamento emergono tardi come asimmetria, slittamento tallone o deformazione gola, quindi i team esperti combinano controllo modello e verifica visiva su forma.',
            'es' => 'El cuarto es la envolvente estructural del control de retropié, conectando confort de collar con sujeción de talón y respuesta de cordonera. Los errores de balance suelen aparecer tarde como asimetría, deslizamiento de talón o distorsión de garganta; por eso se combina chequeo de patrón con galga visual en horma.',
        ],
        'toe-puff' => [
            'en' => 'Toe puff selection is a thermal-mechanical decision, not just a stiffness choice: activation curve, memory, and bonding compatibility determine long-term toe shape retention. Over-specification creates comfort hotspots; under-specification leads to collapse, profile flattening, and premature visual aging.',
            'pt' => 'A escolha da ponteira é uma decisão termo-mecânica, não apenas de rigidez: curva de ativação, memória e compatibilidade de colagem definem retenção de forma ao longo do tempo. Sobre-especificação cria pontos de pressão; subespecificação provoca colapso, achatamento de perfil e envelhecimento visual precoce.',
            'fr' => 'Le choix du contrefort avant est thermo-mécanique, pas seulement une question de rigidité: courbe d’activation, mémoire et compatibilité de collage déterminent la tenue de forme. Un surdimensionnement crée des points durs; un sous-dimensionnement entraîne affaissement et perte de profil.',
            'de' => 'Die Auswahl der Zehenverstärkung ist eine thermo-mechanische Entscheidung und nicht nur eine Frage der Steifigkeit: Aktivierungskurve, Rückstellverhalten und Klebkompatibilität bestimmen die Formstabilität. Überauslegung erzeugt Druckspitzen, Unterauslegung führt zu Kollaps und Profilverlust.',
            'it' => 'La scelta del puntale è termo-meccanica, non solo di rigidità: curva di attivazione, memoria e compatibilità di incollaggio determinano la tenuta forma nel tempo. Sovra-specifica crea punti pressione; sotto-specifica porta a collasso e appiattimento del profilo.',
            'es' => 'La selección del refuerzo de puntera es termo-mecánica, no solo de rigidez: curva de activación, memoria y compatibilidad de pegado determinan la retención de forma. La sobreespecificación crea puntos de presión; la subespecificación causa colapso y pérdida de perfil.',
        ],
        'heel-counter' => [
            'en' => 'Heel counter engineering controls rearfoot integrity, heel lock, and topline stability under repeated entry and gait cycles. Counter thickness, skive edge, and molding profile must match seat geometry and lining stack; otherwise, field failures show as heel collapse, lining abrasion, or persistent slip.',
            'pt' => 'A engenharia do contraforte controla integridade do retropé, bloqueio do calcanhar e estabilidade da linha superior em ciclos repetidos de calce e marcha. Espessura, borda de rebaixo e perfil de moldação têm de casar com o assento e o pacote de forros; caso contrário surgem colapso de talão, abrasão de forro e escorregamento persistente.',
            'fr' => 'L’ingénierie du contrefort arrière pilote l’intégrité du talon, le verrouillage et la stabilité du col en usage répété. Épaisseur, parage et profil de moulage doivent correspondre à l’assise talon et à l’empilement doublure, sinon apparaissent affaissement, abrasion doublure et glissement.',
            'de' => 'Die Fersenkappen-Auslegung steuert Rückfußintegrität, Fersenhalt und Schaftrandstabilität bei wiederholter Nutzung. Stärke, Schärfkante und Formprofil müssen zu Fersensitz und Futterpaket passen; sonst entstehen Fersenkollaps, Futterabrieb und dauerhafter Schlupf.',
            'it' => 'L’ingegneria del contrafforte governa integrità retropiede, blocco tallone e stabilità topline in uso ripetuto. Spessore, bordo di scarnitura e profilo di stampaggio devono combaciare con sede tallone e stack fodere, altrimenti compaiono collasso tallone, abrasione fodera e slittamento persistente.',
            'es' => 'La ingeniería del contrafuerte controla integridad del retropié, bloqueo de talón y estabilidad de línea superior en uso repetido. Espesor, rebaje y perfil de moldeado deben ajustarse al asiento de talón y al paquete de forros; si no, aparecen colapso de talón, abrasión de forro y deslizamiento persistente.',
        ],
        'strobel-board' => [
            'en' => 'Strobel board is the flat foundation staged before the Strobel stitch closes the upper: board flatness, moisture, and perimeter stiffness set whether the next seam can run at toe-spring radius without gap. This step is upstream of lasting on cemented lightweight routes—once the board is stitched to the upper, the pair enters the lasting chain and, after seat release, the cemented sequence roughing → cementing → activation → press.',
            'pt' => 'A base Strobel é a fundação plana preparada antes da costura Strobel fechar o cabedal: planicidade, humidade e rigidez perimetral definem se a costura seguinte corre no raio do toe spring sem vão. Esta etapa fica a montante da moldação em rotas coladas leves—após costurar base ao cabedal, o par entra na cadeia de moldação e, após libertação do assento, na sequência rugosagem → colagem → ativação → prensa.',
            'fr' => 'La base Strobel est la fondation plane préparée avant que la couture Strobel boucle la tige: planéité, humidité et rigidité périmétrique déterminent si la couture suivante tourne au rayon toe spring sans écart. Cette étape est en amont du montage sur routes collées légères—une fois base cousue à la tige, la paire entre dans la chaîne montage puis après libération assise la séquence rugosification → cimentation → activation → presse.',
            'de' => 'Die Strobelplatte ist die flache Grundlage vor der Strobelnaht den Schaft schliesst: Ebenheit, Feuchte und Umfangssteifigkeit bestimmen, ob die folgende Naht am Toe-Spring-Radius ohne Spalt laeuft. Dieser Schritt liegt vor dem Aufziehen auf leichten Kleberouten—nach dem Vernaehen von Platte und Schaft geht das Paar in die Aufziehkette und nach Sitzfreigabe in die Sequenz Aufrauen → Verkleben → Aktivierung → Presse.',
            'it' => 'La base Strobel e la fondazione piatta preparata prima che la cucitura Strobel chiuda la tomaia: planarità, umidità e rigidità perimetrale definiscono se la cucitura successiva corre al raggio toe spring senza gap. Questo passo e a monte del montaggio sulle route cementate leggere—dopo aver cucito base e tomaia, la paia entra nella catena di montaggio e, dopo rilascio sede, nella sequenza rugosatura → incollaggio → attivazione → pressa.',
            'es' => 'La base Strobel es la fundación plana preparada antes de que la costura Strobel cierre el corte: planicidad, humedad y rigidez perimetral definen si la costura siguiente corre en el radio de toe spring sin holgura. Este paso queda aguas arriba del montado en rutas cementadas ligeras—tras coser base al corte, el par entra en la cadena de montado y, tras liberación de asiento, en la secuencia rugosado → pegado → activación → prensa.',
        ],
        'strobel-stitch' => [
            'en' => 'Strobel stitch is the closure that converts board and upper into one flexible platform before lasting entry: at toe-spring radius, feed synchronization drift appears first as measurable board-to-upper gap. Process control is strict because a 0.5 mm seam gap at Strobel quickly propagates into lining shear after side pull and into unstable perimeter geometry at seat release. Handoff is therefore route-gated: Strobel QC releases only when seam lock integrity and board tension both match the last-family route card.',
            'pt' => 'A costura Strobel é o fecho base-cabedal que define se um par leve pode entrar em moldação sem delaminação sob tração: a costura corre em alta cadência no raio do toe spring onde deriva de sincronismo se manifesta primeiro como vão mensurável base-cabedal. Resets de manutenção são rastreados porque 0,5 mm de vão nesta costura tornam-se cisalhamento do forro após moldação lateral. A passagem operacional é explícita—o CQ Strobel liberta para moldação só quando fecho do ponto e tensão da base cumprem a ficha de rota da família de forma.',
            'fr' => 'La couture Strobel est la fermeture qui transforme base et tige en plateforme flexible avant entrée en montage: au rayon toe spring, la dérive de synchronisme apparaît d’abord comme un écart base-tige mesurable. Le contrôle est strict car 0,5 mm d’écart à cette couture se propage rapidement en cisaillement doublure après tirage latéral puis en géométrie périmétrique instable à la libération d’assise. Le transfert est donc gate de route: le QC Strobel libère seulement si verrouillage du point et tension de base correspondent à la gamme de la famille de forme.',
            'de' => 'Die Strobelnaht ist der Abschluss, der Platte und Schaft vor dem Aufziehen zu einer flexiblen Plattform verbindet: am Toe-Spring-Radius wird Vorschub-Sync-Drift zuerst als messbarer Platten-Schaft-Spalt sichtbar. Die Prozessfuehrung ist strikt, weil 0,5 mm Nahtspalt nach dem Seitenzug rasch in Futter-Scherung und bei Sitzfreigabe in instabile Umfangsgeometrie uebergeht. Die Uebergabe ist daher route-gatet: Strobel-QC gibt nur frei, wenn Stichverriegelung und Plattenzug zur Route-Card der Leistenfamilie passen.',
            'it' => 'La cucitura Strobel è la chiusura che unisce base e tomaia in una piattaforma flessibile prima dell\'ingresso in montaggio: al raggio toe spring la deriva di sincronismo emerge prima come gap base-tomaia misurabile. Il controllo processo è rigoroso perché 0,5 mm di gap su questa cucitura si propaga rapidamente in taglio fodera dopo il tiro laterale e in geometria perimetrale instabile al rilascio sede. Il passaggio è quindi gate di route: il QC Strobel rilascia solo quando blocco punto e tensione base rispettano la scheda della famiglia forma.',
            'es' => 'La costura Strobel es el cierre base-corte que define si un par ligero puede entrar en montado sin delaminación bajo tracción: la costura corre a alta cadencia en el radio de toe spring donde la deriva de sincronismo aparece primero como holgura base-corte medible. Los resets de mantenimiento se rastrean porque 0,5 mm de holgura en esta costura se convierte en cizallamiento de forro tras montado lateral. El pase operativo es explícito—el QC Strobel libera a montado solo si el cierre de puntada y la tensión de base cumplen la hoja de ruta de la familia de horma.',
        ],
        'cementing' => [
            'en' => 'On cemented routes, cementing is the governed adhesive-application station that sets chemical viability for the full activation → pressing chain. Spread mass, film continuity, and open-time control at this post determine whether the next tunnel cycle can reactivate a live interface or whether transfer latency will deliver dead tack to the press.',
            'pt' => 'Em rotas coladas, a colagem é a aplicação de adesivo que tem de concluir antes da ativação térmica: gramagem e tempo aberto neste posto definem se a passagem seguinte no túnel reativa a química ou se a latência de transferência anula a união antes da prensagem.',
            'fr' => 'Sur routes cimentées, la cimentation est le poste d’application adhésive gouverné qui conditionne la viabilité chimique de la chaîne activation → pressage. Masse déposée, continuité du film et contrôle du temps ouvert à ce poste déterminent si le tunnel suivant réactive une interface vivante ou si la latence de transfert amène un tack mort à la presse.',
            'de' => 'Auf geklebten Routen ist die Verklebung die gefuehrte Auftragstation, die die chemische Tragfaehigkeit der gesamten Aktivierungs- und Presskette setzt. Auftragsmasse, Filmkontinuitaet und Offenzeitkontrolle an dieser Station entscheiden, ob der naechste Tunnelzyklus eine lebende Grenzflaeche reaktiviert oder ob Transferlatenz toten Tack in die Presse traegt.',
            'it' => 'Nelle route cementate, l\'incollaggio è la stazione governata di applicazione adesivo che imposta la vitalità chimica dell\'intera catena attivazione → pressa. Massa spalmata, continuità film e controllo tempo aperto in questo punto determinano se il tunnel successivo riattiva un\'interfaccia viva oppure se la latenza di trasferimento porta tack morto in pressa.',
            'es' => 'En rutas cementadas, el pegado es el paso de aplicación de adhesivo que debe completarse antes de la activación térmica: gramaje y tiempo abierto en esta estación definen si el siguiente paso en túnel reactiva la química o si la latencia de transferencia anula la unión antes del prensado.',
        ],
        'outsole' => [
            'en' => 'Outsole specification is a route-coupled compromise between grip, abrasion, flex signature, noise, and manufacturability under real process windows. Compound hardness, lug geometry, and tooling shrink compensation must be validated against wear and climate data, then checked against route constraints such as channel stitch bite on welted pairs and perimeter wetting behavior on cemented pairs.',
            'pt' => 'A especificação da sola exterior é um compromisso multiobjetivo entre aderência, abrasão, padrão de flexão, ruído e fabricabilidade. Dureza do composto, geometria de piso e compensação de retração de molde precisam de afinação com dados reais de uso e clima, não apenas com aceitação laboratorial.',
            'fr' => 'La spécification de semelle extérieure est un compromis couplé à la route entre adhérence, abrasion, signature de flexion, bruit et fabricabilité dans des fenêtres process réelles. Dureté compound, géométrie crampons et compensation de retrait moule doivent être validées sur données terrain et climat, puis vérifiées contre les contraintes de route: prise de couture canal sur paires trépointées et comportement de mouillage périmétrique sur paires cimentées.',
            'de' => 'Die Laufsohlen-Spezifikation ist ein routengekoppelter Mehrziel-Kompromiss aus Grip, Abrieb, Flexsignatur, Geraeusch und Herstellbarkeit innerhalb realer Prozessfenster. Mischhaerte, Profilgeometrie und Werkzeugschrumpfkompensation muessen mit Feld- und Klimadaten validiert und anschliessend gegen Routenrestriktionen geprueft werden: Kanalnaht-Aufnahme bei rahmengenaehten Paaren und Umfangsbenetzung bei geklebten Paaren.',
            'it' => 'La specifica suola esterna e un compromesso multi-obiettivo accoppiato alla route tra grip, abrasione, firma di flessione, rumorosita e producibilita dentro finestre processo reali. Durezza compound, geometria battistrada e compensazione ritiro stampo vanno validate su dati di uso e clima, poi verificate rispetto ai vincoli di route: presa della cucitura in canale nelle paia guardolo e comportamento di bagnatura perimetrale nelle paia cementate.',
            'es' => 'La especificación de suela exterior es un compromiso multiobjetivo entre agarre, abrasión, patrón de flexión, ruido y fabricabilidad. Dureza del compuesto, geometría de taco y compensación de retracción del molde deben ajustarse con datos reales de uso y clima, no solo con valores de laboratorio.',
        ],
        'shank' => [
            'en' => 'Shank design controls torsional behavior and waist stability across walking loads, especially in heeled and long-waist constructions. Position, length, and stiffness must align with flex groove strategy; otherwise the shoe hinges at unintended points and creates plantar pressure peaks.',
            'pt' => 'O desenho da alma controla comportamento torsional e estabilidade da cintura sob carga de marcha, sobretudo em construções com salto e cintura longa. Posição, comprimento e rigidez devem alinhar com a estratégia de sulcos de flexão; caso contrário o calçado articula em pontos errados e cria picos de pressão plantar.',
            'fr' => 'Le cambrion pilote le comportement torsionnel et la stabilité de voûte sous charge, notamment sur constructions à talon. Position, longueur et rigidité doivent s’aligner avec la stratégie de flexion; sinon la chaussure charnière hors zone et génère des pics de pression plantaire.',
            'de' => 'Die Gelenkfeder steuert Torsionsverhalten und Taillenstabilität unter Gehbelastung, besonders bei Absatzkonstruktionen. Lage, Länge und Steifigkeit müssen zur Flexrillenstrategie passen; sonst knickt der Schuh an falscher Stelle und erzeugt plantare Druckspitzen.',
            'it' => 'Il cambrione controlla comportamento torsionale e stabilità del punto vita sotto carico, soprattutto nelle costruzioni con tacco. Posizione, lunghezza e rigidità devono allinearsi alla strategia delle scanalature di flessione; altrimenti la scarpa cede in punti errati e genera picchi pressori plantari.',
            'es' => 'El cambrillón controla el comportamiento torsional y la estabilidad de la cintura bajo carga de marcha, especialmente en construcciones con tacón. Posición, longitud y rigidez deben alinearse con la estrategia de ranuras de flexión; de lo contrario el zapato bisagra en puntos no deseados y crea picos de presión plantar.',
        ],
        'eyelet-setting' => [
            'en' => 'Eyelet setting is a hardware-system operation: punch geometry, flange deformation, reinforcement support, and plating integrity all affect lace-load durability. Robust control includes pull-out sampling by panel zone and crack inspection after accelerated lacing cycles.',
            'pt' => 'A aplicação de ilhós é uma operação de sistema de ferragem: geometria de furação, deformação da aba, suporte de reforço e integridade do banho influenciam a durabilidade sob carga do atacador. O controlo robusto inclui amostragem de arranque por zona do painel e inspeção de fissuras após ciclos acelerados de aperto.',
            'fr' => 'La pose d’œillets est une opération système de quincaillerie: géométrie perçage, déformation collerette, support renfort et intégrité de traitement impactent la tenue sous charge lacet. Le contrôle robuste inclut arrachement par zone panneau et inspection fissures après cycles accélérés.',
            'de' => 'Ösensetzen ist eine Hardware-Systemoperation: Lochgeometrie, Flanschumformung, Verstärkungsunterstützung und Beschichtungsintegrität bestimmen die Haltbarkeit unter Schnürlast. Robuste Kontrolle umfasst Ausreißproben nach Panelzone und Rissprüfung nach beschleunigten Schnürzyklen.',
            'it' => 'L’applicazione occhielli è un’operazione di sistema ferramenta: geometria foratura, deformazione flangia, supporto rinforzo e integrità placcatura influenzano la durata sotto carico lacci. Il controllo robusto include campionamento estrazione per zona pannello e ispezione cricche dopo cicli accelerati.',
            'es' => 'La colocación de ojales es una operación de sistema de herrajes: geometría de perforado, deformación de pestaña, soporte de refuerzo e integridad de recubrimiento afectan la durabilidad bajo carga de cordón. El control robusto incluye muestreo de arranque por zona de panel e inspección de fisuras tras ciclos acelerados.',
        ],
        'flex-test' => [
            'en' => 'Flex testing should reproduce category-specific stress, not just count cycles: bend radius, temperature, dwell, and contamination state fundamentally change failure signatures. Meaningful footwear flex protocols correlate crack onset and bond degradation with real gait zones and expected climate exposure.',
            'pt' => 'O teste de flexão deve reproduzir esforço específico da categoria, não apenas contar ciclos: raio de dobra, temperatura, dwell e condição de contaminação mudam o tipo de falha. Protocolos úteis correlacionam início de fissura e degradação de colagem com zonas reais de marcha e clima esperado.',
            'fr' => 'L’essai de flexion doit reproduire la contrainte de catégorie, pas seulement un nombre de cycles: rayon, température, maintien et contamination modifient fortement la signature de défaillance. Les protocoles pertinents corrèlent amorce de fissure et dégradation collage aux zones réelles de marche et au climat attendu.',
            'de' => 'Der Biegetest muss kategoriegerechte Beanspruchung abbilden und nicht nur Zyklen zählen: Biegeradius, Temperatur, Verweilzeit und Kontaminationszustand verändern das Ausfallbild wesentlich. Relevante Protokolle korrelieren Rissanlauf und Klebdegradation mit realen Gangzonen und erwartetem Klima.',
            'it' => 'Il test di flessione deve riprodurre lo stress specifico di categoria, non solo conteggiare cicli: raggio piega, temperatura, dwell e stato di contaminazione cambiano la firma di guasto. Protocolli significativi correlano innesco cricca e degrado incollaggio con zone reali di passo e clima previsto.',
            'es' => 'El ensayo de flexión debe reproducir el esfuerzo específico de categoría, no solo contar ciclos: radio de doblado, temperatura, tiempo de permanencia y estado de contaminación cambian la firma de fallo. Protocolos útiles correlacionan inicio de grieta y degradación de unión con zonas reales de marcha y clima esperado.',
        ],
        'open-time-drift' => [
            'en' => 'Open-time drift is a stability signal of adhesive process health: when solvent evaporation, line temperature, or transfer delay shifts tack response, bonding becomes probabilistic instead of engineered. Mature plants monitor drift by station and hour, then rebalance spread weight, tunnel profile, and dispatch cadence before peel fallout appears.',
            'pt' => 'A deriva do tempo aberto é um sinal de estabilidade do processo adesivo: quando evaporação de solvente, temperatura de linha ou atraso de transferência alteram o tack, a colagem torna-se probabilística em vez de controlada. Fábricas maduras monitorizam a deriva por posto e hora e reequilibram gramagem, perfil do túnel e cadência antes de surgir queda em peel.',
            'fr' => 'La dérive du temps ouvert est un signal de santé process adhésif: quand évaporation solvant, température ligne ou délai de transfert déplacent la fenêtre de tack, le collage devient probabiliste. Les ateliers matures suivent cette dérive par poste et par heure puis réajustent dépôt, tunnel et cadence avant la dérive pelage.',
            'de' => 'Offenzeit-Drift ist ein Stabilitätsindikator des Klebeprozesses: verschieben Lösungsmittelverdunstung, Linientemperatur oder Transferverzug das Tack-Verhalten, wird die Verklebung probabilistisch statt beherrscht. Reife Werke überwachen Drift nach Station und Stunde und korrigieren Auftragsmenge, Tunnelprofil und Takt vor Schälabfällen.',
            'it' => 'La deriva del tempo aperto è un segnale di stabilità del processo adesivo: quando evaporazione solvente, temperatura linea o ritardo di trasferimento spostano il tack, l’incollaggio diventa probabilistico. Le linee mature monitorano la deriva per stazione e ora e ribilanciano grammatura, profilo tunnel e cadenza prima del calo peel.',
            'es' => 'La deriva del tiempo abierto es una señal de estabilidad del proceso adhesivo: cuando evaporación de solvente, temperatura de línea o retraso de transferencia desplazan el tack, el pegado se vuelve probabilístico. Las plantas maduras monitorean deriva por estación y hora y reequilibran gramaje, perfil de túnel y cadencia antes de caída en pelado.',
        ],
        'outsole-cure-window' => [
            'en' => 'Outsole cure window control defines when a bonded pair can be stressed without hidden damage: early handling may pass appearance while reducing cohesive strength reserve. Advanced teams model cure readiness with adhesive chemistry, part mass, and ambient moisture, then lock release criteria to measured rather than assumed cure states.',
            'pt' => 'O controlo da janela de cura da sola define quando um par colado pode ser manuseado sem dano oculto: manipulação precoce pode passar no aspeto e reduzir reserva de coesão. Equipas avançadas modelam prontidão de cura por química do adesivo, massa da peça e humidade ambiente, e bloqueiam liberação por estado medido.',
            'fr' => 'Le pilotage de la fenêtre de cure semelle détermine quand une paire collée peut être sollicitée sans dommage latent: une manipulation trop tôt peut rester visuellement conforme mais réduire la réserve cohésive. Les équipes avancées modélisent la cure avec chimie, masse pièce et humidité puis libèrent sur mesure réelle.',
            'de' => 'Die Steuerung des Laufsohlen-Aushärtefensters definiert, wann ein verklebtes Paar ohne verdeckten Schaden belastet werden darf: zu frühe Handhabung kann optisch bestehen und dennoch Kohäsionsreserve verlieren. Fortgeschrittene Teams modellieren Aushärtung mit Klebstoffchemie, Bauteilmasse und Umgebungsfeuchte und geben nur messbasiert frei.',
            'it' => 'Il controllo della finestra di cura suola definisce quando un paio incollato può essere sollecitato senza danno latente: manipolazioni precoci possono apparire conformi ma ridurre la riserva coesiva. Team avanzati modellano la prontezza di cura con chimica adesivo, massa pezzo e umidità e rilasciano su misura reale.',
            'es' => 'El control de la ventana de curado de suela define cuándo un par pegado puede someterse a esfuerzo sin daño oculto: manipulación temprana puede verse conforme y reducir reserva cohesiva. Equipos avanzados modelan curado con química del adhesivo, masa de pieza y humedad ambiental y liberan con criterio medido.',
        ],
        'peel-signature-taxonomy' => [
            'en' => 'Peel signature taxonomy classifies failure surfaces into actionable categories such as adhesive, cohesive, substrate tear, and mixed fracture. Its value is operational: by encoding signature frequency by material lot, activation profile, and size, teams move from anecdotal troubleshooting to targeted CAPA on real root-cause clusters.',
            'pt' => 'A taxonomia de assinatura de peel classifica superfícies de falha em categorias acionáveis como adesiva, coesiva, rasgo de substrato e fratura mista. O valor é operacional: ao codificar frequência por lote de material, perfil de ativação e tamanho, as equipas saem da anedota e aplicam CAPA dirigida à causa-raiz.',
            'fr' => 'La taxonomie des signatures de pelage classe les surfaces de rupture en catégories actionnables: adhésive, cohésive, arrachement substrat et fracture mixte. Son intérêt est opérationnel: en codant la fréquence par lot matière, profil d’activation et pointure, les équipes passent du constat anecdotique au CAPA ciblé.',
            'de' => 'Die Schälsignatur-Taxonomie ordnet Bruchflächen in umsetzbare Kategorien wie adhäsiv, kohäsiv, Substratriss und Mischbruch. Ihr Nutzen ist operativ: durch Codierung der Häufigkeit nach Materialcharge, Aktivierungsprofil und Größe wechseln Teams von Anekdoten zu gezielter CAPA auf echte Ursachencluster.',
            'it' => 'La tassonomia delle firme di peel classifica le superfici di rottura in categorie azionabili: adesiva, coesiva, strappo substrato e frattura mista. Il valore è operativo: codificando frequenza per lotto materiale, profilo attivazione e taglia, i team passano dall’aneddoto al CAPA mirato su cluster causa-radice.',
            'es' => 'La taxonomía de firma de pelado clasifica superficies de fallo en categorías accionables: adhesiva, cohesiva, desgarro de sustrato y fractura mixta. Su valor es operativo: al codificar frecuencia por lote de material, perfil de activación y talla, los equipos pasan de anécdotas a CAPA focalizado en causas raíz.',
        ],
        'thermal-aging-behavior' => [
            'en' => 'Thermal aging behavior describes how bond systems evolve under repeated heat exposure, storage, and service climate: modulus rise, embrittlement, and interfacial weakening rarely occur at the same rate. Reliable programs couple accelerated aging with peel and flex signatures so shelf-life, transport, and field durability assumptions stay evidence-based.',
            'pt' => 'O comportamento de envelhecimento térmico descreve como sistemas de união evoluem sob exposição repetida ao calor, armazenagem e clima de uso: aumento de módulo, fragilização e enfraquecimento interfacial raramente evoluem à mesma velocidade. Programas robustos ligam envelhecimento acelerado a assinaturas de peel e flexão para validar premissas de durabilidade.',
            'fr' => 'Le comportement de vieillissement thermique décrit l’évolution des systèmes de liaison sous chaleur répétée, stockage et climat d’usage: hausse de module, fragilisation et affaiblissement interfacial n’évoluent pas au même rythme. Les programmes robustes couplent vieillissement accéléré et signatures pelage/flexion pour valider la durabilité réelle.',
            'de' => 'Das thermische Alterungsverhalten beschreibt die Entwicklung von Verbundsystemen unter wiederholter Wärme, Lagerung und Einsatzklima: Modulanstieg, Versprödung und Grenzflächenabbau verlaufen selten gleich schnell. Belastbare Programme koppeln beschleunigte Alterung mit Schäl- und Biegesignaturen für evidenzbasierte Haltbarkeitsannahmen.',
            'it' => 'Il comportamento di invecchiamento termico descrive come i sistemi di legame evolvono con esposizione ripetuta al calore, stoccaggio e clima d’uso: aumento modulo, infragilimento e indebolimento interfacciale non avanzano allo stesso ritmo. Programmi affidabili accoppiano invecchiamento accelerato e firme peel/flessione per validare la durata.',
            'es' => 'El comportamiento de envejecimiento térmico describe cómo evolucionan los sistemas de unión bajo calor repetido, almacenamiento y clima de uso: aumento de módulo, fragilización y debilitamiento interfacial no avanzan al mismo ritmo. Programas robustos acoplan envejecimiento acelerado con firmas de pelado/flexión para validar durabilidad real.',
        ],
        'adhesive-viscosity' => [
            'en' => 'Adhesive viscosity is a first-order process variable that governs wetting, transfer film continuity, and bond-line thickness distribution. Expert control links viscosity windows to solvent ratio, temperature, and pot life, then confirms application mass by zone to avoid hidden dry spots or squeeze-out driven weak interfaces.',
            'pt' => 'A viscosidade do adesivo é uma variável de primeira ordem que governa molhabilidade, continuidade do filme transferido e distribuição de espessura da linha de cola. O controlo especialista liga janelas de viscosidade a razão de solvente, temperatura e vida útil de mistura, confirmando massa aplicada por zona.',
            'fr' => 'La viscosité adhésif est une variable process de premier ordre qui pilote mouillage, continuité du film transféré et répartition d’épaisseur de ligne de collage. Le contrôle expert relie fenêtres de viscosité au ratio solvant, température et vie en pot, puis valide la masse déposée par zone.',
            'de' => 'Die Klebstoffviskosität ist eine Prozessgröße erster Ordnung für Benetzung, Filmkontinuität und Dickenverteilung der Klebefuge. Expertensteuerung verknüpft Viskositätsfenster mit Lösungsmittelanteil, Temperatur und Topfzeit und prüft die Auftragsmasse zonenbezogen, um Trockenzonen oder Ausquetschung zu vermeiden.',
            'it' => 'La viscosità adesivo è una variabile di primo livello che governa bagnabilità, continuità del film trasferito e distribuzione spessore linea di incollaggio. Il controllo esperto collega finestre viscosità a rapporto solvente, temperatura e vita miscela, poi verifica massa applicata per zona.',
            'es' => 'La viscosidad del adhesivo es una variable de primer orden que gobierna humectación, continuidad de película transferida y distribución de espesor de línea de pegado. El control experto conecta ventanas de viscosidad con relación de solvente, temperatura y vida útil de mezcla, validando masa aplicada por zona.',
        ],
        'primer-dry-time' => [
            'en' => 'Primer dry time is the boundary between activation-ready chemistry and trapped-solvent risk: under-dry films suppress adhesion stability, over-dry films lose reactivity. High-capability lines define dry-time envelopes by substrate and climate, then verify with process indicators instead of relying on clock time alone.',
            'pt' => 'O tempo de secagem do primário é a fronteira entre química pronta para ativação e risco de solvente aprisionado: secagem insuficiente reduz estabilidade de adesão, secagem excessiva perde reatividade. Linhas de alta capacidade definem envelopes por substrato e clima e verificam com indicadores de processo, não só por relógio.',
            'fr' => 'Le temps de séchage du primaire est la frontière entre chimie prête à l’activation et risque de solvant piégé: un sous-séchage dégrade la stabilité d’adhésion, un sur-séchage réduit la réactivité. Les lignes performantes définissent des enveloppes par substrat et climat, validées par indicateurs process.',
            'de' => 'Die Primer-Trocknungszeit markiert die Grenze zwischen aktivierbarer Chemie und eingeschlossenem Lösungsmittel: Untertrocknung schwächt die Haftstabilität, Übertrocknung senkt die Reaktivität. Leistungsfähige Linien definieren Zeitfenster nach Substrat und Klima und verifizieren mit Prozessindikatoren statt nur per Uhr.',
            'it' => 'Il tempo di asciugatura primer è il confine tra chimica pronta all’attivazione e rischio solvente intrappolato: sotto-asciugatura riduce stabilità adesiva, sovra-asciugatura riduce reattività. Linee capaci definiscono finestre per substrato e clima e verificano con indicatori di processo, non solo col tempo cronologico.',
            'es' => 'El tiempo de secado del primer es la frontera entre química lista para activación y riesgo de solvente atrapado: secado insuficiente reduce estabilidad adhesiva, secado excesivo reduce reactividad. Líneas de alta capacidad definen ventanas por sustrato y clima y validan con indicadores de proceso, no solo por reloj.',
        ],
        'press-pressure-map' => [
            'en' => 'Press pressure mapping translates machine settings into real perimeter load, exposing dead zones that drive local peel failures. Engineering-grade control compares pressure distribution against sole geometry and upper stiffness stack, then adjusts bladder condition, tooling compliance, and cycle profile to normalize bond consolidation.',
            'pt' => 'O mapeamento de pressão da prensa traduz parâmetros de máquina em carga real no perímetro, expondo zonas mortas que geram falhas locais de peel. O controlo de engenharia compara distribuição de pressão com geometria da sola e rigidez do conjunto superior e ajusta estado da bexiga, conformidade do ferramental e ciclo.',
            'fr' => 'La cartographie de pression presse convertit les réglages machine en charge réelle au périmètre et révèle les zones mortes responsables d’échecs de pelage localisés. Le pilotage ingénierie compare la distribution à la géométrie semelle et à la rigidité de tige puis ajuste vessie, compliance outillage et profil de cycle.',
            'de' => 'Das Druckbild der Presse übersetzt Maschineneinstellungen in reale Umfangslast und macht Totzonen sichtbar, die lokale Schälfehler erzeugen. Engineering-Kontrolle vergleicht die Verteilung mit Sohlengeometrie und Schaftsteifigkeit und passt Blasenzustand, Werkzeugnachgiebigkeit und Zyklusprofil zur Homogenisierung an.',
            'it' => 'La mappa pressione pressa traduce i settaggi macchina in carico reale sul perimetro, evidenziando zone morte che causano failure locali di peel. Il controllo ingegneristico confronta la distribuzione con geometria suola e rigidità stack tomaia, regolando stato membrana, compliance attrezzaggio e profilo ciclo.',
            'es' => 'El mapa de presión de prensa traduce ajustes de máquina en carga real perimetral, revelando zonas muertas que provocan fallos locales de pelado. El control de ingeniería compara distribución con geometría de suela y rigidez del conjunto superior y ajusta estado de vejiga, compliance de herramental y perfil de ciclo.',
        ],
        'cement-open-time' => [
            'en' => 'Cement open time is a moving process window, not a static lab number: line stoppages, queue density, and ambient shifts can collapse tack faster than operators perceive. Robust governance defines maximum transfer latency by material family and verifies in-shift tack behavior to prevent late-press bonds that fail in early life.',
            'pt' => 'O tempo aberto da cola é uma janela de processo dinâmica, não um número fixo de laboratório: paragens de linha, densidade de fila e variações ambientais podem colapsar o tack mais rápido do que o operador percebe. A governação robusta define latência máxima por família de material e valida tack em turno.',
            'fr' => 'Le temps ouvert de colle est une fenêtre process dynamique, pas une valeur labo fixe: arrêts ligne, densité de file et variations ambiantes peuvent faire chuter le tack avant perception opérateur. Une gouvernance robuste fixe la latence transfert maximale par famille matière et vérifie le tack en poste.',
            'de' => 'Die Offenzeit des Klebers ist ein dynamisches Prozessfenster und kein statischer Laborwert: Linienstopps, Wartedichte und Umgebungsänderungen können Tack schneller einbrechen lassen als wahrgenommen. Robuste Steuerung definiert maximale Transferlatenz je Materialfamilie und prüft Tack-Verhalten schichtnah.',
            'it' => 'Il tempo aperto adesivo è una finestra di processo dinamica, non un numero fisso da laboratorio: fermate linea, densità coda e variazioni ambientali possono far collassare il tack prima della percezione operatore. La governance robusta definisce latenza massima di trasferimento per famiglia materiale e verifica tack in turno.',
            'es' => 'El tiempo abierto del adhesivo es una ventana de proceso dinámica, no un número fijo de laboratorio: paradas de línea, densidad de cola y cambios ambientales pueden colapsar el tack antes de que el operador lo perciba. Una gobernanza robusta define latencia máxima de transferencia por familia de material y verifica tack en turno.',
        ],
        'heat-activation' => [
            'en' => 'Heat activation is the second lock-control step of cemented bottoming between cementing and sole pressing: pairs must enter tunnel flow only inside route-card transfer latency. Tunnel dwell is tuned to reactivate tack without collapsing roughing topography—under-activation transfers dead chemistry to press, while over-activation exhausts volatiles before perimeter pressure can achieve stable wetting. Inline QC compares IR belt read to the energy-density profile for the stack; temperature drift toward cold shows as heel-seat peel after press, drift hot scorches forepart film before the compression map can wet the perimeter.',
            'pt' => 'A ativação térmica é o segundo posto de lock control do fundo colado entre colagem e prensagem: os pares só entram no túnel dentro da latência de transferência da ficha. O dwell reativa tack sem degradar a topografia de rugosagem—subativação envia química morta à prensa; sobre-ativação esgota voláteis antes do mapa perimetral. O CQ compara leitura IR do túnel ao perfil de densidade energética da pilha; deriva para frio aparece como peel no assento do calcanhar, deriva para quente queima o filme da frente antes da compressão perimetral.',
            'fr' => 'L’activation thermique est le deuxième poste de verrouillage de la route cimentée, entre cimentation et pressage semelle: les paires n’entrent au tunnel que dans la latence de transfert de la gamme. Le maintien réactive le tack sans dégrader la rugosification—sous-activation envoie une chimie morte à la presse, sur-activation épuise les volatils. Le QC compare la lecture IR tunnel au profil de densité énergétique de l’empilement; une dérive froide donne pelage assise talon, une dérive chaude brûle le film avant-pied avant mouillage périmétrique.',
            'de' => 'Die Waermeaktivierung ist der zweite Lock-Control-Schritt der geklebten Route zwischen Verklebung und Sohlenpressung: Paare duerfen nur innerhalb der Route-Card-Transferlatenz in den Tunnel. Die Verweilzeit reaktiviert Tack ohne Aufrauhtopografie zu zerstoeren—Unteraktivierung liefert tote Chemie, Ueberaktivierung verbraucht Volatile. Inline-QC vergleicht IR-Tunnelwert mit Energiedichteprofil des Stapels; Kalt-Drift zeigt Fersensitz-Schaelung nach der Presse, Heiss-Drift versengt Vorfussfilm vor Umfangsbenetzung.',
            'it' => 'L\'attivazione termica è il secondo controllo di blocco del fondo cementato tra incollaggio e pressatura suola: le paia entrano nel tunnel solo entro la latenza di trasferimento della scheda. Il tempo di permanenza riattiva il tack senza degradare la rugosatura—sotto-attivazione porta chimica inattiva in pressa, sovra-attivazione consuma i volatili. Il CQ confronta la lettura IR del tunnel con il profilo di densità energetica dello stack; deriva verso il freddo produce peel sulla sede tallone, deriva verso il caldo brucia il film avampiede prima della bagnatura perimetrale.',
            'es' => 'La activación térmica es el segundo lock control del fondo cementado entre pegado y prensado: los pares entran al túnel solo dentro de la latencia de transferencia de la hoja. El dwell reactiva tack sin degradar rugosado—subactivación envía química muerta a prensa; sobreactivación agota volátiles. El QC compara lectura IR del túnel con el perfil de densidad energética del apilado; deriva en frío muestra pelado en asiento de talón, deriva en calor quema película de antepié antes del humectado perimetral.',
        ],
        'sole-pressing' => [
            'en' => 'Sole pressing is the irreversible lock step that follows heat activation on cemented chains: the press must mate upper margin and outsole landings through perimeter pressure mapping, not uniform plate load. Dwell is route-specific—heel-seat quadrants often require longer compression than forepart, especially on cupsole cavities—and QC only releases when compression signatures confirm continuous bond-line contact at vamp flex radius and seat transitions. After press opening there is no stitched recovery path; cold perimeter zones must be contained here or they become field peel.',
            'pt' => 'A prensagem da sola em rotas coladas é o passo irreversível de bloqueio após ativação: a prensa acopla margens do cabedal aos assentamentos da sola sob mapa de pressão perimetral, não carga uniforme de placa. A engenharia de planta reparte o tempo de permanência—o assento do calcanhar costuma exigir compressão mais longa que a frente em geometria cupsole na cintura—e o CQ retém a libertação até as assinaturas de compressão confirmarem contacto da linha de cola no raio de flexão do vampão e na transição do assento. Após abertura da prensa não há recuperação por costura de vira; zonas frias no perímetro do assento contêm-se aqui ou originam peel em campo.',
            'fr' => 'Le pressage semelle est l’étape de lock irréversible qui suit l’activation sur les chaînes cimentées: la presse doit accoupler marges tige et assises semelle via une carte de pression périmétrique, pas par charge uniforme de plateau. Le maintien est spécifique à la route—les quadrants d’assise talon demandent souvent plus de compression que l’avant-pied, surtout sur cavités cupsole—et le QC ne libère que lorsque les signatures de compression prouvent un contact continu de ligne de collage au rayon de flexion de claque et aux transitions d’assise. Après ouverture presse, il n’existe pas de voie cousue de récupération; les zones froides périphériques doivent être contenues ici ou deviennent des pelages terrain.',
            'de' => 'Die Sohlenpressung ist der irreversible Lock-Schritt nach der Waermeaktivierung auf geklebten Ketten: die Presse muss Schaftrand und Sohlenauflagen ueber eine Umfangsdruckkarte fuenktional vermaten, nicht ueber uniforme Plattenlast. Die Verweilzeit ist routenspezifisch—Fersensitz-Quadranten benoetigen oft laengere Kompression als der Vorfuss, besonders bei Cupsole-Hohlraeumen—und QC gibt nur frei, wenn Kompressionssignaturen einen durchgaengigen Klebefugenkontakt am Vorderblatt-Flexradius und an Sitzuebergaengen bestaetigen. Nach Pressoeffnung gibt es keinen genaehten Rueckholpfad; kalte Umfangszonen muessen hier beherrscht werden oder werden zu Feldschaelung.',
            'it' => 'La pressatura suola è il passaggio di lock irreversibile che segue l\'attivazione sulle catene cementate: la pressa deve accoppiare margine tomaia e appoggi suola tramite mappa di pressione perimetrale, non con carico uniforme di piastra. Il dwell è specifico per route—i quadranti della sede tallone richiedono spesso più compressione dell\'avampiede, soprattutto nelle cavità cupsole—e il QC rilascia solo quando le firme di compressione confermano contatto continuo della linea di incollaggio al raggio di flessione vamp e nelle transizioni sede. Dopo apertura pressa non esiste un percorso cucito di recupero; le zone fredde perimetrali vanno contenute qui o diventano peel sul campo.',
            'es' => 'La fijación de suela en rutas cementadas es el paso de bloqueo irreversible tras activación: la prensa acopla márgenes del corte a asientos de suela bajo mapa de presión perimetral, no carga uniforme de placa. Ingeniería de planta divide el dwell—el asiento de talón suele necesitar más compresión que el antepié en geometría cupsole en cintura—y el QC retiene la liberación hasta que firmas de compresión muestren contacto de línea de pegado en radio de flexión del empeine y transición de asiento. Al abrir la prensa no hay canal de cerco para retrabajo; zonas frías en perímetro de asiento se contienen aquí o se convierten en pelado en campo.',
        ],
        'bond-line' => [
            'en' => 'Bond-line quality is the physical trace of upstream discipline: roughing depth, primer condition, spread control, activation, and pressing all write their signature into this interface. Failure analytics become reliable only when bond-line defects are coded by location, morphology, and process state at build time.',
            'pt' => 'A qualidade da linha de colagem é o traço físico da disciplina a montante: profundidade de rugosagem, condição do primário, controlo de aplicação, ativação e prensagem deixam assinatura nesta interface. A análise de falhas só é fiável quando defeitos são codificados por localização, morfologia e estado do processo no fabrico.',
            'fr' => 'La qualité de la ligne de collage est la trace physique de la discipline amont: rugosification, état primaire, dépôt, activation et pressage écrivent leur signature dans cette interface. L’analyse de défaillance devient fiable quand les défauts sont codés par zone, morphologie et état process lors de l’assemblage.',
            'de' => 'Die Qualität der Klebefuge ist die physische Spur vorgelagerter Prozessdisziplin: Aufrauhtiefe, Primerzustand, Auftrag, Aktivierung und Pressung hinterlassen hier ihre Signatur. Zuverlässige Fehleranalyse entsteht erst, wenn Defekte nach Ort, Morphologie und Prozesszustand beim Aufbau codiert werden.',
            'it' => 'La qualità della linea di incollaggio è la traccia fisica della disciplina a monte: rugosatura, stato primer, controllo deposito, attivazione e pressatura lasciano qui la loro firma. L’analisi guasti è affidabile solo quando i difetti sono codificati per posizione, morfologia e stato processo in costruzione.',
            'es' => 'La calidad de la línea de pegado es la huella física de la disciplina aguas arriba: rugosado, estado del primer, control de aplicación, activación y prensado dejan su firma en esta interfaz. El análisis de fallos es fiable solo cuando defectos se codifican por ubicación, morfología y estado de proceso en fabricación.',
        ],
        'bond-peel-audit' => [
            'en' => 'A bond peel audit is a trend instrument, not a pass-fail ritual: it detects directional drift in bond robustness before customer-visible failure spikes. Best practice stratifies results by model, size, material lot, and shift, then links anomalies to process telemetry so containment and parameter correction are immediate.',
            'pt' => 'A auditoria de descolamento é um instrumento de tendência, não um ritual de aprovação/reprovação: deteta deriva direcional na robustez da união antes de picos de falha visíveis no cliente. A melhor prática estratifica por modelo, tamanho, lote e turno e liga anomalias à telemetria para conter e corrigir rapidamente.',
            'fr' => 'L’audit de pelage liaison est un outil de tendance, pas un rite conforme/non conforme: il détecte une dérive de robustesse de liaison avant les pics de retours terrain. La bonne pratique stratifie par modèle, pointure, lot et équipe puis relie les écarts à la télémétrie process pour correction immédiate.',
            'de' => 'Das Schäl-Audit der Verbindung ist ein Trendinstrument und kein bloßes Bestanden/Nicht-bestanden: es erkennt Richtungsdrift der Verbundrobustheit vor kundensichtbaren Ausfallspitzen. Best Practice schichtet Ergebnisse nach Modell, Größe, Materialcharge und Schicht und koppelt Anomalien direkt an Prozesstelemetrie.',
            'it' => 'L’audit pelatura incollaggio è uno strumento di trend, non un rito pass/fail: intercetta derive direzionali della robustezza legame prima dei picchi di failure visibili al cliente. La best practice stratifica per modello, taglia, lotto e turno e collega anomalie alla telemetria processo per azione immediata.',
            'es' => 'La auditoría de pelado de unión es un instrumento de tendencia, no un ritual de aprobado/reprobado: detecta deriva direccional de robustez de unión antes de picos de fallo visibles al cliente. La mejor práctica estratifica por modelo, talla, lote y turno y conecta anomalías con telemetría de proceso para acción inmediata.',
        ],
        'peel-strength-test' => [
            'en' => 'Peel strength testing is meaningful only when fracture reading accompanies force values: two samples with similar peak force can represent completely different reliability trajectories. Expert protocols normalize angle, rate, conditioning, and substrate state, then record fracture mode to anchor failure taxonomy and CAPA precision.',
            'pt' => 'O teste de resistência ao descolamento só é significativo quando a leitura da fratura acompanha os valores de força: duas amostras com pico semelhante podem ter trajetórias de fiabilidade opostas. Protocolos especialistas normalizam ângulo, velocidade, condicionamento e estado do substrato e registam modo de fratura.',
            'fr' => 'L’essai de pelage n’est pertinent que si la lecture de rupture accompagne la force mesurée: deux échantillons au pic proche peuvent avoir des trajectoires de fiabilité opposées. Les protocoles experts normalisent angle, vitesse, conditionnement et état substrat puis enregistrent le mode de rupture.',
            'de' => 'Der Schälfestigkeitstest ist nur aussagekräftig, wenn zur Kraft auch das Bruchbild erfasst wird: zwei Proben mit ähnlicher Spitzenkraft können völlig unterschiedliche Zuverlässigkeitsverläufe haben. Expertenprotokolle normieren Winkel, Rate, Konditionierung und Substratzustand und dokumentieren den Bruchmodus.',
            'it' => 'Il test resistenza a pelatura è significativo solo se la lettura della frattura accompagna il dato di forza: due campioni con picco simile possono avere traiettorie di affidabilità opposte. I protocolli esperti normalizzano angolo, velocità, condizionamento e stato substrato e registrano il modo di rottura.',
            'es' => 'El ensayo de resistencia al pelado solo es significativo cuando la lectura de fractura acompaña los valores de fuerza: dos muestras con pico similar pueden tener trayectorias de fiabilidad opuestas. Protocolos expertos normalizan ángulo, velocidad, acondicionamiento y estado del sustrato y registran modo de fractura.',
        ],
        'failure-mode-clustering' => [
            'en' => 'Failure mode clustering groups recurring defect signatures into statistically meaningful families, allowing teams to prioritize interventions by impact and recurrence rather than isolated anecdotes. In adhesive systems, clustering across peel morphology, climate exposure, and process telemetry reveals latent coupling effects that single-point analysis misses.',
            'pt' => 'O agrupamento de modos de falha reúne assinaturas recorrentes em famílias estatisticamente relevantes, permitindo priorizar intervenções por impacto e recorrência, não por casos isolados. Em sistemas adesivos, agrupar morfologia de peel, exposição climática e telemetria de processo revela acoplamentos latentes.',
            'fr' => 'Le clustering des modes de défaillance regroupe les signatures récurrentes en familles statistiquement pertinentes, afin de prioriser les actions selon impact et récurrence plutôt que sur des cas isolés. En système adhésif, le croisement morphologie pelage, climat et télémétrie révèle des couplages latents.',
            'de' => 'Das Clustering von Fehlermodi fasst wiederkehrende Defektsignaturen zu statistisch belastbaren Familien zusammen, damit Maßnahmen nach Wirkung und Wiederkehr priorisiert werden statt nach Einzelfällen. Bei Klebesystemen zeigt die Kombination aus Schälmorphologie, Klimaexposition und Prozesstelemetrie latente Kopplungen.',
            'it' => 'Il clustering dei modi di guasto raggruppa firme ricorrenti in famiglie statisticamente significative, permettendo di prioritizzare interventi per impatto e ricorrenza invece che per aneddoti. Nei sistemi adesivi, l’incrocio tra morfologia peel, clima e telemetria processo evidenzia effetti di accoppiamento latenti.',
            'es' => 'El agrupamiento de modos de fallo reúne firmas recurrentes en familias estadísticamente relevantes, permitiendo priorizar intervenciones por impacto y recurrencia en lugar de anécdotas aisladas. En sistemas adhesivos, cruzar morfología de pelado, clima y telemetría de proceso revela acoplamientos latentes.',
        ],
        'accelerated-aging-protocol' => [
            'en' => 'An accelerated aging protocol compresses thermal, humidity, and mechanical stress histories to expose weak bond architectures before market launch. The protocol is valid only when acceleration factors are calibrated against field data; otherwise it produces fast numbers with low predictive value for real-life durability.',
            'pt' => 'Um protocolo de envelhecimento acelerado comprime históricos de stress térmico, humidade e mecânico para expor arquiteturas de união fracas antes do lançamento. O protocolo só é válido quando fatores de aceleração são calibrados com dados de campo; caso contrário produz números rápidos com baixa previsibilidade.',
            'fr' => 'Un protocole de vieillissement accéléré compresse les historiques de contrainte thermique, humidité et mécanique pour révéler les architectures de liaison faibles avant lancement. Il n’est valable que si les facteurs d’accélération sont calibrés avec des données terrain, sinon la valeur prédictive reste faible.',
            'de' => 'Ein beschleunigtes Alterungsprotokoll komprimiert thermische, feuchte- und mechanische Belastungshistorien, um schwache Verbundarchitekturen vor Marktstart sichtbar zu machen. Es ist nur valide, wenn Beschleunigungsfaktoren gegen Felddaten kalibriert sind; sonst entstehen schnelle Zahlen mit geringer Prognosekraft.',
            'it' => 'Un protocollo di invecchiamento accelerato comprime storie di stress termico, umidità e meccanico per evidenziare architetture di legame deboli prima del lancio. È valido solo se i fattori di accelerazione sono calibrati su dati campo; altrimenti produce numeri rapidi ma poco predittivi.',
            'es' => 'Un protocolo de envejecimiento acelerado comprime historiales de estrés térmico, humedad y mecánico para exponer arquitecturas de unión débiles antes del lanzamiento. Solo es válido cuando factores de aceleración se calibran con datos de campo; de lo contrario genera números rápidos con baja capacidad predictiva.',
        ],
        'thermal-shock-cycle' => [
            'en' => 'Thermal shock cycling stresses interfaces by abrupt temperature transitions that amplify mismatch in expansion coefficients between substrates and adhesive layers. It is especially effective for revealing edge-lift propensity and micro-crack initiation at bond-line discontinuities that remain hidden under steady-state conditioning.',
            'pt' => 'O ciclo de choque térmico tensiona interfaces por transições abruptas de temperatura que amplificam incompatibilidades de expansão entre substratos e camadas adesivas. É especialmente útil para revelar propensão a levantamento de bordo e iniciação de microfissuras em descontinuidades da linha de cola.',
            'fr' => 'Le cycle de choc thermique sollicite les interfaces via transitions brusques de température qui amplifient les écarts de dilatation entre substrats et couches adhésives. Il révèle efficacement la tendance au décollement de rive et l’initiation de microfissures aux discontinuités de ligne de collage.',
            'de' => 'Der Thermoschockzyklus belastet Grenzflächen durch abrupte Temperaturwechsel, die Ausdehnungsunterschiede zwischen Substraten und Klebschichten verstärken. Damit lassen sich Kantenablöseneigung und Mikrorissbeginn an Unstetigkeiten der Klebefuge erkennen, die unter stationärer Konditionierung verborgen bleiben.',
            'it' => 'Il ciclo di shock termico sollecita le interfacce tramite transizioni brusche di temperatura che amplificano mismatch di dilatazione tra substrati e strati adesivi. È efficace nel rivelare propensione al distacco bordo e innesco micro-cricche nelle discontinuità della linea di incollaggio.',
            'es' => 'El ciclo de choque térmico tensiona interfaces mediante transiciones bruscas de temperatura que amplifican desajustes de expansión entre sustratos y capas adhesivas. Es especialmente eficaz para revelar tendencia a levantamiento de borde e inicio de microgrietas en discontinuidades de la línea de pegado.',
        ],
        'adhesive-transfer-latency' => [
            'en' => 'Adhesive transfer latency is the elapsed time between adhesive deposition or activation and final mating. Once latency exceeds the validated envelope, wetting continuity drops and the interface shifts from cohesive behavior toward adhesive peel, especially on low-energy outsole compounds.',
            'pt' => 'A latência de transferência do adesivo é o tempo decorrido entre aplicação ou ativação e a união final. Quando a latência excede o envelope validado, a continuidade de molhagem cai e a interface migra de comportamento coesivo para peel adesivo, sobretudo em compostos de sola de baixa energia superficial.',
            'fr' => 'La latence de transfert adhésif est le temps entre dépôt ou activation et assemblage final. Au-delà de l’enveloppe validée, la continuité de mouillage baisse et l’interface dérive d’un comportement cohésif vers un pelage adhésif, surtout sur semelles à faible énergie de surface.',
            'de' => 'Die Klebstoff-Transferlatenz ist die Zeit zwischen Auftrag oder Aktivierung und finalem Fügen. Überschreitet sie das validierte Fenster, sinkt die Benetzungskontinuität und die Grenzfläche verschiebt sich von kohäsivem Verhalten zu adhäsivem Schälversagen, besonders bei Laufsohlen mit geringer Oberflächenenergie.',
            'it' => 'La latenza di trasferimento adesivo è il tempo tra deposito o attivazione e accoppiamento finale. Se supera la finestra validata, cala la continuità di bagnatura e l’interfaccia passa da comportamento coesivo a peel adesivo, soprattutto su compound suola a bassa energia superficiale.',
            'es' => 'La latencia de transferencia adhesiva es el tiempo entre aplicación o activación y el acoplamiento final. Si supera la ventana validada, cae la continuidad de humectación y la interfaz pasa de comportamiento cohesivo a pelado adhesivo, especialmente en compuestos de suela de baja energía superficial.',
        ],
        'primer-reactivation-window' => [
            'en' => 'Primer reactivation window defines the interval where a dried primer can still form a chemically receptive interface after heat activation. Outside this window, apparent tack can mislead operators while bond durability drops under flex and thermal cycling.',
            'pt' => 'A janela de reativação do primário define o intervalo em que um primário seco ainda forma uma interface quimicamente recetiva após ativação térmica. Fora desta janela, o tack aparente pode enganar o operador enquanto a durabilidade da união cai em flexão e ciclo térmico.',
            'fr' => 'La fenêtre de réactivation du primaire définit l’intervalle où un primaire sec reste chimiquement réceptif après activation thermique. Hors fenêtre, un tack apparent peut tromper l’opérateur alors que la durabilité de liaison baisse en flexion et en cycle thermique.',
            'de' => 'Das Reaktivierungsfenster des Primers beschreibt den Zeitraum, in dem ein getrockneter Primer nach Wärmeaktivierung noch chemisch reaktionsfähig ist. Außerhalb dieses Fensters kann scheinbarer Tack täuschen, während die Verbundhaltbarkeit unter Biegung und Thermozyklus abfällt.',
            'it' => 'La finestra di riattivazione del primer definisce l’intervallo in cui un primer asciutto resta chimicamente recettivo dopo attivazione termica. Fuori finestra, un tack apparente può ingannare l’operatore mentre la durata del legame cala sotto flessione e ciclo termico.',
            'es' => 'La ventana de reactivación del primer define el intervalo en que un primer seco sigue siendo químicamente receptivo tras activación térmica. Fuera de ventana, un tack aparente puede engañar al operador mientras la durabilidad de unión cae en flexión y ciclo térmico.',
        ],
        'roughing-depth-profile' => [
            'en' => 'Roughing depth profile is the controlled micro-topography left on bonding surfaces. Too shallow reduces mechanical keying; too deep weakens substrate integrity and creates fracture planes that masquerade as adhesive failure during peel audits.',
            'pt' => 'O perfil de profundidade de rugosagem é a microtopografia controlada deixada na superfície de colagem. Muito raso reduz ancoragem mecânica; muito profundo enfraquece o substrato e cria planos de fratura que mascaram falha adesiva nas auditorias de peel.',
            'fr' => 'Le profil de profondeur de rugosification est la micro-topographie contrôlée laissée sur la surface de collage. Trop faible réduit l’ancrage mécanique; trop profond fragilise le substrat et crée des plans de rupture confondus avec une défaillance adhésive.',
            'de' => 'Das Aufrauhtiefenprofil ist die kontrollierte Mikrotopografie auf Klebeflächen. Zu flach reduziert die mechanische Verankerung; zu tief schwächt das Substrat und erzeugt Bruchflächen, die im Schäl-Audit fälschlich wie Klebstoffversagen wirken.',
            'it' => 'Il profilo di profondità rugosatura è la micro-topografia controllata lasciata sulle superfici di incollaggio. Troppo bassa riduce l’ancoraggio meccanico; troppo alta indebolisce il substrato e crea piani di frattura scambiati per guasto adesivo.',
            'es' => 'El perfil de profundidad de rugosado es la microtopografía controlada de la superficie de pegado. Si es insuficiente reduce anclaje mecánico; si es excesivo debilita el sustrato y crea planos de fractura que se confunden con fallo adhesivo.',
        ],
        'outsole-surface-energy' => [
            'en' => 'Outsole surface energy determines whether primer and adhesive can wet and anchor to the sole substrate with repeatable coverage. Low-energy compounds require stricter roughing, priming, and activation discipline to prevent edge lift and early-life delamination.',
            'pt' => 'A energia superficial da sola determina se primário e adesivo conseguem molhar e ancorar no substrato com cobertura repetível. Compostos de baixa energia exigem maior disciplina de rugosagem, primário e ativação para prevenir levantamento de bordo e delaminação precoce.',
            'fr' => 'L’énergie de surface de semelle détermine si primaire et adhésif peuvent mouiller et ancrer le substrat avec couverture répétable. Les composés à faible énergie exigent plus de discipline en rugosification, primaire et activation pour éviter décollement de rive et délamination précoce.',
            'de' => 'Die Oberflächenenergie der Laufsohle bestimmt, ob Primer und Klebstoff das Substrat reproduzierbar benetzen und verankern können. Niedrigenergetische Mischungen erfordern strengere Disziplin bei Aufrauen, Primern und Aktivierung, um Kantenablösung und frühe Delamination zu vermeiden.',
            'it' => 'L’energia superficiale della suola determina se primer e adesivo riescono a bagnare e ancorare il substrato con copertura ripetibile. I compound a bassa energia richiedono maggiore disciplina di rugosatura, primer e attivazione per evitare distacco bordo e delaminazione precoce.',
            'es' => 'La energía superficial de la suela determina si primer y adhesivo pueden humectar y anclar el sustrato con cobertura repetible. Los compuestos de baja energía exigen mayor disciplina en rugosado, primer y activación para prevenir levantamiento de borde y delaminación temprana.',
        ],
        'toe-puff-activation-curve' => [
            'en' => 'Toe puff activation curve links temperature, dwell, and pressure response to final toe geometry retention. If activation is misaligned, the toe can pass initial appearance checks yet collapse after break-in due to incomplete structural memory setting.',
            'pt' => 'A curva de ativação da ponteira liga temperatura, dwell e resposta à pressão à retenção final da geometria da biqueira. Se a ativação estiver desalinhada, a biqueira pode passar no aspeto inicial e colapsar após uso por memória estrutural incompleta.',
            'fr' => 'La courbe d’activation du contrefort avant relie température, maintien et réponse à la pression à la tenue géométrique de la pointe. Si l’activation est mal réglée, la pointe peut passer le contrôle visuel initial puis s’affaisser après rodage.',
            'de' => 'Die Aktivierungskurve der Zehenverstärkung verknüpft Temperatur, Verweilzeit und Druckantwort mit der finalen Formstabilität der Spitze. Bei Fehlabstimmung besteht die Spitze anfänglich optisch, kollabiert jedoch nach kurzer Tragezeit durch unvollständige Strukturspeicherung.',
            'it' => 'La curva di attivazione del puntale collega temperatura, dwell e risposta alla pressione alla tenuta geometrica finale della punta. Se disallineata, la punta può passare il controllo iniziale ma collassare dopo rodaggio per memoria strutturale incompleta.',
            'es' => 'La curva de activación del refuerzo de puntera relaciona temperatura, dwell y respuesta a presión con la retención geométrica final de la puntera. Si está desalineada, puede aprobar visual inicial y colapsar tras uso por memoria estructural incompleta.',
        ],
        'counter-edge-skive' => [
            'en' => 'Counter edge skive controls stiffness transition between reinforced heel structure and adjacent upper zones. A poorly tapered edge creates stress concentrations that accelerate lining abrasion and visible topline distortion.',
            'pt' => 'O rebaixo da borda do contraforte controla a transição de rigidez entre a estrutura reforçada do calcanhar e zonas adjacentes do cabedal. Uma transição mal afinada cria concentrações de tensão que aceleram abrasão do forro e deformação da linha superior.',
            'fr' => 'Le parage de bord du contrefort contrôle la transition de rigidité entre la structure talon renforcée et les zones voisines de tige. Un effilement mal maîtrisé crée des concentrations de contrainte qui accélèrent l’abrasion doublure et la déformation du col.',
            'de' => 'Die Schärfung der Fersenkappenkante steuert den Steifigkeitsübergang zwischen verstärkter Fersenstruktur und benachbarten Schaftzonen. Ein schlechter Verlauf erzeugt Spannungsspitzen, die Futterabrieb und sichtbaren Schaftrandverzug beschleunigen.',
            'it' => 'La scarnitura bordo contrafforte controlla la transizione di rigidità tra struttura tallone rinforzata e zone tomaia adiacenti. Una rastremazione errata crea concentrazioni di tensione che accelerano abrasione fodera e deformazione topline.',
            'es' => 'El rebajado del borde de contrafuerte controla la transición de rigidez entre la estructura reforzada del talón y zonas adyacentes del corte. Un afinado deficiente genera concentraciones de tensión que aceleran abrasión de forro y deformación de línea superior.',
        ],
        'heel-seat-contour-match' => [
            'en' => 'Heel seat contour match verifies geometric compatibility between lasted upper heel seat and outsole or heel component seat. Mismatch drives rocking contact, local stress peaks, and progressive bond fatigue concentrated at the seat perimeter.',
            'pt' => 'A correspondência de contorno do assento do calcanhar verifica a compatibilidade geométrica entre assento moldado do cabedal e assento da sola ou componente de salto. O desajuste provoca contacto oscilante, picos locais de tensão e fadiga progressiva da união no perímetro.',
            'fr' => 'La concordance de contour d’assise talon vérifie la compatibilité géométrique entre l’assise talon tige montée et l’assise semelle ou talon rapporté. Un décalage entraîne appui instable, pics de contrainte locaux et fatigue progressive de liaison en périphérie.',
            'de' => 'Die Konturpassung des Fersensitzes prüft die geometrische Kompatibilität zwischen aufgezogenem Fersensitz des Schafts und dem Sitz von Sohle oder Absatzteil. Fehlpassung führt zu kippendem Kontakt, lokalen Spannungsspitzen und fortschreitender Verbundermüdung am Umfang.',
            'it' => 'La corrispondenza del profilo sede tallone verifica la compatibilità geometrica tra sede tallone della tomaia montata e sede di suola o componente tacco. Il mismatch causa contatto instabile, picchi di tensione locali e fatica progressiva del legame al perimetro.',
            'es' => 'La concordancia de contorno del asiento de talón verifica compatibilidad geométrica entre asiento de talón del corte montado y asiento de suela o componente de tacón. El desajuste provoca contacto inestable, picos locales de tensión y fatiga progresiva de unión en el perímetro.',
        ],
        'cure-gradient-mapping' => [
            'en' => 'Cure gradient mapping tracks cure progression by zone and depth instead of assuming homogeneous cure across the pair. It reveals cold spots and over-cured zones that correlate with later edge lift, brittle fracture, or cohesive drop in peel retention.',
            'pt' => 'O mapeamento de gradiente de cura acompanha a evolução da cura por zona e profundidade, em vez de assumir cura homogénea no par. Revela zonas frias e sobrecuradas que se correlacionam com levantamento de bordo, fratura frágil ou perda coesiva no peel.',
            'fr' => 'La cartographie du gradient de cure suit la progression de cure par zone et profondeur au lieu de supposer une cure homogène sur la paire. Elle révèle zones froides et surcuites corrélées à décollement de rive, rupture fragile ou perte de cohésion au pelage.',
            'de' => 'Die Aushärte-Gradientenkartierung verfolgt den Härtungsfortschritt nach Zone und Tiefe, statt eine homogene Härtung über das Paar anzunehmen. Sie zeigt kalte und überhärtete Bereiche, die mit Kantenablösung, sprödem Bruch oder kohäsivem Verlust im Schälverhalten korrelieren.',
            'it' => 'La mappatura del gradiente di cura segue la progressione della cura per zona e profondità invece di assumere una cura omogenea sul paio. Evidenzia zone fredde e sovracurate correlate a distacco bordo, frattura fragile o perdita coesiva nel peel.',
            'es' => 'El mapeo de gradiente de curado sigue la progresión de curado por zona y profundidad en lugar de asumir curado homogéneo en el par. Revela zonas frías y sobrecuradas correlacionadas con levantamiento de borde, fractura frágil o caída cohesiva en pelado.',
        ],
        'bond-line-void-detection' => [
            'en' => 'Bond-line void detection identifies discontinuities in adhesive interface coverage before they evolve into field delamination. Effective detection combines visual perimeter criteria with sectioned destructive checks on high-risk zones such as forepart flex and heel seat transitions.',
            'pt' => 'A deteção de vazios na linha de colagem identifica descontinuidades de cobertura da interface adesiva antes de evoluírem para delaminação em campo. A deteção eficaz combina critérios visuais de perímetro com cortes destrutivos em zonas críticas como flexão da frente e transição do assento do calcanhar.',
            'fr' => 'La détection des vides de ligne de collage identifie les discontinuités de couverture d’interface avant leur évolution en délamination terrain. Une détection efficace combine critères visuels de périmètre et coupes destructives sur zones à risque comme flexion avant-pied et transition assise talon.',
            'de' => 'Die Erkennung von Hohlstellen in der Klebefuge identifiziert Unterbrechungen der Interface-Abdeckung, bevor sie zu Feld-Delamination führen. Eine wirksame Erkennung kombiniert visuelle Umfangskriterien mit zerstörenden Schliffprüfungen in Hochrisikozonen wie Vorfußflex und Fersensitzübergang.',
            'it' => 'Il rilevamento vuoti linea di incollaggio identifica discontinuità di copertura dell’interfaccia adesiva prima che evolvano in delaminazione sul campo. Un rilevamento efficace combina criteri visivi perimetrali con sezioni distruttive nelle zone a rischio come flessione avampiede e transizione sede tallone.',
            'es' => 'La detección de vacíos en la línea de pegado identifica discontinuidades de cobertura de interfaz adhesiva antes de evolucionar a delaminación en campo. Una detección eficaz combina criterios visuales perimetrales con cortes destructivos en zonas de riesgo como flexión de antepié y transición de asiento de talón.',
        ],
        'goodyear-welt' => [
            'en' => 'Goodyear welt is the stitched, resole-capable bottoming route used when serviceability and structural lock outweigh cycle-time pressure: after seat release, pairs do not return to lasting and move through rib attach → gemming → holdfast → cork fill → waist shaping → channel stitching → sole lock. Each station inherits geometry from the previous one—holdfast cannot recover a shallow rib, and channel stitching cannot recover a hollow cork bed—so holdfast and channel penetration maps are mandatory hard gates before finishing. The route payoff is controlled resoling architecture; the route penalty is flex-life failure when fill or penetration governance is bypassed.',
            'pt' => 'A Goodyear welt é uma rota de fundo, não um rótulo de componente: após libertação da moldação, os pares não regressam aos postos de tração e avançam nervura → gemming → retenção → cortiça → cintura → costura em canal → bloqueio da sola. Cada posto herda geometria do anterior—a retenção não corrige nervura rasa, a costura em canal não corrige cortiça vazia—e mapas de penetração na retenção e na costura em canal são os dois gates duros antes do acabamento. Acesso à ressolagem é o ganho de desenho; falha em auditoria de flexão é a penalização por saltar auditorias de enchimento ou penetração.',
            'fr' => 'Le Goodyear welt est la route cousue ressemelable retenue quand la maintenabilité et le verrouillage structurel priment sur la pression de cycle: après libération d’assise, les paires ne reviennent plus en montage et enchaînent nervure → gemmage → holdfast → liège → profilage cambrion → couture en canal → verrouillage semelle. Chaque poste hérite de la géométrie du précédent—le holdfast ne récupère pas une nervure faible, la couture canal ne récupère pas un lit de liège creux—d’où cartes de pénétration holdfast et canal comme gates durs obligatoires avant finition. Le gain est une architecture de ressemelage maîtrisée; la pénalité est l’échec en fatigue flexion si la gouvernance remplissage/pénétration est contournée.',
            'de' => 'Das Goodyear-Rahmenverfahren ist die rahmengenaehte, wiederversohlbare Bottoming-Route fuer Programme, bei denen Servicefaehigkeit und mechanischer Lock wichtiger sind als reine Taktzeit: nach Sitzfreigabe kehren Paare nicht ins Aufziehen zurueck und durchlaufen Rippenauftrag → Gemming → Holdfast → Korkfuellung → Taillenformung → Kanalnaht → Sohlenverriegelung. Jede Station erbt Geometrie von der Vorstation—Holdfast gleicht keine flache Rippe aus, Kanalnaht kein hohles Korkbett—daher sind Penetrationskarten fuer Holdfast und Kanalnaht verbindliche Hart-Gates vor dem Finish. Nutzen: kontrollierte Wiederbesohlbarkeit; Risiko: Flex-Lebensdauer-Ausfall bei umgangener Fuell- oder Penetrationsfuehrung.',
            'it' => 'Il Goodyear welt è la route cucita risuolabile scelta quando manutenibilità e lock strutturale contano più della sola pressione di ciclo: dopo il rilascio sede, le paia non tornano al montaggio e seguono nervatura → gemming → holdfast → riempimento sughero → modellatura vita → cucitura in canale → lock suola. Ogni stazione eredita la geometria della precedente—l\'holdfast non recupera una nervatura bassa, la cucitura in canale non recupera un letto sughero vuoto—quindi le mappe di penetrazione holdfast e canale sono gate rigidi obbligatori prima della finitura. Il vantaggio è una risuolatura controllabile; la penalità è il fallimento in fatica flessione quando la governance di riempimento o penetrazione viene aggirata.',
            'es' => 'Goodyear welt es una ruta de fondo, no una etiqueta de componente: tras liberación de montado los pares no vuelven a estaciones de tracción y avanzan nervio → gemming → anclaje → corcho → cintura → costura canal → bloqueo de suela. Cada estación hereda geometría de la anterior—el anclaje no corrige nervio superficial, la costura en canal no corrige corcho vacío—y mapas de penetración en anclaje y costura canal son los dos gates duros antes del acabado. El acceso a resolado es la ventaja de diseño; fallo en auditoría de flexión es la penalización por omitir auditorías de relleno o penetración.',
        ],
        'blake-stitch' => [
            'en' => 'Blake stitch is the direct through-stitch route used when flexible dress/city constructions need fast mechanical lock without full Goodyear preparation. One stitch path couples upper margin, insole, and outsole immediately after lasting, with no cork bed or channel reserve for later geometry recovery. Needle trajectory must be validated against last-waist curvature and outsole flex groove before release—inline endoscope at the stitch head is the last reversible control. After closure, path drift toward the insole edge becomes in-use pressure concentration, not a recoverable bench correction.',
            'pt' => 'A costura Blake é a alternativa rápida de fundo à construção com vira: um ponto atravessado acopla margem do cabedal, palmilha e sola logo após moldação, sem cavidade de cortiça ou canal para absorver correção posterior. O percurso da agulha é definido face à curva da cintura da forma e ao sulco de flexão da sola antes da libertação—endoscópio em linha na cabeça de costura é a última verificação reversível. Após fecho do pacote de sola, deriva para o bordo da palmilha torna-se ponto de pressão em uso, não retrabalho de bancada.',
            'fr' => 'La couture Blake est la route traversante directe utilisée quand des constructions ville/habillé exigent un verrouillage mécanique rapide sans préparation Goodyear complète. Un seul trajet de point accouple marge tige, première et semelle juste après montage, sans lit de liège ni réserve de canal pour récupérer la géométrie ensuite. La trajectoire aiguille doit être validée sur courbure cambrion de forme et rainure de flexion semelle avant libération—l’endoscope en ligne à la tête de couture est le dernier contrôle réversible. Après fermeture, toute dérive vers le bord première devient concentration de pression à l’usage, pas une retouche atelier récupérable.',
            'de' => 'Die Blake-Naht ist die direkte Durchstich-Route fuer flexible City- und Dresskonstruktionen, wenn ein schneller mechanischer Lock ohne komplette Goodyear-Vorbereitung benoetigt wird. Ein Stichpfad koppelt Schaftrand, Innensohle und Laufsohle unmittelbar nach dem Aufziehen; es gibt weder Korkbett noch Kanalreserve fuer spaetere Geometrie-Korrektur. Der Nadelpfad muss vor Freigabe gegen Leistentaille und Flexrille validiert werden—das Inline-Endoskop am Stichkopf ist die letzte reversible Kontrolle. Nach dem Schliessen wird Pfaddrift zur Innensohlenkante zur Druckspitze im Tragen, nicht zur nacharbeitbaren Bankkorrektur.',
            'it' => 'La cucitura Blake è la route passante diretta usata quando costruzioni city/eleganti richiedono lock meccanico rapido senza la preparazione Goodyear completa. Un solo percorso punto accoppia margine tomaia, soletta e suola subito dopo il montaggio, senza letto di sughero né riserva di canale per recuperare la geometria in seguito. La traiettoria ago deve essere validata su curvatura vita forma e scanalatura di flessione suola prima del rilascio—l\'endoscopio in linea alla testa cucitura è l\'ultimo controllo reversibile. Dopo la chiusura, una deriva verso il bordo soletta diventa concentrazione di pressione in uso, non una correzione recuperabile a banco.',
            'es' => 'La costura Blake es la alternativa rápida de fondo frente a construcción con cerco: una puntada pasante acopla margen del corte, plantilla y suela justo tras montado, sin cavidad de corcho ni canal para absorber corrección posterior. El recorrido de aguja se fija según curva de cintura de horma y ranura de flexión de suela antes de liberación—endoscopio en línea en cabeza de costura es la última verificación reversible. Tras cerrar el paquete de suela, deriva hacia borde de plantilla se convierte en punto de presión en uso, no retrabajo de banco.',
        ],
        'cemented-construction' => [
            'en' => 'Cemented construction is the high-volume bottoming route where structural lock comes from adhesive process discipline, not stitch penetration: after lasting—typically on Strobel or board-lasted uppers—the chain runs roughing → primer → cement spread → activation → sole press → cure window. Plants select this route when takt time and compound bonding outperform welt or stitchdown benches; tradeoff is irreversible bond-line lock after press open with no channel to reopen. Cupsole variants inherit the same open-time and transfer-latency rules with cavity-specific press maps.',
            'pt' => 'A construção colada é a rota de fundo de alto volume em que o bloqueio estrutural vem da disciplina do processo adesivo, não da penetração do ponto: após moldação—tipicamente em cabedais Strobel ou com cartão—a cadeia corre rugosagem → primário → aplicação de cola → ativação → prensa → janela de cura. As fábricas escolhem esta rota quando cadência e união do composto superam bancadas de vira ou stitchdown; o tradeoff é bloqueio irreversível da linha de cola após abertura da prensa, sem canal para reabrir. Variantes cupsole herdam as mesmas regras de tempo aberto e latência de transferência com mapas de prensa específicos da cavidade.',
            'fr' => 'La construction cimentée est la route de fond à grand volume où le verrouillage structurel vient de la discipline adhésive, pas de la pénétration de point: après montage—souvent sur tiges Strobel ou montées sur première rigide—la chaîne enchaîne rugosification → primaire → dépôt colle → activation → presse → fenêtre de cure. Les usines choisissent cette route quand le takt time et le collage compound dépassent les ateliers trépointe ou stitchdown; le compromis est un verrouillage irréversible de ligne de collage après ouverture presse, sans canal à rouvrir. Les variantes cupsole héritent des mêmes règles de temps ouvert et latence de transfert avec cartes presse de cavité.',
            'de' => 'Die geklebte Konstruktion ist die High-Volume-Bottoming-Route, bei der der strukturelle Lock aus Klebeprozessdisziplin kommt, nicht aus Stichpenetration: nach Aufziehen—meist auf Strobel- oder Brandsohlen-Schaeftern—laeuft die Kette Aufrauen → Primer → Auftrag → Aktivierung → Presse → Aushaertefenster. Werke waehlen diese Route, wenn Taktzeit und Compound-Bonding Rahmen- oder Stitchdown-Baenke schlagen; Tradeoff ist irreversibler Klebefugen-Lock nach Pressoeffnung ohne wiedereroeffenbaren Kanal. Cupsole-Varianten erben dieselben Offenzeit- und Transferlatenzregeln mit Hohlraum-Presskarten.',
            'it' => 'La costruzione cementata e la route di fondo ad alto volume dove il blocco strutturale viene dalla disciplina adesiva, non dalla penetrazione punto: dopo montaggio—tipicamente su tomaie Strobel o montate su cartone rigido—la catena esegue rugosatura → primer → spalmatura → attivazione → pressa → finestra cura. Gli stabilimenti scelgono questa route quando takt time e bonding compound superano banchi guardolo o stitchdown; il tradeoff e blocco irreversibile linea incollaggio dopo apertura pressa senza canale da riaprire. Le varianti cupsole ereditano le stesse regole tempo aperto e latenza trasferimento con mappe pressa per cavità.',
            'es' => 'La construcción cementada es la ruta de fondo de alto volumen donde el bloqueo estructural viene de la disciplina del proceso adhesivo, no de la penetración de puntada: tras montado—típicamente en cortes Strobel o montados sobre cartón—la cadena ejecuta rugosado → primer → extendido → activación → prensa → ventana de curado. Las plantas eligen esta ruta cuando el takt time y la unión del compuesto superan bancos de cerco o stitchdown; el tradeoff es bloqueo irreversible de línea de pegado tras abrir la prensa, sin canal que reabrir. Las variantes cupsole heredan las mismas reglas de tiempo abierto y latencia de transferencia con mapas de prensa de cavidad.',
        ],
        'board-lasted-construction' => [
            'en' => 'Board-lasted construction defines how the upper is captured on a rigid insole board before bottoming route selection: lasting margin and seat pull reference board stack height, not Strobel board flex. The same plant may release board-lasted pairs to cemented press or Goodyear rib prep—the lasting ticket records board family and margin stress, while bottoming diverges only after seat release. Used on dress boots and structured footwear where shape retention and shank seat in the footbed stack outweigh Strobel weight savings.',
            'pt' => 'A construção com montagem em cartão define como o cabedal é capturado num cartão de palmilha rígido antes da escolha da rota de fundo: margem de moldação e tração do assento referenciam a altura do pacote do cartão, não a flexão da base Strobel. A mesma fábrica pode libertar pares com cartão para prensa colada ou prep Goodyear—a ficha de moldação regista família de cartão e tensão das margens, enquanto o fundo diverge só após libertação do assento. Usada em botas de vestir e calçado estruturado onde retenção de forma e assento da alma no pacote interno superam a poupança de peso Strobel.',
            'fr' => 'La construction montée sur première rigide définit comment la tige est capturée sur une première rigide avant le choix de route de fond: marge de montage et tirage assise référencent l’épaisseur de pile carton, pas la flexion base Strobel. La même usine peut libérer des paires carton vers presse collée ou prep Goodyear—le ticket montage consigne famille de première et tension de marge, le fond diverge seulement après libération assise. Utilisée sur bottes habillées et chaussures structurées où rétention de forme et assise cambrion dans l’empilement interne l’emportent sur le gain de poids Strobel.',
            'de' => 'Die Brandsohlen-Aufziehkonstruktion definiert, wie der Schaft auf einer starren Brandsohle vor Bottoming-Routenwahl erfasst wird: Aufziehmarge und Sitzzug beziehen sich auf Brett-Stackhoehe, nicht Strobel-Plattenflex. Dasselbe Werk kann Brandsohlen-Paare zur geklebten Presse oder Goodyear-Rippenvorbereitung freigeben—der Aufziehschein dokumentiert Brettfamilie und Randspannung, Bottoming divergiert erst nach Sitzfreigabe. Eingesetzt bei Dressstiefeln und strukturiertem Schuhwerk, wo Formhalt und Schanksitz im Fussbettaufbau Strobel-Gewichtsvorteile ueberwiegen.',
            'it' => 'La costruzione con montaggio su sottopiede rigido definisce come la tomaia viene catturata su cartone rigido prima della scelta route fondo: margine montaggio e tiro sede riferiscono altezza stack cartone, non flessione base Strobel. Lo stesso stabilimento puo rilasciare paia a cartone verso pressa cementata o prep Goodyear—il ticket montaggio registra famiglia cartone e tensione margini, il fondo diverge solo dopo rilascio sede. Usata su stivali eleganti e calzature strutturate dove ritenzione forma e sede cambrione nello stack interno superano il risparmio peso Strobel.',
            'es' => 'La construcción montada sobre plantilla rígida define cómo el corte se captura en cartón rígido antes de elegir ruta de fondo: margen de montado y tracción de asiento referencian altura del paquete de cartón, no flexión de base Strobel. La misma planta puede liberar pares con cartón a prensa cementada o prep Goodyear—la ficha de montado registra familia de cartón y tensión de márgenes, el fondo diverge solo tras liberación de asiento. Usada en botas de vestir y calzado estructurado donde retención de forma y asiento del cambrillón en el paquete interno superan el ahorro de peso Strobel.',
        ],
        'stitchdown-construction' => [
            'en' => 'Stitchdown construction is the perimeter-stitch bottoming route where turned-out upper margins are lockstitched directly to the sole platform—no welt rib, holdfast, or primary cement lock. Lasting still runs toe → side → seat, then margin turn-out and perimeter stitch replace roughing-through-cure; selected on work and outdoor families needing field edge serviceability without Goodyear bench investment. Waist-curve edge-guide discipline is the release gate; diverges from cemented at no activation tunnel and from Goodyear at no rib prep.',
            'pt' => 'A construção stitchdown é a rota de fundo por costura perimetral em que margens do cabedal viradas para fora são fechadas por lockstitch diretamente na plataforma da sola—sem nervura de vira, retenção ou bloqueio primário por cola. A moldação mantém biqueira → lateral → assento, depois viragem da margem e costura perimetral substituem rugosagem-até-cura; escolhida em famílias de trabalho e outdoor que precisam de serviço de bordo em campo sem investimento em bancada Goodyear. Disciplina da guia de bordo na curva da cintura é o gate de libertação; diverge da colada pela ausência de túnel de ativação e da Goodyear pela ausência de prep de nervura.',
            'fr' => 'La construction stitchdown est la route de fond par couture périphérique où les marges tige rabattues sont lockstitchées directement sur la plateforme semelle—sans nervure trépointe, holdfast ni verrouillage colle primaire. Le montage reste pointe → latéral → assise, puis rabat de marge et couture périphérique remplacent rugosification-jusqu’à-cure; choisie sur familles travail et outdoor nécessitant service de rive terrain sans investissement atelier Goodyear. La discipline du guide de rive au cambrion est le gate de libération; diverge du collé sans tunnel d’activation et du Goodyear sans prep nervure.',
            'de' => 'Die Stitchdown-Konstruktion ist die perimeter-naht Bottoming-Route, bei der umgeschlagenen Schaftraender per Lockstitch direkt auf die Sohlenplattform genaeht werden—ohne Rahmenrippe, Holdfast oder primaeren Klebelock. Aufziehen bleibt Spitze → Seite → Sitz, dann Randumschlag und Umfangsnaht ersetzen Aufrauen-bis-Aushaertung; gewaehlt bei Work- und Outdoor-Familien mit Feldkantenservice ohne Goodyear-Bank-Invest. Kantenfuehrungsdisziplin im Taillenbogen ist das Freigabegate; divergiert von geklebt ohne Aktivierungstunnel und von Goodyear ohne Rippenvorbereitung.',
            'it' => 'La costruzione stitchdown è la route fondo a cucitura perimetrale dove margini tomaia rivoltati sono lockstitchati direttamente sulla piattaforma suola—senza nervatura guardolo, holdfast o blocco colla primario. Il montaggio resta punta → laterale → sede, poi rivoltamento margine e cucitura perimetrale sostituiscono rugosatura-fino-cura; scelta su famiglie work e outdoor che richiedono servizio bordo in campo senza investimento banco Goodyear. La disciplina guida bordo in vita è il gate di rilascio; diverge dalla cementata senza tunnel attivazione e dal Goodyear senza prep nervatura.',
            'es' => 'La construcción stitchdown es la ruta de fondo por costura perimetral donde márgenes del corte volteados se cosen con lockstitch directamente a la plataforma de suela—sin nervio de cerco, anclaje ni bloqueo adhesivo primario. El montado sigue puntera → lateral → asiento, luego vuelta de margen y costura perimetral sustituyen rugosado-hasta-curado; elegida en familias de trabajo y outdoor que necesitan servicio de canto en campo sin inversión en banco Goodyear. La disciplina de guía de canto en curva de cintura es la puerta de liberación; diverge de cementada sin túnel de activación y de Goodyear sin prep de nervio.',
        ],
        'cupsole-cementing' => [
            'en' => 'Cupsole cementing is a cemented sub-route where the outsole arrives as a pre-formed sidewall cup: after lasting release the sequence still runs roughing and activation, but the press mates the upper into cavity landings with heel-pocket and forepart dwell split on the route card. Selected when tooling includes cup geometry on athletic and casual lines; inherits open-time and transfer-latency gates from cemented construction while adding cold-zone risk at heel seat that flat-soled press maps do not show.',
            'pt' => 'A colagem de cupsole é uma sub-rota colada em que a sola chega como cavidade lateral pré-formada: após libertação da moldação a sequência mantém rugosagem e ativação, mas a prensa acopla o cabedal aos assentamentos da cavidade com divisão de dwell entre bolsa do calcanhar e frente na ficha de rota. Escolhida quando o ferramental inclui geometria cup em linhas desportivas e casuais; herda gates de tempo aberto e latência de transferência da construção colada, acrescentando risco de zona fria no assento que mapas de prensa de sola plana não mostram.',
            'fr' => 'La cimentation cupsole est une sous-route collée où la semelle arrive en cavité de flanc préformée: après libération montage la séquence garde rugosification et activation, mais la presse accouple la tige aux assises de cavité avec maintien talon et avant-pied fractionnés sur la gamme. Choisie quand l’outillage inclut géométrie cup sur lignes sport et casual; hérite des gates temps ouvert et latence transfert de la construction cimentée, avec risque de zone froide à l’assise talon absent des cartes presse semelle plate.',
            'de' => 'Die Cupsole-Verklebung ist eine geklebte Sub-Route, bei der die Laufsohle als vorgeformte Seitenwand-Hohlung ankommt: nach Aufziehfreigabe bleiben Aufrauen und Aktivierung, aber die Presse vermated den Schaft mit Hohlraum-Auflagen bei geteilter Fersen- und Vorfuß-Verweilzeit auf der Route-Card. Gewaehlt bei Werkzeugen mit Cup-Geometrie auf Sport- und Casual-Linien; erbt Offenzeit- und Transferlatenz-Gates der geklebten Konstruktion plus Kaltzonenrisiko am Fersensitz, das flache Presskarten nicht zeigen.',
            'it' => 'L incollaggio cupsole e una sub-route cementata dove la suola arriva come cavità fianco preformata: dopo rilascio montaggio la sequenza mantiene rugosatura e attivazione, ma la pressa accoppia la tomaia agli appoggi cavità con dwell tallone e avampiede divisi sulla scheda rotta. Scelta quando l attrezzaggio include geometria cup su linee sport e casual; eredita gate tempo aperto e latenza trasferimento dalla costruzione cementata, con rischio zona fredda sede tallone assente nelle mappe pressa suola piatta.',
            'es' => 'El pegado de cupsole es una subruta cementada donde la suela llega como cavidad lateral preformada: tras liberación de montado la secuencia mantiene rugosado y activación, pero la prensa acopla el corte a asientos de cavidad con dwell de bolsa de talón y antepié dividido en la hoja de ruta. Elegida cuando el herramental incluye geometría cup en líneas deportivas y casual; hereda gates de tiempo abierto y latencia de transferencia de construcción cementada, con riesgo de zona fría en asiento de talón que mapas de prensa de suela plana no muestran.',
        ],
        'feather-edge' => [
            'en' => 'Feather edge is prepared in closing before lasting: skiving sets the taper that edge folding and seam capture depend on, and poor feather geometry telegraphs into topline ripple once side and seat pulls load the margin. QC at this stage is a forward gate—lines that release stiff or discontinuous feather edges accept collar distortion and stitch drift that no bottoming step can correct.',
            'pt' => 'O bordo em pena prepara-se no fecho antes da moldação: o rebaixamento define o afino de que dependem a dobra e a captura do ponto, e geometria fraca propaga ondulação da linha superior quando trações lateral e de assento carregam a margem. O CQ nesta fase é gate antecipado—linhas que libertam bordos rígidos ou descontínuos aceitam deformação do colarinho e deriva de ponto que nenhuma etapa de fundo corrige.',
            'fr' => 'Le bord aminci se prépare au piquage avant montage: le parage fixe l’affinage dont dépendent rabat et capture de point, et une géométrie faible se propage en ondulation de col quand les tirages latéral et assise chargent la marge. Le CQ à cette étape est un gate amont—une ligne qui libère des bords durs ou discontinus accepte une déformation de col et une dérive de point qu’aucune étape de fond ne corrige.',
            'de' => 'Die Federkante wird in der Schließerei vor dem Aufziehen vorbereitet: Schaerfen setzt die Ausduennung, von der Kantenumschlag und Stichaufnahme abhaengen; schwache Geometrie telegraphiert Schaftrandwelligkeit, sobald Seiten- und Sitzzug den Rand belasten. QC hier ist ein vorgelagertes Gate—Wer steife oder unterbrochene Federkanten freigibt, akzeptiert Kragendrift und Stichdrift, die kein Bottoming-Schritt korrigiert.',
            'it' => 'Il bordo a piuma si prepara in giunteria prima del montaggio: la scarnitura definisce la rastremazione da cui dipendono piega e cattura punto, e geometria debole si propaga in ondulazione topline quando trazioni laterale e sede caricano il margine. Il CQ in questa fase e gate a monte—linee che rilasciano bordi rigidi o discontinui accettano deformazione collarino e deriva punto che nessun passo fondo corregge.',
            'es' => 'El borde en pluma se prepara en aparado antes del montado: el rebajado fija el afinado del que dependen el doblado y la captura de puntada, y geometria debil se propaga en ondulacion de linea superior cuando tracciones lateral y de asiento cargan el margen. El CQ en esta fase es puerta previa—lineas que liberan bordes rigidos o discontinuos aceptan deformacion de collar y deriva de puntada que ningun paso de fondo corrige.',
        ],
        'lasting-margin' => [
            'en' => 'Lasting margin is the operational allowance that converts pattern intent into stable capture during toe, side, and seat lasting, and it must be tuned by material family, last curvature, and bottoming route. If margin strategy is mis-sized, operators compensate with excess pull or tacks, driving asymmetry, edge stress, and downstream bond-line instability that appears later as forepart lift or heel-seat distortion.',
            'pt' => 'A margem de moldação é a folga operacional que converte a intenção de modelagem em captura estável durante moldação de biqueira, lateral e assento, devendo ser ajustada por família de material, curvatura da forma e rota de montagem de fundo. Se a estratégia de margem estiver mal dimensionada, os operadores compensam com tração excessiva ou pregos, gerando assimetria, tensão de bordo e instabilidade da linha de colagem que surge depois como levantamento frontal ou deformação do assento do calcanhar.',
            'fr' => 'La marge de montage est l’allocation opérationnelle qui transforme l’intention patron en capture stable aux postes pointe, latéral et assise, avec réglage par famille matière, courbure de forme et route de montage de fond. Si la stratégie de marge est mal dimensionnée, les opérateurs compensent par traction excessive ou pointage, créant asymétrie, contrainte de rive et instabilité de ligne de collage visible ensuite en relevage avant-pied ou déformation d’assise talon.',
            'de' => 'Die Aufziehmarge ist die operative Zugabe, die Schnittabsicht in stabile Fixierung bei Spitzen-, Seiten- und Sitzaufziehen ueberfuehrt und nach Materialfamilie, Leistenkruemmung sowie Bottoming-Route abgestimmt werden muss. Bei falscher Margenstrategie kompensieren Bediener mit ueberhoehtem Zug oder Stiften, was Asymmetrie, Kantenstress und nachgelagerte Klebefugeninstabilitaet ausloest, spaeter sichtbar als Vorfussanhebung oder Fersensitzverzug.',
            'it' => 'Il margine di montaggio e l’allowance operativa che trasforma l’intento modellistico in cattura stabile nelle fasi punta, laterale e sede, e va tarato per famiglia materiale, curvatura forma e route di fondo. Se la strategia margine e dimensionata male, gli operatori compensano con trazione eccessiva o chiodatura, generando asimmetria, stress di bordo e instabilita della linea di incollaggio che emerge poi come sollevamento avampiede o deformazione sede tallone.',
            'es' => 'El margen de montado es la holgura operativa que transforma la intencion de patronaje en captura estable en montado de puntera, lateral y asiento, y debe ajustarse por familia de material, curvatura de horma y ruta de fondo. Si la estrategia de margen esta mal dimensionada, los operarios compensan con traccion excesiva o clavado, generando asimetria, tension de canto e inestabilidad de linea de pegado que aparece despues como levantamiento de antepie o deformacion del asiento de talon.',
        ],
        'gemming-rib' => [
            'en' => 'Gemming rib is formed on the insole board after rib attaching and before welt sewing: it is the structural anchor that holdfast stitch must engage before channel stitching and outsole lock. Rib height and lock integrity are audited against welt-channel depth so the welting line does not proceed with a margin that will fail penetration mapping at the waist curve.',
            'pt' => 'A nervura de gemming forma-se na palmilha rígida após aplicação da nervura e antes da costura da vira: é a ancoragem estrutural que o ponto de retenção deve envolver antes da costura em canal e do bloqueio da sola. Altura da nervura e integridade do fecho são auditadas face à profundidade do canal da vira para a linha de viração não avançar com margem que falhará no mapa de penetração na curva da cintura.',
            'fr' => 'La nervure de gemmage se forme sur la première rigide après pose de nervure et avant couture trépointe: c’est l’ancrage structurel que le point d’ancrage doit engager avant couture en canal et verrouillage semelle. Hauteur de nervure et intégrité de verrouillage sont auditées versus profondeur de canal trépointe pour que la ligne trépointe n’avance pas avec une marge qui échouera à la cartographie de pénétration au cambrion.',
            'de' => 'Die Gemming-Rippe entsteht auf der Brandsohle nach Rippenanbringung und vor Rahmennaehen: sie ist der strukturelle Anker, den der Holdfast-Stich vor Kanalnaht und Sohlenverriegelung erfassen muss. Rippenhoehe und Schlussintegritaet werden gegen Rahmenkanaltiefe auditiert, damit die Rahmenlinie nicht mit einer Randlage weiterlaeuft, die in der Penetrationskarte an der Taillee scheitert.',
            'it' => 'La nervatura di gemming si forma sulla soletta rigida dopo applicazione nervatura e prima della cucitura guardolo: e l’ancoraggio strutturale che il punto di tenuta deve coinvolgere prima della cucitura in canale e del blocco suola. Altezza nervatura e integrita di chiusura sono auditate rispetto alla profondita canale guardolo per non far proseguire la linea con un margine che fallira nella mappa di penetrazione in vita.',
            'es' => 'El nervio de gemming se forma en la plantilla rigida tras aplicacion del nervio y antes de coser el cerco: es el anclaje estructural que la puntada de anclaje debe capturar antes de la costura en canal y el bloqueo de suela. Altura del nervio e integridad de cierre se auditan frente a profundidad de canal de cerco para que la linea de cercado no avance con un margen que fallara en el mapa de penetracion en curva de cintura.',
        ],
        'sockliner' => [
            'en' => 'Sockliner is the final internal foot-contact layer that tunes perceived volume, moisture transport, and underfoot friction within the completed footbed stack. It must be sequenced after shank placement and filler leveling so edge lift, heel pocket collapse, and lining abrasion do not appear only after wear trials.',
            'pt' => 'O forro de palmilha é a camada final de contacto com o pé que regula volume percebido, transporte de humidade e fricção plantar dentro do pacote interno concluído. Deve ser sequenciado após posicionamento da alma e nivelamento de enchimento para que levantamento de bordo, colapso do assento do calcanhar e abrasão do forro não surjam apenas após ensaios de uso.',
            'fr' => 'La première de propreté est la couche finale de contact pied qui règle volume perçu, transport d’humidité et friction plantaire dans l’empilement interne terminé. Elle doit être séquencée après pose cambrion et nivellement de remplissage pour éviter relevage de bord, affaissement d’assise talon et abrasion doublure révélés seulement en essais de port.',
            'de' => 'Die Decksohle ist die letzte fussseitige Kontaktschicht, die wahrgenommenes Volumen, Feuchtigkeitstransport und plantare Reibung im fertigen Fussbettaufbau einstellt. Sie muss nach Schankpositionierung und Fuellnivellierung erfolgen, damit Kantenablösung, Fersenbeckenkollaps und Futterabrieb nicht erst in Trageversuchen sichtbar werden.',
            'it' => 'Il sottopiede di finitura e lo strato finale a contatto piede che regola volume percepito, trasporto umidita e attrito plantare nello stack interno completato. Va sequenziato dopo posizionamento cambrione e livellamento riempimento per evitare sollevamento bordo, collasso sede tallone e abrasione fodera visibili solo nei wear test.',
            'es' => 'La plantilla de acabado es la capa final de contacto con el pie que ajusta volumen percibido, transporte de humedad y friccion plantar dentro del paquete interno terminado. Debe secuenciarse tras posicionamiento de cambrillon y nivelado de relleno para que levantamiento de canto, colapso de asiento de talon y abrasion de forro no aparezcan solo en pruebas de uso.',
        ],
        'heel-counter-reinforcement' => [
            'en' => 'Heel counter reinforcement is engineered in flat back-part assembly because once the upper is pulled, stack thickness at the heel quarter cannot be reshaped without reopening seams. The reinforcement sets rearfoot stiffness before hot counter molding: skive transition into the quarter edge must match the width-grade matrix so molded counter curvature seats cleanly at seat lasting. Plants audit stack height per last family—excess stiffness telegraphs as collar stand-off; insufficient stiffness shows up as heel-pocket collapse after molding, not as a lasting-pull defect.',
            'pt' => 'O reforço do contraforte é engenharia no fecho plano dos traseiros porque, após tração, a espessura do pacote no quarto do calcanhar não se remodela sem reabrir costuras. O reforço define rigidez do retropé antes da moldagem a quente do contraforte: a transição de rebaixo para o bordo do quarto tem de corresponder à matriz por largura para a curvatura moldada assentar limpa na moldação do assento. As fábricas auditam altura do pacote por família de forma—rigidez excessiva manifesta-se como afastamento do colarinho; rigidez insuficiente como colapso do assento após moldagem, não como defeito de tração.',
            'fr' => 'Le renfort de contrefort arrière s’ingénierie en assemblage arrière à plat car une fois la tige tirée, l’épaisseur de pile au quartier talon ne se remodelle pas sans rouvrir les coutures. Le renfort fixe la rigidité arrière-pied avant moulage chaud du contrefort: la transition de parage vers le bord quartier doit correspondre à la matrice par largeur pour que la courbure moulée s’assoie proprement au montage de siège. Les usines auditent la hauteur de pile par famille de forme—rigidité excessive se voit en décollage de col; rigidité insuffisante en affaissement d’assise après moulage, pas comme défaut de traction.',
            'de' => 'Die Fersenkappenverstaerkung wird im flachen Hinterkappenbereich ausgelegt, weil nach dem Zug die Lagenstaerke im Fersenquartier ohne Nahtoeffnung nicht umgeformt werden kann. Die Verstaerkung setzt Rueckfusssteifigkeit vor heisser Kappenformung: der Schaerfuebergang zur Quartierkante muss zur Breitengrad-Matrix passen, damit die geformte Kappenkruemmung beim Sitzaufziehen sauber sitzt. Werke auditieren Stackhoehe je Leistenfamilie—zu viel Steifigkeit zeigt sich als Kragenabstand; zu wenig als Fersenbeckenkollaps nach Formung, nicht als Zugfehler.',
            'it' => 'Il rinforzo contrafforte si progetta in assemblaggio tallone piatto perche dopo la trazione lo spessore stack nel quartiere tallone non si rimodella senza riaprire cuciture. Il rinforzo definisce rigidita retropiede prima dello stampaggio a caldo del contrafforte: la transizione scarnitura sul bordo quartiere deve combaciare con la matrice per larghezza affinche la curvatura stampata si sieda pulita al montaggio sede. Gli stabilimenti auditano altezza stack per famiglia forma—eccesso rigidezza si manifesta come distacco collarino; insufficiente come collasso sede dopo stampaggio, non come difetto di trazione.',
            'es' => 'El refuerzo del contrafuerte se diseña en ensamblaje plano del trasero porque, tras la tracción, el espesor del paquete en el cuarto del talón no se remodela sin reabrir costuras. El refuerzo fija rigidez del retropié antes del moldeado en caliente del contrafuerte: la transición de rebajado al borde del cuarto debe coincidir con la matriz por ancho para que la curvatura moldeada asiente limpia en montado de asiento. Las plantas auditan altura del paquete por familia de horma—exceso de rigidez se manifiesta como separación del collar; insuficiente como colapso del asiento tras moldeado, no como defecto de tracción.',
        ],
        'welt-channel' => [
            'en' => 'Welt channel is the machined or cut groove in the insole board that receives channel stitching and defines stitch sink, moisture path, and resoling access. Depth, wall angle, and rib alignment must stay within route-card tolerance because shallow or wandering channels produce visible stitch telegraphing, welt lift, and field failures at the waist curve.',
            'pt' => 'O canal da vira é o sulco usinado ou cortado na palmilha rígida que recebe a costura em canal e define afundamento do ponto, percurso de humidade e acesso à ressolagem. Profundidade, ângulo da parede e alinhamento da nervura devem respeitar tolerâncias da ficha de rota, porque canais rasos ou erráticos geram telegraphing visível do ponto, levantamento da vira e falhas em campo na curva da cintura.',
            'fr' => 'Le canal de trépointe est la rainure usinée ou coupée dans la première rigide qui reçoit la couture en canal et définit enfoncement du point, chemin d’humidité et accès au ressemelage. Profondeur, angle de paroi et alignement de nervure doivent rester dans les tolérances de gamme, car des canaux peu profonds ou erratiques provoquent télégraphie de point, soulèvement de trépointe et défaillances terrain au cambrion.',
            'de' => 'Der Rahmenkanal ist die gefraeste oder geschnittene Nut in der Brandsohle, die die Kanalnaht aufnimmt und Stichsenkung, Feuchtigkeitsweg und Wiederbesohlungszugang definiert. Tiefe, Wandwinkel und Rippenausrichtung muessen innerhalb der Route-Card-Toleranz bleiben, weil flache oder wandernde Kanaele sichtbare Stichtelegraphie, Rahmenanhebung und Feldausfaelle in der Taillee erzeugen.',
            'it' => 'Il canale guardolo è la scanalatura lavorata o tagliata nella soletta rigida che riceve la cucitura in canale e definisce affondamento punto, percorso umidità e accesso risuolatura. Profondità, angolo parete e allineamento nervatura devono restare nelle tolleranze della scheda rotta, perché canali superficiali o erratici producono telegraphing visibile, sollevamento guardolo e guasti sul campo in vita.',
            'es' => 'El canal de cerco es el surco mecanizado o cortado en la plantilla rigida que recibe la costura en canal y define hundimiento de puntada, ruta de humedad y acceso al resolado. Profundidad, angulo de pared y alineacion del nervio deben mantenerse en tolerancia de hoja de ruta, porque canales superficiales o erraticos generan telegraphing visible, levantamiento de cerco y fallos de campo en la curva de cintura.',
        ],
        'channel-stitching' => [
            'en' => 'On welted routes, channel stitching is the outsole-lock step at the end of the welt-prep chain: upstream must complete rib attach → channeling → gemming → holdfast → cork fill → waist shaping before this station runs. The stitch buries thread in the prepared groove and secures welt and sole package—penetration and crown height are the final mechanical gate because no cemented press can recover shallow bite afterward.',
            'pt' => 'Em rotas com vira, a costura em canal é o bloqueio da sola no fim da cadeia de prep: a montante deve completar nervura → canal → gemming → holdfast → cortiça → modelação da cintura. O ponto enterra o fio no sulco e fixa vira e pacote de sola—penetração e coroa são o gate mecânico final porque a prensa colada não recupera mordida rasa.',
            'fr' => 'Sur routes trépointées, la couture en canal est le verrouillage semelle en fin de chaîne prep: l’amont doit compléter nervure → rainurage → gemming → holdfast → liège → profilage cambrion. Le point enfouit le fil dans la rainure et verrouille trépointe et paquet semelle—pénétration et couronne sont le gate mécanique final car aucun pressage collé ne rattrape une prise faible.',
            'de' => 'Auf Rahmenkonstruktionen ist die Kanalnaht der Sohlenverriegelungsschritt am Ende der Rahmenvorbereitung: vorgelagert muessen Rippe → Nut → Gemming → Holdfast → Kork → Taillenformung abgeschlossen sein. Der Stich vergraetzt den Faden in der Nut und verriegelt Rahmen und Sohlenpaket—Eindringtiefe und Kronenhoehe sind das letzte mechanische Freigabe-Gate, weil keine Klebepresse flache Aufnahme nachzieht.',
            'it' => 'Sulle filiere a guardolo, la cucitura in canale è il blocco suola finale della preparazione: a monte devono essere completati nervatura → canalizzazione → gemming → punto di tenuta → sughero → modellatura vita. Il punto interra il filo nella scanalatura e blocca guardolo e pacchetto suola—penetrazione e corona del filo sono il gate meccanico irreversibile perché nessuna pressa cementata recupera una presa insufficiente.',
            'es' => 'En rutas de cerco, la costura en canal es el bloqueo de suela al final de la cadena de prep: aguas arriba deben completarse nervio → canal → gemming → holdfast → corcho → conformado de cintura. La puntada entierra el hilo en el surco y bloquea cerco y paquete de suela—penetración y corona son la puerta mecánica final porque la prensa cementada no recupera mordida superficial.',
        ],
        'inseam-stitch' => [
            'en' => 'Inseam stitch closes the internal lining and lasting margin before pull operations, forming the hidden envelope that blocks grit ingress and stabilizes throat curvature. SPI, thread class, and tack placement at the throat curve are controlled because failures here surface as lining abrasion, sand entry, and collar drift after lasting.',
            'pt' => 'A costura de entrecosto fecha o forro interno e a margem de moldação antes das operações de tração, formando o envelope oculto que bloqueia entrada de granulados e estabiliza a curvatura da garganta. SPI, classe de fio e posição de reforços na curva da garganta são controlados porque falhas aqui surgem como abrasão do forro, entrada de areia e deriva do colarinho após moldação.',
            'fr' => 'La couture intérieure de fermeture boucle doublure interne et marge de montage avant les opérations de traction, formant l’enveloppe cachée qui bloque l’ingress de particules et stabilise la courbure de gorge. SPI, classe de fil et placement d’attaches sur la courbe de gorge sont pilotés car les défauts se révèlent en abrasion doublure, entrée de sable et dérive de col après montage.',
            'de' => 'Die Schliess-Innennaht verbindet Futter und Aufziehrand vor den Zugoperationen und bildet die verdeckte Huelle, die Schmutzeintritt blockiert und die Ristkruemmung stabilisiert. SPI, Garnklasse und Heftplatzierung an der Ristkurve werden kontrolliert, weil Fehler hier als Futterabrieb, Sandeintritt und Kragendrift nach dem Aufziehen sichtbar werden.',
            'it' => 'La cucitura interna di chiusura chiude fodera interna e margine di montaggio prima delle operazioni di trazione, formando l’involucro nascosto che blocca ingresso di detriti e stabilizza la curvatura gola. SPI, classe filo e posizionamento rinforzi sulla curva gola sono controllati perché i guasti emergono come abrasione fodera, ingresso sabbia e deriva collarino dopo montaggio.',
            'es' => 'La costura de entrecorte cierra el forro interno y el margen de montado antes de las operaciones de traccion, formando el sobre oculto que bloquea entrada de particulas y estabiliza la curvatura de garganta. SPI, clase de hilo y colocacion de refuerzos en la curva de garganta se controlan porque los fallos aparecen como abrasion de forro, entrada de arena y deriva de collar tras montado.',
        ],
        'lockstitch-seam' => [
            'en' => 'Lockstitch seam is the closing and perimeter stitch class that governs upper join integrity before lasting: needle pattern, thread balance, and seam-class release map determine whether margins survive pull without grin, thread cut-through, or quarter-throat migration. On stitchdown and welt-prep lines the same machine class also sets edge capture quality that downstream channel or margin turn-out cannot recover.',
            'pt' => 'A costura lockstitch é a classe de costura de fecho e perímetro que governa a integridade das junções do cabedal antes da moldação: padrão de agulha, equilíbrio de fio e mapa de libertação da classe de costura definem se as margens resistem à tração sem abrir costura, cortar fio ou migrar quarto-garganta. Em linhas stitchdown e prep de vira, a mesma classe de máquina fixa também a qualidade de captura de bordo que o canal ou o virar de margem a jusante não recuperam.',
            'fr' => 'La couture point noué est la classe de couture de fermeture et de périmètre qui gouverne l’intégrité des assemblages tige avant montage: patron d’aiguille, équilibre fil et carte de libération de classe déterminent si les marges survivent au tirage sans ouverture, coupure fil ou migration quartier-gorge. Sur lignes stitchdown et prep trépointe, cette même classe fixe la qualité de capture de rive que le canal ou le retournement de marge aval ne rattrapent pas.',
            'de' => 'Die Doppelsteppnaht ist die Schliess- und Umfangsnahtklasse, die die Schaftfugenintegritaet vor dem Aufziehen steuert: Nadelbild, Garnbalance und Nahtklassen-Freigabekarte legen fest, ob Raender den Zug ohne Aufgehen, Fadenbruch oder Quartier-Rist-Wanderung ueberstehen. Auf Stitchdown- und Rahmenvorbereitungslinien setzt dieselbe Maschinenklasse auch die Randaufnahmequalitaet, die nachgelagertes Kanalfraesen oder Margin-Turn-out nicht heilt.',
            'it' => 'La cucitura a punto catenella è la classe di chiusura e perimetro che governa l’integrità delle giunzioni tomaia prima del montaggio: schema ago, bilanciamento filo e mappa di rilascio per classe cucitura determinano se i margini resistono al tiro senza grinning, rottura filo o migrazione quartiere-gola. Sulle filiere stitchdown e preparazione guardolo, la stessa classe macchina fissa la qualità di cattura bordo che canale o risvolto margine a valle non recuperano.',
            'es' => 'La costura lockstitch es la clase de costura de cierre y perímetro que gobierna la integridad de uniones del corte antes del montado: patrón de aguja, balance de hilo y mapa de liberación de clase definen si los márgenes resisten la tracción sin abrir costura, cortar hilo o migrar cuarto-garganta. En líneas stitchdown y prep de cerco, la misma clase de máquina fija también la calidad de captura de canto que el canal o el volteo de margen aguas abajo no recuperan.',
        ],
        'filler-cork' => [
            'en' => 'Filler cork runs on Goodyear routes after holdfast lock and before waist shaping and outsole stitch: it levels the cavity between insole board and incoming sole package while the shank reinforcement is already seated. Incomplete fill or density drift is contained at bench audit before edge ink and sole lock, because voids under the waist become flex squeak and heel-seat rock that finishing cannot mask.',
            'pt' => 'A cortiça de enchimento corre em rotas Goodyear após bloqueio de retenção e antes da modelação da cintura e costura da sola: nivela a cavidade entre palmilha e pacote de sola com a alma já assentada. Enchimento incompleto ou deriva de densidade contém-se na auditoria de bancada antes de tinta de bordo e bloqueio da sola, porque vazios sob a cintura viram ranger em flexão e balanço do assento que o acabamento não disfarça.',
            'fr' => 'Le liège de remplissage s’exécute sur routes Goodyear après verrouillage holdfast et avant profilage cambrion et couture semelle: il nivel le vide entre première et paquet semelle entrant, cambrion déjà posé. Remplissage incomplet ou dérive de densité est contenu à l’audit atelier avant encre de tranche et verrouillage semelle, car les vides sous cambrion deviennent grincement en flexion et bascule assise que la finition ne masque pas.',
            'de' => 'Die Korkfuellung laeuft auf Goodyear-Routen nach Holdfast-Verriegelung und vor Taillenformung sowie Sohlennaehen: sie nivelliert den Hohlraum zwischen Brandsohle und ankommendem Sohlenpaket bei bereits gesetzter Gelenkfeder. Unvollstaendige Fuellung oder Dichtedrift wird an der Bank auditiert vor Kantenfarbe und Sohlenverriegelung, weil Hohlstellen unter der Taillee zu Flex-Knarren und Fersensitz-Wippen werden, die das Finish nicht verdeckt.',
            'it' => 'Il sughero di riempimento corre su route Goodyear dopo blocco holdfast e prima di modellatura vita e cucitura suola: livella la cavità tra soletta e pacchetto suola in arrivo con cambrione già posizionato. Riempimento incompleto o deriva densità si contiene in audit banco prima di inchiostro bordo e blocco suola, perche vuoti sotto vita diventano scricchiolio in flessione e oscillazione sede che la finitura non maschera.',
            'es' => 'El corcho de relleno corre en rutas Goodyear tras bloqueo de anclaje y antes de conformado de cintura y costura de suela: nivela la cavidad entre plantilla y paquete de suela entrante con el cambrillon ya asentado. Relleno incompleto o deriva de densidad se contiene en auditoria de banco antes de tinta de canto y bloqueo de suela, porque vacios bajo cintura se vuelven chirridos en flexion y balanceo de asiento que el acabado no oculta.',
        ],
        'lasting-tuck' => [
            'en' => 'Lasting tuck is the controlled fold of upper margin into the lasting capture zone before tack or cement fixation, converting pattern allowance into stable edge capture without grain rupture. Tuck angle and overlap must match material stretch memory; poor tuck discipline creates edge bubbles, asymmetric pull, and bond-line lift visible only after sole attachment.',
            'pt' => 'A dobragem de moldação é o vinco controlado da margem do cabedal na zona de captura antes da fixação por prego ou cola, convertendo folga de modelagem em captura estável de bordo sem rutura do grão. Ângulo e sobreposição do vinco devem corresponder à memória de estiramento do material; disciplina fraca gera bolhas de bordo, tração assimétrica e levantamento da linha de colagem visível apenas após colagem da sola.',
            'fr' => 'Le rabat de montage est le pli contrôlé de marge de tige dans la zone de capture avant fixation par pointe ou colle, transformant la marge patron en capture stable de rive sans rupture de grain. Angle et recouvrement doivent correspondre à la mémoire d’allongement matière; une discipline faible crée bullage de rive, traction asymétrique et relevage de ligne de collage visible seulement après pose semelle.',
            'de' => 'Die Aufziehfalte ist die kontrollierte Umschlagfaltung des Schaftrands in die Aufnahmezone vor Heft- oder Klebefixierung, die Schnittzugabe in stabile Randaufnahme ohne Narbenbruch ueberfuehrt. Faltenwinkel und Ueberlapp muessen zur Materialdehnungsgedaechtnis passen; schwache Faltdisziplin erzeugt Kantenblasen, asymmetrischen Zug und Klebefugenanhebung erst nach Sohlenanbringung.',
            'it' => 'La ripiegatura di montaggio e la piega controllata del margine tomaia nella zona di cattura prima di fissaggio con chiodo o colla, che converte allowance modellistica in cattura bordo stabile senza rottura grano. Angolo e sovrapposizione devono combaciare con memoria di allungamento materiale; disciplina debole crea bolle bordo, trazione asimmetrica e sollevamento linea incollaggio visibile solo dopo applicazione suola.',
            'es' => 'El pliegue de montado es el doblez controlado del margen del corte en la zona de captura antes de fijacion por clavo o adhesivo, convirtiendo holgura de patronaje en captura estable de canto sin rotura de grano. Angulo y solape deben corresponder a la memoria de estiramiento del material; disciplina debil genera burbujas de canto, traccion asimetrica y levantamiento de linea de pegado visible solo tras colocacion de suela.',
        ],
        'tuck' => [
            'en' => 'Tuck is the station-level micro-fold operation that seats lasting margin into capture geometry before tack or cement lock, and it is tuned by material stretch memory and pincer direction. In production flow, tuck discipline bridges allowance design to pull execution; unstable tuck at toe or side stations propagates into seat imbalance, edge bubble emergence, and delayed bond-line lift.',
            'pt' => 'Tuck é a operação de microdobra ao nível da estação que assenta a margem de moldação na geometria de captura antes do bloqueio por prego ou cola, sendo afinada por memória de estiramento do material e direção da pinça. No fluxo produtivo, a disciplina de tuck liga o desenho da folga à execução do puxo; tuck instável nas estações de biqueira ou lateral propaga desequilíbrio no assento, bolha de bordo e levantamento tardio da linha de colagem.',
            'fr' => 'Le tuck est l’opération de micro-pli au poste qui assied la marge de montage dans la géométrie de capture avant verrouillage par pointe ou colle, réglée selon mémoire d’allongement matière et direction de pince. Dans le flux usine, la discipline tuck relie conception de marge et exécution du tirage; un tuck instable en pointe ou latéral propage déséquilibre d’assise, bullage de rive et relevage tardif de ligne de collage.',
            'de' => 'Tuck ist die stationsbezogene Mikro-Faltoperation, die die Aufziehmarge vor Stift- oder Klebeverriegelung in die Aufnahmegeometrie setzt und nach Materialdehnungsgedaechtnis sowie Zangenzugrichtung abgestimmt wird. Im Produktionsfluss verbindet Tuck-Disziplin Zugabenauslegung mit Zugausfuehrung; instabiler Tuck an Spitzen- oder Seitenstationen fuehrt zu Sitzungleichgewicht, Kantenblasen und spaeter Klebefugenanhebung.',
            'it' => 'Il tuck e l’operazione di micro-piega a livello stazione che assesta il margine di montaggio nella geometria di cattura prima del blocco con chiodo o colla, tarata su memoria di allungamento materiale e direzione pinza. Nel flusso produttivo, la disciplina tuck collega progetto allowance ed esecuzione tiro; tuck instabile in punta o laterale propaga squilibrio sede, bolle bordo e sollevamento tardivo linea incollaggio.',
            'es' => 'Tuck es la operación de micropliegue a nivel de estación que asienta el margen de montado en la geometría de captura antes del bloqueo con clavo o adhesivo, ajustada por memoria de estiramiento del material y dirección de pinza. En el flujo productivo, la disciplina de tuck conecta diseño de holgura y ejecución de tracción; tuck inestable en puntera o lateral propaga desequilibrio de asiento, burbujas de canto y levantamiento tardío de línea de pegado.',
        ],
        'shank-reinforcement' => [
            'en' => 'Shank reinforcement is seated in the internal footbed stack at the waist window after lasting release and before cork fill or cemented press: its fore-aft position relative to last waist curve and outsole flex groove sets where the shoe will hinge in gait. On Goodyear routes, shank seat must clear cork pour paths; on Blake or cemented routes, it must not stand proud of filler profile or the press will bridge air. Torsion bench sign-off is mandatory before sockliner lay-down—longitudinal drift of even 2 mm relocates squeak from acceptable flex to midfoot complaint in wear trials.',
            'pt' => 'O reforço da alma assenta-se na janela da cintura do pacote interno após libertação da moldação e antes da cortiça ou prensa colada: a posição longitudinal face à curva da cintura da forma e ao sulco de flexão da sola define onde o calçado articulará na marcha. Em rotas Goodyear, o assento da alma tem de libertar percurso de vertido de cortiça; em Blake ou coladas, não pode sobressair ao perfil de enchimento ou a prensa cria ponte de ar. Homologação em bancada de torção é obrigatória antes do forro de palmilha—deriva de 2 mm desloca ranger de flexão aceitável para reclamação de mediopé em ensaios de uso.',
            'fr' => 'Le renfort de cambrion se pose dans la fenêtre de cambrion de l’empilement interne après libération montage et avant liège ou presse collée: sa position avant-arrière versus courbe de taille de forme et rainure flex semelle fixe où la chaussure articulera à la marche. Sur routes Goodyear, l’assise cambrion doit libérer les coulées de liège; sur Blake ou collées, il ne doit pas dépasser le profil de remplissage sous peine de pont d’air à la presse. Validation banc de torsion obligatoire avant première de propreté—une dérive de 2 mm déplace un grincement de flexion acceptable vers plainte médio-pied en essais de port.',
            'de' => 'Die Gelenkfeder-Verstaerkung sitzt im Taillenfenster des internen Fussbettaufbaus nach Aufziehfreigabe und vor Kork oder geklebter Presse: ihre Laengsposition zu Leistentaille und Sohlenflexrille legt fest, wo der Schuh im Gang artikuliert. Auf Goodyear-Routen muss der Schanksitz Korkgießwege freihalten; auf Blake- oder Kleberouten darf sie nicht ueber dem Fuellprofil stehen, sonst presst die Presse Luftbruecken. Torsionspruefstand-Freigabe ist Pflicht vor Decksohle—Laengsdrift von 2 mm verschiebt Knarren von akzeptabler Flex zu Mittelfuss-Reklamation im Trageversuch.',
            'it' => 'Il rinforzo cambrione si inserisce nella finestra vita dello stack interno dopo rilascio montaggio e prima di sughero o pressa cementata: la posizione longitudinale rispetto a curva vita forma e scanalatura flessione suola definisce dove la calzatura articolerà nel passo. Sulle filiere Goodyear il sede cambrione deve liberare i percorsi di colata sughero; su Blake o cementate non deve sporgere dal profilo riempimento o la pressa crea ponti d’aria. Omologazione banco torsione obbligatoria prima del sottopiede—deriva di 2 mm sposta scricchiolio da flessione accettabile a reclamo mesopiede nei wear test.',
            'es' => 'El refuerzo del cambrillón se asienta en la ventana de cintura del paquete interno tras liberación de montado y antes de corcho o prensa cementada: su posición longitudinal respecto a curva de cintura de horma y ranura de flexión de suela fija dónde articulará el calzado al caminar. En rutas Goodyear el asiento del cambrillón debe liberar vertidos de corcho; en Blake o cementadas no debe sobresalir del perfil de relleno o la prensa crea puente de aire. Homologación en banco de torsión es obligatoria antes de plantilla de acabado—deriva de 2 mm desplaza chirridos de flexión aceptable a queja de mediopié en pruebas de uso.',
        ],
        'topline-reinforcement' => [
            'en' => 'Topline reinforcement stabilizes the collar and opening edge against pull-induced waviness, lining shear, and hardware stress during entry cycles. It must integrate with feather-edge transition and counter skive so stiffness does not telegraph as a visible ridge along the throat and quarter junction.',
            'pt' => 'O reforço da linha superior estabiliza o colarinho e o bordo de abertura contra ondulação por tração, cisalhamento do forro e tensão de ferragens durante ciclos de entrada. Deve integrar-se com a transição do bordo em pena e o rebaixo do contraforte para que a rigidez não se manifeste como crista visível na junção garganta-quarto.',
            'fr' => 'Le renfort de ligne de col stabilise col et bord d’ouverture contre ondulation par traction, cisaillement doublure et contrainte de ferrures lors des cycles d’enfilage. Il doit s’intégrer à la transition de bord aminci et au parage contrefort pour éviter une crête visible à la jonction gorge-quartier.',
            'de' => 'Die Verstaerkung der Schaftrandlinie stabilisiert Kragen und Oeffnungskante gegen zuginduzierte Welligkeit, Futterverschiebung und Beschlagstress bei Einstiegszyklen. Sie muss mit Federkantenuebergang und Kappenschaerfung integriert werden, damit Steifigkeit nicht als sichtlicher Grat an Rist-Quartieruebergang telegraphiert.',
            'it' => 'Il rinforzo topline stabilizza collarino e bordo apertura contro ondulazione da trazione, taglio fodera e stress ferramenta nei cicli di calzata. Deve integrarsi con transizione bordo a piuma e scarnitura contrafforte per evitare che la rigidita si manifesti come cresta visibile alla giunzione gola-quartiere.',
            'es' => 'El refuerzo de linea superior estabiliza el collar y el borde de apertura contra ondulacion por traccion, cizallamiento de forro y tension de herrajes en ciclos de entrada. Debe integrarse con la transicion de borde en pluma y el rebajado de contrafuerte para que la rigidez no se manifieste como cresta visible en la union garganta-cuarto.',
        ],
        'waist-shaping' => [
            'en' => 'Waist shaping is the Goodyear bench operation that profiles the midfoot cavity between shank seat, cork fill, and outsole landing so flex hinge, support, and edge-ink read stay aligned before channel stitch. It couples last waist curve, shank width, and cork density map—void pockets or asymmetric shave depth propagate into channel crown failure and flex squeak that edge finish cannot mask. Cemented routes rarely run a full waist bench pass; when they do, it is only to level filler proud of the shank before press, not to substitute for holdfast bite geometry.',
            'pt' => 'A modelação da cintura é a operação de bancada Goodyear que perfila a cavidade do mediopé entre assento da alma, enchimento em cortiça e assentamento da sola para alinhar articulação, suporte e leitura da tinta de bordo antes da costura em canal. Acopla curva da cintura da forma, largura da alma e mapa de densidade da cortiça—bolsas vazias ou desbaste assimétrico propagam falha de coroa de canal e ranger em flexão. Rotas coladas raramente fazem passagem completa de bancada; quando fazem, é só para nivelar enchimento acima da alma antes da prensa.',
            'fr' => 'Le profilage du cambrion est l’opération d’atelier Goodyear qui profile la cavité médio-pied entre assise cambrion, liège et assise semelle pour aligner charnière, support et lecture encre de tranche avant couture en canal. Il couple courbe de taille, largeur cambrion et carte densité liège—poches vides ou rabotage asymétrique provoquent échec couronne canal et grincement en flexion. Les routes collées passent rarement au banc complet; quand elles le font, c’est pour niveler le remplissage avant presse, pas pour remplacer la prise holdfast.',
            'de' => 'Die Taillenformung ist der Goodyear-Bankposten, der die Mittelfusskavität zwischen Schanksitz, Korkfüllung und Sohlenauflage profiliert, damit Knickpunkt, Stützung und Kantenfarb-Lesung vor der Kanalnaht zusammenpassen. Sie koppelt Leistentaille, Schankbreite und Korkdichtekarte—Hohlstellen oder asymmetrisches Abschrägen führen zu Kronenfehlern in der Kanalnaht und Flex-Knarren. Kleberouten fahren selten den vollen Bankgang; wenn doch, nur zum Nivellieren überstehenden Füllmaterials vor der Presse.',
            'it' => 'La modellatura punto vita è l’operazione banco Goodyear che profila la cavità mesopiede tra sede cambrione, riempimento sughero e appoggio suola per allineare cerniera, supporto e lettura inchiostro bordo prima della cucitura in canale. Accoppia curva vita forma, larghezza cambrione e mappa densità sughero—tasche vuote o rasatura asimmetrica propagano falla corona canale e scricchiolio in flessione. Le route cementate raramente eseguono il passaggio banco completo; quando lo fanno, solo per livellare riempimento sporgente prima della pressa.',
            'es' => 'El conformado de cintura es la operación de banco Goodyear que perfila la cavidad del mediopié entre asiento del cambrillón, corcho y asiento de suela para alinear bisagra, soporte y lectura de tinta de canto antes de la costura en canal. Acopla curva de cintura, ancho de cambrillón y mapa de densidad de corcho—bolsas vacías o desbaste asimétrico propagan fallo de corona de canal y chirridos en flexión. Las rutas cementadas rara vez hacen el paso completo de banco; cuando lo hacen, es solo para nivelar relleno orgulloso antes de la prensa.',
        ],
        'holdfast-stitch' => [
            'en' => 'Holdfast stitch is the primary mechanical lock on Goodyear routes after gemming: it anchors the welt into the formed rib with governed penetration depth and lock-loop geometry. The bite map is a hard gate—shallow penetration or skipped loops compromise welt security and are leading precursors to channel reopening and resoling failure at the waist curve.',
            'pt' => 'O ponto de retenção da vira é o bloqueio mecânico primário nas rotas Goodyear após gemming: ancora a vira na nervura formada com profundidade de penetração e geometria de fecho governadas. O mapa de mordida é gate duro—penetração rasa ou saltos no fecho comprometem a segurança da vira e antecedem reabertura de canal e falha de ressolagem na curva da cintura.',
            'fr' => 'Le point d’ancrage trépointe est le verrouillage mécanique principal sur routes Goodyear après gemmage: il fixe la trépointe dans la nervure formée avec profondeur de pénétration et géométrie de boucle pilotées. La carte de prise est un gate dur—pénétration faible ou boucles manquantes compromettent la sécurité trépointe et précèdent réouverture de canal et échec ressemelage au cambrion.',
            'de' => 'Der Holdfast-Stich ist der primaere mechanische Verriegelungsschritt auf Goodyear-Routen nach dem Gemming: er verankert die weltende Rahmenleiste in der geformten Rippe mit gefuehrter Eindringtiefe und Schlingenschloss. Die Aufnahmekarte ist ein hartes Freigabe-Gate—zu flache Eindringung oder ausgelassene Schlingen gefaehrden die Rahmenhalterung und sind typische Vorlaeufer fuer Kanalwiedereroeffnung und Wiederbesohlungsfehler in der Taillee.',
            'it' => 'Il punto di tenuta guardolo è il blocco meccanico primario sulle filiere Goodyear dopo gemming: ancora il guardolo nella nervatura formata con profondità di penetrazione e geometria di chiusura governate. La mappa di presa è un gate rigido—penetrazione ridotta o salti di chiusura compromettono la tenuta del guardolo e precedono riapertura canale e guasti di risuolatura in vita.',
            'es' => 'La puntada de anclaje del cerco es el bloqueo mecánico primario en rutas Goodyear tras gemming: ancla el cerco en el nervio formado con profundidad de penetración y geometría de cierre gobernadas. El mapa de mordida es puerta dura—penetración superficial o saltos de cierre comprometen la seguridad del cerco y preceden reapertura de canal y fallo de resolado en curva de cintura.',
        ],
        'welt-stitch-penetration' => [
            'en' => 'Welt stitch penetration is the measured depth and angle at which holdfast and outsole stitches engage rib and welt material, used as a release gate for welting and resoling integrity. Shallow penetration maps correlate with welt lift, moisture ingress at the waist, and stitch pull-out under flex endurance.',
            'pt' => 'A penetração do ponto da vira é a profundidade e o ângulo medidos em que os pontos de retenção e de sola envolvem nervura e material da vira, usados como gate de libertação para integridade de viração e ressolagem. Mapas de penetração rasa correlacionam com levantamento da vira, entrada de humidade na cintura e arrancamento do ponto em resistência à flexão.',
            'fr' => 'La pénétration de point trépointe est la profondeur et l’angle mesurés d’engagement des points d’ancrage et de semelle dans nervure et matière de trépointe, utilisés comme gate de libération pour intégrité de trépointe et ressemelage. Les cartes de pénétration faible corrèlent soulèvement de trépointe, ingress d’humidité au cambrion et arrachement de point en endurance flexion.',
            'de' => 'Die Rahmenstichpenetration ist die gemessene Tiefe und der Winkel, mit dem Holdfast- und Laufsohlenstiche Rippe und Rahmenmaterial erfassen, als Freigabe-Gate fuer Rahmen- und Wiederbesohlungsintegritaet. Karten flacher Penetration korrelieren mit Rahmenanhebung, Feuchteeintritt in der Taillee und Stichauszug unter Biegeendurance.',
            'it' => 'La penetrazione punto guardolo e la profondita e angolo misurati con cui punti di tenuta e suola coinvolgono nervatura e materiale guardolo, usati come gate di rilascio per integrita guardolo e risuolatura. Mappe di penetrazione ridotta correlano con sollevamento guardolo, ingresso umidita in vita e estrazione punto in endurance flessione.',
            'es' => 'La penetracion de puntada de cerco es la profundidad y angulo medidos con que las puntadas de anclaje y suela involucran nervio y material de cerco, usados como puerta de liberacion para integridad de cercado y resolado. Mapas de penetracion superficial correlacionan con levantamiento de cerco, ingreso de humedad en cintura y arranque de puntada en resistencia a flexion.',
        ],
        'internal-footbed-stack' => [
            'en' => 'Internal footbed stack is the ordered assembly of insole board, shank, filler, and sockliner that defines underfoot support, volume, and moisture path before final closing. Stack sequencing errors—such as sockliner ahead of shank validation—surface late as heel pocket collapse, arch hotspot, or lining delamination at the waist.',
            'pt' => 'O pacote interno de palmilha é a montagem ordenada de cartão de palmilha, alma, enchimento e forro de palmilha que define suporte plantar, volume e percurso de humidade antes do fecho final. Erros de sequência—como forro de palmilha antes da validação da alma—surgem tarde como colapso do assento do calcanhar, hotspot no arco ou delaminação do forro na cintura.',
            'fr' => 'L’empilement interne de première est l’assemblage ordonné de carton de première, cambrion, remplissage et première de propreté qui définit support plantaire, volume et chemin d’humidité avant fermeture finale. Les erreurs de séquence—première de propreté avant validation cambrion—apparaissent tard en affaissement d’assise talon, point chaud voûte ou délamination doublure au cambrion.',
            'de' => 'Der interne Fussbettaufbau ist die geordnete Montage aus Brandsohlenbrett, Gelenkfeder, Fuellung und Decksohle, die plantare Stuetze, Volumen und Feuchtigkeitsweg vor dem Endverschluss definiert. Sequenzfehler—etwa Decksohle vor Schankvalidierung—zeigen sich spaet als Fersenbeckenkollaps, Gewoelbe-Hotspot oder Futterdelamination in der Taillee.',
            'it' => 'Lo stack interno sottopiede e l’assemblaggio ordinato di cartone soletta, cambrione, riempimento e sottopiede finitura che definisce supporto plantare, volume e percorso umidita prima della chiusura finale. Errori di sequenza—sottopiede prima della validazione cambrione—emergono tardi come collasso sede tallone, hotspot arco o delaminazione fodera in vita.',
            'es' => 'El paquete interno de plantilla es el ensamblaje ordenado de carton de plantilla, cambrillon, relleno y plantilla de acabado que define soporte plantar, volumen y ruta de humedad antes del cierre final. Errores de secuencia—plantilla de acabado antes de validacion de cambrillon—aparecen tarde como colapso de asiento de talon, hotspot de arco o delaminacion de forro en cintura.',
        ],
        'feather-line' => [
            'en' => 'Feather line is the visible or tactile reference along the skived margin where taper transitions into folded or stitched edge geometry; it is the quality read operators use before lasting to predict topline behavior. Line continuity depends on skive angle class, leather compressibility, and fold pressure—breaks in the line forecast collar ripple after pull.',
            'pt' => 'A linha em pena é a referência visível ou tátil ao longo da margem rebaixada onde o afino transita para geometria de bordo dobrado ou costurado; é a leitura de qualidade usada antes da moldação para prever comportamento da linha superior. A continuidade depende da classe de ângulo de rebaixo, compressibilidade do couro e pressão de dobra—quebras na linha antecipam ondulação do colarinho após tração.',
            'fr' => 'La ligne amincie est la référence visible ou tactile le long de la marge parée où l’affinage passe en géométrie de bord rabattu ou cousu; c’est la lecture qualité avant montage pour prédire le comportement de ligne de col. La continuité dépend de la classe d’angle de parage, compressibilité cuir et pression de rabat—une rupture de ligne annonce ondulation de col après traction.',
            'de' => 'Die Federlinie ist die sichtbare oder taktile Referenz entlang des geschaerften Randes, wo die Ausduennung in gefaltete oder genaehte Kantengeometrie uebergeht; sie ist die Qualitaetslesung vor dem Aufziehen zur Prognose des Schaftrandverhaltens. Linienstetigkeit haengt von Schaerfwinkelklasse, Lederkomprimierbarkeit und Faltdruck ab—Linienbrueche prognostizieren Kragenwelligkeit nach dem Zug.',
            'it' => 'La linea a piuma e il riferimento visibile o tattile lungo il margine scarnito dove la rastremazione passa in geometria bordo ripiegato o cucito; e la lettura qualita pre-montaggio per prevedere il comportamento topline. La continuita dipende da classe angolo scarnitura, comprimibilita pelle e pressione piega—interruzioni della linea anticipano ondulazione collarino dopo trazione.',
            'es' => 'La linea en pluma es la referencia visible o tactil a lo largo del margen rebajado donde el afinado transita a geometria de canto doblado o cosido; es la lectura de calidad previa al montado para predecir comportamiento de linea superior. La continuidad depende de clase de angulo de rebajado, compresibilidad del cuero y presion de doblado—roturas de linea anticipan ondulacion de collar tras traccion.',
        ],
    ];

    $expertTierExamples = [
        'upper-assembly' => [
            'en' => 'Closing supervisor quarantined bundle 14 when quarter-to-vamp seam offset exceeded 1.5 mm and mirror-pair symmetry failed final upper board inspection.',
            'pt' => 'O supervisor de fecho colocou em quarentena o lote 14 quando o desvio da costura quarto-vampão excedeu 1,5 mm e a simetria do par espelhado falhou na inspeção final de cabedal.',
            'fr' => 'Le superviseur de piquage a mis en quarantaine le lot 14 quand le décalage de couture quartier-claque a dépassé 1,5 mm et que la symétrie paire miroir a échoué.',
            'de' => 'Die Schließerei-Leitung sperrte Bündel 14, nachdem der Quartier-Vorderblatt-Nahtversatz 1,5 mm überschritt und die Spiegelpaar-Symmetrie in der Endprüfung ausfiel.',
            'it' => 'Il responsabile giunteria ha messo in quarantena il lotto 14 quando lo scostamento cucitura quartiere-tomaia ha superato 1,5 mm e la simmetria coppia speculare è fallita.',
            'es' => 'El supervisor de aparado puso en cuarentena el lote 14 cuando el desfase de costura cuarto-empeine superó 1,5 mm y falló la simetría de par espejo.',
        ],
        'back-part-lasting' => [
            'en' => 'Back-part lasting returned six pairs to counter molding when heel-quarter wrap gauge showed 0.7 mm gap to hot-formed counter edge before seat transfer on board-lasted route 12-C.',
            'pt' => 'A moldação traseira devolveu seis pares à moldagem do contraforte quando a galga de envolvimento do quarto mostrou 0,7 mm de vão face ao bordo do contraforte termoformado antes da passagem para o assento na rota com cartão 12-C.',
            'fr' => 'Le montage arrière a renvoyé six paires au moulage contrefort quand la jauge d’enveloppement quartier a montré 0,7 mm d’écart versus bord contrefort thermoformé avant transfert assise sur route carton 12-C.',
            'de' => 'Das Hinterkappen-Aufziehen schickte sechs Paare zur Kappenformung zurueck, als die Quartier-Umschlag-Lehre 0,7 mm Spalt zur warmgeformten Kappenkante vor Sitzuebergabe auf Route 12-C zeigte.',
            'it' => 'Il montaggio posteriore ha rimandato sei paia allo stampaggio contrafforte quando la dima avvolgimento quartiere ha mostrato 0,7 mm di gap sul bordo contrafforte termoformato prima del passaggio sede sulla route cartone 12-C.',
            'es' => 'El montado trasero devolvió seis pares al moldeado de contrafuerte cuando la galga de envolvente del cuarto mostró 0,7 mm de holgura frente al borde termoformado antes del pase a asiento en ruta cartón 12-C.',
        ],
        'toe-lasting' => [
            'en' => 'Toe station held lot 9 when mirror gauge showed 1.4 mm toe-spring asymmetry after first pull, blocking side-lasting handoff until pincer map was recalibrated for the microfiber last family.',
            'pt' => 'A estação de biqueira reteve o lote 9 quando o medidor espelhado mostrou 1,4 mm de assimetria de toe spring após a primeira tração, bloqueando passagem para moldação lateral até recalibração do mapa de pinça na família de forma em microfibra.',
            'fr' => 'Le poste pointe a retenu le lot 9 quand la jauge miroir a montré 1,4 mm d’asymétrie toe spring après premier tirage, bloquant le transfert vers montage latéral jusqu’à recalibrage de la carte pinces sur la famille de forme microfibre.',
            'de' => 'Die Spitzenstation stoppte Los 9, als die Spiegellehre nach dem Erstzug 1,4 mm Toe-Spring-Asymmetrie zeigte und den Uebergang zum Seitenaufziehen bis zur Zangenkarten-Neukalibrierung fuer die Mikrofaser-Leistenfamilie blockierte.',
            'it' => 'La stazione punta ha trattenuto il lotto 9 quando la dima specchio ha mostrato 1,4 mm di asimmetria toe spring dopo il primo tiro, bloccando il passaggio al montaggio laterale fino a ricalibro mappa pinza sulla famiglia forma microfibra.',
            'es' => 'La estación de puntera retuvo el lote 9 cuando la galga espejo mostró 1,4 mm de asimetría de toe spring tras la primera tracción, bloqueando el pase a montado lateral hasta recalibrar el mapa de pinza en la familia de horma de microfibra.',
        ],
        'rib-attaching' => [
            'en' => 'Insole prep quarantined size 41 boards when rib-bond shear on the waist quadrant failed 12% below route nominal, stopping channel routing before holdfast maps were generated.',
            'pt' => 'A preparação de palmilha colocou em quarentena cartões tamanho 41 quando o cisalhamento da união da nervura no quadrante da cintura ficou 12% abaixo do nominal da rota, parando encaminhamento de canal antes de gerar mapas de retenção.',
            'fr' => 'La préparation première a mis en quarantaine les cartons pointure 41 quand le cisaillement collage nervure au quadrant cambrion est tombé à 12 % sous le nominal gamme, stoppant le routage canal avant génération des cartes holdfast.',
            'de' => 'Die Brandsohlenvorbereitung sperrte Groesse-41-Bretter, als Rippenklebescherung im Taillenquadranten 12 % unter Routen-Nominal lag und Kanalrouting vor Holdfast-Kartenerzeugung stoppte.',
            'it' => 'La preparazione soletta ha messo in quarantena cartoni taglia 41 quando lo shear del legame nervatura nel quadrante vita e sceso del 12% sotto nominale rotta, fermando il routing canale prima di generare mappe holdfast.',
            'es' => 'La preparación de plantilla puso en cuarentena cartones talla 41 cuando el cizallamiento de unión del nervio en el cuadrante de cintura quedó un 12% por debajo del nominal de ruta, deteniendo enrutado de canal antes de generar mapas de anclaje.',
        ],
        'roughing' => [
            'en' => 'Roughing QC held lot 22 when bond-interface topography on the heel-seat quadrant showed 0.4 mm shallow versus route card—pairs returned before primer could wet a starved margin.',
            'pt' => 'O CQ de rugosagem reteve o lote 22 quando a topografia da interface de colagem no quadrante do assento mediu 0,4 mm abaixo da ficha—pares devolvidos antes do primário molhar margem privada.',
            'fr' => 'Le QC rugosification a retenu le lot 22 quand la topographie d’interface collage au quadrant assise talon était 0,4 mm sous la gamme—retour avant primaire sur marge affamée.',
            'de' => 'Das Aufrau-QC stoppte Los 22, als die Klebefugen-Topografie im Fersensitz-Quadranten 0,4 mm unter Route-Card lag—Rueckgabe vor Primerbenetzung einer verhungerten Kante.',
            'it' => 'Il QC rugosatura ha trattenuto il lotto 22 quando la topografia interfaccia incollaggio nel quadrante sede tallone era 0,4 mm sotto scheda—ritorno prima che il primer bagnasse margine affamato.',
            'es' => 'El QC de rugosado retuvo el lote 22 cuando la topografía de interfaz de pegado en el cuadrante del asiento midió 0,4 mm por debajo de la hoja—devolución antes de que el primer humectara margen deficiente.',
        ],
        'primer-coat' => [
            'en' => 'Primer cell stopped spread queue when flash-off read 18 s under climate envelope on TPU stacks—under-dry film would have trapped solvent and produced bond-line weakness at heel transition.',
            'pt' => 'A célula de primário parou a fila de aplicação quando o tempo de secagem mediu 18 s abaixo do envelope climático em pilhas TPU—filme sub-seco aprisionaria solvente e enfraqueceria a linha de cola na transição do calcanhar.',
            'fr' => 'La cellule primaire a stoppé la file dépôt quand le flash-off était 18 s sous l’enveloppe climat sur piles TPU—film sous-séché aurait piégé solvant et affaibli la ligne collage au talon.',
            'de' => 'Die Primzelle stoppte die Auftragsschlange, als Flash-off auf TPU-Stapeln 18 s unter Klimaumschlag lag—untertrockener Film haette Loesungsmittel gebunden und die Klebefuge am Fersenuebergang geschwaecht.',
            'it' => 'La cella primer ha fermato la coda spalmatura quando il flash-off era 18 s sotto envelope climatico su pile TPU—film sotto-asciugato avrebbe intrappolato solvente e indebolito la linea incollaggio al tallone.',
            'es' => 'La célula de primer detuvo la cola de extendido cuando el flash-off midió 18 s por debajo del envelope climático en pilas TPU—película subsecada habría atrapado solvente y debilitado la línea de pegado en el talón.',
        ],
        'channeling' => [
            'en' => 'CNC channel audit blocked welt prep when waist-curve depth measured 0.35 mm under nominal on left boards, preventing holdfast stitch release with predictable shallow bite.',
            'pt' => 'A auditoria CNC de canal bloqueou preparação de vira quando a profundidade na curva da cintura mediu 0,35 mm abaixo do nominal nos cartões esquerdo, impedindo libertação de ponto de retenção com mordida rasa previsível.',
            'fr' => 'L’audit CNC canal a bloqué la préparation trépointe quand la profondeur sur courbe cambrion a mesuré 0,35 mm sous nominal sur cartons gauche, empêchant libération point holdfast à prise faible prévisible.',
            'de' => 'Das CNC-Kanalaudit stoppte die Rahmenvorbereitung, als die Taillenkurventiefe auf linken Brettern 0,35 mm unter Nominal maß und Holdfast-Freigabe mit vorhersehbar flacher Aufnahme verhinderte.',
            'it' => 'L’audit CNC canale ha bloccato prep guardolo quando la profondità su curva vita ha misurato 0,35 mm sotto nominale su cartoni sinistri, impedendo rilascio punto holdfast con presa ridotta prevedibile.',
            'es' => 'La auditoría CNC de canal bloqueó prep de cerco cuando la profundidad en curva de cintura midió 0,35 mm por debajo del nominal en cartones izquierdos, impidiendo liberación de puntada de anclaje con mordida superficial previsible.',
        ],
        'heat-activation' => [
            'en' => 'Tunnel QC extended dwell 3 s on cupsole stacks after transfer-latency alarms showed cold-press peel at the heel seat while forepart tack remained nominal.',
            'pt' => 'O CQ do túnel prolongou o dwell 3 s em pilhas cupsole após alarmes de latência de transferência mostrarem peel em prensa fria no assento do calcanhar com tack nominal na frente.',
            'fr' => 'Le QC tunnel a prolongé le maintien de 3 s sur piles cupsole après alarmes de latence de transfert montrant pelage à presse froide à l’assise talon avec tack nominal à l’avant-pied.',
            'de' => 'Das Tunnel-QC verlaengerte den Dwell um 3 s bei Cupsole-Stapeln, nachdem Transferlatenz-Alarme Kaltpress-Schaelung am Fersensitz bei nominalem Vorfuss-Tack zeigten.',
            'it' => 'Il QC tunnel ha esteso il dwell di 3 s su pile cupsole dopo allarmi latenza trasferimento con peel a pressa fredda sulla sede tallone e tack nominale sull’avampiede.',
            'es' => 'El QC de túnel extendió el dwell 3 s en pilas cupsole tras alarmas de latencia de transferencia que mostraron pelado en prensa fría en asiento de talón con tack nominal en antepié.',
        ],
        'bottoming' => [
            'en' => 'Bottoming supervisor routed lot 27 to welt prep after lasting release flagged Goodyear construction, while lot 28 entered roughing-primer within 6 min of Strobel handoff on the cemented track.',
            'pt' => 'O supervisor de montagem de fundo encaminhou o lote 27 para preparação de vira após libertação da moldação sinalizar construção Goodyear, enquanto o lote 28 entrou em rugosagem-primário em 6 min após passagem Strobel na rota colada.',
            'fr' => 'Le superviseur montage de fond a routé le lot 27 vers préparation trépointe après libération montage signalant construction Goodyear, tandis que le lot 28 entrait rugosification-primaire en 6 min après transfert Strobel sur la piste collée.',
            'de' => 'Die Bottoming-Leitung leitete Los 27 nach Rahmenvorbereitung, nachdem die Aufziehfreigabe Goodyear-Konstruktion meldete, waehrend Los 28 innerhalb von 6 min nach Strobel-Uebergabe auf der Kleberoute ins Aufrauen-Primern ging.',
            'it' => 'Il supervisore fondo ha instradato il lotto 27 a prep guardolo dopo rilascio montaggio con costruzione Goodyear, mentre il lotto 28 e entrato in rugosatura-primer entro 6 min dal passaggio Strobel sulla traccia cementata.',
            'es' => 'El supervisor de fondo enrutó el lote 27 a prep de cerco tras liberación de montado con construcción Goodyear, mientras el lote 28 entró en rugosado-primario en 6 min tras pase Strobel en la vía cementada.',
        ],
        'lasting' => [
            'en' => 'Lasting control issued two route tickets from the same seat release: lot 31 to welt prep after Goodyear flag, lot 32 to roughing within four minutes on Strobel-cement after margin-stress and collar-symmetry checks passed.',
            'pt' => 'O controlo de moldação emitiu duas fichas de rota na mesma libertação do assento: lote 31 para prep de vira com flag Goodyear, lote 32 para rugosagem em quatro minutos em Strobel colado após verificação de tensão das margens e simetria do colarinho.',
            'fr' => 'Le contrôle montage a émis deux fiches de route sur la même libération assise: lot 31 vers prep nervure avec flag Goodyear, lot 32 vers rugosification en quatre minutes sur Strobel collé après contrôle tension de marge et symétrie de col.',
            'de' => 'Die Aufziehkontrolle stellte zwei Routenscheine aus derselben Sitzfreigabe aus: Los 31 zur Rippenvorbereitung nach Goodyear-Flag, Los 32 ins Aufrauen innerhalb von vier Minuten auf Strobel-Kleberoute nach Randspannungs- und Kragensymmetrie-Check.',
            'it' => 'Il controllo montaggio ha emesso due schede rotta dallo stesso rilascio sede: lotto 31 a prep nervatura con flag Goodyear, lotto 32 a rugosatura entro quattro minuti su Strobel cementato dopo verifica tensione margini e simmetria collarino.',
            'es' => 'El control de montado emitió dos fichas de ruta en la misma liberación de asiento: lote 31 a prep de nervio con flag Goodyear, lote 32 a rugosado en cuatro minutos en Strobel cementado tras comprobar tensión de márgenes y simetría de collar.',
        ],
        'side-lasting' => [
            'en' => 'Engineering logged a side-lasting pull-ratio change on size 39 after the waist mirror flagged 1.2° collar twist—correction stayed at side post and avoided a seat-lasting rework on twelve pairs.',
            'pt' => 'A engenharia registou alteração da razão de tração lateral no tamanho 39 após o espelho da cintura sinalizar 1,2° de torção do colarinho—a correção ficou no posto lateral e evitou retrabalho de moldação do assento em doze pares.',
            'fr' => 'L’ingénierie a journalisé un changement de ratio de traction latérale en pointure 39 après vrille de col de 1,2° sur jauge miroir cambrion—la correction est restée au poste latéral et a évité une retouche montage de siège sur douze paires.',
            'de' => 'Die Technik protokollierte eine Aenderung des Seitenaufzieh-Zugverhaeltnisses in Groesse 39 nach 1,2° Kragenverdrillung an der Taillen-Spiegellehre—Korrektur blieb am Seitenposten und vermied Sitzaufzieh-Nacharbeit an zwoelf Paaren.',
            'it' => 'L’ingegneria ha registrato un cambio del rapporto di trazione laterale in taglia 39 dopo 1,2° di torsione collarino sulla dima specchio vita—la correzione e rimasta al posto laterale ed ha evitato rilavorazione montaggio sede su dodici paia.',
            'es' => 'Ingeniería registró un cambio de relación de tracción lateral en talla 39 tras 1,2° de torsión de collar en galga espejo de cintura—la corrección quedó en el puesto lateral y evitó retrabajo de montado de asiento en doce pares.',
        ],
        'seat-lasting' => [
            'en' => 'Seat-lasting stopped welt-prep queue when heel-seat contour gauge showed 0.6 mm mismatch to molded counter on size 40 right—pairs returned to counter room before any rib work started.',
            'pt' => 'A moldação do assento parou a fila de prep de vira quando o medidor de contorno do assento mostrou 0,6 mm de desvio face ao contraforte moldado no direito tamanho 40—os pares regressaram à sala do contraforte antes de qualquer trabalho de nervura.',
            'fr' => 'Le montage de siège a stoppé la file prep trépointe quand la jauge contour assise talon a montré 0,6 mm d’écart versus contrefort moulé sur pointure 40 droite—les paires sont retournées à l’atelier contrefort avant toute pose de nervure.',
            'de' => 'Das Sitzaufziehen stoppte die Rahmenvorbereitungs-Schlange, als die Fersensitz-Konturlehre 0,6 mm Abweichung zur geformten Kappe rechts Groesse 40 zeigte—Paare gingen zur Kappenabteilung zurueck, bevor Rippenarbeit begann.',
            'it' => 'Il montaggio sede ha fermato la coda prep guardolo quando la dima contorno sede tallone ha mostrato 0,6 mm di scostamento dal contrafforte stampato sul destro taglia 40—le paia sono tornate al reparto contrafforte prima di qualsiasi lavoro nervatura.',
            'es' => 'El montado de asiento detuvo la cola de prep de cerco cuando la galga de contorno del asiento mostró 0,6 mm de desajuste frente al contrafuerte moldeado en derecho talla 40—los pares volvieron a sala de contrafuerte antes de cualquier trabajo de nervio.',
        ],
        'shoe-last' => [
            'en' => 'Pattern engineering raised a controlled last revision after ball-girth scans from wear trials drifted 2.2 mm against the approved grading matrix.',
            'pt' => 'A engenharia de modelagem abriu revisão controlada da forma após leituras de perímetro metatarsal em ensaios de uso derivarem 2,2 mm face à matriz de graduação aprovada.',
            'fr' => 'L’ingénierie patronage a lancé une révision contrôlée de forme après dérive de 2,2 mm des relevés de périmètre métatarsien en essais de port versus matrice de gradation validée.',
            'de' => 'Die Schnitttechnik leitete eine kontrollierte Leistenrevision ein, nachdem Ballenumfang-Scans aus Trageversuchen um 2,2 mm gegenüber der freigegebenen Gradierungsmatrix abwichen.',
            'it' => 'L’ingegneria modelleria ha aperto revisione controllata forma dopo deriva di 2,2 mm nelle scansioni circonferenza metatarsale dai wear test rispetto alla matrice gradazione approvata.',
            'es' => 'Ingeniería de patronaje abrió revisión controlada de horma tras deriva de 2,2 mm en escaneos de perímetro metatarsal de pruebas de uso frente a la matriz de gradación aprobada.',
        ],
        'upper' => [
            'en' => 'Upper audit blocked packing after panel nesting swap changed grain direction and created visible quarter-vamp mismatch under final light booth checks.',
            'pt' => 'A auditoria de cabedal bloqueou embalamento após troca de nesting de painéis alterar direção de grão e criar desajuste visível quarto-vampão na inspeção final em cabine de luz.',
            'fr' => 'L’audit tige a bloqué le conditionnement après un changement de nesting panneaux ayant modifié l’orientation du grain et créé un décalage visible quartier-claque sous cabine lumière finale.',
            'de' => 'Das Schaftaudit stoppte die Verpackung, nachdem ein Panel-Nesting-Wechsel die Narbenrichtung änderte und unter Endlichtprüfung einen sichtbaren Quartier-Vorderblatt-Versatz erzeugte.',
            'it' => 'L’audit tomaia ha bloccato il confezionamento dopo cambio nesting pannelli che ha modificato direzione grana creando mismatch visibile quartiere-vamp sotto cabina luce finale.',
            'es' => 'La auditoría de corte bloqueó empaque tras cambio de nesting de paneles que alteró dirección de grano y creó desajuste visible cuarto-empeine bajo cabina de luz final.',
        ],
        'vamp' => [
            'en' => 'Development corrected vamp break line by adjusting throat notch reference after flex bench showed premature creasing ahead of intended articulation zone.',
            'pt' => 'O desenvolvimento corrigiu a linha de quebra do vampão ajustando a referência do entalhe da garganta após bancada de flexão mostrar pregueamento precoce antes da zona de articulação prevista.',
            'fr' => 'Le développement a corrigé la ligne de cassure de claque en ajustant la référence d’encoche gorge après banc de flexion montrant un plissement précoce avant la zone d’articulation visée.',
            'de' => 'Die Entwicklung korrigierte die Vorderblatt-Knicklinie durch Anpassung der Ristkerben-Referenz, nachdem der Flexprüfstand vorzeitige Faltenbildung vor der vorgesehenen Bewegungszone zeigte.',
            'it' => 'Lo sviluppo ha corretto la linea piega vamp regolando il riferimento intaglio gola dopo banco flessione con piegatura precoce prima della zona di articolazione prevista.',
            'es' => 'Desarrollo corrigió línea de quiebre del empeine ajustando referencia de muesca de garganta tras banco de flexión que mostró arrugado prematuro antes de la zona de articulación prevista.',
        ],
        'quarter' => [
            'en' => 'Closing station rebalanced quarter seam allowance after mirror-pair camera checks detected lateral collar drift above customer visual tolerance.',
            'pt' => 'A estação de fecho rebalanceou a folga de costura do quarto após controlo por câmara de par espelhado detetar deriva lateral do colarinho acima da tolerância visual do cliente.',
            'fr' => 'Le poste de piquage a rééquilibré la marge couture quartier après détection caméra paire miroir d’une dérive latérale du col au-delà de la tolérance visuelle client.',
            'de' => 'Die Schließstation balancierte die Nahtzugabe des Quartiers neu, nachdem die Spiegelpaar-Kamera lateralen Schaftranddrift über Kundensichttoleranz erkannte.',
            'it' => 'La stazione giunteria ha riequilibrato il margine cucitura quartiere dopo controllo camera coppia speculare che rilevava deriva laterale collarino oltre tolleranza visiva cliente.',
            'es' => 'La estación de aparado reequilibró margen de costura del cuarto tras control de cámara de par espejo que detectó deriva lateral de collar por encima de tolerancia visual de cliente.',
        ],
        'toe-puff' => [
            'en' => 'Material lab changed toe puff grade after hot-box aging caused forepart collapse on two colorways despite acceptable room-temperature shape checks.',
            'pt' => 'O laboratório de materiais alterou o grau da ponteira após envelhecimento em hot-box provocar colapso da frente em duas colorways, apesar de verificações de forma aceitáveis à temperatura ambiente.',
            'fr' => 'Le laboratoire matières a changé le grade de contrefort avant après qu’un vieillissement hot-box ait provoqué un affaissement avant-pied sur deux coloris malgré des contrôles de forme ambiants conformes.',
            'de' => 'Das Materiallabor wechselte die Zehenverstärkungsqualität, nachdem Hot-Box-Altern den Vorfuß bei zwei Colorways kollabieren ließ, obwohl Raumtemperatur-Formprüfungen bestanden wurden.',
            'it' => 'Il laboratorio materiali ha cambiato il grado puntale dopo invecchiamento hot-box con collasso avampiede su due colorway nonostante controlli forma a temperatura ambiente conformi.',
            'es' => 'El laboratorio de materiales cambió el grado de refuerzo de puntera tras envejecimiento en hot-box que provocó colapso de antepié en dos colorways pese a controles de forma aceptables en ambiente.',
        ],
        'heel-counter' => [
            'en' => 'Back-part room increased counter mold dwell after fit lab confirmed recurrent heel slip linked to under-formed counter curvature on wide fittings.',
            'pt' => 'A sala de traseiros aumentou o dwell de moldação do contraforte após o laboratório de calce confirmar escorregamento recorrente do calcanhar ligado a curvatura subformada em larguras amplas.',
            'fr' => 'L’atelier arrière a augmenté le maintien de moulage contrefort après confirmation au labo chaussant d’un glissement talon récurrent lié à une courbure sous-formée sur formes larges.',
            'de' => 'Der Hinterkappenbereich erhöhte die Formverweilzeit, nachdem das Passformlabor wiederkehrenden Fersenschlupf auf unterformte Kappenkrümmung bei breiten Passformen zurückführte.',
            'it' => 'Il reparto tallone ha aumentato il dwell di stampaggio contrafforte dopo conferma dal laboratorio calzata di slittamento tallone ricorrente legato a curvatura sotto-formata su calzate larghe.',
            'es' => 'El área de trasero aumentó el dwell de moldeado de contrafuerte tras confirmar el laboratorio de ajuste deslizamiento recurrente de talón ligado a curvatura subformada en hormas anchas.',
        ],
        'lining' => [
            'en' => 'Quality team replaced lining lot after friction-rig results exceeded slip threshold and created heel blister risk in pre-season wear simulation.',
            'pt' => 'A equipa de qualidade substituiu o lote de forro após ensaio em rig de fricção exceder o limiar de escorregamento e criar risco de bolha no calcanhar na simulação pré-sazonal de uso.',
            'fr' => 'L’équipe qualité a remplacé le lot de doublure après que les résultats sur banc de friction aient dépassé le seuil de glissement, créant un risque d’ampoule talon en simulation de port pré-saison.',
            'de' => 'Das Qualitätsteam ersetzte die Futtercharge, nachdem Reibprüfstandswerte die Schlupfgrenze überschritten und im Vorsaison-Tragesimulationslauf Blasenrisiko an der Ferse erzeugten.',
            'it' => 'Il team qualità ha sostituito il lotto fodera dopo risultati banco attrito oltre soglia di slittamento con rischio vescica tallone nella simulazione pre-stagione.',
            'es' => 'El equipo de calidad sustituyó el lote de forro tras resultados en banco de fricción por encima del umbral de deslizamiento y riesgo de ampolla de talón en simulación de uso pretemporada.',
        ],
        'welt' => [
            'en' => 'Welt bench halted run when channel depth variation exceeded tolerance, preventing stitch bite inconsistency that would compromise resoling integrity.',
            'pt' => 'A bancada de vira parou a série quando a variação de profundidade do canal excedeu a tolerância, evitando inconsistência de mordida do ponto que comprometeria a integridade da ressolagem.',
            'fr' => 'L’atelier trépointe a stoppé la série quand la variation de profondeur de canal a dépassé la tolérance, évitant une prise de point irrégulière compromettant l’intégrité de ressemelage.',
            'de' => 'Die Rahmenbank stoppte den Lauf, als die Kanal-Tiefenvariation die Toleranz überschritt, um inkonsistente Stichaufnahme und gefährdete Wiederbesohlungsintegrität zu vermeiden.',
            'it' => 'Il banco guardolo ha fermato la serie quando la variazione profondità canale ha superato tolleranza, evitando presa punto incoerente che avrebbe compromesso l’integrità di risuolatura.',
            'es' => 'La bancada de cerco detuvo la serie cuando la variación de profundidad de canal superó tolerancia, evitando mordida de puntada inconsistente que comprometería la integridad del resolado.',
        ],
        'insole' => [
            'en' => 'Assembly team rejected insole lot after thickness mapping showed local low spots that shifted lasting margin capture and altered forepart volume.',
            'pt' => 'A equipa de montagem rejeitou o lote de palmilha após mapeamento de espessura mostrar zonas de baixa local que deslocaram a captura da margem de moldação e alteraram o volume da frente.',
            'fr' => 'L’équipe assemblage a rejeté le lot de semelles intérieures après cartographie d’épaisseur montrant des zones basses locales qui déplaçaient la capture de marge montage et modifiaient le volume avant-pied.',
            'de' => 'Das Montageteam sperrte die Innensohlencharge, nachdem Dickenmapping lokale Untermaße zeigte, die die Randaufnahme beim Aufziehen verschoben und das Vorfußvolumen veränderten.',
            'it' => 'Il team assemblaggio ha respinto il lotto solette dopo mappatura spessore con zone basse locali che spostavano la cattura margine montaggio e modificavano il volume avampiede.',
            'es' => 'El equipo de ensamblaje rechazó lote de plantillas tras mapeo de espesor con zonas bajas locales que desplazaron captura de margen de montado y alteraron volumen de antepié.',
        ],
        'midsole' => [
            'en' => 'Midsole validation reopened after compression-set data at 50C drifted above limit, forcing density review before commercialization gate.',
            'pt' => 'A validação da entressola foi reaberta após dados de deformação permanente a 50C derivarem acima do limite, forçando revisão de densidade antes do gate de comercialização.',
            'fr' => 'La validation semelle intermédiaire a été réouverte après dérive des données de compression permanente à 50C au-delà de limite, imposant une revue de densité avant gate de commercialisation.',
            'de' => 'Die Zwischensohlen-Validierung wurde erneut geöffnet, nachdem Compression-Set-Daten bei 50C über Grenzwert drifteten und eine Dichteüberprüfung vor dem Kommerzialisierungs-Gate erzwangen.',
            'it' => 'La validazione intersuola è stata riaperta dopo deriva dati compression set a 50C oltre limite, imponendo revisione densità prima del gate di commercializzazione.',
            'es' => 'La validación de entresuela se reabrió tras deriva de datos de deformación permanente a 50C por encima de límite, forzando revisión de densidad antes del gate de comercialización.',
        ],
        'foxing' => [
            'en' => 'Vulcanization pilot adjusted foxing overlap gauge after perimeter scans found local step transitions causing edge crack initiation in flex cycling.',
            'pt' => 'O piloto de vulcanização ajustou o medidor de sobreposição de foxing após varrimento perimetral encontrar transições em degrau locais que iniciavam fissuras de bordo em ciclos de flexão.',
            'fr' => 'Le pilote vulcanisation a ajusté la jauge de recouvrement foxing après scans périmétriques révélant des transitions en marche locale initiant des fissures de rive en cyclage flexion.',
            'de' => 'Der Vulkanisationspilot justierte die Foxing-Überlappungslehre, nachdem Umfangsscans lokale Stufentransitionen fanden, die Kantenrissanlauf im Flexzyklus auslösten.',
            'it' => 'Il pilota vulcanizzazione ha regolato la dima sovrapposizione foxing dopo scansioni perimetrali con transizioni a gradino locali che innescavano cricche bordo in cicli di flessione.',
            'es' => 'El piloto de vulcanizado ajustó galga de solape de foxing tras escaneos perimetrales que hallaron transiciones en escalón locales iniciando grietas de canto en ciclado de flexión.',
        ],
        'heel-seat' => [
            'en' => 'Heel room applied contour re-level before attachment after seat planarity check showed rotational tilt risking asymmetric outsole wear.',
            'pt' => 'A sala de saltos aplicou renivelamento de contorno antes da fixação após verificação de planicidade do assento mostrar inclinação rotacional com risco de desgaste assimétrico da sola.',
            'fr' => 'L’atelier talon a appliqué un re-nivellement de contour avant fixation après contrôle de planéité d’assise montrant une inclinaison rotationnelle risquant une usure semelle asymétrique.',
            'de' => 'Der Absatzbereich führte vor der Anbindung eine Kontur-Nivellierung durch, nachdem die Ebenheitsprüfung des Sitzes eine Rotationsneigung mit Risiko asymmetrischen Sohlenverschleißes zeigte.',
            'it' => 'Il reparto tacchi ha applicato rilivellamento profilo prima del fissaggio dopo controllo planarità sede con inclinazione rotazionale a rischio usura asimmetrica suola.',
            'es' => 'El área de tacón aplicó renivelado de contorno antes de fijación tras control de planitud del asiento que mostró inclinación rotacional con riesgo de desgaste asimétrico de suela.',
        ],
        'shank' => [
            'en' => 'Structural audit shifted shank position 3 mm forward after torsion rig showed hinge concentration behind intended flex groove on heeled style.',
            'pt' => 'A auditoria estrutural deslocou a posição da alma 3 mm para a frente após o rig de torção mostrar concentração de articulação atrás do sulco de flexão previsto num modelo com salto.',
            'fr' => 'L’audit structurel a déplacé le cambrion de 3 mm vers l’avant après banc de torsion montrant une concentration de charnière derrière la rainure de flexion visée sur modèle à talon.',
            'de' => 'Das Strukturaudit verlagerte die Gelenkfeder um 3 mm nach vorn, nachdem der Torsionsprüfstand eine Knickkonzentration hinter der vorgesehenen Flexrille beim Absatzmodell zeigte.',
            'it' => 'L’audit strutturale ha spostato il cambrione di 3 mm in avanti dopo banco torsione con concentrazione cerniera dietro la scanalatura flessione prevista su modello con tacco.',
            'es' => 'La auditoría estructural desplazó 3 mm hacia delante el cambrillón tras banco de torsión que mostró concentración de bisagra detrás de la ranura de flexión prevista en modelo con tacón.',
        ],
        'cementing' => [
            'en' => 'Cement cell held pairs before tunnel queue when open-time clock showed 42 s remaining at spread completion—below the 55 s minimum for the cupsole waist stack on route card 7-B.',
            'pt' => 'A célula de colagem reteve pares antes da fila do túnel quando o relógio de tempo aberto mostrou 42 s restantes ao fim da aplicação—abaixo do mínimo de 55 s para a pilha de cintura cupsole na ficha 7-B.',
            'fr' => 'La cellule cimentation a retenu les paires avant file tunnel quand l’horloge temps ouvert affichait 42 s restantes à la fin du dépôt—sous le minimum de 55 s pour l’empilement cambrion cupsole sur gamme 7-B.',
            'de' => 'Die Verklebezelle stoppte Paare vor der Tunnelschlange, als die Offenzeit-Uhr bei Auftragsende 42 s Rest zeigte—unter dem 55-s-Minimum fuer den Cupsole-Taillenstack auf Route-Card 7-B.',
            'it' => 'La cella incollaggio ha trattenuto le paia prima della coda tunnel quando l’orologio tempo aperto segnava 42 s residui a fine spalmatura—sotto il minimo 55 s per lo stack vita cupsole sulla scheda 7-B.',
            'es' => 'La célula de pegado retuvo pares antes de cola de túnel cuando el reloj de tiempo abierto mostró 42 s restantes al terminar extendido—por debajo del mínimo de 55 s para la pila de cintura cupsole en hoja 7-B.',
        ],
        'outsole' => [
            'en' => 'Tooling approval was delayed after abrasion drum results diverged by compound lot, requiring hardness retune before outsole serial release.',
            'pt' => 'A aprovação de ferramental foi adiada após resultados de tambor de abrasão divergirem por lote de composto, exigindo reajuste de dureza antes da libertação serial de sola.',
            'fr' => 'L’homologation outillage a été retardée après divergence des résultats de tambour d’abrasion selon lot de compound, nécessitant un retune de dureté avant lancement série semelle.',
            'de' => 'Die Werkzeugfreigabe wurde verschoben, nachdem Abriebstrommel-Ergebnisse je Compound-Charge auseinanderliefen und eine Härtenachjustierung vor Serienfreigabe der Laufsohle erforderten.',
            'it' => 'L’approvazione attrezzaggio è stata rinviata dopo divergenza risultati tamburo abrasione per lotto compound, richiedendo ritocco durezza prima del rilascio seriale suola.',
            'es' => 'La aprobación de herramental se retrasó tras divergencia en resultados de tambor de abrasión por lote de compuesto, requiriendo reajuste de dureza antes de liberar serie de suela.',
        ],
        'sole-pressing' => [
            'en' => 'Infrared scan at press station 2 flagged a cold heel-seat quadrant on cupsole pairs; engineers added 3 s heel dwell before release—forepart map was already nominal.',
            'pt' => 'O varrimento infravermelho na prensa 2 sinalizou quadrante frio no assento do calcanhar em pares cupsole; a engenharia acrescentou 3 s de dwell no calcanhar antes da libertação—o mapa da frente já estava nominal.',
            'fr' => 'Le balayage infrarouge à la presse 2 a signalé un quadrant froid d’assise talon sur paires cupsole; l’ingénierie a ajouté 3 s de maintien talon avant libération—la carte avant-pied était déjà nominale.',
            'de' => 'Der Infrarot-Scan an Presse 2 markierte ein kaltes Fersensitz-Quadrant bei Cupsole-Paaren; die Technik ergaenzte 3 s Fersen-Verweilzeit vor Freigabe—die Vorfuss-Karte war bereits nominal.',
            'it' => 'La scansione infrarosso alla pressa 2 ha segnalato un quadrante freddo sede tallone su paia cupsole; l’ingegneria ha aggiunto 3 s di dwell tallone prima del rilascio—la mappa avampiede era gia nominale.',
            'es' => 'El escaneo infrarrojo en prensa 2 marcó un cuadrante frío del asiento de talón en pares cupsole; ingeniería añadió 3 s de dwell en talón antes de liberar—el mapa de antepié ya era nominal.',
        ],
        'strobel-stitch' => [
            'en' => 'Strobel cell held line 4 when seam-gap at toe spring reached 0.55 mm; unlike board-lasted chains, release required seam closure recovery before seat-lasting to avoid routing flexible uppers into cemented press with unstable perimeter geometry.',
            'pt' => 'A célula Strobel reteve a linha 4 quando o vão de costura no toe spring chegou a 0,55 mm; ao contrário das cadeias com cartão, a libertação exigiu recuperar o fecho da costura antes da moldação do assento para evitar enviar cabedais flexíveis para a prensa colada com geometria perimetral instável.',
            'fr' => 'La cellule Strobel a retenu la ligne 4 quand l’écart de couture au toe spring a atteint 0,55 mm; contrairement aux chaînes montées carton, la libération a exigé la reprise de fermeture couture avant montage de siège pour éviter d’envoyer des tiges flexibles en presse collée avec géométrie périphérique instable.',
            'de' => 'Die Strobel-Zelle hielt Linie 4, als der Nahtspalt am Toe Spring 0,55 mm erreichte; anders als bei Brandsohlen-Ketten verlangte die Freigabe zuerst die Nahtschließung vor Sitzaufziehen, um flexible Schäfte nicht mit instabiler Umfangsgeometrie in die Klebepresse zu schicken.',
            'it' => 'La cella Strobel ha trattenuto la linea 4 quando il gap cucitura al toe spring ha raggiunto 0,55 mm; diversamente dalle catene su cartone, il rilascio ha richiesto recupero chiusura cucitura prima del montaggio sede per evitare invio di tomaie flessibili in pressa cementata con geometria perimetrale instabile.',
            'es' => 'La célula Strobel retuvo la línea 4 cuando la holgura de costura en toe spring llegó a 0,55 mm; a diferencia de las cadenas sobre cartón, la liberación exigió recuperar cierre de costura antes del montado de asiento para evitar enviar cortes flexibles a prensa cementada con geometría perimetral inestable.',
        ],
        'adhesive-transfer-latency' => [
            'en' => 'The bottoming supervisor blocked the batch after transfer latency exceeded 7 minutes on two stations and peel signatures shifted from cohesive to adhesive at the forepart edge.',
            'pt' => 'O supervisor de montagem de fundo bloqueou o lote após a latência de transferência ultrapassar 7 minutos em dois postos e as assinaturas de peel mudarem de coesiva para adesiva no bordo frontal.',
            'fr' => 'Le superviseur de montage de fond a bloqué le lot après une latence de transfert supérieure à 7 minutes sur deux postes et un basculement des signatures de pelage vers l’adhésif en avant-pied.',
            'de' => 'Die Bottoming-Leitung sperrte das Los, nachdem die Transferlatenz an zwei Stationen über 7 Minuten lag und die Schälsignaturen am Vorfußrand von kohäsiv zu adhäsiv wechselten.',
            'it' => 'Il responsabile fondo ha bloccato il lotto dopo una latenza di trasferimento oltre 7 minuti su due stazioni e un passaggio delle firme peel da coesive ad adesive sul bordo avampiede.',
            'es' => 'El supervisor de montaje de fondo bloqueó el lote tras superar 7 minutos de latencia de transferencia en dos estaciones y cambiar firmas de pelado de cohesiva a adhesiva en borde de antepié.',
        ],
        'cure-gradient-mapping' => [
            'en' => 'Lab mapped cure by perimeter quadrant and found the lateral heel seat under-cured versus medial zones, leading to a revised tunnel dwell profile before release restart.',
            'pt' => 'O laboratório mapeou a cura por quadrante do perímetro e encontrou subcura no assento lateral do calcanhar face às zonas mediais, levando à revisão do perfil de dwell do túnel antes do reinício.',
            'fr' => 'Le laboratoire a cartographié la cure par quadrant périmétrique et détecté une sous-cure sur l’assise talon latérale versus zones médiales, imposant une révision du profil de maintien tunnel.',
            'de' => 'Das Labor kartierte die Aushärtung je Umfangsquadrant und fand Unterhärtung am lateralen Fersensitz gegenüber medialen Zonen, worauf das Tunnel-Verweilprofil vor Neustart angepasst wurde.',
            'it' => 'Il laboratorio ha mappato la cura per quadrante perimetrale e rilevato sotto-cura sulla sede tallone laterale rispetto alle zone mediali, con revisione del profilo dwell tunnel prima del riavvio.',
            'es' => 'El laboratorio mapeó curado por cuadrante perimetral y detectó subcurado en asiento lateral de talón frente a zonas mediales, con revisión del perfil de dwell del túnel antes de reiniciar.',
        ],
        'bond-line-void-detection' => [
            'en' => 'Final audit opened sectional cuts on three rejected pairs and confirmed bond-line voids concentrated at the vamp flex radius where pressure-map dead zones had already been flagged.',
            'pt' => 'A auditoria final abriu cortes seccionais em três pares rejeitados e confirmou vazios na linha de colagem concentrados no raio de flexão do vampão, onde o mapa de pressão já tinha sinalizado zona morta.',
            'fr' => 'L’audit final a réalisé des coupes sectionnelles sur trois paires rejetées et confirmé des vides de ligne de collage concentrés sur le rayon de flexion claque déjà signalé en zone morte de pression.',
            'de' => 'Das Endaudit führte Schliffprüfungen an drei Sperrpaaren durch und bestätigte Hohlstellen in der Klebefuge am Vorderblatt-Flexradius, wo das Druckbild zuvor Totzonen markiert hatte.',
            'it' => 'L’audit finale ha eseguito sezioni distruttive su tre paia respinte confermando vuoti linea di incollaggio concentrati sul raggio di flessione tomaia già segnalato come zona morta dalla mappa pressione.',
            'es' => 'La auditoría final realizó cortes seccionales en tres pares rechazados y confirmó vacíos en línea de pegado concentrados en radio de flexión del empeine ya marcado como zona muerta en mapa de presión.',
        ],
        'stitchdown-construction' => [
            'en' => 'Route card 4.2 held stitchdown start until waist edge-guide and turn-out depth passed on eight rework pairs; release criterion was margin geometry for perimeter lock, not adhesive open-time as on cemented routes.',
            'pt' => 'A ficha 4.2 reteve o arranque stitchdown até a guia de bordo da cintura e a profundidade de viragem passarem em oito pares de retrabalho; o critério de libertação foi geometria da margem para bloqueio perimetral, não tempo aberto de cola como nas rotas coladas.',
            'fr' => 'La gamme 4.2 a retenu le démarrage stitchdown jusqu’à validation du guide de rive cambrion et de la profondeur de rabat sur huit paires retouche; le critère de libération était la géométrie de marge pour verrouillage périphérique, pas le temps ouvert colle des routes cimentées.',
            'de' => 'Route-Card 4.2 hielt den Stitchdown-Start bis Taillen-Kantenführung und Umschlagtiefe an acht Nacharbeitspaaren freigegeben waren; Freigabekriterium war Randgeometrie für Umfangsverriegelung, nicht Kleber-Offenzeit wie bei geklebten Routen.',
            'it' => 'La scheda 4.2 ha trattenuto l’avvio stitchdown finché guida bordo in vita e profondità rivoltamento sono state approvate su otto paia rilavorate; il criterio di rilascio era la geometria margine per lock perimetrale, non il tempo aperto colla delle route cementate.',
            'es' => 'La hoja 4.2 retuvo el inicio stitchdown hasta aprobar guía de canto en cintura y profundidad de volteo en ocho pares de retrabajo; el criterio de liberación fue la geometría de margen para bloqueo perimetral, no el tiempo abierto de adhesivo de rutas cementadas.',
        ],
        'cupsole-cementing' => [
            'en' => 'Engineering tied route card 7-B minimum 55 s open-time to cupsole waist stacks after flat-sole spread profiles failed heel-pocket wetting; cup cavity seating required split press dwell unlike standard cemented maps.',
            'pt' => 'A engenharia ligou o mínimo de 55 s de tempo aberto da ficha 7-B às pilhas cupsole na cintura após perfis de sola plana falharem humectação da bolsa do calcanhar; o assentamento em cavidade cup exigiu dwell dividido na prensa, ao contrário dos mapas colados padrão.',
            'fr' => 'L’ingénierie a lié le minimum 55 s temps ouvert de la gamme 7-B aux piles cupsole cambrion après échec d’humectation de poche talon avec profils semelle plate; l’assise en cavité cup a exigé un maintien presse fractionné, contrairement aux cartes cimentées standard.',
            'de' => 'Die Technik koppelte das Route-Card-7-B-Minimum von 55 s Offenzeit an Cupsole-Taillenstapel, nachdem flache Sohlenprofile die Fersentaschen-Benetzung verfehlten; die Cup-Hohlraumsitzung verlangte geteilte Pressverweilzeit statt Standard-Klebekarten.',
            'it' => 'L’ingegneria ha collegato il minimo di 55 s tempo aperto della scheda 7-B agli stack cupsole in vita dopo fallimento bagnatura tasca tallone con profili suola piatta; la seduta in cavità cup ha richiesto dwell pressa diviso, diversamente dalle mappe cementate standard.',
            'es' => 'Ingeniería vinculó el mínimo de 55 s de tiempo abierto de la hoja 7-B a pilas cupsole en cintura tras fallar humectación de bolsa de talón con perfiles de suela plana; el asiento en cavidad cup exigió dwell dividido en prensa, a diferencia de mapas cementados estándar.',
        ],
        'cemented-construction' => [
            'en' => 'Bottoming control froze lot 28 on cemented route 5-A when transfer latency reached 7 min; unlike stitch routes, no mechanical lock remained after dead tack, so pairs were diverted to peel re-test before press entry.',
            'pt' => 'O controlo de fundo congelou o lote 28 na rota colada 5-A quando a latência de transferência atingiu 7 min; ao contrário das rotas cosidas, não havia bloqueio mecânico após tack morto, por isso os pares foram desviados para novo ensaio de peel antes de entrar na prensa.',
            'fr' => 'Le contrôle fond a gelé le lot 28 sur route cimentée 5-A quand la latence transfert a atteint 7 min; contrairement aux routes cousues, aucun verrouillage mécanique ne restait après tack mort, donc les paires ont été déroutées vers un nouveau test pelage avant entrée presse.',
            'de' => 'Die Bottoming-Kontrolle stoppte Los 28 auf geklebter Route 5-A bei 7 min Transferlatenz; anders als bei Naht-Routen blieb nach totem Tack kein mechanischer Lock, daher gingen die Paare vor Presseintritt in den Peel-Nachtest.',
            'it' => 'Il controllo fondo ha bloccato il lotto 28 sulla route cementata 5-A a 7 min di latenza trasferimento; a differenza delle route cucite non restava lock meccanico dopo tack morto, quindi le paia sono state deviate a nuovo test peel prima dell’ingresso in pressa.',
            'es' => 'El control de fondo congeló el lote 28 en ruta cementada 5-A cuando la latencia de transferencia llegó a 7 min; a diferencia de rutas cosidas no quedaba bloqueo mecánico tras tack muerto, por lo que los pares se desviaron a nuevo ensayo de pelado antes de entrar a prensa.',
        ],
        'board-lasted-construction' => [
            'en' => 'Lasting released board-lasted lot 15 to Goodyear rib prep while lot 16 on the same last family entered roughing in six minutes; route fork was set by seat ticket workflow flag, not board thickness alone.',
            'pt' => 'A moldação libertou o lote 15 com cartão para prep de nervura Goodyear enquanto o lote 16 na mesma família de forma entrou em rugosagem em seis minutos; a bifurcação de rota foi definida pela flag de workflow na ficha do assento, não apenas pela espessura do cartão.',
            'fr' => 'Le montage a libéré le lot 15 monté carton vers prep nervure Goodyear tandis que le lot 16 sur la même famille de forme entrait en rugosification en six minutes; la bifurcation route était définie par le flag workflow du ticket assise, pas par l’épaisseur carton seule.',
            'de' => 'Das Aufziehen gab Brandsohlen-Los 15 zur Goodyear-Rippenvorbereitung frei, waehrend Los 16 derselben Leistenfamilie in sechs Minuten ins Aufrauen ging; die Routen-Gabelung wurde durch das Workflow-Flag auf dem Sitzschein gesetzt, nicht nur durch Brettstaerke.',
            'it' => 'Il montaggio ha rilasciato il lotto 15 a cartone verso prep nervatura Goodyear mentre il lotto 16 sulla stessa famiglia forma è entrato in rugosatura in sei minuti; la biforcazione route era definita dal flag workflow sul ticket sede, non dal solo spessore cartone.',
            'es' => 'El montado liberó el lote 15 con cartón a prep de nervio Goodyear mientras el lote 16 en la misma familia de horma entró en rugosado en seis minutos; la bifurcación de ruta se definió por la flag de workflow en la ficha de asiento, no solo por el espesor del cartón.',
        ],
        'foxing-tape-application' => [
            'en' => 'Quality hold triggered when foxing tape overlap exceeded spec at the lateral quarter, creating visible step marks after vulcanization.',
            'pt' => 'Foi ativada contenção de qualidade quando a sobreposição da fita de foxing excedeu especificação no quarto lateral, criando marcas de degrau visíveis após vulcanização.',
            'fr' => 'Une retenue qualité a été déclenchée quand le recouvrement de bande foxing a dépassé la spécification sur le quartier latéral, générant des marches visibles après vulcanisation.',
            'de' => 'Eine Qualitätssperre wurde ausgelöst, als die Überlappung des Foxing-Tapes am lateralen Quartier über Spezifikation lag und nach der Vulkanisation sichtbare Stufen erzeugte.',
            'it' => 'È scattato un blocco qualità quando la sovrapposizione del foxing tape ha superato la specifica sul quartiere laterale, creando gradini visibili dopo vulcanizzazione.',
            'es' => 'Se activó contención de calidad cuando el solape de la cinta foxing superó especificación en el cuarto lateral, generando marcas de escalón visibles tras vulcanizado.',
        ],
        'seam-sealing-tape' => [
            'en' => 'Laminating cell reduced tape feed speed 12% after hydrostatic tests showed micro-leaks at crossed seam intersections on waterproof uppers.',
            'pt' => 'A célula de laminação reduziu 12% a velocidade de alimentação da fita após ensaios hidrostáticos mostrarem microfugas em cruzamentos de costuras nos cabedais impermeáveis.',
            'fr' => 'La cellule de lamination a réduit de 12 % la vitesse d’avance de bande après des essais hydrostatiques révélant des micro-fuites aux intersections de coutures sur tiges imperméables.',
            'de' => 'Die Laminierzelle reduzierte die Bandvorschubgeschwindigkeit um 12 %, nachdem hydrostatische Prüfungen Mikroleckagen an Nahtkreuzungen wasserdichter Schäfte zeigten.',
            'it' => 'La cella di laminazione ha ridotto del 12% la velocità di avanzamento nastro dopo test idrostatici con micro-perdite alle intersezioni cuciture delle tomaie waterproof.',
            'es' => 'La célula de laminación redujo 12 % la velocidad de avance de cinta tras ensayos hidrostáticos con microfugas en intersecciones de costuras de cortes impermeables.',
        ],
        'heel-seat-nailing-pattern' => [
            'en' => 'Engineering revised the heel-seat nailing matrix after torque bench data showed rotational creep on stacked-heel builds above size 44.',
            'pt' => 'A engenharia reviu a matriz de pregos do assento do calcanhar após dados de bancada de torque mostrarem fluência rotacional em construções de salto empilhado acima do tamanho 44.',
            'fr' => 'L’ingénierie a révisé la matrice de cloutage d’assise talon après des mesures de banc de couple montrant une dérive rotationnelle sur montages talon empilé au-dessus de la pointure 44.',
            'de' => 'Die Technik überarbeitete das Nagelmuster am Fersensitz, nachdem Drehmomentprüfstände Rotationskriechen bei gestapelten Absätzen ab Größe 44 zeigten.',
            'it' => 'L’ingegneria ha rivisto la matrice chiodatura sede tallone dopo dati banco coppia con creep rotazionale su costruzioni tacco impilato oltre taglia 44.',
            'es' => 'Ingeniería revisó la matriz de clavado del asiento de talón tras datos de banco de torque que mostraron deriva rotacional en construcciones de tacón apilado por encima de talla 44.',
        ],
        'cement-spread-weight' => [
            'en' => 'Process audit found a 14 g/m2 spread overweight on medial forepart, explaining squeeze-out traces and early edge-soiling complaints.',
            'pt' => 'A auditoria de processo encontrou sobrepeso de 14 g/m2 na aplicação de cola no antepé medial, explicando vestígios de extravasamento e reclamações de sujidade precoce no bordo.',
            'fr' => 'L’audit process a trouvé un surpoids de dépôt de 14 g/m2 à l’avant-pied médial, expliquant les traces de bavure et les plaintes de salissure de rive précoce.',
            'de' => 'Das Prozessaudit fand am medialen Vorfuß ein Auftragsübergewicht von 14 g/m2, was Ausquetschspuren und frühe Kantenverschmutzungsreklamationen erklärte.',
            'it' => 'L’audit di processo ha rilevato un sovrappeso di spalmatura di 14 g/m2 sull’avampiede mediale, spiegando tracce di squeeze-out e reclami precoci di sporco bordo.',
            'es' => 'La auditoría de proceso detectó sobrepeso de extendido de 14 g/m2 en antepié medial, explicando trazas de rebose y reclamaciones tempranas por suciedad en canto.',
        ],
        'sidewall-wrap-tension' => [
            'en' => 'Cell lead retuned sidewall wrap tension after curvature memory at the lateral toe caused rebound lift during 24-hour conditioning.',
            'pt' => 'O líder da célula reajustou a tensão de envolvimento da parede lateral após memória de curvatura na biqueira lateral causar levantamento por retorno durante condicionamento de 24 horas.',
            'fr' => 'Le responsable cellule a retuné la tension d’enveloppement de flanc après mémoire de courbure en pointe latérale causant un relevage de retour pendant le conditionnement 24 h.',
            'de' => 'Die Zellleitung justierte die Spannkraft der Seitenwand-Umschlingung neu, nachdem Krümmungsgedächtnis an der lateralen Spitze während 24-h-Konditionierung zu Rückfederungsablösung führte.',
            'it' => 'Il capocella ha ritoccato la tensione avvolgimento fianco dopo memoria di curvatura in punta laterale che causava sollevamento di ritorno nel condizionamento 24 ore.',
            'es' => 'El líder de célula reajustó la tensión de envolvente de pared lateral tras memoria de curvatura en puntera lateral que causaba levantamiento por rebote durante acondicionamiento de 24 horas.',
        ],
        'outsole-priming-sequence' => [
            'en' => 'Bottoming team split priming into dual-pass sequence for TPU outsoles after single-pass coverage left unreacted islands at the waist groove.',
            'pt' => 'A equipa de montagem de fundo dividiu o primário em sequência de dupla passagem para solas TPU após cobertura de passagem única deixar ilhas não reativas no sulco da cintura.',
            'fr' => 'L’équipe montage de fond a fractionné le primaire en séquence double passage pour semelles TPU après qu’un passage unique ait laissé des zones non réactives dans la gorge de cambrion.',
            'de' => 'Das Bottoming-Team teilte das Primern für TPU-Laufsohlen in eine Zweifachsequenz, nachdem ein Einzelauftrag im Taillenkanal nicht reaktive Inseln hinterließ.',
            'it' => 'Il team fondo ha diviso il primer in sequenza a doppio passaggio per suole TPU dopo che il passaggio singolo lasciava isole non reattive nella gola del punto vita.',
            'es' => 'El equipo de fondo dividió el primer en secuencia de doble pasada para suelas TPU después de que una sola pasada dejara islas no reactivas en la ranura de cintura.',
        ],
        'toe-lasting-pincer-pressure' => [
            'en' => 'Lasting maintenance lowered toe pincer pressure by 0.3 bar after grain embossing appeared on corrected leather in women’s pointed lasts.',
            'pt' => 'A manutenção da moldação reduziu a pressão da pinça da biqueira em 0,3 bar após embossing do grão surgir em couro corrigido nas formas femininas de ponta fina.',
            'fr' => 'La maintenance montage a réduit la pression des pinces de pointe de 0,3 bar après apparition d’empreintes de grain sur cuir corrigé des formes femme à bout pointu.',
            'de' => 'Die Aufziehwartung senkte den Spitzenzangendruck um 0,3 bar, nachdem bei korrigiertem Leder auf Damen-Spitzleisten Prägungsabzeichnungen auftraten.',
            'it' => 'La manutenzione montaggio ha ridotto di 0,3 bar la pressione pinza punta dopo comparsa di impronte grana su pelle corretta nelle forme donna a punta.',
            'es' => 'Mantenimiento de montado redujo 0,3 bar la presión de pinza de puntera tras aparición de marcado de grano en cuero corregido de hormas femeninas de punta fina.',
        ],
        'goodyear-welt' => [
            'en' => 'Bench audit on Goodyear lot 44 found a waist void after holdfast lock; pairs were reopened for cork fill rework and channel-stitch was blocked, because unlike Blake closure this route still had a recoverable pre-lock stage.',
            'pt' => 'A auditoria de bancada no lote Goodyear 44 encontrou vazio na cintura após bloqueio holdfast; os pares foram reabertos para retrabalho de cortiça e a costura em canal foi bloqueada, porque ao contrário do fecho Blake esta rota ainda tinha etapa recuperável antes do lock final.',
            'fr' => 'L’audit atelier sur le lot Goodyear 44 a trouvé un vide cambrion après verrouillage holdfast; les paires ont été rouvertes pour retouche liège et la couture canal a été bloquée, car contrairement à la fermeture Blake cette route gardait encore une étape récupérable avant lock final.',
            'de' => 'Die Bankpruefung auf Goodyear-Los 44 fand eine Taillee-Hohlstelle nach Holdfast-Lock; Paare wurden fuer Kork-Nacharbeit wieder geoeffnet und Kanalnaht gesperrt, weil diese Route im Gegensatz zur Blake-Schließung noch eine vorfinale, korrigierbare Stufe hatte.',
            'it' => 'L’audit banco sul lotto Goodyear 44 ha trovato un vuoto in vita dopo lock holdfast; le paia sono state riaperte per rilavorazione sughero e la cucitura canale e stata bloccata, perché a differenza della chiusura Blake questa route aveva ancora uno stadio recuperabile prima del lock finale.',
            'es' => 'La auditoría de banco en lote Goodyear 44 encontró un vacío en cintura tras lock holdfast; los pares se reabrieron para retrabajo de corcho y se bloqueó la costura en canal, porque a diferencia del cierre Blake esta ruta aún tenía etapa recuperable antes del lock final.',
        ],
        'blake-stitch' => [
            'en' => 'First-pair endoscope at the Blake head rejected lot 07 when needle path drifted 0.4 mm toward the insole edge at the waist; unlike Goodyear chains there was no downstream fill/channel recovery, so sole lock was stopped for the entire cell.',
            'pt' => 'O endoscópio do primeiro par na cabeça Blake rejeitou o lote 07 quando o percurso da agulha derivou 0,4 mm para o bordo da palmilha na cintura; ao contrário das cadeias Goodyear não havia recuperação a jusante por enchimento/canal, por isso o lock da sola foi parado na célula inteira.',
            'fr' => 'L’endoscope première paire à la tête Blake a rejeté le lot 07 quand le trajet aiguille a dérivé de 0,4 mm vers le bord première au cambrion; contrairement aux chaînes Goodyear il n’y avait pas de reprise aval via remplissage/canal, donc le lock semelle a été arrêté sur toute la cellule.',
            'de' => 'Das Erstpaar-Endoskop am Blake-Kopf sperrte Los 07, als der Nadelfad an der Taillee 0,4 mm zur Innensohlenkante driftete; anders als bei Goodyear-Ketten gab es keine nachgelagerte Fuell-/Kanal-Korrektur, daher wurde der Sohlen-Lock in der gesamten Zelle gestoppt.',
            'it' => 'L’endoscopio prima paia sulla testa Blake ha respinto il lotto 07 quando il percorso ago ha derivato di 0,4 mm verso il bordo soletta in vita; a differenza delle catene Goodyear non c’era recupero a valle tramite riempimento/canale, quindi il lock suola e stato fermato in tutta la cella.',
            'es' => 'El endoscopio del primer par en cabeza Blake rechazó el lote 07 cuando el recorrido de aguja derivó 0,4 mm hacia el borde de plantilla en cintura; a diferencia de cadenas Goodyear no había recuperación aguas abajo por relleno/canal, por lo que se detuvo el lock de suela en toda la celda.',
        ],
        'feather-edge' => [
            'en' => 'Closing calibration switched skive angle class after visual booth data linked feather-edge hardness telegraphing to topline ripple on paired uppers.',
            'pt' => 'A calibração de fecho mudou a classe de ângulo de rebaixo após dados de cabine visual ligarem telegraphing de rigidez no bordo em pena a ondulação de linha superior em pares espelhados.',
            'fr' => 'La calibration piquage a changé la classe d’angle de parage après corrélation cabine visuelle entre dureté de bord aminci et ondulation de ligne de col sur paires miroir.',
            'de' => 'Die Schliesskalibrierung wechselte die Schaerfwinkelklasse, nachdem Lichtkabinen-Daten eine Sichttelegraphie der Federkantenhaerte mit Schaftrandwelligkeit bei Spiegelpaaren verknuepften.',
            'it' => 'La calibrazione giunteria ha cambiato la classe angolo di scarnitura dopo dati cabina visiva che collegavano durezza bordo a piuma a ondulazione topline su paia speculari.',
            'es' => 'La calibracion de aparado cambio la clase de angulo de rebajado tras datos de cabina visual que vincularon dureza de borde en pluma con ondulacion de topline en pares espejo.',
        ],
        'lasting-margin' => [
            'en' => 'Pattern engineering revised lasting margin by material family after toe station audits showed operators over-pulling microfiber variants to close undersized capture allowance.',
            'pt' => 'A engenharia de modelagem reviu a margem de moldação por família de material após auditorias na estação de biqueira mostrarem tração excessiva em microfibras para compensar captura subdimensionada.',
            'fr' => 'L’ingénierie patronage a revu la marge de montage par famille matière après audits poste pointe montrant une sur-traction des variantes microfibre pour compenser une capture sous-dimensionnée.',
            'de' => 'Die Schnitttechnik ueberarbeitete die Aufziehmarge nach Materialfamilie, nachdem Spitzenstations-Audits zeigten, dass Mikrofaser-Varianten wegen zu geringer Randaufnahme ueberzogen wurden.',
            'it' => 'L’ingegneria modelleria ha rivisto il margine di montaggio per famiglia materiale dopo audit alla stazione punta che mostravano sovra-trazione su microfibra per compensare cattura sottodimensionata.',
            'es' => 'Ingenieria de patronaje reviso el margen de montado por familia de material despues de auditorias en estacion de puntera que mostraron sobretraccion en microfibras para compensar captura subdimensionada.',
        ],
        'gemming-rib' => [
            'en' => 'Insole prep halted lot 22 when rib-height mapping showed 0.4 mm drift on size 42 left boards, risking shallow holdfast bite before welting release.',
            'pt' => 'A preparação de palmilha parou o lote 22 quando o mapeamento de altura de nervura mostrou deriva de 0,4 mm nos cartões esquerdo tamanho 42, arriscando mordida rasa do ponto de retenção antes da libertação de viração.',
            'fr' => 'La préparation première a stoppé le lot 22 quand la cartographie de hauteur de nervure a montré une dérive de 0,4 mm sur cartons gauche pointure 42, risquant une prise holdfast faible avant libération trépointe.',
            'de' => 'Die Brandsohlenvorbereitung stoppte Los 22, als die Rippenhoehenkartierung 0,4 mm Drift auf linken Brettern Groesse 42 zeigte und flache Holdfast-Aufnahme vor Rahmenfreigabe riskierte.',
            'it' => 'La preparazione soletta ha fermato il lotto 22 quando la mappatura altezza nervatura ha mostrato deriva 0,4 mm su cartoni sinistri taglia 42, rischiando presa holdfast ridotta prima del rilascio guardolo.',
            'es' => 'La preparacion de plantilla detuvo el lote 22 cuando el mapeo de altura de nervio mostro deriva de 0,4 mm en cartones izquierdos talla 42, arriesgando mordida superficial del anclaje antes de liberar cercado.',
        ],
        'sockliner' => [
            'en' => 'Final assembly revised sockliner placement sequence after wear trials linked heel-pocket collapse to premature lay-down before shank torque validation on board-lasted builds.',
            'pt' => 'A montagem final reviu a sequência de colocação do forro de palmilha após ensaios de uso ligarem colapso do assento do calcanhar a assentamento prematuro antes da validação de torque da alma em construções com cartão.',
            'fr' => 'L’assemblage final a révisé la séquence de pose de première de propreté après essais de port reliant affaissement d’assise talon à une pose anticipée avant validation couple cambrion sur montages carton.',
            'de' => 'Die Endmontage ueberarbeitete die Decksohlen-Sequenz, nachdem Trageversuche Fersenbeckenkollaps mit vorzeitiger Einlage vor Schank-Drehmomentvalidierung bei Brettaufziehkonstruktionen verknuepften.',
            'it' => 'L’assemblaggio finale ha rivisto la sequenza di posa sottopiede dopo wear test che collegavano collasso sede tallone a posa anticipata prima della validazione coppia cambrione su costruzioni cartone.',
            'es' => 'El ensamblaje final reviso la secuencia de colocacion de plantilla de acabado tras pruebas de uso que vincularon colapso de asiento de talon con asentamiento prematuro antes de validacion de par del cambrillon en montados sobre carton.',
        ],
        'welt-channel' => [
            'en' => 'Welting QA blocked release when CNC channel depth audit on waist curve showed 0.3 mm undercut versus route-card nominal on size 44 pairs.',
            'pt' => 'O QA de viração bloqueou a libertação quando a auditoria CNC de profundidade de canal na curva da cintura mostrou 0,3 mm abaixo do nominal da ficha de rota nos pares tamanho 44.',
            'fr' => 'Le QA trépointe a bloqué la libération quand l’audit CNC de profondeur de canal sur courbe cambrion a montré 0,3 mm sous le nominal gamme sur paires pointure 44.',
            'de' => 'Das Rahmen-QA stoppte die Freigabe, als die CNC-Kanaltiefenpruefung an der Taillenkurve 0,3 mm Unterschnitt gegen Route-Card-Nominal bei Groesse 44 zeigte.',
            'it' => 'Il QA guardolo ha bloccato il rilascio quando l’audit CNC profondita canale sulla curva vita ha mostrato 0,3 mm sotto il nominale scheda rotta sulle coppie taglia 44.',
            'es' => 'El QA de cercado bloqueo la liberacion cuando la auditoria CNC de profundidad de canal en curva de cintura mostro 0,3 mm por debajo del nominal de hoja de ruta en pares talla 44.',
        ],
        'channel-stitching' => [
            'en' => 'Bench rework SOP reopened three pairs after channel stitch inspection found thread crown above groove lip on lateral waist, a precursor to field abrasion wear-through.',
            'pt' => 'O POP de retrabalho de bancada reabriu três pares após inspeção de costura em canal detetar coroa de fio acima do lábio do sulco na cintura lateral, precursor de desgaste por abrasão em campo.',
            'fr' => 'La procédure de retouche atelier a rouvert trois paires après inspection couture canal montrant couronne de fil au-dessus du bord de rainure au cambrion latéral, précurseur d’usure par abrasion terrain.',
            'de' => 'Die Banknacharbeit-SOP oeffnete drei Paare erneut, nachdem die Kanalnahtpruefung Fadenkronen ueber der Nutlippe lateral in der Taillee zeigte – Vorlaeufer fuer Abrasionsdurchbruch im Feld.',
            'it' => 'La SOP rilavorazione banco ha riaperto tre paia dopo ispezione cucitura canale con corona filo sopra il labbro scanalatura in vita laterale, precursore di usura da abrasione sul campo.',
            'es' => 'El SOP de retrabajo de banco reabrio tres pares tras inspeccion de costura en canal que detecto corona de hilo sobre el labio del surco en cintura lateral, precursor de desgaste por abrasion en campo.',
        ],
        'lockstitch-seam' => [
            'en' => 'Closing held lot 14 after SPI audit on quarter-throat junctions showed thread balance drift 0.4 mm off class map—pairs returned to post-bed reset before lasting pull.',
            'pt' => 'O fecho reteve o lote 14 após auditoria SPI nas junções quarto-garganta mostrar deriva de equilíbrio de fio 0,4 mm fora do mapa de classe—pares regressaram ao reset da máquina de coluna antes da tração de moldação.',
            'fr' => 'Le piquage a retenu le lot 14 après audit SPI sur jonctions quartier-gorge montrant une dérive d’équilibre fil de 0,4 mm hors carte de classe—retour poste colonne avant tirage montage.',
            'de' => 'Die Schliesserei stoppte Los 14, nachdem SPI-Audit an Quartier-Rist-Fugen Garnbalance-Drift von 0,4 mm ausserhalb der Klassenkarte zeigte—Ruecksetzung Saeulenmaschine vor Aufziehzug.',
            'it' => 'La giunteria ha trattenuto il lotto 14 dopo audit SPI sulle giunzioni quartiere-gola con deriva bilanciamento filo 0,4 mm fuori mappa classe—reset macchina a colonna prima del tiro montaggio.',
            'es' => 'El aparado retuvo el lote 14 tras auditoría SPI en juntas cuarto-garganta con deriva de balance de hilo 0,4 mm fuera del mapa de clase—reset de máquina de columna antes del tiraje de montado.',
        ],
        'inseam-stitch' => [
            'en' => 'Closing added a throat-curve tack reinforcement after field returns traced sand ingress to skipped inseam lock loops on desert-boot lining stacks.',
            'pt' => 'O fecho acrescentou reforço de ponto na curva da garganta após devoluções em campo ligarem entrada de areia a saltos de fecho na costura de entrecosto em pacotes de forro de botas de deserto.',
            'fr' => 'Le piquage a ajouté un renfort de pointe sur courbe de gorge après retours terrain reliant ingress de sable à des boucles manquantes sur couture intérieure de fermeture en doublure desert boot.',
            'de' => 'Die Schliesserei ergaenzte eine Ristkurven-Heftung, nachdem Feldruecklaeufe Sandeintritt auf ausgelassene Schliess-Innennaht-Schlaufen bei Desert-Boot-Futterstapel zurueckfuehrten.',
            'it' => 'La giunteria ha aggiunto rinforzo punta sulla curva gola dopo resi sul campo che collegavano ingresso sabbia a salti di chiusura sulla cucitura interna in stack fodera desert boot.',
            'es' => 'El aparado anadio refuerzo de puntada en curva de garganta tras devoluciones de campo que vincularon entrada de arena a saltos de cierre en costura de entrecorte en paquetes de forro de botas de desierto.',
        ],
        'filler-cork' => [
            'en' => 'Bench finisher replaced cork fill under waist after flex noise tracing mapped a void pocket to incomplete fill ahead of outsole lock on Goodyear route pairs.',
            'pt' => 'O acabador de bancada substituiu enchimento em cortiça sob a cintura após rastreio de ruído em flexão mapear bolsa vazia para enchimento incompleto antes do bloqueio da sola em pares Goodyear.',
            'fr' => 'Le finisseur atelier a remplacé le liège sous cambrion après traçage bruit en flexion ayant cartographié une poche vide vers remplissage incomplet avant verrouillage semelle sur paires Goodyear.',
            'de' => 'Der Bankfinisher ersetzte Korkfuellung unter der Taillee, nachdem Flexgeraeusch-Tracing eine Hohlstelle auf unvollstaendige Fuellung vor Sohlenverriegelung bei Goodyear-Paaren kartierte.',
            'it' => 'Il finitore banco ha sostituito riempimento sughero sotto vita dopo tracciamento rumore in flessione che mappava tasca vuota su riempimento incompleto prima del blocco suola su paia Goodyear.',
            'es' => 'El acabador de banco sustituyo relleno de corcho bajo cintura tras rastreo de ruido en flexion que mapeo bolsa vacia a relleno incompleto antes del bloqueo de suela en pares Goodyear.',
        ],
        'lasting-tuck' => [
            'en' => 'Lasting trainer flagged station 6 after tuck overlap audits showed margin fold short of capture zone on corrected-grain vamps, causing edge lift post sole press.',
            'pt' => 'O formador de moldação sinalizou a estação 6 após auditorias de sobreposição de vinco mostrarem dobra da margem aquém da zona de captura em vampões de couro corrigido, causando levantamento de bordo após prensagem da sola.',
            'fr' => 'Le formateur montage a signalé le poste 6 après audits de recouvrement de rabat montrant un pli de marge avant la zone de capture sur claques cuir corrigé, provoquant relevage de rive après presse semelle.',
            'de' => 'Der Aufzieh-Trainer markierte Station 6, nachdem Faltenueberlappungs-Audits zeigten, dass der Randfalz die Aufnahmezone bei korrigiertem Leder-Vorderblatt nicht erreichte und nach Sohlenpresse Kantenanhebung verursachte.',
            'it' => 'Il formatore montaggio ha segnalato la stazione 6 dopo audit sovrapposizione piega con margine piegato prima della zona cattura su vamp pelle corretta, causando sollevamento bordo dopo pressa suola.',
            'es' => 'El formador de montado marco la estacion 6 tras auditorias de solape de pliegue que mostraron doblez de margen por debajo de la zona de captura en empeines de cuero corregido, causando levantamiento de canto tras prensado de suela.',
        ],
        'tuck' => [
            'en' => 'Line supervisor stopped side-lasting release when tuck-angle checks at station 4 drifted above the approved band, preventing seat pull compensation that would hide asymmetry until final bond audit.',
            'pt' => 'O supervisor de linha parou a libertação de moldação lateral quando as verificações de ângulo de tuck na estação 4 saíram da banda aprovada, evitando compensação no puxo do assento que esconderia a assimetria até à auditoria final de colagem.',
            'fr' => 'Le superviseur ligne a stoppé la libération de montage latéral lorsque les contrôles d’angle tuck au poste 4 sont sortis de la bande approuvée, évitant une compensation au tirage d’assise qui masquerait l’asymétrie jusqu’à l’audit final collage.',
            'de' => 'Die Linienaufsicht stoppte die Freigabe des Seitenaufziehens, als Tuck-Winkelpruefungen an Station 4 ueber das freigegebene Band drifteten, und verhinderte so eine Sitzzug-Kompensation, die Asymmetrie bis zum Endkleb-Audit verborgen haette.',
            'it' => 'Il supervisore linea ha fermato il rilascio del montaggio laterale quando i controlli angolo tuck alla stazione 4 sono usciti dalla banda approvata, evitando compensazione del tiro sede che avrebbe nascosto l’asimmetria fino all’audit finale incollaggio.',
            'es' => 'El supervisor de línea detuvo la liberación de montado lateral cuando los controles de ángulo de tuck en estación 4 salieron de la banda aprobada, evitando compensación de tiraje en asiento que ocultaría la asimetría hasta la auditoría final de pegado.',
        ],
        'shank-reinforcement' => [
            'en' => 'Torsion bench held lot 19 when shank seat drift measured 2.1 mm forward of waist window nominal; cork fill was paused until three pairs passed re-seat sign-off.',
            'pt' => 'A bancada de torção reteve o lote 19 quando a deriva do assento da alma mediu 2,1 mm à frente do nominal da janela da cintura; a cortiça de enchimento pausou até três pares passarem homologação de reassentamento.',
            'fr' => 'Le banc de torsion a retenu le lot 19 quand la dérive d’assise cambrion a mesuré 2,1 mm en avant du nominal fenêtre cambrion; le liège de remplissage a été suspendu jusqu’à homologation de re-pose sur trois paires.',
            'de' => 'Der Torsionspruefstand hielt Los 19, als Schanksitz-Drift 2,1 mm vor Taillenfenster-Nominal maß; Korkfuellung pausierte bis drei Paare die Wiedereinsetz-Freigabe bestanden.',
            'it' => 'Il banco torsione ha trattenuto il lotto 19 quando la deriva sede cambrione ha misurato 2,1 mm in avanti rispetto al nominale finestra vita; il riempimento sughero e stato in pausa finche tre paia non hanno superato omologazione riposizionamento.',
            'es' => 'El banco de torsión retuvo el lote 19 cuando la deriva del asiento del cambrillón midió 2,1 mm por delante del nominal de ventana de cintura; el relleno de corcho se pausó hasta homologación de reasentado en tres pares.',
        ],
        'topline-reinforcement' => [
            'en' => 'Closing revised topline tape spec after mirror-pair booth flagged collar ripple on derby throats where reinforcement stack overlapped feather-line transition.',
            'pt' => 'O fecho reviu a especificação de fita de reforço da linha superior após cabina de par espelhado sinalizar ondulação do colarinho em gargantas derby onde o pacote de reforço sobrepôs a transição da linha em pena.',
            'fr' => 'Le piquage a révisé la spec bande de renfort col après cabine paire miroir signalant ondulation de col sur gorges derby où l’empilement renfort chevauchait la transition ligne amincie.',
            'de' => 'Die Schliesserei ueberarbeitete die Topline-Band-Spec, nachdem die Spiegelpaar-Kabine Kragenwelligkeit an Derby-Risten zeigte, wo der Verstaerkungsstack die Federlinien-Transition ueberlappte.',
            'it' => 'La giunteria ha rivisto la spec nastro rinforzo topline dopo cabina coppia speculare con ondulazione collarino su gola derby dove lo stack rinforzo sovrapponeva la transizione linea a piuma.',
            'es' => 'El aparado reviso la especificacion de cinta de refuerzo de linea superior tras cabina de par espejo que marco ondulacion de collar en gargantas derby donde el paquete de refuerzo solapo la transicion de linea en pluma.',
        ],
        'waist-shaping' => [
            'en' => 'Bench profiling reset waist shave depth after edge-ink photos showed asymmetric waist read on left pairs following cork fill density drift.',
            'pt' => 'O perfilamento de bancada redefiniu a profundidade de desbaste da cintura após fotos de tinta de bordo mostrarem leitura assimétrica da cintura em pares esquerdos após deriva de densidade do enchimento em cortiça.',
            'fr' => 'Le profilage atelier a réinitialisé la profondeur de taille cambrion après photos encre de tranche montrant une lecture asymétrique du cambrion sur paires gauches suite à dérive de densité liège.',
            'de' => 'Das Bankprofiling setzte die Taillenabschraeftiefe neu, nachdem Kantenfarb-Fotos asymmetrische Taillenlesung auf linken Paaren nach Korkfuell-Dichtedrift zeigten.',
            'it' => 'Il profilatura banco ha resettato la profondita di rasatura vita dopo foto inchiostro bordo con lettura vita asimmetrica su paia sinistre dopo deriva densita riempimento sughero.',
            'es' => 'El perfilado de banco reajusto la profundidad de desbaste de cintura tras fotos de tinta de canto que mostraron lectura asimetrica de cintura en pares izquierdos tras deriva de densidad del relleno de corcho.',
        ],
        'holdfast-stitch' => [
            'en' => 'Welting maintenance recalibrated needle bend after holdfast bite map showed skipped lock loops on size 41 waist entries across two shifts.',
            'pt' => 'A manutenção de viração recalibrou a curvatura da agulha após mapa de mordida do ponto de retenção mostrar saltos de fecho nas entradas de cintura tamanho 41 em dois turnos.',
            'fr' => 'La maintenance trépointe a recalibré la courbure d’aiguille après carte de prise holdfast montrant des boucles manquantes sur entrées cambrion pointure 41 sur deux équipes.',
            'de' => 'Die Rahmenwartung kalibrierte die Nadelbiegung neu, nachdem die Holdfast-Aufnahmekarte ausgelassene Schlaufen an Tailleneintraegen Groesse 41 ueber zwei Schichten zeigte.',
            'it' => 'La manutenzione guardolo ha ricalibrato la curvatura ago dopo mappa presa holdfast con salti di chiusura sugli ingressi vita taglia 41 su due turni.',
            'es' => 'El mantenimiento de cercado recalibro la curvatura de aguja tras mapa de mordida de anclaje que mostro saltos de cierre en entradas de cintura talla 41 en dos turnos.',
        ],
        'welt-stitch-penetration' => [
            'en' => 'Quality gate held welting release when penetration map on lateral waist averaged 0.6 mm below minimum for holdfast class on resole-certified derby builds.',
            'pt' => 'O gate de qualidade reteve a libertação de viração quando o mapa de penetração na cintura lateral ficou em média 0,6 mm abaixo do mínimo para a classe de retenção em construções derby certificadas para ressolagem.',
            'fr' => 'Le gate qualité a retenu la libération trépointe quand la carte de pénétration au cambrion latéral était en moyenne 0,6 mm sous le minimum pour la classe holdfast sur montages derby certifiés ressemelage.',
            'de' => 'Das Qualitaets-Gate stoppte die Rahmenfreigabe, als die Penetrationskarte lateral in der Taillee im Mittel 0,6 mm unter Holdfast-Mindestwert bei wiederverwertbaren Derby-Konstruktionen lag.',
            'it' => 'Il gate qualita ha trattenuto il rilascio guardolo quando la mappa penetrazione in vita laterale era in media 0,6 mm sotto il minimo per la classe holdfast su costruzioni derby certificate risuolatura.',
            'es' => 'La puerta de calidad retuvo la liberacion de cercado cuando el mapa de penetracion en cintura lateral promedio 0,6 mm por debajo del minimo para la clase de anclaje en construcciones derby certificadas para resolado.',
        ],
        'internal-footbed-stack' => [
            'en' => 'Assembly SOP reordered internal footbed stack after heel-seat X-ray showed sockliner compression ahead of shank seat on board-lasted pilot pairs.',
            'pt' => 'O POP de montagem reordenou o pacote interno de palmilha após radiografia do assento do calcanhar mostrar compressão do forro de palmilha antes do assento da alma em pares piloto com cartão.',
            'fr' => 'La gamme assemblage a réordonné l’empilement interne après radiographie assise talon montrant compression de première de propreté avant assise cambrion sur paires pilotes carton.',
            'de' => 'Die Montage-SOP ordnete den internen Fussbettaufbau neu, nachdem eine Fersensitz-Roentgenaufnahme Decksohlenkompression vor Schanksitz bei Brettaufzieh-Pilotpaaren zeigte.',
            'it' => 'La SOP assemblaggio ha riordinato lo stack interno dopo radiografia sede tallone con compressione sottopiede prima del sedile cambrione su paia pilota cartone.',
            'es' => 'El SOP de ensamblaje reordeno el paquete interno de plantilla tras radiografia del asiento de talon que mostro compresion de plantilla de acabado antes del asiento del cambrillon en pares piloto sobre carton.',
        ],
        'feather-line' => [
            'en' => 'Skiving QC rejected quarter panels when feather-line continuity broke across throat notch, predicting topline ripple after side-lasting pull on calfskin derby uppers.',
            'pt' => 'O CQ de rebaixamento rejeitou painéis de quarto quando a continuidade da linha em pena quebrou no entalhe da garganta, antecipando ondulação da linha superior após tração lateral em cabedais derby de vitela.',
            'fr' => 'Le CQ parage a rejeté des panneaux quartier quand la continuité de ligne amincie se rompait à l’encoche gorge, prédisant ondulation de col après traction latérale sur tiges derby veau.',
            'de' => 'Die Schaerf-QC sperrte Quartierpanels, als die Federlinien-Stetigkeit am Ristkerben brach und Schaftrandwelligkeit nach seitlichem Aufziehen bei Derby-Kalbleder vorhersagte.',
            'it' => 'Il CQ scarnitura ha respinto pannelli quartiere quando la continuita linea a piuma si interrompeva sull’intaglio gola, prevedendo ondulazione topline dopo trazione laterale su tomaie derby vitello.',
            'es' => 'El CQ de rebajado rechazo paneles de cuarto cuando la continuidad de linea en pluma se rompio en la muesca de garganta, anticipando ondulacion de linea superior tras traccion lateral en cortes derby de ternera.',
        ],
    ];

    $rows = [
        ['key' => 'upper-assembly', 'terms' => ['en' => 'Upper assembly', 'pt' => 'Fecho do cabedal', 'fr' => 'Assemblage de tige', 'de' => 'Schaftmontage', 'it' => 'Assemblaggio tomaia', 'es' => 'Ensamblaje del corte'], 'focus' => 'assemble upper components into a stable pre-lasting shell', 'stage' => 'closing and pre-lasting handoff', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'bottoming', 'terms' => ['en' => 'Bottoming', 'pt' => 'Montagem de fundo', 'fr' => 'Montage de fond', 'de' => 'Bottoming', 'it' => 'Montaggio fondo', 'es' => 'Montaje de fondo'], 'focus' => 'attach and finish sole structures after lasting', 'stage' => 'bottoming cell and final assembly', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'quality-checkpoint', 'terms' => ['en' => 'Quality checkpoint', 'pt' => 'Ponto de controlo de qualidade', 'fr' => 'Point de contrôle qualité', 'de' => 'Qualitäts-Prüfpunkt', 'it' => 'Punto controllo qualità', 'es' => 'Punto de control de calidad'], 'focus' => 'validate workmanship before defects propagate downstream', 'stage' => 'inline verification and release gates', 'domains' => ['footwear-construction', 'production', 'quality-control']],

        ['key' => 'lasting', 'terms' => ['en' => 'Lasting', 'pt' => 'Moldação', 'fr' => 'Montage sur forme', 'de' => 'Aufziehen', 'it' => 'Montaggio su forma', 'es' => 'Montado en horma'], 'focus' => 'pull and stabilize the upper over the last geometry', 'stage' => 'lasting line', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true, 'editorial_notes' => 'Primary geometric lock operation linking upper tension, lasting margin capture, and downstream bottoming stability.', 'source_reference_text' => 'Lasting station control sheet, toe spring gauge protocol, and mirror-pair asymmetry reaction plan.'],
        ['key' => 'shoe-last', 'terms' => ['en' => 'Shoe last', 'pt' => 'Forma de calçado', 'fr' => 'Forme chaussure', 'de' => 'Schuhleisten', 'it' => 'Forma calzaturiera', 'es' => 'Horma de calzado'], 'focus' => 'define volume, girth, toe spring, and heel pitch targets', 'stage' => 'design to pattern engineering transfer', 'domains' => ['footwear-construction', 'footwear', 'design', 'pattern-making'], 'featured' => true],
        ['key' => 'upper', 'terms' => ['en' => 'Upper', 'pt' => 'Cabedal', 'fr' => 'Tige', 'de' => 'Schaft', 'it' => 'Tomaia', 'es' => 'Corte'], 'focus' => 'form the visible and functional shell above the sole package', 'stage' => 'cutting and closing operations', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'editorial_notes' => 'Critical shell assembly where seam class, panel orientation, and reinforcement placement define later fit fidelity.', 'source_reference_text' => 'Upper assembly route card with panel alignment checkpoints and mirror-pair visual standard.'],
        ['key' => 'vamp', 'terms' => ['en' => 'Vamp', 'pt' => 'Vampão', 'fr' => 'Claque', 'de' => 'Vorderblatt', 'it' => 'Puntina tomaia', 'es' => 'Empeine delantero'], 'focus' => 'control forepart fit and flex-break location', 'stage' => 'pattern engineering and closing', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'quarter', 'terms' => ['en' => 'Quarter', 'pt' => 'Quarto', 'fr' => 'Quartier', 'de' => 'Quartier', 'it' => 'Quartiere', 'es' => 'Cuarto'], 'focus' => 'stabilize heel and midfoot sections of the upper', 'stage' => 'upper closing and back-part preparation', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'lining', 'terms' => ['en' => 'Lining', 'pt' => 'Forro', 'fr' => 'Doublure', 'de' => 'Futter', 'it' => 'Fodera', 'es' => 'Forro'], 'focus' => 'manage internal comfort, moisture, and seam encapsulation', 'stage' => 'upper assembly and comfort package build', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'toe-puff', 'terms' => ['en' => 'Toe puff', 'pt' => 'Ponteira', 'fr' => 'Contrefort avant', 'de' => 'Zehenkappe-Verstärkung', 'it' => 'Puntale di rinforzo', 'es' => 'Refuerzo de puntera'], 'focus' => 'preserve toe shape and stiffness under wear and heat', 'stage' => 'reinforcement placement before lasting', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'editorial_notes' => 'Thermal activation and memory behavior must be tuned to preserve profile without comfort hotspots.', 'source_reference_text' => 'Toe reinforcement activation matrix and hot-box durability correlation records.'],
        ['key' => 'heel-counter', 'terms' => ['en' => 'Heel counter', 'pt' => 'Contraforte', 'fr' => 'Contrefort arrière', 'de' => 'Fersenkappe', 'it' => 'Contrafforte', 'es' => 'Contrafuerte'], 'focus' => 'lock rearfoot structure and reduce heel collapse', 'stage' => 'back-part reinforcement and molding', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'editorial_notes' => 'Rearfoot retention anchor; mismatch with seat contour and lining stack drives slip and abrasion failures.', 'source_reference_text' => 'Back-part molding setup card and heel-retention validation protocol by fit family.'],
        ['key' => 'counter-molding', 'terms' => ['en' => 'Counter molding', 'pt' => 'Moldação do contraforte', 'fr' => 'Moulage du contrefort', 'de' => 'Fersenkappen-Formung', 'it' => 'Stampaggio contrafforte', 'es' => 'Moldeado de contrafuerte'], 'focus' => 'thermoform and lock heel counter geometry to last seat', 'stage' => 'back-part setting', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'toe-box', 'terms' => ['en' => 'Toe box', 'pt' => 'Caixa de biqueira', 'fr' => 'Boîte à orteils', 'de' => 'Zehenbox', 'it' => 'Volume punta', 'es' => 'Caja de puntera'], 'focus' => 'define forepart clearance and shape retention under flex', 'stage' => 'fit engineering and wear validation', 'domains' => ['footwear-construction', 'footwear', 'design']],
        ['key' => 'foxing', 'terms' => ['en' => 'Foxing', 'pt' => 'Faixa lateral', 'fr' => 'Bande de renfort', 'de' => 'Foxing-Band', 'it' => 'Fascia laterale', 'es' => 'Banda lateral'], 'focus' => 'reinforce sidewall transition between upper and sole edge', 'stage' => 'sidewall reinforcement and finishing', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'welt', 'terms' => ['en' => 'Welt', 'pt' => 'Vira', 'fr' => 'Trépointe', 'de' => 'Rahmen', 'it' => 'Guardolo', 'es' => 'Cerco'], 'focus' => 'create a stitched interface between upper package and outsole', 'stage' => 'welt construction and resoling architecture', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'outsole', 'terms' => ['en' => 'Outsole', 'pt' => 'Sola exterior', 'fr' => 'Semelle extérieure', 'de' => 'Laufsohle', 'it' => 'Suola esterna', 'es' => 'Suela exterior'], 'focus' => 'deliver ground contact grip, abrasion resistance, and flex behavior', 'stage' => 'bottoming and durability tuning', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'featured' => true, 'editorial_notes' => 'Ground-contact control node linking compound behavior, flex-groove alignment, and perimeter bond durability.', 'source_reference_text' => 'Outsole tooling compensation log, abrasion-climate matrix, and bond-line perimeter audit criteria.'],
        ['key' => 'midsole', 'terms' => ['en' => 'Midsole', 'pt' => 'Entressola', 'fr' => 'Semelle intermédiaire', 'de' => 'Zwischensohle', 'it' => 'Intersuola', 'es' => 'Entresuela'], 'focus' => 'manage cushioning stack and load transfer above outsole', 'stage' => 'stack design and bottoming assembly', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'insole', 'terms' => ['en' => 'Insole', 'pt' => 'Palmilha', 'fr' => 'Semelle intérieure', 'de' => 'Innensohle', 'it' => 'Soletta', 'es' => 'Plantilla'], 'focus' => 'support foot interface, comfort, and upper anchoring references', 'stage' => 'internal assembly and fit setup', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'shank', 'terms' => ['en' => 'Shank', 'pt' => 'Alma', 'fr' => 'Cambrion', 'de' => 'Gelenkfeder', 'it' => 'Cambrione', 'es' => 'Cambrillón'], 'focus' => 'stabilize waist geometry and heel-to-forefoot load path', 'stage' => 'bottoming structural reinforcement', 'domains' => ['footwear-construction', 'footwear', 'hardware']],
        ['key' => 'rand', 'terms' => ['en' => 'Rand', 'pt' => 'Biqueira lateral', 'fr' => 'Bande de protection', 'de' => 'Rand', 'it' => 'Rand', 'es' => 'Rand'], 'focus' => 'protect upper edge and improve abrasion resistance at perimeter', 'stage' => 'edge protection and sidewall bonding', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'heel-seat', 'terms' => ['en' => 'Heel seat', 'pt' => 'Assento do calcanhar', 'fr' => 'Assise talon', 'de' => 'Fersensitz', 'it' => 'Sede tallone', 'es' => 'Asiento de talón'], 'focus' => 'control rearfoot seating geometry and slip performance', 'stage' => 'fit tuning and lasting reference setting', 'domains' => ['footwear-construction', 'footwear', 'pattern-making'], 'editorial_notes' => 'Seat geometry defines rearfoot load transfer and attachment quality; planarity and contour matching are release-critical.', 'source_reference_text' => 'Heel-seat contour and planarity checklist with rotational stability acceptance thresholds.'],
        ['key' => 'toe-spring', 'terms' => ['en' => 'Toe spring', 'pt' => 'Elevação da biqueira', 'fr' => 'Relevé de pointe', 'de' => 'Zehenfeder', 'it' => 'Alzata punta', 'es' => 'Elevación de puntera'], 'focus' => 'set forepart rocker and gait transition behavior', 'stage' => 'last design and outsole tooling alignment', 'domains' => ['footwear-construction', 'footwear', 'design']],
        ['key' => 'ball-girth', 'terms' => ['en' => 'Ball girth', 'pt' => 'Perímetro metatarsal', 'fr' => 'Périmètre métatarsien', 'de' => 'Ballenumfang', 'it' => 'Circonferenza metatarsale', 'es' => 'Perímetro metatarsal'], 'focus' => 'control fit grading around the forefoot load zone', 'stage' => 'last engineering and grading validation', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'vamp-break', 'terms' => ['en' => 'Vamp break', 'pt' => 'Linha de quebra do vampão', 'fr' => 'Ligne de cassure claque', 'de' => 'Vorderblatt-Knicklinie', 'it' => 'Linea piega tomaia', 'es' => 'Línea de quiebre del empeine'], 'focus' => 'position natural flex line without visual collapse', 'stage' => 'pattern development and wear test loop', 'domains' => ['footwear-construction', 'footwear', 'design']],
        ['key' => 'lasting-allowance', 'terms' => ['en' => 'Lasting allowance', 'pt' => 'Folga de moldação', 'fr' => 'Marge de montage', 'de' => 'Aufziehzugabe', 'it' => 'Margine di montaggio', 'es' => 'Margen de montado'], 'focus' => 'reserve material for controlled pull during lasting', 'stage' => 'pattern engineering for lasting operations', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'side-lasting', 'terms' => ['en' => 'Side lasting', 'pt' => 'Moldação lateral', 'fr' => 'Montage latéral', 'de' => 'Seitliches Aufziehen', 'it' => 'Montaggio laterale', 'es' => 'Montado lateral'], 'focus' => 'stabilize lateral and medial tension around waist curves', 'stage' => 'lasting-chain station 2 after toe; precedes back-part (structured) or seat (Strobel)', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'Second station in lasting sequence; next post is back-part or seat depending on route; twist gauge gates transfer.', 'source_reference_text' => 'Side-lasting pull-ratio matrix and waist mirror-gauge reaction plan.'],
        ['key' => 'toe-lasting', 'terms' => ['en' => 'Toe lasting', 'pt' => 'Moldação da biqueira', 'fr' => 'Montage de pointe', 'de' => 'Spitzenaufziehen', 'it' => 'Montaggio punta', 'es' => 'Montado de puntera'], 'focus' => 'shape forepart volume while protecting toe reinforcement', 'stage' => 'toe station in lasting line', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'First pull gate common to all routes; toe-spring symmetry here constrains whether side and seat stations can hold route-specific geometry downstream.', 'source_reference_text' => 'Toe pull pressure map, toe-spring symmetry gauge, and route-branch handoff checklist.'],
        ['key' => 'back-part-lasting', 'terms' => ['en' => 'Back-part lasting', 'pt' => 'Moldação traseira', 'fr' => 'Montage arrière', 'de' => 'Hinterkappen-Aufziehen', 'it' => 'Montaggio posteriore', 'es' => 'Montado trasero'], 'focus' => 'set heel wrap tension and seat alignment', 'stage' => 'rearfoot lasting sequence', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'Rearfoot pull before seat on structured chains; counter contour match gates seat transfer.', 'source_reference_text' => 'Back-part pull card linked to counter molding and seat contour gate.'],
        ['key' => 'seat-lasting', 'terms' => ['en' => 'Seat lasting', 'pt' => 'Moldação do assento', 'fr' => 'Montage de siège', 'de' => 'Sitzaufziehen', 'it' => 'Montaggio sede', 'es' => 'Montado de asiento'], 'focus' => 'secure heel seat margin before sole build', 'stage' => 'terminal lasting station; releases to rib prep (welt) or roughing-primer (cemented)', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'Follows back-part or side pull; seat release routes to welt rib prep or cemented roughing within open-time clock.', 'source_reference_text' => 'Seat-lasting capture checklist and heel-seat contour match gate.'],
        ['key' => 'lasting-pincher', 'terms' => ['en' => 'Lasting pincher', 'pt' => 'Pinça de moldação', 'fr' => 'Pince de montage', 'de' => 'Aufziehzange', 'it' => 'Pinza di montaggio', 'es' => 'Pinza de montado'], 'focus' => 'apply directional pull without damaging upper grain', 'stage' => 'lasting machine setup and operation', 'domains' => ['footwear-construction', 'footwear', 'machinery']],
        ['key' => 'lasting-tack', 'terms' => ['en' => 'Lasting tack', 'pt' => 'Prego de moldação', 'fr' => 'Pointe de montage', 'de' => 'Aufziehstift', 'it' => 'Chiodo di montaggio', 'es' => 'Clavo de montado'], 'focus' => 'temporarily lock upper margin before permanent bonding', 'stage' => 'mechanical fixation in lasting', 'domains' => ['footwear-construction', 'footwear', 'production']],

        ['key' => 'strobel-stitch', 'terms' => ['en' => 'Strobel stitch', 'pt' => 'Costura Strobel', 'fr' => 'Couture Strobel', 'de' => 'Strobelnaht', 'it' => 'Cucitura Strobel', 'es' => 'Costura Strobel'], 'focus' => 'join upper margin to strobel board with flexible seam architecture', 'stage' => 'strobel closing before lasting entry; gates cemented handoff after seat release', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true, 'editorial_notes' => 'Flexible route anchor typically feeding cemented chains; differs from board-lasted architecture by seam-based flexibility instead of rigid board capture.', 'source_reference_text' => 'Strobel seam capability benchmark, curved-toe stitch skip mitigation protocol, and board-vs-strobel route decision note.'],
        ['key' => 'strobel-board', 'terms' => ['en' => 'Strobel board', 'pt' => 'Base Strobel', 'fr' => 'Semelle Strobel', 'de' => 'Strobel-Basis', 'it' => 'Base Strobel', 'es' => 'Base Strobel'], 'focus' => 'provide lightweight internal foundation in strobel constructions', 'stage' => 'strobel assembly step 1 before board-to-upper stitch and lasting entry', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'editorial_notes' => 'Upstream of Strobel stitch; board flatness gates lasting and cemented bottoming chain after seat release.', 'source_reference_text' => 'Strobel board tension and flatness record before stitch handoff.'],
        ['key' => 'insole-board', 'terms' => ['en' => 'Insole board', 'pt' => 'Cartão de palmilha', 'fr' => 'Première carton', 'de' => 'Brandsohlenplatte', 'it' => 'Sottopiede in cartone', 'es' => 'Cartón de plantilla'], 'focus' => 'provide lasting anchor and structural interface under upper', 'stage' => 'insole preparation before lasting', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'rib-attaching', 'terms' => ['en' => 'Rib attaching', 'pt' => 'Aplicação de nervura', 'fr' => 'Pose de nervure', 'de' => 'Rippenanbringung', 'it' => 'Applicazione nervatura', 'es' => 'Aplicación de nervio'], 'focus' => 'bond rib perimeter to insole board for welt channels', 'stage' => 'insole rib setup for stitched constructions', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'channeling', 'terms' => ['en' => 'Channeling', 'pt' => 'Canal de costura', 'fr' => 'Canalisation de couture', 'de' => 'Nahtkanalierung', 'it' => 'Canalizzazione', 'es' => 'Canal de costura'], 'focus' => 'prepare hidden stitch paths and clean seam sink', 'stage' => 'sole and welt preparation', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'lockstitch-seam', 'terms' => ['en' => 'Lockstitch seam', 'pt' => 'Costura lockstitch', 'fr' => 'Couture point noué', 'de' => 'Doppelsteppnaht', 'it' => 'Cucitura a punto bloccato', 'es' => 'Costura lockstitch'], 'focus' => 'secure upper joins with controlled thread balance before lasting pull', 'stage' => 'closing and perimeter stitch class — gates margin integrity before lasting', 'domains' => ['footwear-construction', 'footwear', 'production'], 'terminology_status' => 'validated', 'editorial_notes' => 'Seam-class release and SPI discipline at throat and quarter junctions prevent grin and cut-through that lasting cannot correct.', 'source_reference_text' => 'Closing seam-class release map, thread-balance audit, and SPI gate before lasting handoff.'],
        ['key' => 'edge-folding', 'terms' => ['en' => 'Edge folding', 'pt' => 'Dobra de bordo', 'fr' => 'Rabat de bord', 'de' => 'Kantenumschlag', 'it' => 'Ripiegatura bordo', 'es' => 'Doblado de canto'], 'focus' => 'hide cut edges and stabilize visible upper lines', 'stage' => 'upper finishing before assembly', 'domains' => ['footwear-construction', 'footwear', 'finishing']],
        ['key' => 'skiving', 'terms' => ['en' => 'Skiving', 'pt' => 'Rebaixamento', 'fr' => 'Parage', 'de' => 'Schärfen', 'it' => 'Scarnitura', 'es' => 'Rebajado'], 'focus' => 'reduce edge thickness before folding, stitching, or lasting', 'stage' => 'cutting room and closing prep', 'domains' => ['footwear-construction', 'footwear', 'leather']],
        ['key' => 'feather-edge', 'terms' => ['en' => 'Feather edge', 'pt' => 'Bordo em pena', 'fr' => 'Bord aminci', 'de' => 'Federkante', 'it' => 'Bordo a piuma', 'es' => 'Borde en pluma'], 'focus' => 'achieve tapered transition with no hard edge telegraphing', 'stage' => 'post-skiving quality control', 'domains' => ['footwear-construction', 'footwear', 'finishing'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Micro-geometry quality gate between skiving and edge folding; poor taper propagates into visible line noise.', 'source_reference_text' => 'Edge-finishing visual atlas and skive-angle process capability sheet.'],
        ['key' => 'roughing', 'terms' => ['en' => 'Roughing', 'pt' => 'Rugosagem', 'fr' => 'Rugosification', 'de' => 'Aufrauen', 'it' => 'Rugosatura', 'es' => 'Rugosado'], 'focus' => 'profile bond interface topography before primer on cemented routes', 'stage' => 'cemented route entry after seat release — depth map gates primer queue', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'Shallow roughing starves primer wetting; over-roughing collapses margins—topography audit blocks spread if depth map fails.', 'source_reference_text' => 'Roughing depth profile card and bond-interface topography audit.'],
        ['key' => 'buffing', 'terms' => ['en' => 'Buffing', 'pt' => 'Lixagem', 'fr' => 'Ponçage', 'de' => 'Schleifen', 'it' => 'Levigatura', 'es' => 'Lijado'], 'focus' => 'control surface finish and edge uniformity before final coating', 'stage' => 'finishing and pre-bond cleanup', 'domains' => ['footwear-construction', 'footwear', 'finishing']],
        ['key' => 'primer-coat', 'terms' => ['en' => 'Primer coat', 'pt' => 'Primário de colagem', 'fr' => 'Primaire d’adhésion', 'de' => 'Primerauftrag', 'it' => 'Primer adesivo', 'es' => 'Imprimación de adhesión'], 'focus' => 'raise surface energy before spread on low-energy stacks', 'stage' => 'between roughing and cement spread — flash-off gate starts open-time discipline', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'editorial_notes' => 'Under-dry primer traps solvent and weakens bond line; over-dry kills tack before spread—climate band sets flash-off envelope.', 'source_reference_text' => 'Primer dry-time envelope sheet by substrate stack and climate band.'],
        ['key' => 'cementing', 'terms' => ['en' => 'Cementing', 'pt' => 'Colagem', 'fr' => 'Cimentation', 'de' => 'Verklebung', 'it' => 'Incollaggio', 'es' => 'Pegado'], 'focus' => 'bond upper and sole interfaces within open-time windows', 'stage' => 'cemented bottoming step 1 of 3 — spread before activation tunnel queue', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true, 'editorial_notes' => 'Route-critical adhesive application gate; spread mass and transfer latency discipline determine downstream activation viability.', 'source_reference_text' => 'Cement spread-weight control sheet, open-time governance card, and station transfer-latency checklist.'],
        ['key' => 'heat-activation', 'terms' => ['en' => 'Heat activation', 'pt' => 'Ativação térmica', 'fr' => 'Activation thermique', 'de' => 'Wärmeaktivierung', 'it' => 'Attivazione termica', 'es' => 'Activación térmica'], 'focus' => 'reactivate spread film inside transfer latency before irreversible press', 'stage' => 'cemented bottoming step 2 of 3 — tunnel IR profile before sole press', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'IR energy-density check gates press queue; cold drift shows heel-seat peel, hot drift scorches forepart before perimeter wetting.', 'source_reference_text' => 'Tunnel dwell profile matrix, IR verification protocol, and activation-to-press latency control log.'],
        ['key' => 'sole-pressing', 'terms' => ['en' => 'Sole pressing', 'pt' => 'Prensagem da sola', 'fr' => 'Pressage semelle', 'de' => 'Sohlenpressung', 'it' => 'Pressatura suola', 'es' => 'Prensado de suela'], 'focus' => 'consolidate bond-line pressure around full perimeter', 'stage' => 'cemented bottoming step 3 of 3 — irreversible perimeter press lock after activation', 'domains' => ['footwear-construction', 'footwear', 'production'], 'editorial_notes' => 'Irreversible lock step of cemented bottoming; pressure-map conformity at seat and vamp radius is release-critical.', 'source_reference_text' => 'Press map acceptance sheet, sectional compression signature protocol, and cold-zone containment checklist.'],
        ['key' => 'bond-line', 'terms' => ['en' => 'Bond line', 'pt' => 'Linha de colagem', 'fr' => 'Ligne de collage', 'de' => 'Klebefuge', 'it' => 'Linea di incollaggio', 'es' => 'Línea de pegado'], 'focus' => 'maintain continuous adhesive interface without voids', 'stage' => 'bond integrity inspection', 'domains' => ['footwear-construction', 'footwear', 'quality-control']],
        ['key' => 'outsole-tooling', 'terms' => ['en' => 'Outsole tooling', 'pt' => 'Ferramental de sola', 'fr' => 'Outillage semelle', 'de' => 'Laufsohlen-Werkzeugbau', 'it' => 'Attrezzatura suola', 'es' => 'Herramental de suela'], 'focus' => 'translate outsole design intent into production molds', 'stage' => 'tooling engineering and first-shot validation', 'domains' => ['footwear-construction', 'footwear', 'machinery']],
        ['key' => 'injection-unit', 'terms' => ['en' => 'Injection unit', 'pt' => 'Unidade de injeção', 'fr' => 'Unité d’injection', 'de' => 'Injektionseinheit', 'it' => 'Unità di iniezione', 'es' => 'Unidad de inyección'], 'focus' => 'control polymer flow and cycle stability in sole production', 'stage' => 'injection molding operations', 'domains' => ['footwear-construction', 'footwear', 'machinery']],
        ['key' => 'post-bed-machine', 'terms' => ['en' => 'Post-bed machine', 'pt' => 'Máquina de coluna', 'fr' => 'Machine à colonne', 'de' => 'Säulenmaschine', 'it' => 'Macchina a colonna', 'es' => 'Máquina de columna'], 'focus' => 'sew contoured upper assemblies with controlled access', 'stage' => 'closing line for shaped seams', 'domains' => ['footwear-construction', 'footwear', 'machinery']],
        ['key' => 'heat-tunnel', 'terms' => ['en' => 'Heat tunnel', 'pt' => 'Túnel térmico', 'fr' => 'Tunnel thermique', 'de' => 'Wärmetunnel', 'it' => 'Tunnel termico', 'es' => 'Túnel térmico'], 'focus' => 'stabilize activation temperature before sole pressing', 'stage' => 'adhesive activation flow', 'domains' => ['footwear-construction', 'footwear', 'machinery']],

        ['key' => 'clicking', 'terms' => ['en' => 'Clicking', 'pt' => 'Corte de cabedal', 'fr' => 'Découpe emporte-pièce', 'de' => 'Zuschneiden', 'it' => 'Tranciatura', 'es' => 'Troquelado'], 'focus' => 'cut upper components with defect-aware yield control', 'stage' => 'cutting room planning and execution', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'die-cutting', 'terms' => ['en' => 'Die cutting', 'pt' => 'Corte por faca', 'fr' => 'Découpe à l’emporte-pièce', 'de' => 'Stanzschnitt', 'it' => 'Taglio a fustella', 'es' => 'Corte por troquel'], 'focus' => 'standardize repeatable cutting with maintained steel-rule tools', 'stage' => 'tool-based cutting operation', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'nesting', 'terms' => ['en' => 'Nesting', 'pt' => 'Nesting', 'fr' => 'Placement matière', 'de' => 'Nesting', 'it' => 'Nesting', 'es' => 'Anidado'], 'focus' => 'optimize marker layout for yield and pair symmetry', 'stage' => 'CAD cutting preparation', 'domains' => ['footwear-construction', 'footwear', 'cad-cam']],
        ['key' => 'defect-mapping', 'terms' => ['en' => 'Defect mapping', 'pt' => 'Mapeamento de defeitos', 'fr' => 'Cartographie des défauts', 'de' => 'Fehlerkartierung', 'it' => 'Mappatura difetti', 'es' => 'Mapeo de defectos'], 'focus' => 'exclude weak or visual defects before component cutting', 'stage' => 'hide inspection before cutting', 'domains' => ['footwear-construction', 'footwear', 'quality-control']],
        ['key' => 'pair-matching', 'terms' => ['en' => 'Pair matching', 'pt' => 'Emparelhamento', 'fr' => 'Appairage', 'de' => 'Paarabgleich', 'it' => 'Abbinamento paio', 'es' => 'Emparejado'], 'focus' => 'align left-right visual and material consistency within pair', 'stage' => 'cutting and bundling handoff', 'domains' => ['footwear-construction', 'footwear', 'quality-control']],
        ['key' => 'pattern-grading', 'terms' => ['en' => 'Pattern grading', 'pt' => 'Graduação de modelagem', 'fr' => 'Gradation patronage', 'de' => 'Schnittgradierung', 'it' => 'Gradazione modelli', 'es' => 'Gradación de patrones'], 'focus' => 'scale pattern geometry across size run without fit drift', 'stage' => 'pattern engineering release', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'size-run', 'terms' => ['en' => 'Size run', 'pt' => 'Série de tamanhos', 'fr' => 'Gamme de pointures', 'de' => 'Größenlauf', 'it' => 'Serie taglie', 'es' => 'Serie de tallas'], 'focus' => 'define market size curve for production and inventory planning', 'stage' => 'planning and grading alignment', 'domains' => ['footwear-construction', 'footwear', 'production']],

        ['key' => 'eyelet-setting', 'terms' => ['en' => 'Eyelet setting', 'pt' => 'Aplicação de ilhós', 'fr' => 'Pose d’œillets', 'de' => 'Ösensetzen', 'it' => 'Applicazione occhielli', 'es' => 'Colocación de ojales'], 'focus' => 'install lace hardware with pull-out compliance', 'stage' => 'closing hardware station', 'domains' => ['footwear-construction', 'footwear', 'hardware']],
        ['key' => 'lace-stay', 'terms' => ['en' => 'Lace stay', 'pt' => 'Zona de atacador', 'fr' => 'Quartier à laçage', 'de' => 'Schnürleiste', 'it' => 'Zona allacciatura', 'es' => 'Zona de cordonera'], 'focus' => 'stabilize lace load path and throat shape retention', 'stage' => 'upper pattern and reinforcement layout', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'tongue-gusset', 'terms' => ['en' => 'Tongue gusset', 'pt' => 'Fole da língua', 'fr' => 'Soufflet de languette', 'de' => 'Zungenzwickel', 'it' => 'Soffietto linguetta', 'es' => 'Fuelle de lengüeta'], 'focus' => 'control tongue movement and debris ingress resistance', 'stage' => 'upper closing and comfort assembly', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'collar-foam', 'terms' => ['en' => 'Collar foam', 'pt' => 'Espuma de colarinho', 'fr' => 'Mousse de col', 'de' => 'Schaum am Schaftrand', 'it' => 'Schiuma collarino', 'es' => 'Espuma de collar'], 'focus' => 'manage ankle comfort and top-line pressure profile', 'stage' => 'collar build before closing completion', 'domains' => ['footwear-construction', 'footwear', 'materials']],

        ['key' => 'pull-strength-test', 'terms' => ['en' => 'Pull strength test', 'pt' => 'Teste de resistência ao arranque', 'fr' => 'Essai de résistance à l’arrachement', 'de' => 'Ausreißtest', 'it' => 'Test resistenza allo strappo', 'es' => 'Ensayo de resistencia al arranque'], 'focus' => 'verify attachment performance of stitched or fixed components', 'stage' => 'lab and inline verification plan', 'domains' => ['footwear-construction', 'quality-control', 'footwear']],
        ['key' => 'peel-strength-test', 'terms' => ['en' => 'Peel strength test', 'pt' => 'Teste de resistência ao descolamento', 'fr' => 'Essai de pelage', 'de' => 'Schälfestigkeitstest', 'it' => 'Test resistenza a pelatura', 'es' => 'Ensayo de resistencia al pelado'], 'focus' => 'measure adhesive bond durability under controlled peel angles', 'stage' => 'bond validation and release criteria', 'domains' => ['footwear-construction', 'quality-control', 'footwear']],
        ['key' => 'flex-test', 'terms' => ['en' => 'Flex test', 'pt' => 'Teste de flexão', 'fr' => 'Essai de flexion', 'de' => 'Biegetest', 'it' => 'Test di flessione', 'es' => 'Ensayo de flexión'], 'focus' => 'assess crack, delamination, and fatigue under repeated bending', 'stage' => 'durability validation pre-launch', 'domains' => ['footwear-construction', 'quality-control', 'footwear']],

        // Controlled expansion: deeper footwear construction operations and quality nodes.
        ['key' => 'cement-open-time', 'terms' => ['en' => 'Cement open time', 'pt' => 'Tempo aberto da cola', 'fr' => 'Temps ouvert de colle', 'de' => 'Offenzeit des Klebers', 'it' => 'Tempo aperto adesivo', 'es' => 'Tiempo abierto del adhesivo'], 'focus' => 'keep adhesive tack within valid activation window before sole pressing', 'stage' => 'adhesive application and tunnel transfer', 'domains' => ['footwear-construction', 'production', 'materials']],
        ['key' => 'adhesive-viscosity', 'terms' => ['en' => 'Adhesive viscosity', 'pt' => 'Viscosidade do adesivo', 'fr' => 'Viscosité adhésif', 'de' => 'Klebstoffviskosität', 'it' => 'Viscosità adesivo', 'es' => 'Viscosidad del adhesivo'], 'focus' => 'stabilize spread and wetting for consistent bond line quality', 'stage' => 'mixing room and line dispensing setup', 'domains' => ['footwear-construction', 'materials', 'quality-control']],
        ['key' => 'primer-dry-time', 'terms' => ['en' => 'Primer dry time', 'pt' => 'Tempo de secagem do primário', 'fr' => 'Temps de séchage du primaire', 'de' => 'Trocknungszeit des Primers', 'it' => 'Tempo asciugatura primer', 'es' => 'Tiempo de secado del primer'], 'focus' => 'avoid solvent entrapment before adhesive reactivation', 'stage' => 'pre-cementing chemistry control', 'domains' => ['footwear-construction', 'production', 'quality-control']],
        ['key' => 'press-pressure-map', 'terms' => ['en' => 'Press pressure map', 'pt' => 'Mapa de pressão da prensa', 'fr' => 'Carte de pression de presse', 'de' => 'Druckbild der Presse', 'it' => 'Mappa pressione pressa', 'es' => 'Mapa de presión de prensa'], 'focus' => 'verify uniform pressure distribution around the full perimeter', 'stage' => 'sole pressing calibration', 'domains' => ['footwear-construction', 'machinery', 'quality-control']],
        ['key' => 'outsole-cure-window', 'terms' => ['en' => 'Outsole cure window', 'pt' => 'Janela de cura da sola', 'fr' => 'Fenêtre de cure de semelle', 'de' => 'Aushärtefenster der Laufsohle', 'it' => 'Finestra di cura suola', 'es' => 'Ventana de curado de suela'], 'focus' => 'release bonded pairs only after stable adhesive and substrate cure', 'stage' => 'post-press conditioning', 'domains' => ['footwear-construction', 'production', 'quality-control']],
        ['key' => 'upper-tension-profile', 'terms' => ['en' => 'Upper tension profile', 'pt' => 'Perfil de tensão do cabedal', 'fr' => 'Profil de tension de tige', 'de' => 'Spannungsprofil des Schafts', 'it' => 'Profilo tensione tomaia', 'es' => 'Perfil de tensión del corte'], 'focus' => 'balance medial and lateral pull to prevent twist and wrinkling', 'stage' => 'lasting machine parameter setup', 'domains' => ['footwear-construction', 'production', 'footwear']],
        ['key' => 'toe-spring-calibration', 'terms' => ['en' => 'Toe spring calibration', 'pt' => 'Calibração do toe spring', 'fr' => 'Calibration du relevé de pointe', 'de' => 'Kalibrierung der Zehenfeder', 'it' => 'Calibrazione alzata punta', 'es' => 'Calibración de elevación de puntera'], 'focus' => 'lock rocker geometry against target last and outsole tooling', 'stage' => 'engineering validation and first-off checks', 'domains' => ['footwear-construction', 'design', 'quality-control']],
        ['key' => 'heel-slip-evaluation', 'terms' => ['en' => 'Heel slip evaluation', 'pt' => 'Avaliação de escorregamento do calcanhar', 'fr' => 'Évaluation du glissement talon', 'de' => 'Bewertung des Fersenschlupfs', 'it' => 'Valutazione slittamento tallone', 'es' => 'Evaluación de deslizamiento de talón'], 'focus' => 'measure rearfoot retention under walk and stair protocols', 'stage' => 'fit lab and wear test gate', 'domains' => ['footwear-construction', 'quality-control', 'footwear']],
        ['key' => 'vamp-centerline', 'terms' => ['en' => 'Vamp centerline', 'pt' => 'Linha central do vampão', 'fr' => 'Ligne centrale de claque', 'de' => 'Mittellinie des Vorderblatts', 'it' => 'Linea centrale tomaia', 'es' => 'Línea central del empeine'], 'focus' => 'align pattern symmetry and visual balance on lasted pairs', 'stage' => 'pattern check and lasting visual control', 'domains' => ['footwear-construction', 'pattern-making', 'quality-control']],
        ['key' => 'quarter-balance', 'terms' => ['en' => 'Quarter balance', 'pt' => 'Balanceamento do quarto', 'fr' => 'Équilibrage du quartier', 'de' => 'Balance des Quartiere', 'it' => 'Bilanciamento quartiere', 'es' => 'Balance del cuarto'], 'focus' => 'control lateral-medial heel wrap symmetry and collar position', 'stage' => 'closing alignment and back-part lasting', 'domains' => ['footwear-construction', 'pattern-making', 'footwear']],
        ['key' => 'strobel-tension-control', 'terms' => ['en' => 'Strobel tension control', 'pt' => 'Controlo de tensão Strobel', 'fr' => 'Contrôle tension Strobel', 'de' => 'Strobel-Spannungskontrolle', 'it' => 'Controllo tensione Strobel', 'es' => 'Control de tensión Strobel'], 'focus' => 'prevent wave, puckering, and seam drift in flexible strobel joins', 'stage' => 'strobel sewing setup and inline checks', 'domains' => ['footwear-construction', 'production', 'machinery']],
        ['key' => 'insole-rib-height', 'terms' => ['en' => 'Insole rib height', 'pt' => 'Altura da nervura da palmilha', 'fr' => 'Hauteur de nervure première', 'de' => 'Rippenhöhe der Brandsohle', 'it' => 'Altezza nervatura soletta', 'es' => 'Altura del nervio de plantilla'], 'focus' => 'maintain stitch channel geometry and secure lasting margin capture', 'stage' => 'insole prep and welt readiness', 'domains' => ['footwear-construction', 'materials', 'quality-control']],
        ['key' => 'shank-positioning', 'terms' => ['en' => 'Shank positioning', 'pt' => 'Posicionamento da alma', 'fr' => 'Positionnement du cambrion', 'de' => 'Positionierung der Gelenkfeder', 'it' => 'Posizionamento cambrione', 'es' => 'Posicionamiento de cambrillón'], 'focus' => 'align waist support with flex and heel geometry targets', 'stage' => 'bottoming structural assembly', 'domains' => ['footwear-construction', 'production', 'footwear']],
        ['key' => 'heel-seat-leveling', 'terms' => ['en' => 'Heel seat leveling', 'pt' => 'Nivelamento do assento do calcanhar', 'fr' => 'Nivellement assise talon', 'de' => 'Nivellierung des Fersensitzes', 'it' => 'Livellamento sede tallone', 'es' => 'Nivelación del asiento de talón'], 'focus' => 'stabilize heel contact plane before heel attachment and finishing', 'stage' => 'heel seat prep before final bottoming', 'domains' => ['footwear-construction', 'production', 'quality-control']],
        ['key' => 'flex-groove-alignment', 'terms' => ['en' => 'Flex groove alignment', 'pt' => 'Alinhamento do sulco de flexão', 'fr' => 'Alignement rainure de flexion', 'de' => 'Ausrichtung der Flexrille', 'it' => 'Allineamento scanalatura flessione', 'es' => 'Alineación de ranura de flexión'], 'focus' => 'match outsole flex points with vamp break and gait path', 'stage' => 'tooling validation and wear simulation', 'domains' => ['footwear-construction', 'design', 'quality-control']],
        ['key' => 'bond-peel-audit', 'terms' => ['en' => 'Bond peel audit', 'pt' => 'Auditoria de descolamento', 'fr' => 'Audit de pelage liaison', 'de' => 'Audit der Schälfestigkeit', 'it' => 'Audit pelatura incollaggio', 'es' => 'Auditoría de pelado de unión'], 'focus' => 'track peel performance trends by line, material lot, and size range', 'stage' => 'final quality release and CAPA feedback', 'domains' => ['footwear-construction', 'quality-control', 'production']],
        ['key' => 'sidewall-trimming', 'terms' => ['en' => 'Sidewall trimming', 'pt' => 'Recorte de parede lateral', 'fr' => 'Parage de flanc semelle', 'de' => 'Seitenwand-Beschnitt', 'it' => 'Rifilo parete laterale', 'es' => 'Recorte de pared lateral'], 'focus' => 'remove excess flash and clean perimeter geometry before finishing', 'stage' => 'post-bottoming finishing', 'domains' => ['footwear-construction', 'finishing', 'production']],
        ['key' => 'edge-ink-build', 'terms' => ['en' => 'Edge ink build', 'pt' => 'Construção de tinta de bordo', 'fr' => 'Construction encre de tranche', 'de' => 'Aufbau der Kantenfarbe', 'it' => 'Costruzione inchiostro bordo', 'es' => 'Construcción de tinta de canto'], 'focus' => 'control layer thickness and cure for crack-free edge finish', 'stage' => 'final edge finishing passes', 'domains' => ['footwear-construction', 'finishing', 'quality-control']],
        ['key' => 'lace-hole-punching', 'terms' => ['en' => 'Lace hole punching', 'pt' => 'Furação de atacadores', 'fr' => 'Perçage oeillets lacet', 'de' => 'Schnürloch-Stanzen', 'it' => 'Punzonatura fori lacci', 'es' => 'Punzonado de ojales de cordón'], 'focus' => 'maintain clean hole geometry and spacing before eyelet setting', 'stage' => 'upper hardware preparation', 'domains' => ['footwear-construction', 'hardware', 'production']],
        ['key' => 'eyelet-flange-crack', 'terms' => ['en' => 'Eyelet flange crack', 'pt' => 'Fissura na aba do ilhó', 'fr' => 'Fissure collerette oeillet', 'de' => 'Riss im Ösenflansch', 'it' => 'Cricca flangia occhiello', 'es' => 'Fisura de pestaña de ojal'], 'focus' => 'detect and prevent hardware fracture under lacing load cycles', 'stage' => 'hardware pull test and incoming quality control', 'domains' => ['footwear-construction', 'hardware', 'quality-control']],

        // Expert pass 2: bottoming chemistry and failure analytics.
        ['key' => 'open-time-drift', 'terms' => ['en' => 'Open-time drift', 'pt' => 'Deriva do tempo aberto', 'fr' => 'Dérive du temps ouvert', 'de' => 'Offenzeit-Drift', 'it' => 'Deriva del tempo aperto', 'es' => 'Deriva del tiempo abierto'], 'focus' => 'track loss of adhesive tack window under real line conditions', 'stage' => 'adhesive application and transfer timing control', 'domains' => ['footwear-construction', 'materials', 'quality-control']],
        ['key' => 'peel-signature-taxonomy', 'terms' => ['en' => 'Peel signature taxonomy', 'pt' => 'Taxonomia de assinatura de peel', 'fr' => 'Taxonomie des signatures de pelage', 'de' => 'Schälsignatur-Taxonomie', 'it' => 'Tassonomia firme di peel', 'es' => 'Taxonomía de firma de pelado'], 'focus' => 'classify fracture signatures into repeatable failure categories', 'stage' => 'failure analysis and CAPA governance', 'domains' => ['footwear-construction', 'quality-control', 'materials']],
        ['key' => 'thermal-aging-behavior', 'terms' => ['en' => 'Thermal aging behavior', 'pt' => 'Comportamento de envelhecimento térmico', 'fr' => 'Comportement de vieillissement thermique', 'de' => 'Thermisches Alterungsverhalten', 'it' => 'Comportamento di invecchiamento termico', 'es' => 'Comportamiento de envejecimiento térmico'], 'focus' => 'characterize bond durability drift under heat exposure and storage', 'stage' => 'durability modeling and validation planning', 'domains' => ['footwear-construction', 'materials', 'quality-control']],
        ['key' => 'failure-mode-clustering', 'terms' => ['en' => 'Failure mode clustering', 'pt' => 'Agrupamento de modos de falha', 'fr' => 'Clustering des modes de défaillance', 'de' => 'Clustering von Fehlermodi', 'it' => 'Clustering dei modi di guasto', 'es' => 'Agrupamiento de modos de fallo'], 'focus' => 'group repeated defects into high-confidence root-cause families', 'stage' => 'quality analytics and corrective-action prioritization', 'domains' => ['footwear-construction', 'quality-control', 'production']],
        ['key' => 'accelerated-aging-protocol', 'terms' => ['en' => 'Accelerated aging protocol', 'pt' => 'Protocolo de envelhecimento acelerado', 'fr' => 'Protocole de vieillissement accéléré', 'de' => 'Beschleunigtes Alterungsprotokoll', 'it' => 'Protocollo di invecchiamento accelerato', 'es' => 'Protocolo de envejecimiento acelerado'], 'focus' => 'compress durability stress histories for pre-launch validation', 'stage' => 'lab reliability screening before commercialization', 'domains' => ['footwear-construction', 'quality-control', 'materials']],
        ['key' => 'thermal-shock-cycle', 'terms' => ['en' => 'Thermal shock cycle', 'pt' => 'Ciclo de choque térmico', 'fr' => 'Cycle de choc thermique', 'de' => 'Thermoschockzyklus', 'it' => 'Ciclo di shock termico', 'es' => 'Ciclo de choque térmico'], 'focus' => 'stress bonded interfaces with abrupt hot-cold transitions', 'stage' => 'accelerated reliability and edge-lift detection tests', 'domains' => ['footwear-construction', 'quality-control', 'materials']],
        ['key' => 'adhesive-transfer-latency', 'terms' => ['en' => 'Adhesive transfer latency', 'pt' => 'Latência de transferência do adesivo', 'fr' => 'Latence de transfert adhésif', 'de' => 'Klebstoff-Transferlatenz', 'it' => 'Latenza di trasferimento adesivo', 'es' => 'Latencia de transferencia adhesiva'], 'focus' => 'limit elapsed time from activation to mating for stable wetting continuity', 'stage' => 'bottoming takt control and station balancing', 'domains' => ['footwear-construction', 'production', 'quality-control']],
        ['key' => 'primer-reactivation-window', 'terms' => ['en' => 'Primer reactivation window', 'pt' => 'Janela de reativação do primário', 'fr' => 'Fenêtre de réactivation du primaire', 'de' => 'Reaktivierungsfenster des Primers', 'it' => 'Finestra di riattivazione del primer', 'es' => 'Ventana de reactivación del primer'], 'focus' => 'ensure activated primer films remain chemically receptive at mating', 'stage' => 'heat activation and transfer staging', 'domains' => ['footwear-construction', 'materials', 'quality-control']],
        ['key' => 'roughing-depth-profile', 'terms' => ['en' => 'Roughing depth profile', 'pt' => 'Perfil de profundidade de rugosagem', 'fr' => 'Profil de profondeur de rugosification', 'de' => 'Aufrauhtiefenprofil', 'it' => 'Profilo profondità rugosatura', 'es' => 'Perfil de profundidad de rugosado'], 'focus' => 'control micro-topography for repeatable mechanical keying without substrate damage', 'stage' => 'pre-cementing surface preparation', 'domains' => ['footwear-construction', 'production', 'quality-control']],
        ['key' => 'outsole-surface-energy', 'terms' => ['en' => 'Outsole surface energy', 'pt' => 'Energia superficial da sola', 'fr' => 'Énergie de surface de semelle', 'de' => 'Oberflächenenergie der Laufsohle', 'it' => 'Energia superficiale della suola', 'es' => 'Energía superficial de la suela'], 'focus' => 'qualify wetting potential of outsole compounds before bonding', 'stage' => 'material approval and bonding setup', 'domains' => ['footwear-construction', 'materials', 'quality-control']],
        ['key' => 'toe-puff-activation-curve', 'terms' => ['en' => 'Toe puff activation curve', 'pt' => 'Curva de ativação da ponteira', 'fr' => 'Courbe d’activation du contrefort avant', 'de' => 'Aktivierungskurve der Zehenverstärkung', 'it' => 'Curva di attivazione del puntale', 'es' => 'Curva de activación del refuerzo de puntera'], 'focus' => 'tune thermal response of toe reinforcements for long-term shape retention', 'stage' => 'pre-lasting reinforcement activation', 'domains' => ['footwear-construction', 'materials', 'production']],
        ['key' => 'counter-edge-skive', 'terms' => ['en' => 'Counter edge skive', 'pt' => 'Rebaixo da borda do contraforte', 'fr' => 'Parage de bord du contrefort', 'de' => 'Schärfung der Fersenkappenkante', 'it' => 'Scarnitura bordo contrafforte', 'es' => 'Rebajado del borde de contrafuerte'], 'focus' => 'stabilize stiffness transition from heel reinforcement into upper shell', 'stage' => 'back-part preparation before counter molding', 'domains' => ['footwear-construction', 'production', 'materials']],
        ['key' => 'heel-seat-contour-match', 'terms' => ['en' => 'Heel seat contour match', 'pt' => 'Correspondência de contorno do assento do calcanhar', 'fr' => 'Concordance de contour d’assise talon', 'de' => 'Konturpassung des Fersensitzes', 'it' => 'Corrispondenza profilo sede tallone', 'es' => 'Concordancia de contorno del asiento de talón'], 'focus' => 'verify heel seat geometry compatibility before final attachment', 'stage' => 'seat preparation and bottoming verification', 'domains' => ['footwear-construction', 'quality-control', 'production']],
        ['key' => 'cure-gradient-mapping', 'terms' => ['en' => 'Cure gradient mapping', 'pt' => 'Mapeamento de gradiente de cura', 'fr' => 'Cartographie du gradient de cure', 'de' => 'Aushärte-Gradientenkartierung', 'it' => 'Mappatura del gradiente di cura', 'es' => 'Mapeo de gradiente de curado'], 'focus' => 'map cure progression by zone to prevent hidden durability drift', 'stage' => 'post-press conditioning validation', 'domains' => ['footwear-construction', 'quality-control', 'materials']],
        ['key' => 'bond-line-void-detection', 'terms' => ['en' => 'Bond-line void detection', 'pt' => 'Deteção de vazios na linha de colagem', 'fr' => 'Détection des vides de ligne de collage', 'de' => 'Erkennung von Hohlstellen in der Klebefuge', 'it' => 'Rilevamento vuoti linea di incollaggio', 'es' => 'Detección de vacíos en la línea de pegado'], 'focus' => 'detect discontinuities in adhesive interface coverage before field delamination', 'stage' => 'final audit and destructive verification sampling', 'domains' => ['footwear-construction', 'quality-control', 'production']],
        ['key' => 'stitchdown-construction', 'terms' => ['en' => 'Stitchdown construction', 'pt' => 'Construção stitchdown', 'fr' => 'Construction stitchdown', 'de' => 'Stitchdown-Konstruktion', 'it' => 'Costruzione stitchdown', 'es' => 'Construcción stitchdown'], 'focus' => 'secure outsole package by stitching turned-out upper margins directly to the sole platform', 'stage' => 'heavy-duty bottoming and perimeter stitching', 'domains' => ['footwear-construction', 'footwear', 'production'], 'terminology_status' => 'validated', 'editorial_notes' => 'Chosen when serviceable perimeter stitching is prioritized over adhesive throughput; differs from cemented routes by margin turn-out and stitch-gated release.', 'source_reference_text' => 'Factory technical route cards, stitchdown perimeter SOP 4.2, and cross-section acceptance set for edge serviceability.'],
        ['key' => 'cupsole-cementing', 'terms' => ['en' => 'Cupsole cementing', 'pt' => 'Colagem de cupsole', 'fr' => 'Cimentation cupsole', 'de' => 'Cupsole-Verklebung', 'it' => 'Incollaggio cupsole', 'es' => 'Pegado de cupsole'], 'focus' => 'bond lasted uppers into pre-formed cupsole cavities with heel and waist pressure control', 'stage' => 'athletic bottoming cell', 'domains' => ['footwear-construction', 'production', 'materials'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Cemented micro-variant with cavity-driven pressure logic; differs from flat cemented routes by heel-pocket wetting and sidewall seating constraints.', 'source_reference_text' => 'Cupsole bottoming validation protocol, cavity pressure map set, and route-comparison wetting audit record.'],
        ['key' => 'foxing-tape-application', 'terms' => ['en' => 'Foxing tape application', 'pt' => 'Aplicação de fita de foxing', 'fr' => 'Application de bande foxing', 'de' => 'Aufbringen von Foxing-Tape', 'it' => 'Applicazione nastro foxing', 'es' => 'Aplicación de cinta foxing'], 'focus' => 'apply and overlap foxing tape with controlled alignment around outsole sidewall transitions', 'stage' => 'vulcanized sidewall preparation', 'domains' => ['footwear-construction', 'production', 'finishing'], 'terminology_status' => 'reviewed', 'source_reference_text' => 'Visual QC standard for vulcanized sidewall builds.'],
        ['key' => 'seam-sealing-tape', 'terms' => ['en' => 'Seam sealing tape', 'pt' => 'Fita de selagem de costura', 'fr' => 'Bande d’étanchéité de couture', 'de' => 'Nahtabdichtungsband', 'it' => 'Nastro sigillante cuciture', 'es' => 'Cinta de sellado de costuras'], 'focus' => 'seal stitched seams on waterproof uppers to block capillary ingress under flex', 'stage' => 'post-closing waterproofing', 'domains' => ['footwear-construction', 'materials', 'production'], 'terminology_status' => 'validated', 'editorial_notes' => 'Used primarily on waterproof categories; tape chemistry must match membrane supplier spec.', 'source_reference_text' => 'Waterproof upper lamination and seam sealing protocol.'],
        ['key' => 'heel-seat-nailing-pattern', 'terms' => ['en' => 'Heel seat nailing pattern', 'pt' => 'Padrão de pregos do assento do calcanhar', 'fr' => 'Schéma de cloutage d’assise talon', 'de' => 'Nagelmuster am Fersensitz', 'it' => 'Schema chiodatura sede tallone', 'es' => 'Patrón de clavado del asiento de talón'], 'focus' => 'define nail count and distribution to control heel-seat fixation under torsional loading', 'stage' => 'heel build and mechanical fixation', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'reviewed'],
        ['key' => 'stacked-heel', 'terms' => ['en' => 'Stacked heel', 'pt' => 'Salto empilhado', 'fr' => 'Talon empilé', 'de' => 'Schichtabsatz', 'it' => 'Tacco impilato', 'es' => 'Tacón apilado'], 'focus' => 'build heel elevation through layered lifts with stable alignment and fastening integrity', 'stage' => 'heel construction and attachment sequence', 'domains' => ['footwear-construction', 'production', 'materials'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Common in structured and dress constructions where heel geometry and attachment durability are critical.', 'source_reference_text' => 'Heel build work instruction and torque-retention validation checklist.'],
        ['key' => 'cement-spread-weight', 'terms' => ['en' => 'Cement spread weight', 'pt' => 'Gramagem de cola aplicada', 'fr' => 'Grammage de dépôt de colle', 'de' => 'Auftragsgewicht des Klebstoffs', 'it' => 'Grammatura di spalmatura adesivo', 'es' => 'Gramaje de aplicación de adhesivo'], 'focus' => 'control adhesive mass per area to balance wetting, squeeze-out risk, and cure behavior', 'stage' => 'adhesive application metrology', 'domains' => ['footwear-construction', 'quality-control', 'production'], 'terminology_status' => 'validated', 'source_reference_text' => 'Adhesive metrology sheet per outsole family.'],
        ['key' => 'sidewall-wrap-tension', 'terms' => ['en' => 'Sidewall wrap tension', 'pt' => 'Tensão de envolvimento da parede lateral', 'fr' => 'Tension d’enveloppement de flanc', 'de' => 'Spannung der Seitenwand-Umschlingung', 'it' => 'Tensione avvolgimento parete laterale', 'es' => 'Tensión de envolvente de pared lateral'], 'focus' => 'maintain controlled wrap force around outsole sidewalls to prevent rebound lift and wrinkles', 'stage' => 'perimeter wrapping and setting', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'reviewed'],
        ['key' => 'outsole-priming-sequence', 'terms' => ['en' => 'Outsole priming sequence', 'pt' => 'Sequência de primário da sola', 'fr' => 'Séquence de primaire semelle', 'de' => 'Primern-Sequenz der Laufsohle', 'it' => 'Sequenza primer suola', 'es' => 'Secuencia de primer de suela'], 'focus' => 'define pass order and flash-off timing for outsole priming prior to adhesive activation', 'stage' => 'pre-bond outsole preparation', 'domains' => ['footwear-construction', 'production', 'materials'], 'terminology_status' => 'validated', 'source_reference_text' => 'Outsole pretreatment process matrix by compound family.'],
        ['key' => 'toe-lasting-pincer-pressure', 'terms' => ['en' => 'Toe lasting pincer pressure', 'pt' => 'Pressão da pinça de moldação da biqueira', 'fr' => 'Pression des pinces de montage de pointe', 'de' => 'Druck der Spitzen-Aufziehzangen', 'it' => 'Pressione pinza montaggio punta', 'es' => 'Presión de pinza de montado de puntera'], 'focus' => 'set toe pincer pressure to hold margins without grain marking or reinforcement collapse', 'stage' => 'toe lasting setup and control', 'domains' => ['footwear-construction', 'machinery', 'quality-control'], 'terminology_status' => 'validated'],
        ['key' => 'lasting-board', 'terms' => ['en' => 'Lasting board', 'pt' => 'Base de moldação', 'fr' => 'Planche de montage', 'de' => 'Aufziehbrett', 'it' => 'Piano di montaggio', 'es' => 'Base de montado'], 'focus' => 'temporary support board used to stabilize upper geometry during lasting and pre-bottoming handling', 'stage' => 'lasting support and transfer handling', 'domains' => ['footwear-construction', 'production', 'materials'], 'terminology_status' => 'reviewed', 'source_reference_text' => 'Line balancing and handling standard for lasted uppers.'],

        // Wave B: semantic deepening through strongly connected secondary clusters.
        ['key' => 'saddle-stitch', 'terms' => ['en' => 'Saddle stitch', 'pt' => 'Costura de sela', 'fr' => 'Couture sellier', 'de' => 'Sattlernaht', 'it' => 'Cucitura a sella', 'es' => 'Costura de guarnicionero'], 'focus' => 'secure high-stress upper joints with controlled two-needle lock integrity', 'stage' => 'closing operations for premium and repairable constructions', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Used where seam durability and repairability are prioritized over high-speed throughput.', 'source_reference_text' => 'Closing operation standards and seam failure review sheets.'],
        ['key' => 'blake-stitch', 'terms' => ['en' => 'Blake stitch', 'pt' => 'Costura Blake', 'fr' => 'Couture Blake', 'de' => 'Blake-Naht', 'it' => 'Cucitura Blake', 'es' => 'Costura Blake'], 'focus' => 'join upper, insole, and outsole through direct internal stitching for flexible dress constructions', 'stage' => 'post seat release — through-stitch lock before sole finish; no cork cavity', 'domains' => ['footwear-construction', 'production', 'footwear'], 'terminology_status' => 'validated', 'editorial_notes' => 'Through-stitch route selected for flexibility and cycle speed where full welt chain is not required; needle-path control is the critical release gate.', 'source_reference_text' => 'Blake stitched-bottoming specification, inline endoscope criteria, and Goodyear-vs-Blake route selection note.'],
        ['key' => 'goodyear-welt', 'terms' => ['en' => 'Goodyear welt', 'pt' => 'Vira Goodyear', 'fr' => 'Trépointe Goodyear', 'de' => 'Goodyear-Rahmen', 'it' => 'Guardolo Goodyear', 'es' => 'Cerco Goodyear'], 'focus' => 'build resole-capable stitched perimeter architecture with controlled welt-channel geometry', 'stage' => 'post seat release — rib through channel stitch before sole lock', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Full stitched route prioritized when repairability and mechanical lock integrity outweigh throughput; relies on rib, holdfast, fill, and channel gates.', 'source_reference_text' => 'Goodyear route card, welt-channel acceptance protocol, and sectional cross-cut checklist for route release.'],
        ['key' => 'gemming-rib', 'terms' => ['en' => 'Gemming rib', 'pt' => 'Nervura de gemming', 'fr' => 'Nervure de gemmage', 'de' => 'Gemming-Rippe', 'it' => 'Nervatura di gemming', 'es' => 'Nervio de gemming'], 'focus' => 'form stitched anchoring rib on insole board for welted constructions', 'stage' => 'insole preparation for stitched welt systems', 'domains' => ['footwear-construction', 'materials', 'production'], 'terminology_status' => 'reviewed'],
        ['key' => 'sockliner', 'terms' => ['en' => 'Sockliner', 'pt' => 'Forro de palmilha', 'fr' => 'Première de propreté', 'de' => 'Decksohle', 'it' => 'Sottopiede di finitura', 'es' => 'Plantilla de acabado'], 'focus' => 'provide final foot-contact layer for comfort, moisture handling, and fit perception tuning', 'stage' => 'final internal assembly and comfort finishing', 'domains' => ['footwear-construction', 'materials', 'footwear'], 'terminology_status' => 'reviewed'],
        ['key' => 'toe-cap', 'terms' => ['en' => 'Toe cap', 'pt' => 'Biqueira externa', 'fr' => 'Bout rapporté', 'de' => 'Zehenkappe', 'it' => 'Puntale esterno', 'es' => 'Puntera exterior'], 'focus' => 'reinforce and protect forepart upper zones exposed to abrasion and impact', 'stage' => 'upper component assembly and forepart reinforcement', 'domains' => ['footwear-construction', 'materials', 'production'], 'terminology_status' => 'reviewed'],
        ['key' => 'lasting-margin', 'terms' => ['en' => 'Lasting margin', 'pt' => 'Margem de moldação', 'fr' => 'Marge de montage', 'de' => 'Aufziehmarge', 'it' => 'Margine di montaggio', 'es' => 'Margen de montado'], 'focus' => 'control upper edge capture allowance to stabilize geometry during lasting and bottoming', 'stage' => 'pattern engineering and lasting setup', 'domains' => ['footwear-construction', 'pattern-making', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Pattern-to-production control point that governs capture stability and downstream bond-line reliability.', 'source_reference_text' => 'Pattern release checklist and lasting station capture capability report.'],
        ['key' => 'heel-counter-reinforcement', 'terms' => ['en' => 'Heel counter reinforcement', 'pt' => 'Reforço do contraforte', 'fr' => 'Renfort de contrefort arrière', 'de' => 'Verstärkung der Fersenkappe', 'it' => 'Rinforzo contrafforte', 'es' => 'Refuerzo del contrafuerte'], 'focus' => 'increase rearfoot structural retention through calibrated reinforcement stack and placement', 'stage' => 'back-part reinforcement and molding preparation', 'domains' => ['footwear-construction', 'materials', 'quality-control'], 'terminology_status' => 'validated', 'source_reference_text' => 'Back-part reinforcement matrix by last family and width grade.'],
        ['key' => 'filler-layer', 'terms' => ['en' => 'Filler layer', 'pt' => 'Camada de enchimento', 'fr' => 'Couche de remplissage', 'de' => 'Füllschicht', 'it' => 'Strato di riempimento', 'es' => 'Capa de relleno'], 'focus' => 'level internal sole cavities to stabilize support feel and bottoming interface continuity', 'stage' => 'pre-outsole assembly and underfoot build leveling', 'domains' => ['footwear-construction', 'materials', 'production'], 'terminology_status' => 'reviewed'],
        ['key' => 'waist-zone', 'terms' => ['en' => 'Waist zone', 'pt' => 'Zona da cintura', 'fr' => 'Zone de cambrion', 'de' => 'Taillenzone', 'it' => 'Zona del punto vita', 'es' => 'Zona de cintura'], 'focus' => 'govern midfoot structural behavior between forepart flex and heel support blocks', 'stage' => 'structural tuning and shank integration', 'domains' => ['footwear-construction', 'design', 'quality-control'], 'terminology_status' => 'reviewed'],
        ['key' => 'throat-line', 'terms' => ['en' => 'Throat line', 'pt' => 'Linha da garganta', 'fr' => 'Ligne de gorge', 'de' => 'Ristlinie', 'it' => 'Linea della gola', 'es' => 'Línea de garganta'], 'focus' => 'define lacing opening geometry and forefoot entry behavior on the upper', 'stage' => 'pattern engineering and upper visual alignment', 'domains' => ['footwear-construction', 'pattern-making', 'footwear'], 'terminology_status' => 'reviewed'],
        ['key' => 'cemented-construction', 'terms' => ['en' => 'Cemented construction', 'pt' => 'Construção colada', 'fr' => 'Construction cimentée', 'de' => 'Geklebte Konstruktion', 'it' => 'Costruzione cementata', 'es' => 'Construcción cementada'], 'focus' => 'assemble upper-to-sole systems primarily through controlled adhesive bonding architecture', 'stage' => 'bottoming route selection and process governance', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Throughput-first route where bond chemistry replaces stitch mechanics; process control concentrates on roughing, activation timing, press map, and cure windows.', 'source_reference_text' => 'Cemented route governance sheet, open-time latency control log, and comparative release criteria versus stitch-based routes.'],
        ['key' => 'board-lasted-construction', 'terms' => ['en' => 'Board-lasted construction', 'pt' => 'Construção com montagem em cartão', 'fr' => 'Construction montée sur première rigide', 'de' => 'Brandsohlen-Aufziehkonstruktion', 'it' => 'Costruzione con montaggio su sottopiede rigido', 'es' => 'Construcción montada sobre plantilla rígida'], 'focus' => 'use rigid lasting board architecture to stabilize shape retention and support in structured footwear', 'stage' => 'lasting architecture definition and bottoming preparation', 'domains' => ['footwear-construction', 'production', 'materials'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Structural architecture choice before bottoming route fork; emphasizes shape retention and controlled back-part/seat behavior over Strobel-style flexibility.', 'source_reference_text' => 'Board-lasted architecture sheet, seat-release fork criteria, and comparative workflow note versus Strobel constructions.'],

        // Wave C: second-order semantic expansion — internal structures, reinforcement, stitching, bottoming micro-variants.
        ['key' => 'welt-channel', 'terms' => ['en' => 'Welt channel', 'pt' => 'Canal da vira', 'fr' => 'Canal de trépointe', 'de' => 'Rahmenkanal', 'it' => 'Canale guardolo', 'es' => 'Canal de cerco'], 'focus' => 'receive channel stitching and define stitch sink geometry on insole board', 'stage' => 'insole channel cutting and welt readiness verification', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Geometry gate linking channeling, gemming rib, and holdfast penetration before welting release.', 'source_reference_text' => 'Welt-channel depth and wall-angle acceptance protocol by size run.'],
        ['key' => 'channel-stitching', 'terms' => ['en' => 'Channel stitching', 'pt' => 'Costura em canal', 'fr' => 'Couture en canal', 'de' => 'Kanalnaht', 'it' => 'Cucitura in canale', 'es' => 'Costura en canal'], 'focus' => 'lock welt and upper margin inside a prepared groove with concealed thread path', 'stage' => 'terminal welt-route lock after holdfast, cork fill, and waist shaping', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Outsole lock at end of Goodyear prep chain; crown height and penetration are irreversible mechanical gates.', 'source_reference_text' => 'Channel stitch crown-height and reopening rework SOP.'],
        ['key' => 'inseam-stitch', 'terms' => ['en' => 'Inseam stitch', 'pt' => 'Costura de entrecosto', 'fr' => 'Couture intérieure de fermeture', 'de' => 'Schließ-Innennaht', 'it' => 'Cucitura interna di chiusura', 'es' => 'Costura de entrecorte'], 'focus' => 'close lining and lasting margin before pull to block ingress and stabilize throat', 'stage' => 'pre-lasting closing and internal envelope integrity', 'domains' => ['footwear-construction', 'production', 'footwear'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Hidden closure seam; SPI and tack discipline at throat curve prevent post-lasting collar drift.', 'source_reference_text' => 'Closing route card — inseam integrity and throat reinforcement matrix.'],
        ['key' => 'filler-cork', 'terms' => ['en' => 'Filler cork', 'pt' => 'Cortiça de enchimento', 'fr' => 'Liège de remplissage', 'de' => 'Korkfüllung', 'it' => 'Sughero di riempimento', 'es' => 'Corcho de relleno'], 'focus' => 'level welted footbed void and tune waist flex and underfoot feel', 'stage' => 'bench filling between insole and outsole on stitched routes', 'domains' => ['footwear-construction', 'materials', 'production'], 'terminology_status' => 'reviewed', 'source_reference_text' => 'Cork fill density map and void-pocket audit for Goodyear waist zone.'],
        ['key' => 'lasting-tuck', 'terms' => ['en' => 'Lasting tuck', 'pt' => 'Dobragem de moldação', 'fr' => 'Rabat de montage', 'de' => 'Aufziehfalte', 'it' => 'Ripiegatura di montaggio', 'es' => 'Pliegue de montado'], 'focus' => 'fold upper margin into capture zone without grain rupture before fixation', 'stage' => 'lasting margin capture at toe, side, and seat stations', 'domains' => ['footwear-construction', 'production', 'footwear'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Operational fold linking lasting allowance, lasting margin, and edge capture stability.', 'source_reference_text' => 'Lasting tuck overlap audit sheet by leather family.'],
        ['key' => 'tuck', 'terms' => ['en' => 'Tuck', 'pt' => 'Tuck de moldação', 'fr' => 'Tuck de montage', 'de' => 'Tuck im Aufziehen', 'it' => 'Tuck di montaggio', 'es' => 'Tuck de montado'], 'focus' => 'execute micro-fold control of upper margin before fixation to preserve capture symmetry', 'stage' => 'station-level margin fold control during lasting sequence', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Operator-level control term used for tuck-angle and overlap checks on side and seat stations.', 'source_reference_text' => 'Tuck-angle control card and station audit checklist.'],
        ['key' => 'shank-reinforcement', 'terms' => ['en' => 'Shank reinforcement', 'pt' => 'Reforço da alma', 'fr' => 'Renfort de cambrion', 'de' => 'Verstärkung der Gelenkfeder', 'it' => 'Rinforzo cambrione', 'es' => 'Refuerzo del cambrillón'], 'focus' => 'set gait hinge in waist window before cork fill or cemented press', 'stage' => 'internal assembly after lasting — torsion bench before cork or press', 'domains' => ['footwear-construction', 'materials', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Torsion bench sign-off mandatory; 2 mm drift relocates flex squeak, proud seat bridges air in press or blocks cork pour.', 'source_reference_text' => 'Shank placement and torsion response validation by construction family.'],
        ['key' => 'topline-reinforcement', 'terms' => ['en' => 'Topline reinforcement', 'pt' => 'Reforço da linha superior', 'fr' => 'Renfort de ligne de col', 'de' => 'Verstärkung der Schaftrandlinie', 'it' => 'Rinforzo topline', 'es' => 'Refuerzo de línea superior'], 'focus' => 'stabilize collar and opening edge against pull-induced waviness and lining shear', 'stage' => 'upper reinforcement and closing preparation', 'domains' => ['footwear-construction', 'materials', 'footwear'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Integrates with feather-edge transition; stiffness mismatch telegraphs as collar ripple.', 'source_reference_text' => 'Topline reinforcement stack spec and visual collar stability standard.'],
        ['key' => 'waist-shaping', 'terms' => ['en' => 'Waist shaping', 'pt' => 'Modelação da cintura', 'fr' => 'Profilage du cambrion', 'de' => 'Taillenformung', 'it' => 'Modellatura punto vita', 'es' => 'Conformado de cintura'], 'focus' => 'profile Goodyear waist cavity before channel stitch lock', 'stage' => 'Goodyear bench between cork fill and channel stitch — cavity profile gate', 'domains' => ['footwear-construction', 'production', 'finishing'], 'terminology_status' => 'validated', 'editorial_notes' => 'Links shank seat, cork density, and edge-ink symmetry; void pockets predict channel crown failure at sole lock.', 'source_reference_text' => 'Waist profile control sheet linking shank width, cork fill, and edge ink read.'],
        ['key' => 'holdfast-stitch', 'terms' => ['en' => 'Holdfast stitch', 'pt' => 'Ponto de retenção da vira', 'fr' => 'Point d’ancrage trépointe', 'de' => 'Holdfast-Stich', 'it' => 'Punto di ancoraggio guardolo', 'es' => 'Puntada de anclaje del cerco'], 'focus' => 'anchor welt into gemming rib with controlled penetration and thread lock', 'stage' => 'Goodyear prep lock after gemming — bite map gates cork fill', 'domains' => ['footwear-construction', 'production', 'quality-control'], 'terminology_status' => 'validated', 'editorial_notes' => 'Primary welt bite stitch; skipped lock loops are leading resoling failure precursors.', 'source_reference_text' => 'Holdfast bite map and lock-loop completeness gate.'],
        ['key' => 'welt-stitch-penetration', 'terms' => ['en' => 'Welt stitch penetration', 'pt' => 'Penetração do ponto da vira', 'fr' => 'Pénétration de point trépointe', 'de' => 'Rahmenstichpenetration', 'it' => 'Penetrazione punto guardolo', 'es' => 'Penetración de puntada de cerco'], 'focus' => 'measure stitch depth and angle as welting and resoling integrity release gate', 'stage' => 'welting QC and resole certification audit', 'domains' => ['footwear-construction', 'quality-control', 'production'], 'terminology_status' => 'validated', 'source_reference_text' => 'Penetration map acceptance criteria for holdfast and outsole stitch classes.'],
        ['key' => 'internal-footbed-stack', 'terms' => ['en' => 'Internal footbed stack', 'pt' => 'Pacote interno de palmilha', 'fr' => 'Empilement interne de première', 'de' => 'Interner Fußbettaufbau', 'it' => 'Stack interno sottopiede', 'es' => 'Paquete interno de plantilla'], 'focus' => 'sequence insole board, shank, filler, and sockliner for underfoot support and volume', 'stage' => 'final internal assembly before upper closing and wear validation', 'domains' => ['footwear-construction', 'materials', 'production'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Ordered internal assembly; sequencing errors surface late as heel-pocket collapse or waist delamination.', 'source_reference_text' => 'Internal stack sequence SOP and heel-seat X-ray verification checklist.'],
        ['key' => 'feather-line', 'terms' => ['en' => 'Feather line', 'pt' => 'Linha em pena', 'fr' => 'Ligne amincie', 'de' => 'Federlinie', 'it' => 'Linea a piuma', 'es' => 'Línea en pluma'], 'focus' => 'read skived margin continuity to predict topline behavior before lasting', 'stage' => 'post-skiving visual QC and closing handoff', 'domains' => ['footwear-construction', 'footwear', 'finishing'], 'terminology_status' => 'reviewed', 'editorial_notes' => 'Industry read reference along feather-edge transition; line breaks forecast collar ripple after pull.', 'source_reference_text' => 'Feather-line continuity atlas and skive-angle correlation log.'],
    ];

    $concepts = [];
    foreach ($rows as $row) {
        $isCoreConcept = in_array($row['key'], $coreConceptKeys, true);
        $translations = [];
        foreach ($locales as $locale) {
            $term = $row['terms'][$locale] ?? $row['terms']['en'];
            $coreVariant = abs(crc32($row['key'].'-'.$locale));
            $short = $isCoreConcept
                ? strtr($coreShortPatterns[$locale][$coreVariant % count($coreShortPatterns[$locale])], [':term' => $term])
                : strtr($shortTemplates[$locale], [':term' => $term, ':focus' => $row['focus']]);

            $full = $expertTierFullDefinitions[$row['key']][$locale] ?? ($isCoreConcept
                ? strtr($coreFullPatterns[$locale][$coreVariant % count($coreFullPatterns[$locale])], [':term' => $term])
                : strtr($fullTemplates[$locale], [':term' => $term, ':stage' => $row['stage']]));

            $example = $expertTierExamples[$row['key']][$locale] ?? ($isCoreConcept
                ? strtr($coreExamplePatterns[$locale][$coreVariant % count($coreExamplePatterns[$locale])], [
                    ':term' => $term,
                    ':stage' => $row['stage'],
                    ':focus' => $row['focus'],
                ])
                : strtr($exampleTemplates[$locale], [':term' => $term]));

            $slug = $locale === 'en'
                ? $row['key']
                : Str::slug($term).'-'.$locale;

            $translations[$locale] = [
                'term' => $term,
                'slug' => $slug,
                'status' => $row['translation_status'] ?? WorkflowStatus::PUBLISHED,
                'terminology_status' => $row['terminology_status'] ?? ($coreTerminologyStatusByKey[$row['key']] ?? \App\Support\Editorial\TerminologyStatus::DRAFT),
                'short_definition' => $short,
                'full_definition' => $full,
                'seo_title' => $term.' | Footwear construction',
                'seo_description' => $short,
                'editorial_notes' => $localizedEditorialNotesByKey[$row['key']][$locale]
                    ?? $row['editorial_notes']
                    ?? ($coreEditorialNotesByKey[$row['key']] ?? ($isCoreConcept ? 'Core concept quality-upgraded for semantic authority and multilingual industrial consistency.' : null)),
                'source_reference_text' => $row['source_reference_text'] ?? ($coreSourceReferenceByKey[$row['key']] ?? ($isCoreConcept ? 'Editorial semantic quality upgrade wave for footwear-construction core anchors.' : null)),
                'examples' => [
                    [
                        'example' => $example,
                        'context' => 'footwear-construction',
                    ],
                ],
            ];
        }

        $concepts[] = [
            'key' => $row['key'],
            'featured' => (bool) ($row['featured'] ?? false),
            'status' => $row['status'] ?? WorkflowStatus::PUBLISHED,
            'translation_status' => $row['translation_status'] ?? WorkflowStatus::PUBLISHED,
            'difficulty_level' => $row['difficulty_level'] ?? 'intermediate',
            'domains' => $row['domains'],
            'translations' => $translations,
        ];
    }

    $relationList = [];
    $add = static function (string $from, string $to, string $type = 'related') use (&$relationList): void {
        if ($from === $to) {
            return;
        }
        $relationList[] = ['from' => $from, 'to' => $to, 'type' => $type];
    };

    // Hub relations for semantic density.
    foreach (['lasting', 'side-lasting', 'toe-lasting', 'back-part-lasting', 'seat-lasting', 'lasting-pincher', 'lasting-tack', 'lasting-allowance', 'toe-lasting-pincer-pressure', 'lasting-board', 'lasting-margin', 'lasting-tuck', 'tuck'] as $key) {
        $add($key, 'lasting', $key === 'lasting' ? 'related' : 'broader');
    }
    foreach (['outsole', 'midsole', 'insole', 'shank', 'rand', 'bond-line', 'sole-pressing', 'heat-activation', 'cementing', 'outsole-tooling', 'injection-unit', 'cupsole-cementing', 'cement-spread-weight', 'outsole-priming-sequence', 'sidewall-wrap-tension', 'cemented-construction', 'blake-stitch', 'filler-layer', 'board-lasted-construction', 'waist-zone', 'stacked-heel', 'filler-cork', 'waist-shaping', 'internal-footbed-stack', 'channel-stitching', 'holdfast-stitch'] as $key) {
        $add($key, 'bottoming', $key === 'bottoming' ? 'related' : 'broader');
    }
    foreach (['upper', 'vamp', 'quarter', 'lining', 'lace-stay', 'tongue-gusset', 'collar-foam', 'eyelet-setting', 'seam-sealing-tape', 'toe-cap', 'throat-line', 'saddle-stitch', 'inseam-stitch', 'topline-reinforcement', 'feather-line'] as $key) {
        $add($key, 'upper-assembly', $key === 'upper-assembly' ? 'related' : 'broader');
    }
    foreach (['pull-strength-test', 'peel-strength-test', 'flex-test', 'pair-matching', 'defect-mapping', 'bond-line', 'heel-seat-nailing-pattern', 'toe-lasting-pincer-pressure', 'goodyear-welt', 'heel-counter-reinforcement', 'sockliner', 'welt-channel', 'channel-stitching', 'holdfast-stitch', 'welt-stitch-penetration', 'shank-reinforcement'] as $key) {
        $add($key, 'quality-checkpoint', $key === 'quality-checkpoint' ? 'related' : 'broader');
    }
    foreach (['cement-open-time', 'adhesive-viscosity', 'primer-dry-time', 'press-pressure-map', 'outsole-cure-window', 'bond-peel-audit', 'open-time-drift'] as $key) {
        $add($key, 'cementing', 'related');
        $add($key, 'quality-checkpoint', 'broader');
    }
    foreach (['peel-signature-taxonomy', 'thermal-aging-behavior', 'failure-mode-clustering', 'accelerated-aging-protocol', 'thermal-shock-cycle', 'peel-strength-test'] as $key) {
        $add($key, 'quality-checkpoint', 'related');
        $add($key, 'bond-peel-audit', 'related');
    }
    foreach (['heat-activation', 'sole-pressing', 'bond-line', 'outsole-cure-window'] as $key) {
        $add($key, 'thermal-aging-behavior', 'related');
        $add($key, 'open-time-drift', 'related');
    }
    foreach (['upper-tension-profile', 'toe-spring-calibration', 'heel-slip-evaluation', 'vamp-centerline', 'quarter-balance'] as $key) {
        $add($key, 'lasting', 'related');
    }
    foreach (['strobel-tension-control', 'insole-rib-height', 'shank-positioning', 'heel-seat-leveling'] as $key) {
        $add($key, 'bottoming', 'related');
    }
    foreach (['flex-groove-alignment', 'sidewall-trimming', 'edge-ink-build'] as $key) {
        $add($key, 'outsole', 'related');
    }
    foreach (['lace-hole-punching', 'eyelet-flange-crack'] as $key) {
        $add($key, 'eyelet-setting', 'related');
        $add($key, 'quality-checkpoint', 'broader');
    }

    // Key synonyms and paired terms.
    $add('clicking', 'die-cutting', 'synonym');
    $add('die-cutting', 'clicking', 'synonym');
    $add('skiving', 'feather-edge', 'related');
    $add('toe-puff', 'toe-box', 'related');
    $add('heel-counter', 'counter-molding', 'related');
    $add('welt', 'channeling', 'related');
    $add('strobel-stitch', 'strobel-board', 'related');
    $add('primer-coat', 'cementing', 'related');
    $add('roughing', 'cementing', 'related');
    $add('buffing', 'edge-folding', 'related');
    $add('upper', 'vamp', 'related');
    $add('upper', 'quarter', 'related');
    $add('upper', 'lining', 'related');
    $add('vamp', 'toe-puff', 'related');
    $add('quarter', 'heel-counter', 'related');
    $add('heel-counter', 'heel-seat', 'related');
    $add('shoe-last', 'ball-girth', 'related');
    $add('shoe-last', 'toe-spring', 'related');
    $add('shoe-last', 'vamp-break', 'related');
    $add('insole', 'strobel-board', 'related');
    $add('insole-board', 'rib-attaching', 'related');
    $add('welt', 'channeling', 'narrower');
    $add('outsole', 'rand', 'related');
    $add('outsole', 'foxing', 'related');
    $add('vamp', 'upper', 'broader');
    $add('quarter', 'upper', 'broader');
    $add('lining', 'upper', 'broader');
    $add('toe-puff', 'upper', 'broader');
    $add('heel-counter', 'upper', 'broader');
    $add('foxing', 'upper', 'broader');
    $add('midsole', 'shank', 'related');
    $add('midsole', 'outsole', 'related');
    $add('insole', 'midsole', 'related');
    $add('heel-seat', 'shank', 'related');
    $add('heel-seat', 'sole-pressing', 'related');
    $add('heat-activation', 'cement-spread-weight', 'related');
    $add('sole-pressing', 'bond-line', 'related');
    $add('bond-line', 'bond-peel-audit', 'related');

    // Curated process-flow links (replace generic sequential chain).
    foreach ([
        ['clicking', 'upper-assembly'],
        ['upper-assembly', 'lasting'],
        ['lasting', 'toe-lasting'],
        ['lasting', 'bottoming'],
        ['strobel-stitch', 'bottoming'],
        ['insole-board', 'rib-attaching'],
        ['rib-attaching', 'channeling'],
        ['channeling', 'gemming-rib'],
        ['roughing', 'primer-coat'],
        ['primer-coat', 'cementing'],
        ['cementing', 'heat-activation'],
        ['heat-activation', 'sole-pressing'],
        ['sole-pressing', 'outsole-cure-window'],
        ['outsole-cure-window', 'bond-peel-audit'],
        ['bond-peel-audit', 'failure-mode-clustering'],
        ['toe-puff', 'toe-puff-activation-curve'],
        ['toe-puff-activation-curve', 'toe-lasting'],
        ['toe-lasting', 'side-lasting'],
        ['side-lasting', 'back-part-lasting'],
        ['side-lasting', 'seat-lasting'],
        ['back-part-lasting', 'seat-lasting'],
        ['seat-lasting', 'roughing'],
        ['seat-lasting', 'rib-attaching'],
        ['seat-lasting', 'bottoming'],
        ['seat-lasting', 'goodyear-welt'],
        ['seat-lasting', 'blake-stitch'],
        ['seat-lasting', 'stitchdown-construction'],
        ['seat-lasting', 'cemented-construction'],
        ['strobel-board', 'strobel-stitch'],
        ['skiving', 'feather-edge'],
        ['feather-edge', 'edge-folding'],
        ['feather-edge', 'lasting'],
        ['strobel-stitch', 'lasting'],
        ['heel-counter-reinforcement', 'counter-molding'],
        ['counter-molding', 'seat-lasting'],
        ['gemming-rib', 'holdfast-stitch'],
        ['holdfast-stitch', 'filler-cork'],
        ['filler-cork', 'waist-shaping'],
        ['waist-shaping', 'channel-stitching'],
        ['channel-stitching', 'outsole'],
        ['channel-stitching', 'sidewall-trimming'],
        ['sole-pressing', 'sidewall-trimming'],
        ['sidewall-trimming', 'edge-ink-build'],
        ['blake-stitch', 'sole-pressing'],
        ['outsole', 'sole-pressing'],
        ['heel-counter', 'counter-edge-skive'],
        ['counter-edge-skive', 'counter-molding'],
        ['heel-seat', 'heel-seat-contour-match'],
        ['heel-seat-contour-match', 'heel-seat-leveling'],
        ['roughing', 'roughing-depth-profile'],
        ['outsole', 'outsole-surface-energy'],
        ['primer-dry-time', 'primer-reactivation-window'],
        ['cement-open-time', 'adhesive-transfer-latency'],
        ['outsole-cure-window', 'cure-gradient-mapping'],
        ['bond-line', 'bond-line-void-detection'],
        ['stitchdown-construction', 'bottoming'],
        ['stitchdown-construction', 'lockstitch-seam'],
        ['cupsole-cementing', 'sole-pressing'],
        ['back-part-lasting', 'counter-molding'],
        ['back-part-lasting', 'seat-lasting'],
        ['cemented-construction', 'roughing'],
        ['outsole-priming-sequence', 'primer-coat'],
        ['cement-spread-weight', 'cementing'],
        ['foxing-tape-application', 'sidewall-trimming'],
        ['sidewall-wrap-tension', 'sidewall-trimming'],
        ['toe-lasting-pincer-pressure', 'toe-lasting'],
        ['lasting-board', 'lasting'],
        ['seam-sealing-tape', 'upper-assembly'],
        ['heel-seat-nailing-pattern', 'heel-seat-leveling'],
        ['throat-line', 'lace-stay'],
        ['toe-cap', 'upper-assembly'],
        ['lasting-margin', 'lasting'],
        ['heel-counter-reinforcement', 'counter-molding'],
        ['cemented-construction', 'cementing'],
        ['board-lasted-construction', 'lasting-board'],
        ['filler-layer', 'midsole'],
        ['waist-zone', 'shank'],
        ['goodyear-welt', 'welt'],
        ['blake-stitch', 'bottoming'],
        ['sockliner', 'insole'],
        ['saddle-stitch', 'upper-assembly'],
        ['tuck', 'lasting-tuck'],
        ['inseam-stitch', 'upper-assembly'],
        ['inseam-stitch', 'lining'],
        ['channeling', 'welt-channel'],
        ['welt-channel', 'channel-stitching'],
        ['gemming-rib', 'holdfast-stitch'],
        ['holdfast-stitch', 'goodyear-welt'],
        ['holdfast-stitch', 'welt-stitch-penetration'],
        ['channel-stitching', 'welt-stitch-penetration'],
        ['filler-cork', 'goodyear-welt'],
        ['filler-cork', 'waist-shaping'],
        ['lasting-tuck', 'lasting-margin'],
        ['lasting-tuck', 'lasting-allowance'],
        ['shank', 'shank-reinforcement'],
        ['shank-reinforcement', 'waist-shaping'],
        ['waist-zone', 'waist-shaping'],
        ['side-lasting', 'waist-shaping'],
        ['seat-lasting', 'heel-seat-leveling'],
        ['internal-footbed-stack', 'sockliner'],
        ['internal-footbed-stack', 'insole-board'],
        ['topline-reinforcement', 'heel-counter-reinforcement'],
        ['feather-line', 'feather-edge'],
        ['feather-line', 'topline-reinforcement'],
        ['skiving', 'feather-line'],
    ] as [$from, $to]) {
        $add($from, $to, 'related');
    }

    foreach ([
        'adhesive-transfer-latency',
        'primer-reactivation-window',
        'roughing-depth-profile',
        'outsole-surface-energy',
        'toe-puff-activation-curve',
        'counter-edge-skive',
        'heel-seat-contour-match',
        'cure-gradient-mapping',
        'bond-line-void-detection',
    ] as $key) {
        $add($key, 'quality-checkpoint', 'broader');
    }

    $add('adhesive-transfer-latency', 'open-time-drift', 'related');
    $add('adhesive-transfer-latency', 'cement-open-time', 'related');
    $add('primer-reactivation-window', 'primer-dry-time', 'related');
    $add('primer-reactivation-window', 'heat-activation', 'related');
    $add('cementing', 'heat-tunnel', 'related');
    $add('heat-activation', 'heat-tunnel', 'related');
    $add('roughing-depth-profile', 'roughing', 'related');
    $add('outsole-surface-energy', 'primer-coat', 'related');
    $add('outsole-surface-energy', 'cementing', 'related');
    $add('toe-puff-activation-curve', 'toe-puff', 'related');
    $add('counter-edge-skive', 'heel-counter', 'related');
    $add('heel-seat-contour-match', 'heel-seat', 'related');
    $add('heel-seat-contour-match', 'heel-slip-evaluation', 'related');
    $add('cure-gradient-mapping', 'outsole-cure-window', 'related');
    $add('cure-gradient-mapping', 'thermal-aging-behavior', 'related');
    $add('bond-line-void-detection', 'bond-line', 'related');
    $add('bond-line-void-detection', 'peel-signature-taxonomy', 'related');
    $add('stitchdown-construction', 'welt', 'related');
    $add('stitchdown-construction', 'lockstitch-seam', 'related');
    $add('cupsole-cementing', 'outsole-cure-window', 'related');
    $add('foxing-tape-application', 'foxing', 'related');
    $add('seam-sealing-tape', 'tongue-gusset', 'related');
    $add('heel-seat-nailing-pattern', 'stacked-heel', 'related');
    $add('cement-spread-weight', 'adhesive-viscosity', 'related');
    $add('sidewall-wrap-tension', 'rand', 'related');
    $add('outsole-priming-sequence', 'outsole-surface-energy', 'related');
    $add('lasting-board', 'insole-board', 'related');
    $add('saddle-stitch', 'lockstitch-seam', 'related');
    $add('saddle-stitch', 'upper-assembly', 'related');
    $add('blake-stitch', 'cemented-construction', 'related');
    $add('blake-stitch', 'insole', 'related');
    $add('blake-stitch', 'outsole', 'related');
    $add('goodyear-welt', 'welt', 'broader');
    $add('goodyear-welt', 'channeling', 'related');
    $add('goodyear-welt', 'gemming-rib', 'related');
    $add('goodyear-welt', 'outsole', 'related');
    $add('gemming-rib', 'rib-attaching', 'related');
    $add('gemming-rib', 'insole-board', 'related');
    $add('sockliner', 'insole', 'broader');
    $add('sockliner', 'lining', 'related');
    $add('toe-cap', 'toe-puff', 'related');
    $add('toe-cap', 'upper', 'broader');
    $add('lasting-margin', 'lasting-allowance', 'related');
    $add('lasting-margin', 'lasting', 'broader');
    $add('lasting-margin', 'toe-lasting', 'related');
    $add('heel-counter-reinforcement', 'heel-counter', 'broader');
    $add('heel-counter-reinforcement', 'counter-molding', 'related');
    $add('filler-layer', 'midsole', 'related');
    $add('filler-layer', 'outsole', 'related');
    $add('waist-zone', 'shank', 'related');
    $add('waist-zone', 'flex-groove-alignment', 'related');
    $add('throat-line', 'vamp', 'related');
    $add('throat-line', 'lace-stay', 'related');
    $add('throat-line', 'quarter', 'related');
    $add('cemented-construction', 'cementing', 'broader');
    $add('cemented-construction', 'cupsole-cementing', 'related');
    $add('feather-edge', 'edge-folding', 'related');
    $add('feather-edge', 'upper', 'related');
    $add('board-lasted-construction', 'insole-board', 'related');
    $add('board-lasted-construction', 'lasting-board', 'related');
    $add('board-lasted-construction', 'lasting', 'related');
    $add('stacked-heel', 'heel-seat', 'related');
    $add('stacked-heel', 'shank', 'related');
    $add('stacked-heel', 'heel-seat-leveling', 'related');

    // Wave C: second-order stitching, internal structure, and bottoming micro-variant links.
    $add('welt-channel', 'channeling', 'broader');
    $add('welt-channel', 'insole-board', 'related');
    $add('welt-channel', 'insole-rib-height', 'related');
    $add('welt-channel', 'goodyear-welt', 'related');
    $add('channel-stitching', 'welt-channel', 'related');
    $add('channel-stitching', 'welt', 'related');
    $add('channel-stitching', 'lockstitch-seam', 'related');
    $add('holdfast-stitch', 'gemming-rib', 'related');
    $add('holdfast-stitch', 'welt', 'related');
    $add('holdfast-stitch', 'rib-attaching', 'related');
    $add('welt-stitch-penetration', 'goodyear-welt', 'related');
    $add('welt-stitch-penetration', 'welt', 'related');
    $add('welt-stitch-penetration', 'holdfast-stitch', 'related');
    $add('inseam-stitch', 'lockstitch-seam', 'related');
    $add('inseam-stitch', 'throat-line', 'related');
    $add('filler-cork', 'filler-layer', 'related');
    $add('filler-cork', 'shank', 'related');
    $add('lasting-tuck', 'toe-lasting', 'related');
    $add('lasting-tuck', 'side-lasting', 'related');
    $add('tuck', 'lasting-tuck', 'synonym');
    $add('lasting-tuck', 'tuck', 'synonym');
    $add('tuck', 'lasting-margin', 'related');
    $add('tuck', 'side-lasting', 'related');
    $add('shank-reinforcement', 'shank-positioning', 'related');
    $add('shank-reinforcement', 'midsole', 'related');
    $add('topline-reinforcement', 'heel-counter', 'related');
    $add('topline-reinforcement', 'feather-edge', 'related');
    $add('topline-reinforcement', 'counter-edge-skive', 'related');
    $add('waist-shaping', 'edge-ink-build', 'related');
    $add('waist-shaping', 'stacked-heel', 'related');
    $add('internal-footbed-stack', 'insole', 'broader');
    $add('internal-footbed-stack', 'shank-positioning', 'related');
    $add('internal-footbed-stack', 'board-lasted-construction', 'related');
    $add('sockliner', 'internal-footbed-stack', 'related');
    $add('gemming-rib', 'welt-channel', 'related');
    $add('feather-line', 'feather-edge', 'industry_variant');
    $add('feather-edge', 'feather-line', 'industry_variant');
    $add('goodyear-welt', 'welt-channel', 'related');
    $add('goodyear-welt', 'holdfast-stitch', 'related');
    $add('goodyear-welt', 'filler-cork', 'related');
    $add('heel-counter-reinforcement', 'topline-reinforcement', 'related');
    $add('side-lasting', 'lasting-tuck', 'related');
    $add('seat-lasting', 'lasting-tuck', 'related');
    $add('lasting-allowance', 'lasting-tuck', 'related');
    $add('side-lasting', 'toe-lasting', 'related');
    $add('seat-lasting', 'side-lasting', 'related');
    $add('seat-lasting', 'heel-seat-leveling', 'related');
    $add('feather-edge', 'skiving', 'related');
    $add('feather-edge', 'lasting', 'related');
    $add('strobel-stitch', 'lasting', 'related');
    $add('strobel-stitch', 'strobel-board', 'related');
    $add('strobel-stitch', 'side-lasting', 'related');
    $add('strobel-stitch', 'seat-lasting', 'related');
    $add('sole-pressing', 'outsole', 'related');
    $add('sole-pressing', 'heat-activation', 'related');
    $add('goodyear-welt', 'seat-lasting', 'related');
    $add('goodyear-welt', 'rib-attaching', 'related');
    $add('goodyear-welt', 'channel-stitching', 'related');
    $add('blake-stitch', 'lasting', 'related');
    $add('blake-stitch', 'side-lasting', 'related');
    $add('blake-stitch', 'seat-lasting', 'related');
    $add('cemented-construction', 'seat-lasting', 'related');
    $add('cemented-construction', 'heat-activation', 'related');
    $add('stitchdown-construction', 'seat-lasting', 'related');
    $add('stitchdown-construction', 'lasting-tuck', 'related');
    $add('board-lasted-construction', 'back-part-lasting', 'related');
    $add('cupsole-cementing', 'seat-lasting', 'related');
    $add('cupsole-cementing', 'heat-activation', 'related');
    $add('shank-reinforcement', 'internal-footbed-stack', 'related');
    $add('heel-counter-reinforcement', 'seat-lasting', 'related');

    // Manufacturing route intelligence — comparative navigation (decision forks, not generic tables).
    foreach ([
        ['goodyear-welt', 'blake-stitch'],
        ['blake-stitch', 'goodyear-welt'],
        ['strobel-stitch', 'board-lasted-construction'],
        ['board-lasted-construction', 'strobel-stitch'],
        ['cemented-construction', 'stitchdown-construction'],
        ['stitchdown-construction', 'cemented-construction'],
        ['cemented-construction', 'cupsole-cementing'],
        ['cupsole-cementing', 'cemented-construction'],
        ['board-lasted-construction', 'cemented-construction'],
        ['board-lasted-construction', 'goodyear-welt'],
        ['goodyear-welt', 'cemented-construction'],
        ['cemented-construction', 'goodyear-welt'],
        ['goodyear-welt', 'stitchdown-construction'],
        ['stitchdown-construction', 'goodyear-welt'],
        ['back-part-lasting', 'seat-lasting'],
        ['side-lasting', 'back-part-lasting'],
        ['cemented-construction', 'strobel-stitch'],
        ['strobel-stitch', 'cemented-construction'],
        ['blake-stitch', 'board-lasted-construction'],
        ['board-lasted-construction', 'blake-stitch'],
    ] as [$from, $to]) {
        $add($from, $to, 'related');
    }

    // Workflow chain sequencing — forward operational order (navigation only; process-flow array is canonical).
    foreach ([
        ['strobel-board', 'strobel-stitch'],
        ['strobel-stitch', 'lasting'],
        ['toe-lasting', 'side-lasting'],
        ['side-lasting', 'back-part-lasting'],
        ['back-part-lasting', 'seat-lasting'],
        ['seat-lasting', 'roughing'],
        ['seat-lasting', 'rib-attaching'],
        ['rib-attaching', 'channeling'],
        ['holdfast-stitch', 'channel-stitching'],
        ['channel-stitching', 'outsole'],
        ['roughing', 'primer-coat'],
        ['primer-coat', 'cementing'],
        ['cementing', 'heat-activation'],
        ['heat-activation', 'sole-pressing'],
    ] as [$from, $to]) {
        $add($from, $to, 'related');
    }

    // Deduplicate relation tuples.
    $seen = [];
    $relations = [];
    foreach ($relationList as $rel) {
        $k = $rel['from'].'|'.$rel['to'].'|'.$rel['type'];
        if (isset($seen[$k])) {
            continue;
        }
        $seen[$k] = true;
        $relations[] = $rel;
    }

    return [
        'concepts' => $concepts,
        'relations' => $relations,
    ];
})();
