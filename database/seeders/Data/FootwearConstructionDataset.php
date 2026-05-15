<?php

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

    $rows = [
        ['key' => 'upper-assembly', 'terms' => ['en' => 'Upper assembly', 'pt' => 'Fecho do cabedal', 'fr' => 'Assemblage de tige', 'de' => 'Schaftmontage', 'it' => 'Assemblaggio tomaia', 'es' => 'Ensamblaje del corte'], 'focus' => 'assemble upper components into a stable pre-lasting shell', 'stage' => 'closing and pre-lasting handoff', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'bottoming', 'terms' => ['en' => 'Bottoming', 'pt' => 'Montagem de fundo', 'fr' => 'Montage de fond', 'de' => 'Bottoming', 'it' => 'Montaggio fondo', 'es' => 'Montaje de fondo'], 'focus' => 'attach and finish sole structures after lasting', 'stage' => 'bottoming cell and final assembly', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'quality-checkpoint', 'terms' => ['en' => 'Quality checkpoint', 'pt' => 'Ponto de controlo de qualidade', 'fr' => 'Point de contrôle qualité', 'de' => 'Qualitäts-Prüfpunkt', 'it' => 'Punto controllo qualità', 'es' => 'Punto de control de calidad'], 'focus' => 'validate workmanship before defects propagate downstream', 'stage' => 'inline verification and release gates', 'domains' => ['footwear-construction', 'production', 'quality-control']],

        ['key' => 'lasting', 'terms' => ['en' => 'Lasting', 'pt' => 'Moldação', 'fr' => 'Montage sur forme', 'de' => 'Aufziehen', 'it' => 'Montaggio su forma', 'es' => 'Montado en horma'], 'focus' => 'pull and stabilize the upper over the last geometry', 'stage' => 'lasting line', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'shoe-last', 'terms' => ['en' => 'Shoe last', 'pt' => 'Forma de calçado', 'fr' => 'Forme chaussure', 'de' => 'Schuhleisten', 'it' => 'Forma calzaturiera', 'es' => 'Horma de calzado'], 'focus' => 'define volume, girth, toe spring, and heel pitch targets', 'stage' => 'design to pattern engineering transfer', 'domains' => ['footwear-construction', 'footwear', 'design', 'pattern-making'], 'featured' => true],
        ['key' => 'upper', 'terms' => ['en' => 'Upper', 'pt' => 'Cabedal', 'fr' => 'Tige', 'de' => 'Schaft', 'it' => 'Tomaia', 'es' => 'Corte'], 'focus' => 'form the visible and functional shell above the sole package', 'stage' => 'cutting and closing operations', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'vamp', 'terms' => ['en' => 'Vamp', 'pt' => 'Vampão', 'fr' => 'Claque', 'de' => 'Vorderblatt', 'it' => 'Puntina tomaia', 'es' => 'Empeine delantero'], 'focus' => 'control forepart fit and flex-break location', 'stage' => 'pattern engineering and closing', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'quarter', 'terms' => ['en' => 'Quarter', 'pt' => 'Quarto', 'fr' => 'Quartier', 'de' => 'Quartier', 'it' => 'Quartiere', 'es' => 'Cuarto'], 'focus' => 'stabilize heel and midfoot sections of the upper', 'stage' => 'upper closing and back-part preparation', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'lining', 'terms' => ['en' => 'Lining', 'pt' => 'Forro', 'fr' => 'Doublure', 'de' => 'Futter', 'it' => 'Fodera', 'es' => 'Forro'], 'focus' => 'manage internal comfort, moisture, and seam encapsulation', 'stage' => 'upper assembly and comfort package build', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'toe-puff', 'terms' => ['en' => 'Toe puff', 'pt' => 'Ponteira', 'fr' => 'Contrefort avant', 'de' => 'Zehenkappe-Verstärkung', 'it' => 'Puntale di rinforzo', 'es' => 'Refuerzo de puntera'], 'focus' => 'preserve toe shape and stiffness under wear and heat', 'stage' => 'reinforcement placement before lasting', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'heel-counter', 'terms' => ['en' => 'Heel counter', 'pt' => 'Contraforte', 'fr' => 'Contrefort arrière', 'de' => 'Fersenkappe', 'it' => 'Contrafforte', 'es' => 'Contrafuerte'], 'focus' => 'lock rearfoot structure and reduce heel collapse', 'stage' => 'back-part reinforcement and molding', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'counter-molding', 'terms' => ['en' => 'Counter molding', 'pt' => 'Moldação do contraforte', 'fr' => 'Moulage du contrefort', 'de' => 'Fersenkappen-Formung', 'it' => 'Stampaggio contrafforte', 'es' => 'Moldeado de contrafuerte'], 'focus' => 'thermoform and lock heel counter geometry to last seat', 'stage' => 'back-part setting', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'toe-box', 'terms' => ['en' => 'Toe box', 'pt' => 'Caixa de biqueira', 'fr' => 'Boîte à orteils', 'de' => 'Zehenbox', 'it' => 'Volume punta', 'es' => 'Caja de puntera'], 'focus' => 'define forepart clearance and shape retention under flex', 'stage' => 'fit engineering and wear validation', 'domains' => ['footwear-construction', 'footwear', 'design']],
        ['key' => 'foxing', 'terms' => ['en' => 'Foxing', 'pt' => 'Faixa lateral', 'fr' => 'Bande de renfort', 'de' => 'Foxing-Band', 'it' => 'Fascia laterale', 'es' => 'Banda lateral'], 'focus' => 'reinforce sidewall transition between upper and sole edge', 'stage' => 'sidewall reinforcement and finishing', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'welt', 'terms' => ['en' => 'Welt', 'pt' => 'Vira', 'fr' => 'Trépointe', 'de' => 'Rahmen', 'it' => 'Guardolo', 'es' => 'Cerco'], 'focus' => 'create a stitched interface between upper package and outsole', 'stage' => 'welt construction and resoling architecture', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'outsole', 'terms' => ['en' => 'Outsole', 'pt' => 'Sola exterior', 'fr' => 'Semelle extérieure', 'de' => 'Laufsohle', 'it' => 'Suola esterna', 'es' => 'Suela exterior'], 'focus' => 'deliver ground contact grip, abrasion resistance, and flex behavior', 'stage' => 'bottoming and durability tuning', 'domains' => ['footwear-construction', 'footwear', 'materials'], 'featured' => true],
        ['key' => 'midsole', 'terms' => ['en' => 'Midsole', 'pt' => 'Entressola', 'fr' => 'Semelle intermédiaire', 'de' => 'Zwischensohle', 'it' => 'Intersuola', 'es' => 'Entresuela'], 'focus' => 'manage cushioning stack and load transfer above outsole', 'stage' => 'stack design and bottoming assembly', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'insole', 'terms' => ['en' => 'Insole', 'pt' => 'Palmilha', 'fr' => 'Semelle intérieure', 'de' => 'Innensohle', 'it' => 'Soletta', 'es' => 'Plantilla'], 'focus' => 'support foot interface, comfort, and upper anchoring references', 'stage' => 'internal assembly and fit setup', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'shank', 'terms' => ['en' => 'Shank', 'pt' => 'Alma', 'fr' => 'Cambrion', 'de' => 'Gelenkfeder', 'it' => 'Cambrione', 'es' => 'Cambrillón'], 'focus' => 'stabilize waist geometry and heel-to-forefoot load path', 'stage' => 'bottoming structural reinforcement', 'domains' => ['footwear-construction', 'footwear', 'hardware']],
        ['key' => 'rand', 'terms' => ['en' => 'Rand', 'pt' => 'Biqueira lateral', 'fr' => 'Bande de protection', 'de' => 'Rand', 'it' => 'Rand', 'es' => 'Rand'], 'focus' => 'protect upper edge and improve abrasion resistance at perimeter', 'stage' => 'edge protection and sidewall bonding', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'heel-seat', 'terms' => ['en' => 'Heel seat', 'pt' => 'Assento do calcanhar', 'fr' => 'Assise talon', 'de' => 'Fersensitz', 'it' => 'Sede tallone', 'es' => 'Asiento de talón'], 'focus' => 'control rearfoot seating geometry and slip performance', 'stage' => 'fit tuning and lasting reference setting', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'toe-spring', 'terms' => ['en' => 'Toe spring', 'pt' => 'Elevação da biqueira', 'fr' => 'Relevé de pointe', 'de' => 'Zehenfeder', 'it' => 'Alzata punta', 'es' => 'Elevación de puntera'], 'focus' => 'set forepart rocker and gait transition behavior', 'stage' => 'last design and outsole tooling alignment', 'domains' => ['footwear-construction', 'footwear', 'design']],
        ['key' => 'ball-girth', 'terms' => ['en' => 'Ball girth', 'pt' => 'Perímetro metatarsal', 'fr' => 'Périmètre métatarsien', 'de' => 'Ballenumfang', 'it' => 'Circonferenza metatarsale', 'es' => 'Perímetro metatarsal'], 'focus' => 'control fit grading around the forefoot load zone', 'stage' => 'last engineering and grading validation', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'vamp-break', 'terms' => ['en' => 'Vamp break', 'pt' => 'Linha de quebra do vampão', 'fr' => 'Ligne de cassure claque', 'de' => 'Vorderblatt-Knicklinie', 'it' => 'Linea piega tomaia', 'es' => 'Línea de quiebre del empeine'], 'focus' => 'position natural flex line without visual collapse', 'stage' => 'pattern development and wear test loop', 'domains' => ['footwear-construction', 'footwear', 'design']],
        ['key' => 'lasting-allowance', 'terms' => ['en' => 'Lasting allowance', 'pt' => 'Folga de moldação', 'fr' => 'Marge de montage', 'de' => 'Aufziehzugabe', 'it' => 'Margine di montaggio', 'es' => 'Margen de montado'], 'focus' => 'reserve material for controlled pull during lasting', 'stage' => 'pattern engineering for lasting operations', 'domains' => ['footwear-construction', 'footwear', 'pattern-making']],
        ['key' => 'side-lasting', 'terms' => ['en' => 'Side lasting', 'pt' => 'Moldação lateral', 'fr' => 'Montage latéral', 'de' => 'Seitliches Aufziehen', 'it' => 'Montaggio laterale', 'es' => 'Montado lateral'], 'focus' => 'stabilize lateral and medial tension around waist curves', 'stage' => 'lasting sequence control', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'toe-lasting', 'terms' => ['en' => 'Toe lasting', 'pt' => 'Moldação da biqueira', 'fr' => 'Montage de pointe', 'de' => 'Spitzenaufziehen', 'it' => 'Montaggio punta', 'es' => 'Montado de puntera'], 'focus' => 'shape forepart volume while protecting toe reinforcement', 'stage' => 'toe station in lasting line', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'back-part-lasting', 'terms' => ['en' => 'Back-part lasting', 'pt' => 'Moldação traseira', 'fr' => 'Montage arrière', 'de' => 'Hinterkappen-Aufziehen', 'it' => 'Montaggio posteriore', 'es' => 'Montado trasero'], 'focus' => 'set heel wrap tension and seat alignment', 'stage' => 'rearfoot lasting sequence', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'seat-lasting', 'terms' => ['en' => 'Seat lasting', 'pt' => 'Moldação do assento', 'fr' => 'Montage de siège', 'de' => 'Sitzaufziehen', 'it' => 'Montaggio sede', 'es' => 'Montado de asiento'], 'focus' => 'secure heel seat margin before sole build', 'stage' => 'heel-seat lasting station', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'lasting-pincher', 'terms' => ['en' => 'Lasting pincher', 'pt' => 'Pinça de moldação', 'fr' => 'Pince de montage', 'de' => 'Aufziehzange', 'it' => 'Pinza di montaggio', 'es' => 'Pinza de montado'], 'focus' => 'apply directional pull without damaging upper grain', 'stage' => 'lasting machine setup and operation', 'domains' => ['footwear-construction', 'footwear', 'machinery']],
        ['key' => 'lasting-tack', 'terms' => ['en' => 'Lasting tack', 'pt' => 'Prego de moldação', 'fr' => 'Pointe de montage', 'de' => 'Aufziehstift', 'it' => 'Chiodo di montaggio', 'es' => 'Clavo de montado'], 'focus' => 'temporarily lock upper margin before permanent bonding', 'stage' => 'mechanical fixation in lasting', 'domains' => ['footwear-construction', 'footwear', 'production']],

        ['key' => 'strobel-stitch', 'terms' => ['en' => 'Strobel stitch', 'pt' => 'Costura Strobel', 'fr' => 'Couture Strobel', 'de' => 'Strobelnaht', 'it' => 'Cucitura Strobel', 'es' => 'Costura Strobel'], 'focus' => 'join upper margin to strobel board with flexible seam architecture', 'stage' => 'strobel closing line', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'strobel-board', 'terms' => ['en' => 'Strobel board', 'pt' => 'Base Strobel', 'fr' => 'Semelle Strobel', 'de' => 'Strobel-Basis', 'it' => 'Base Strobel', 'es' => 'Base Strobel'], 'focus' => 'provide lightweight internal foundation in strobel constructions', 'stage' => 'strobel preparation and closing', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'insole-board', 'terms' => ['en' => 'Insole board', 'pt' => 'Cartão de palmilha', 'fr' => 'Première carton', 'de' => 'Brandsohlenplatte', 'it' => 'Sottopiede in cartone', 'es' => 'Cartón de plantilla'], 'focus' => 'provide lasting anchor and structural interface under upper', 'stage' => 'insole preparation before lasting', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'rib-attaching', 'terms' => ['en' => 'Rib attaching', 'pt' => 'Aplicação de nervura', 'fr' => 'Pose de nervure', 'de' => 'Rippenanbringung', 'it' => 'Applicazione nervatura', 'es' => 'Aplicación de nervio'], 'focus' => 'bond rib perimeter to insole board for welt channels', 'stage' => 'insole rib setup for stitched constructions', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'channeling', 'terms' => ['en' => 'Channeling', 'pt' => 'Canal de costura', 'fr' => 'Canalisation de couture', 'de' => 'Nahtkanalierung', 'it' => 'Canalizzazione', 'es' => 'Canal de costura'], 'focus' => 'prepare hidden stitch paths and clean seam sink', 'stage' => 'sole and welt preparation', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'lockstitch-seam', 'terms' => ['en' => 'Lockstitch seam', 'pt' => 'Costura lockstitch', 'fr' => 'Couture point noué', 'de' => 'Doppelsteppnaht', 'it' => 'Cucitura lockstitch', 'es' => 'Costura lockstitch'], 'focus' => 'secure upper joins with controlled thread balance', 'stage' => 'closing machine operation', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'edge-folding', 'terms' => ['en' => 'Edge folding', 'pt' => 'Dobra de bordo', 'fr' => 'Rabat de bord', 'de' => 'Kantenumschlag', 'it' => 'Ripiegatura bordo', 'es' => 'Doblado de canto'], 'focus' => 'hide cut edges and stabilize visible upper lines', 'stage' => 'upper finishing before assembly', 'domains' => ['footwear-construction', 'footwear', 'finishing']],
        ['key' => 'skiving', 'terms' => ['en' => 'Skiving', 'pt' => 'Rebaixamento', 'fr' => 'Parage', 'de' => 'Schärfen', 'it' => 'Scarnitura', 'es' => 'Rebajado'], 'focus' => 'reduce edge thickness before folding, stitching, or lasting', 'stage' => 'cutting room and closing prep', 'domains' => ['footwear-construction', 'footwear', 'leather']],
        ['key' => 'feather-edge', 'terms' => ['en' => 'Feather edge', 'pt' => 'Bordo em pena', 'fr' => 'Bord aminci', 'de' => 'Federkante', 'it' => 'Bordo a piuma', 'es' => 'Borde en pluma'], 'focus' => 'achieve tapered transition with no hard edge telegraphing', 'stage' => 'post-skiving quality control', 'domains' => ['footwear-construction', 'footwear', 'finishing']],
        ['key' => 'roughing', 'terms' => ['en' => 'Roughing', 'pt' => 'Rugosagem', 'fr' => 'Rugosification', 'de' => 'Aufrauen', 'it' => 'Rugosatura', 'es' => 'Rugosado'], 'focus' => 'prepare bonding surfaces for adhesive keying', 'stage' => 'pre-cementing preparation', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'buffing', 'terms' => ['en' => 'Buffing', 'pt' => 'Lixagem', 'fr' => 'Ponçage', 'de' => 'Schleifen', 'it' => 'Levigatura', 'es' => 'Lijado'], 'focus' => 'control surface finish and edge uniformity before final coating', 'stage' => 'finishing and pre-bond cleanup', 'domains' => ['footwear-construction', 'footwear', 'finishing']],
        ['key' => 'primer-coat', 'terms' => ['en' => 'Primer coat', 'pt' => 'Primário de colagem', 'fr' => 'Primaire d’adhésion', 'de' => 'Primerauftrag', 'it' => 'Primer adesivo', 'es' => 'Imprimación de adhesión'], 'focus' => 'stabilize low-energy surfaces before adhesive application', 'stage' => 'chemical preparation in bottoming', 'domains' => ['footwear-construction', 'footwear', 'materials']],
        ['key' => 'cementing', 'terms' => ['en' => 'Cementing', 'pt' => 'Colagem', 'fr' => 'Cimentation', 'de' => 'Verklebung', 'it' => 'Incollaggio', 'es' => 'Pegado'], 'focus' => 'bond upper and sole interfaces within open-time windows', 'stage' => 'bottoming adhesive cell', 'domains' => ['footwear-construction', 'footwear', 'production'], 'featured' => true],
        ['key' => 'heat-activation', 'terms' => ['en' => 'Heat activation', 'pt' => 'Ativação térmica', 'fr' => 'Activation thermique', 'de' => 'Wärmeaktivierung', 'it' => 'Attivazione termica', 'es' => 'Activación térmica'], 'focus' => 'reactivate adhesive films at controlled temperature and dwell', 'stage' => 'pre-press tunnel and infrared station', 'domains' => ['footwear-construction', 'footwear', 'production']],
        ['key' => 'sole-pressing', 'terms' => ['en' => 'Sole pressing', 'pt' => 'Prensagem da sola', 'fr' => 'Pressage semelle', 'de' => 'Sohlenpressung', 'it' => 'Pressatura suola', 'es' => 'Prensado de suela'], 'focus' => 'consolidate bond-line pressure around full perimeter', 'stage' => 'post-activation sole attachment', 'domains' => ['footwear-construction', 'footwear', 'production']],
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
    ];

    $concepts = [];
    foreach ($rows as $row) {
        $translations = [];
        foreach ($locales as $locale) {
            $term = $row['terms'][$locale] ?? $row['terms']['en'];
            $short = strtr($shortTemplates[$locale], [
                ':term' => $term,
                ':focus' => $row['focus'],
            ]);
            $full = strtr($fullTemplates[$locale], [
                ':term' => $term,
                ':stage' => $row['stage'],
            ]);
            $example = strtr($exampleTemplates[$locale], [
                ':term' => $term,
            ]);

            $slug = $locale === 'en'
                ? $row['key']
                : Str::slug($term).'-'.$locale;

            $translations[$locale] = [
                'term' => $term,
                'slug' => $slug,
                'short_definition' => $short,
                'full_definition' => $full,
                'seo_title' => $term.' | Footwear construction',
                'seo_description' => $short,
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
    foreach (['lasting', 'side-lasting', 'toe-lasting', 'back-part-lasting', 'seat-lasting', 'lasting-pincher', 'lasting-tack', 'lasting-allowance'] as $key) {
        $add($key, 'lasting', $key === 'lasting' ? 'related' : 'broader');
    }
    foreach (['outsole', 'midsole', 'insole', 'shank', 'rand', 'bond-line', 'sole-pressing', 'heat-activation', 'cementing', 'outsole-tooling', 'injection-unit'] as $key) {
        $add($key, 'bottoming', $key === 'bottoming' ? 'related' : 'broader');
    }
    foreach (['upper', 'vamp', 'quarter', 'lining', 'lace-stay', 'tongue-gusset', 'collar-foam', 'eyelet-setting'] as $key) {
        $add($key, 'upper-assembly', $key === 'upper-assembly' ? 'related' : 'broader');
    }
    foreach (['pull-strength-test', 'peel-strength-test', 'flex-test', 'pair-matching', 'defect-mapping', 'bond-line'] as $key) {
        $add($key, 'quality-checkpoint', $key === 'quality-checkpoint' ? 'related' : 'broader');
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

    // Sequential relation chain for graph navigation.
    $keys = array_map(static fn (array $c): string => $c['key'], $concepts);
    for ($i = 0; $i < count($keys) - 1; $i++) {
        $add($keys[$i], $keys[$i + 1], 'related');
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
