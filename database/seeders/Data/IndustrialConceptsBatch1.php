<?php

/**
 * LexiCraft Glossary concepts (batch 1 of 3). Keys match relation endpoints.
 *
 * @return list<array<string, mixed>>
 */
return [
    [
        'key' => 'lasting',
        'featured' => true,
        'difficulty_level' => 'intermediate',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Lasting',
                'slug' => 'lasting',
                'short_definition' => 'Pulling the closed upper onto the last and anchoring it before bottoming so the shoe takes the intended volume and line.',
                'full_definition' => 'Lasting is the transition from soft upper to three-dimensional shoe: operators tension the upper over the last, position the margin, and tack or cement it before sole build-up. Pull varies by leather stretch, lining stack, and toe spring targets.',
                'seo_title' => 'Lasting — footwear production',
                'seo_description' => 'Definition of lasting in shoe factories: upper tensioning on the last before soling.',
                'examples' => [
                    ['example' => 'The lasting crew raised pull on the medial quarter after the strike-off showed shallow toe depth on size 42.', 'context' => 'lasting-line'],
                    ['example' => 'QA flagged a puckered throat because the vamp was not balanced before the side lasting pincer cycle.', 'context' => 'qc-inline'],
                ],
            ],
            'pt' => [
                'term' => 'Moldação',
                'slug' => 'moldacao',
                'short_definition' => 'Esticar o cabedal fechado sobre a forma e fixá-lo antes da solagem para obter o volume e o alinhamento desejados.',
                'full_definition' => 'A moldação transforma o cabedal plano em volume tridimensional: aplica-se tração controlada, alinha-se a bainha e fixa-se provisoriamente antes de colagem ou costura de sola. O binário de tração depende do estiramento do couro, do pacote de forros e do toe spring definido na ficha técnica.',
                'seo_title' => 'Moldação — calçado',
                'seo_description' => 'O que é moldação na indústria do calçado: tensão do cabedal na forma antes da solagem.',
                'examples' => [
                    ['example' => 'A linha de moldação aumentou a tração no quarto medial depois do strike-off mostrar pouco profundidade de biqueira no 42.', 'context' => 'linha-moldacao'],
                ],
            ],
            'fr' => [
                'term' => 'Montage sur forme',
                'slug' => 'montage-sur-forme',
                'short_definition' => 'Mise en tension de la tige fermée sur la forme avant pose semelle.',
                'full_definition' => 'Le montage sur forme fixe le volume du produit fini : marges, points de tension et attentes de bordage dépendent du cuir, des doublures et de la ligne stylistique.',
                'examples' => [['example' => 'Le chef de ligne a relevé une tension insuffisante au cou-de-pied après contrôle du prototype.', 'context' => 'atelier']],
            ],
            'de' => [
                'term' => 'Aufziehen (Lasting)',
                'slug' => 'aufziehen-lasting',
                'short_definition' => 'Das geschlossene Obermaterial wird auf die Leiste gespannt und vor dem Bottoming fixiert.',
                'full_definition' => 'Beim Aufziehen entsteht die 3D-Form des Schuhs: Zug, Kantenlage und Heftung müssen zur Leistenform, Materialdehnung und zum technischen Datenblatt passen.',
                'examples' => [['example' => 'Nach der Walkprüfung wurde der Zug am medialen Viertel erhöht.', 'context' => 'fertigung']],
            ],
            'it' => [
                'term' => 'Montaggio su forma',
                'slug' => 'montaggio-su-forma',
                'short_definition' => 'Tensione della tomaia chiusa sulla forma prima del fondo.',
                'full_definition' => 'Il montaggio su forma definisce volume e linea: dipende dall\'allungamento pelle, pacchetto fodere e tolleranze di punta.',
                'examples' => [['example' => 'Il capo linea ha rivisto il tiro dopo un campione troppo basso in punta.', 'context' => 'reparto']],
            ],
            'es' => [
                'term' => 'Montado en horma',
                'slug' => 'montado-en-horma',
                'short_definition' => 'Tensado del corte cerrado sobre la horma antes del solado.',
                'full_definition' => 'El montado fija el volumen tridimensional: márgenes, tensión y puntos de refuerzo dependen del cuero, forros y especificación de horma.',
                'examples' => [['example' => 'Ajustaron el tirón medial tras un prototipo bajo en puntera.', 'context' => 'planta']],
            ],
        ],
    ],
    [
        'key' => 'shoe-last',
        'featured' => true,
        'domains' => ['footwear', 'design', 'pattern-making'],
        'translations' => [
            'en' => [
                'term' => 'Shoe last',
                'slug' => 'shoe-last',
                'short_definition' => 'The three-dimensional form that defines inside length, girths, heel pitch, and toe spring for a size and width code.',
                'full_definition' => 'Lasts are the contract between design, pattern, and fit. Engineers pair last families to outsole tooling, board shapes, and flex zones; small edits at the joint line ripple through grading and costing.',
                'examples' => [
                    ['example' => 'Development switched from a sport last to a dress last to recover stack height under the ball line.', 'context' => 'development'],
                ],
            ],
            'pt' => [
                'term' => 'Forma de calçado',
                'slug' => 'forma-de-calcado',
                'short_definition' => 'Volume tridimensional que define comprimento interior, perímetros, inclinação do salto e toe spring por tamanho e largura.',
                'full_definition' => 'A forma é o contrato entre design, malha e conforto: alterações na linha de juntas afetam graduação, ferramental de sola e pacotes de reforço.',
                'examples' => [
                    ['example' => 'A engenharia alinhou a família de formas com o molde de sola injetada para evitar interferência na zona de flexão.', 'context' => 'engenharia'],
                ],
            ],
        ],
    ],
    [
        'key' => 'cementing',
        'domains' => ['footwear', 'production', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'Cementing',
                'slug' => 'cementing',
                'short_definition' => 'Bonding upper, filler stacks, and outsole with contact adhesives under controlled open time and press schedules.',
                'full_definition' => 'Cemented construction dominates athletic and casual lines. Technicians manage viscosity, activation temperature, and nip roller pressure; QA watches for solvent blush and bond peel on the featherline after heat tunnels.',
                'examples' => [
                    ['example' => 'The second cement pass was delayed because humidity in the stack room was above the adhesive supplier’s window.', 'context' => 'bonding-cell'],
                ],
            ],
            'pt' => [
                'term' => 'Colagem',
                'slug' => 'colagem-cementada',
                'short_definition' => 'União de cabedal, preenchimentos e solado com adesivos de contacto, com tempo de aberto e prensagem controlados.',
                'full_definition' => 'A construção colada é comum em desportivo e casual: controla-se viscosidade, temperatura de ativação e pressão de laminagem; o QC monitoriza embaciamento por solvente e descolagem na linha de bordo.',
                'examples' => [
                    ['example' => 'A segunda passagem de cola atrasou-se porque a humidade na sala de secagem estava acima da janela do fornecedor.', 'context' => 'celula-colagem'],
                ],
            ],
        ],
    ],
    [
        'key' => 'skiving',
        'featured' => true,
        'domains' => ['leather', 'machinery', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Skiving',
                'slug' => 'skiving',
                'short_definition' => 'Machine-thinning leather or synthetics at an edge or seam path to reduce bulk before folding or stitching.',
                'full_definition' => 'Skiving replaces hand paring on high-volume lines. Depth maps follow seam allowances and turn paths; inconsistent skive depth shows as telegraphing or weak tear strength at feather edges.',
                'examples' => [
                    ['example' => 'The auto-skiver was reset after caliper checks showed +0.2 mm drift on the quarter throat skive.', 'context' => 'cutting-room'],
                ],
            ],
            'pt' => [
                'term' => 'Rebaixamento',
                'slug' => 'rebaixamento',
                'short_definition' => 'Desbaste mecânico do couro ou sintético ao longo de bordos ou linhas de costura para reduzir volume antes da dobra ou costura.',
                'full_definition' => 'O rebaixamento substitui o desbaste manual em séries grandes. Mapas de profundidade seguem margens de costura e trajetos de dobra; variações criam marcas visíveis ou fragilidade ao rasgo na pena.',
                'examples' => [
                    ['example' => 'Reprogramaram o rebaixador automático após calibração mostrar +0,2 mm no rebaixo do peito de pé.', 'context' => 'corte'],
                ],
            ],
        ],
    ],
    [
        'key' => 'edge-beveling',
        'domains' => ['leather', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Edge beveling',
                'slug' => 'edge-beveling',
                'short_definition' => 'Angling the leather edge to a feather profile, often synonymous with controlled skiving on belts and upper margins.',
                'full_definition' => 'Edge beveling prepares the edge for folding, paint, or burnish. On belts it sets the shoulder line; on uppers it prevents a hard step under binding tape.',
                'examples' => [
                    ['example' => 'Belt operators beveled the shoulder before creasing so the edge paint would not bridge thick.', 'context' => 'belts'],
                ],
            ],
            'pt' => [
                'term' => 'Biselagem de bordo',
                'slug' => 'biselagem-de-bordo',
                'short_definition' => 'Inclinação do bordo do couro para perfil em pena, frequentemente alinhada ao rebaixamento em cintos e bainhas.',
                'full_definition' => 'A biselagem prepara o bordo para dobra, tinta ou brunimento; em cintos define o ombro visual; no cabedal evita degraus sob fita de viés.',
                'examples' => [
                    ['example' => 'Biselaram o ombro do cinto antes da frisagem para a tinta não formar ponte espessa.', 'context' => 'cintos'],
                ],
            ],
        ],
    ],
    [
        'key' => 'feather-edge',
        'domains' => ['leather', 'finishing'],
        'translations' => [
            'en' => [
                'term' => 'Feather edge',
                'slug' => 'feather-edge',
                'short_definition' => 'The thinned leather edge that should taper smoothly into the substrate for paint, adhesive, or fold lines.',
                'full_definition' => 'Feather edge quality is a leading indicator of bond durability. QC uses bend tests and visual light lines to catch hooks or square shoulders left by dull skiver knives.',
                'examples' => [
                    ['example' => 'Finishing rejected a batch of quarters where the feather edge hooked under thumb pressure near the vamp break.', 'context' => 'qc'],
                ],
            ],
            'pt' => [
                'term' => 'Bordo em pena',
                'slug' => 'bordo-em-pena',
                'short_definition' => 'Bordo adelgaçado que deve fundir-se suavemente no suporte para tinta, cola ou dobra.',
                'full_definition' => 'A qualidade do bordo em pena antecipa a durabilidade da colagem; o QC usa flexão manual e inspeção visual para detetar ganchos ou ombros quadrados de faca desgastada.',
                'examples' => [
                    ['example' => 'Acabamentos devolveu um lote de quartos com gancho na pena junto à quebra do vampão sob pressão do polegar.', 'context' => 'cq'],
                ],
            ],
        ],
    ],
    [
        'key' => 'edge-painting',
        'domains' => ['footwear', 'belts', 'finishing'],
        'translations' => [
            'en' => [
                'term' => 'Edge painting',
                'slug' => 'edge-painting',
                'short_definition' => 'Building a smooth pigmented coat on cut leather edges, usually in multiple passes with sanding between.',
                'full_definition' => 'Edge painting hides fiber on belts, straps, and welt visibility lines. Color match to upper dyes and controlled film build prevent cracking on flex zones.',
                'examples' => [
                    ['example' => 'The strap line added a fourth micro-pass after flex testing showed micro-cracks at the tip radius.', 'context' => 'leather-goods'],
                ],
            ],
            'pt' => [
                'term' => 'Pintura de bordo',
                'slug' => 'pintura-de-bordo',
                'short_definition' => 'Aplicação de filmes pigmentados no bordo cortado do couro, em passagens sucessivas com lixagem intermédia.',
                'full_definition' => 'A pintura de bordo esconde fibras em tiras e viés; o cruzamento de cor com tingimentos e a espessura do filme evitam fissuração em zonas de flexão.',
                'examples' => [
                    ['example' => 'A linha de tiras acrescentou uma quarta micro-passagem após teste de flexão mostrar micro-fendas no raio da ponta.', 'context' => 'marroquinaria'],
                ],
            ],
        ],
    ],
    [
        'key' => 'burnishing',
        'domains' => ['finishing', 'leather', 'belts'],
        'translations' => [
            'en' => [
                'term' => 'Burnishing',
                'slug' => 'burnishing',
                'short_definition' => 'Polishing leather edges with friction, wax, and heat to compact fibers for a closed gloss line.',
                'full_definition' => 'Burnishing follows skiving and optional paint on dress belts and some upper edges. Wheel speed and wax load determine gloss level; overheating can glaze the edge without depth.',
                'examples' => [
                    ['example' => 'The team lowered wheel RPM on full-grain straps after the edge started glazing before the fiber closed.', 'context' => 'finishing'],
                ],
            ],
            'pt' => [
                'term' => 'Brunimento',
                'slug' => 'brunimento',
                'short_definition' => 'Polimento de bordos com fricção, cera e calor para compactar fibras e obter linha fechada e brilho.',
                'full_definition' => 'O brunimento segue-se ao rebaixo e opcionalmente à tinta em cintos de vestir; a carga de cera e a rotação definem o brilho; excesso de calor pode vitrificar sem consolidar fibras.',
                'examples' => [
                    ['example' => 'Reduziram as RPM após o bordo vitrificar antes de fechar fibra em tiras de pele inteira.', 'context' => 'acabamentos'],
                ],
            ],
        ],
    ],
    [
        'key' => 'toe-puff',
        'domains' => ['footwear', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'Toe puff',
                'slug' => 'toe-puff',
                'short_definition' => 'Thermoformable toe reinforcement that sets stiffness and shape retention in the forepart.',
                'full_definition' => 'Toe puffs are selected by activation curve, thickness, and environmental softening. Mismatch with vamp leather or lasting pull causes corner lift or shallow toe profile.',
                'examples' => [
                    ['example' => 'Sourcing approved a lower-gram puff after climate chamber tests showed softening on the original grade in tropical retail.', 'context' => 'materials-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Ponteira',
                'slug' => 'ponteira',
                'short_definition' => 'Reforço termoformável da biqueira que define rigidez e retenção de forma na frente do calçado.',
                'full_definition' => 'A ponteira escolhe-se por curva de ativação, espessura e estabilidade térmica; desalinhamento com o couro do vampão ou com a tração de moldação provoca levantamento de cantos ou perfil raso.',
                'examples' => [
                    ['example' => 'Compras homologou uma ponteira mais leve após câmara climática mostrar amolecimento excessivo no grau anterior para retalho tropical.', 'context' => 'laboratorio'],
                ],
            ],
        ],
    ],
    [
        'key' => 'heel-counter',
        'domains' => ['footwear', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'Heel counter',
                'slug' => 'heel-counter',
                'short_definition' => 'Rear stiffener that wraps the heel seat to control collapse and hold the lining stack.',
                'full_definition' => 'Counters are cut to seat shape and skived at the featherline to avoid a hard step. Heat-setting sequence must match adhesive and lining shrinkage.',
                'examples' => [
                    ['example' => 'Closing moved the counter skive start 3 mm lower after a pilot run showed a ridge under the collar foam.', 'context' => 'closing'],
                ],
            ],
            'pt' => [
                'term' => 'Escarpim',
                'slug' => 'escarpim',
                'short_definition' => 'Reforço traseiro envolvente na zona do assento do calcanhar para evitar colapso e fixar o pacote de forros.',
                'full_definition' => 'O escarpim corta-se à forma do assento e rebaixa-se na pena para não criar degrau; a sequência de termoformação deve alinhar cola e encolhimento de forro.',
                'examples' => [
                    ['example' => 'O fecho deslocou o início do rebaixo 3 mm após piloto mostrar degrau sob a espuma do colarinho.', 'context' => 'fecho'],
                ],
            ],
        ],
    ],
    [
        'key' => 'shank-spring',
        'domains' => ['footwear', 'hardware'],
        'translations' => [
            'en' => [
                'term' => 'Shank spring',
                'slug' => 'shank-spring',
                'short_definition' => 'Rigid or semi-rigid insert under the midfoot that bridges the waist and stabilizes heel height during wear.',
                'full_definition' => 'Steel, fiberglass, or composite shanks pair with outsole flex notches. Wrong length or placement telegraphs as a pressure line under the instep.',
                'examples' => [
                    ['example' => 'Bottoming extended the shank 5 mm after flex boards showed waist hinge ahead of the joint line.', 'context' => 'bottoming'],
                ],
            ],
            'pt' => [
                'term' => 'Entressola rígida (shank)',
                'slug' => 'entressola-rigida-shank',
                'short_definition' => 'Inserto rígido ou semi-rígido sob o peito do pé que liga a zona da cintura e estabiliza o salto em uso.',
                'full_definition' => 'Shanks em aço, fibra ou compósito alinham com entalhes de flexão da sola; comprimento ou posição errados criam linha de pressão no peito do pé.',
                'examples' => [
                    ['example' => 'A solagem alongou o shank 5 mm após pranchas de flexão mostrarem dobradiça adiantada à linha articular.', 'context' => 'solagem'],
                ],
            ],
        ],
    ],
    [
        'key' => 'vamp',
        'domains' => ['footwear', 'pattern-making'],
        'translations' => [
            'en' => [
                'term' => 'Vamp',
                'slug' => 'vamp',
                'short_definition' => 'The forepart upper pattern piece covering the instep and toe, often including the tongue on one-piece designs.',
                'full_definition' => 'Vamp break placement drives flex comfort and creasing aesthetics. Pattern engineers balance mean form allowances with leather yield and asymmetry between left and right nests.',
                'examples' => [
                    ['example' => 'Pattern moved the vamp break 4 mm lateral after gait lab pressure maps showed hot spots on the first build.', 'context' => 'pattern-room'],
                ],
            ],
            'pt' => [
                'term' => 'Vampão',
                'slug' => 'vampao',
                'short_definition' => 'Peça de cabedal na frente que cobre o peito do pé e a biqueira; em desenhos de uma peça inclui frequentemente a língua.',
                'full_definition' => 'A linha de quebra do vampão define conforto à flexão e estética de pence. A malha equilibra folgas da forma média com rendimento de pele e assimetria entre esquerdo e direito no nesting.',
                'examples' => [
                    ['example' => 'A malha deslocou a quebra do vampão 4 mm lateral após mapas de pressão mostrarem ponto quente na primeira construção.', 'context' => 'modelagem'],
                ],
            ],
        ],
    ],
    [
        'key' => 'seam-allowance',
        'domains' => ['pattern-making', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Seam allowance',
                'slug' => 'seam-allowance',
                'short_definition' => 'Extra material beyond the net seam line reserved for stitch bite, skiving, and turn-under.',
                'full_definition' => 'Allowances vary by operation: post machines need more bite than flatbed; curved seams need relief notches. CAD rules propagate allowances into cutter files.',
                'examples' => [
                    ['example' => 'Technicians widened the throat allowance after cylinder arm tests showed needle skip on the tight radius.', 'context' => 'closing'],
                ],
            ],
            'pt' => [
                'term' => 'Margem de costura',
                'slug' => 'margem-de-costura',
                'short_definition' => 'Material extra além da linha líquida de costura para mordida da agulha, rebaixamento e dobra.',
                'full_definition' => 'As margens variam com a operação: máquinas de coluna exigem mais mordida que plana; curvas precisam de alívios. Regras CAD propagam margens aos ficheiros de corte.',
                'examples' => [
                    ['example' => 'Alargaram a margem do peito de pé após testes em braço cilíndrico mostrarem saltos de ponto no raio apertado.', 'context' => 'fecho'],
                ],
            ],
        ],
    ],
    [
        'key' => 'pattern-allowance',
        'domains' => ['pattern-making', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Pattern allowance',
                'slug' => 'pattern-allowance',
                'short_definition' => 'Global offsets between net lines and production patterns covering material movement and process shrinkage.',
                'full_definition' => 'Allowance budgets stack seam values, lasting pull, and finishing compensation. A single wrong assumption propagates through grading and nesting yield.',
                'examples' => [
                    ['example' => 'Engineering added +0.5 mm on the collar net after hot melt lamination trials shrank the foam stack.', 'context' => 'development'],
                ],
            ],
            'pt' => [
                'term' => 'Folga de malha',
                'slug' => 'folga-de-malha',
                'short_definition' => 'Compensações globais entre linhas líquidas e peças de produção para movimento de materiais e encolhimentos de processo.',
                'full_definition' => 'O orçamento de folgas acumula margens de costura, tração de moldação e compensações de acabamento; um erro propaga-se na graduação e no aproveitamento do nesting.',
                'examples' => [
                    ['example' => 'Engenharia acrescentou +0,5 mm ao líquido do colarinho após ensaios de cola quente encolherem o pacote de espuma.', 'context' => 'desenvolvimento'],
                ],
            ],
        ],
    ],
    [
        'key' => 'grading',
        'featured' => true,
        'domains' => ['pattern-making', 'cad-cam'],
        'translations' => [
            'en' => [
                'term' => 'Grading',
                'slug' => 'grading',
                'short_definition' => 'Scaling the mean form pattern through a size run using grade rules per reference points.',
                'full_definition' => 'Grading distributes growth across girth, length, and style lines. CAD systems store grade increments per point; manual checks still validate critical flex notches.',
                'examples' => [
                    ['example' => 'The grader rebalanced increments at the waist after size 46 showed over-wide throat relative to heel girth.', 'context' => 'grading-table'],
                ],
            ],
            'pt' => [
                'term' => 'Graduação',
                'slug' => 'graduacao',
                'short_definition' => 'Escalonamento da forma média ao longo da série de tamanhos com regras de grade por pontos de referência.',
                'full_definition' => 'A graduação distribui crescimento em perímetros, comprimentos e linhas de estilo; os CAD guardam incrementos por ponto, mas validações manuais ainda cobrem entalhes críticos de flexão.',
                'examples' => [
                    ['example' => 'O graduador rebalanceou incrementos na cintura após o 46 ficar largo demais no peito de pé face ao perímetro do calcanhar.', 'context' => 'mesa-grade'],
                ],
            ],
        ],
    ],
    [
        'key' => 'size-run',
        'domains' => ['pattern-making', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Size run',
                'slug' => 'size-run',
                'short_definition' => 'The ordered set of sizes produced for a style, including width breaks and market-specific curves.',
                'full_definition' => 'Merchandising sets the size curve; factories explode BOMs and markers per size bucket. Mismatch between sales curve and cutting mix creates dead stock on tails.',
                'examples' => [
                    ['example' => 'Production shifted cutter mix toward 40–42 after sell-through data lagged on sub-39 sizes.', 'context' => 'planning'],
                ],
            ],
            'pt' => [
                'term' => 'Série de tamanhos',
                'slug' => 'serie-de-tamanhos',
                'short_definition' => 'Conjunto ordenado de tamanhos produzidos para um modelo, incluindo larguras e curvas por mercado.',
                'full_definition' => 'O comercial define a curva; a fábrica explode listas de materiais e marcas por balde de tamanho. Desalinhamento entre curva de vendas e mistura de corte gera stock morto nas caudas.',
                'examples' => [
                    ['example' => 'A produção deslocou a mistura de corte para 40–42 após dados de giro mostrarem lentidão abaixo do 39.', 'context' => 'planeamento'],
                ],
            ],
        ],
    ],
    [
        'key' => 'clicking',
        'domains' => ['footwear', 'production', 'leather'],
        'translations' => [
            'en' => [
                'term' => 'Clicking',
                'slug' => 'clicking',
                'short_definition' => 'Cutting upper components from hides or rolls using dies or CNC, optimizing yield and defect avoidance.',
                'full_definition' => 'Clicking balances vein placement, scar avoidance, and pair symmetry. Digital clicking logs nest IDs for traceability when a hide defect appears late in closing.',
                'examples' => [
                    ['example' => 'The clicker rotated the vamp nest 6° to clear a healed vein while keeping pair symmetry within AQL.', 'context' => 'clicking-press'],
                ],
            ],
            'pt' => [
                'term' => 'Corte (clicking)',
                'slug' => 'corte-clicking',
                'short_definition' => 'Corte de componentes de cabedal a partir de peles ou rolos com facas ou CNC, otimizando rendimento e evitando defeitos.',
                'full_definition' => 'O clicking equilibra posição de veios, cicatrizes e simetria do par. O corte digital regista IDs de nesting para rastrear defeitos de pele tardios no fecho.',
                'examples' => [
                    ['example' => 'O cortador rodou o nesting do vampão 6° para fugar a veia cicatrizada mantendo simetria dentro do AQL.', 'context' => 'prensa-corte'],
                ],
            ],
        ],
    ],
    [
        'key' => 'die-cutting',
        'domains' => ['footwear', 'leather-goods', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Die cutting',
                'slug' => 'die-cutting',
                'short_definition' => 'Steel-rule or clicker-die cutting of leather and board components, interchangeable term with clicking in many plants.',
                'full_definition' => 'Die cutting emphasizes tool maintenance: rule height, ejection rubber, and center bevel affect edge quality and press dwell.',
                'examples' => [
                    ['example' => 'Maintenance swapped ejection rubber on the strap die after feather edges began tearing on lift-off.', 'context' => 'tooling'],
                ],
            ],
            'pt' => [
                'term' => 'Corte por faca',
                'slug' => 'corte-por-faca',
                'short_definition' => 'Corte com regra de aço ou faca de clicking em couro e cartões; em muitas fábricas sinónimo operacional de clicking.',
                'full_definition' => 'O corte por faca exige gestão de ferramental: altura de regra, borracha de ejeção e bisel central influenciam qualidade de bordo e tempo de prensa.',
                'examples' => [
                    ['example' => 'Manutenção trocou a borracha de ejeção da faca de tiras após rasgos na elevação do corte.', 'context' => 'ferramentaria'],
                ],
            ],
        ],
    ],
    [
        'key' => 'outsole',
        'domains' => ['footwear', 'materials', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Outsole',
                'slug' => 'outsole',
                'short_definition' => 'The ground-contact layer defining grip, abrasion, flex notches, and stack height with midsole.',
                'full_definition' => 'Outsoles are validated against SATRA-style abrasion, slip, and bend protocols. Tooling shrink and injection parameters must match last bottom curves.',
                'examples' => [
                    ['example' => 'Tooling added a second flex notch after cold-flex cracks appeared at −10 °C lab cycling.', 'context' => 'tooling-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Solado',
                'slug' => 'solado',
                'short_definition' => 'Camada de contacto com o pavimento que define aderência, abrasão, entalhes de flexão e altura de pacote com entressola.',
                'full_definition' => 'Os solados validam-se em abrasão, escorregamento e flexão a frio; o encolhimento de molde e parâmetros de injeção têm de casar com a curva inferior da forma.',
                'examples' => [
                    ['example' => 'O molde ganhou um segundo entalhe de flexão após fendas a −10 °C em ciclos de laboratório.', 'context' => 'laboratorio-solas'],
                ],
            ],
        ],
    ],
    [
        'key' => 'welt',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Welt',
                'slug' => 'welt',
                'short_definition' => 'A strip stitched to the upper and insole edge to create a mechanical anchor for sole attachment in welted constructions.',
                'full_definition' => 'Welts route loads around the featherline and enable resole cycles in bench-grade footwear. Thread tension and welt skiving depth affect stitch sink and water path.',
                'examples' => [
                    ['example' => 'The welt channel was re-cut after audit photos showed inconsistent stitch sink on the waist curve.', 'context' => 'welt-sewing'],
                ],
            ],
            'pt' => [
                'term' => 'Viés (welt)',
                'slug' => 'vies-welt',
                'short_definition' => 'Tira costurada à bainha do cabedal e borda da entressola para criar âncora mecânica de solagem em construções com viés.',
                'full_definition' => 'O viés distribui cargas junto à pena e permite resolagens em calçado de bancada. A tensão de linha e o rebaixo do viés influenciam afundamento de ponto e percurso de água.',
                'examples' => [
                    ['example' => 'Reabriram o canal do viés após auditoria mostrar afundamento irregular na curva da cintura.', 'context' => 'costura-vies'],
                ],
            ],
        ],
    ],
    [
        'key' => 'blake-stitch',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Blake stitch',
                'slug' => 'blake-stitch',
                'short_definition' => 'Direct lockstitch through upper, insole, and outsole, yielding a slim featherline common on dress shoes.',
                'full_definition' => 'Blake construction trades resole ease for profile height. Water intrusion path is different from welted shoes; QC checks stitch sealing at perimeter.',
                'examples' => [
                    ['example' => 'The Blake line added a perimeter cement fillet after salt fog exposed stitch wicking on a derby build.', 'context' => 'bottoming'],
                ],
            ],
            'pt' => [
                'term' => 'Ponto Blake',
                'slug' => 'ponto-blake',
                'short_definition' => 'Costura direta através de cabedal, entressola e solado, com pena fina típica de calçado clássico.',
                'full_definition' => 'A construção Blake troca facilidade de resolagem por perfil baixo; o percurso de água difere do Goodyear; o QC vedação perimetral dos pontos.',
                'examples' => [
                    ['example' => 'A linha Blake aplicou filete perimetral de cola após névoa salina mostrar capilaridade nos pontos num derby.', 'context' => 'solagem'],
                ],
            ],
        ],
    ],
    [
        'key' => 'goodyear-welt',
        'featured' => true,
        'domains' => ['footwear', 'production', 'finishing'],
        'translations' => [
            'en' => [
                'term' => 'Goodyear welt',
                'slug' => 'goodyear-welt',
                'short_definition' => 'Machine-sewn welt through insole rib and upper margin, then outsole stitched or cemented to the welt for repairable builds.',
                'full_definition' => 'Goodyear welt lines sequence temporary lasting, welt sewing, cork filling, and sole lock. Stitch density and heat activation windows separate premium from rework-heavy runs.',
                'examples' => [
                    ['example' => 'The bench finisher replaced a cork void after flex noise traced to incomplete fill under the waist.', 'context' => 'bench-room'],
                ],
            ],
            'pt' => [
                'term' => 'Welt Goodyear',
                'slug' => 'welt-goodyear',
                'short_definition' => 'Viés cosido mecanicamente à nervura da entressola e bainha, com solado cosido ou colado ao viés para construções reparáveis.',
                'full_definition' => 'A linha Goodyear sequencia moldação provisória, costura de viés, enchimento de cortiça e fecho de sola. Densidade de pontos e janelas térmicas separam série premium de retrabalho.',
                'examples' => [
                    ['example' => 'O acabador de bancada substituiu vazio de cortiça após ruído de flexão mapear enchimento incompleto na cintura.', 'context' => 'bancada'],
                ],
            ],
            'de' => [
                'term' => 'Goodyear-Rahmennaht',
                'slug' => 'goodyear-rahmennaht',
                'short_definition' => 'Maschinengenähter Rahmen zwischen Brandsohle und Schaft für reparierbare Schuhkonstruktionen.',
                'full_definition' => 'Die Goodyear-Linie folgt definierten Taktzeiten für Rahmennaht, Korkfüllung und Laufsohlenfixierung; Dichte und Aktivierungstemperaturen steuern Qualität und Nacharbeit.',
                'examples' => [['example' => 'Nach Flexgeräuschen wurde Kork unter der Waist nachgefüllt.', 'context' => 'reparatur']],
            ],
        ],
    ],
    [
        'key' => 'strobel-stitch',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Strobel stitching',
                'slug' => 'strobel-stitching',
                'short_definition' => 'Flat chainstitch closing the upper margin to a textile strobel insole for lightweight cemented athletic builds.',
                'full_definition' => 'Strobel lines run at high SPM with controlled foot tension. Skipped stitches at the toe spring radius are a top failure mode in audit.',
                'examples' => [
                    ['example' => 'Maintenance retimed the conveyor stop after vision inspection flagged chain skips at the toe curve.', 'context' => 'strobel-line'],
                ],
            ],
            'pt' => [
                'term' => 'Costura Strobel',
                'slug' => 'costura-strobel',
                'short_definition' => 'Corrente plana que fecha a bainha do cabedal a uma entressola têxtil Strobel em calçado leve colado.',
                'full_definition' => 'A linha Strobel corre a alta cadência com tensão de pé controlada; saltos de ponto no raio do toe spring são falha frequente em auditoria.',
                'examples' => [
                    ['example' => 'Manutenção retemporizou a paragem do tapete após visão artificial detetar saltos na curva da biqueira.', 'context' => 'linha-strobel'],
                ],
            ],
        ],
    ],
    [
        'key' => 'heel-seat',
        'domains' => ['footwear', 'pattern-making'],
        'translations' => [
            'en' => [
                'term' => 'Heel seat',
                'slug' => 'heel-seat',
                'short_definition' => 'The pocketed area of the last bottom where the heel is shaped and where the counter sits.',
                'full_definition' => 'Seat depth and pitch drive heel slip KPIs. Pattern and lasting teams reference seat lines when setting collar height and lining stops.',
                'examples' => [
                    ['example' => 'Fit trials showed heel slip after the seat pitch was opened 1° without matching collar foam compression.', 'context' => 'fit-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Assento do calcanhar',
                'slug' => 'assento-do-calcanhar',
                'short_definition' => 'Zona da base da forma onde o calcanhar assenta e onde o escarpim trabalha em volume.',
                'full_definition' => 'Profundidade e inclinação do assento influenciam KPIs de escorregamento do calcanhar. Malha e moldação alinham linhas de assento com altura do colarinho e paragens de forro.',
                'examples' => [
                    ['example' => 'Ensaios de calce mostraram escorregamento após abrir 1° a inclinação do assento sem ajustar a compressão da espuma do colarinho.', 'context' => 'laboratorio-calce'],
                ],
            ],
        ],
    ],
    [
        'key' => 'leather-finishes-family',
        'domains' => ['leather', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'Leather finishes (family)',
                'slug' => 'leather-finishes-family',
                'short_definition' => 'Umbrella category for surface systems that control color, handle, light fastness, and pull-up behavior on tanned leather.',
                'full_definition' => 'Finish families bundle top coats, wax pulls, and aniline levels. Sourcing compares strike-offs to bulk drums using the same spray line parameters.',
                'examples' => [
                    ['example' => 'The leather team grouped pull-up and pure aniline under one finish family for supplier scorecards.', 'context' => 'sourcing'],
                ],
            ],
            'pt' => [
                'term' => 'Família de acabamentos de couro',
                'slug' => 'familia-acabamentos-couro',
                'short_definition' => 'Categoria para sistemas de superfície que controlam cor, tacto, solidez à luz e comportamento pull-up em pele curtida.',
                'full_definition' => 'Famílias de acabamento agregam vernizes, ceras e níveis de anilina; compras comparam strike-offs a lotes de tambor com parâmetros de linha de spray idênticos.',
                'examples' => [
                    ['example' => 'O departamento de peles agrupou pull-up e anilina pura numa família para scorecards de fornecedores.', 'context' => 'compras'],
                ],
            ],
        ],
    ],
];
