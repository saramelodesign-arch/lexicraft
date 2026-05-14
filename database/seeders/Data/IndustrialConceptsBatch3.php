<?php

/**
 * LexiCraft Glossary concepts (batch 3 of 3).
 *
 * @return list<array<string, mixed>>
 */
return [
    [
        'key' => 'line-balancing',
        'featured' => true,
        'domains' => ['production', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Line balancing',
                'slug' => 'line-balancing',
                'short_definition' => 'Matching station cycle times to takt so bottlenecks and idle operators stay within planned variance.',
                'full_definition' => 'Balancing uses Yamazumi charts and video audits. Seasonal style mix changes skill demand, especially when welt cells absorb athletic overflow.',
                'examples' => [
                    ['example' => 'Industrial engineering re-timed the heel seat station after video showed 11s idle following each double-size pair.', 'context' => 'industrial-engineering'],
                ],
            ],
            'pt' => [
                'term' => 'Balanceamento de linha',
                'slug' => 'balanceamento-de-linha',
                'short_definition' => 'Igualar tempos de ciclo das estações ao takt para manter gargalos e ociosidade dentro da variação planeada.',
                'full_definition' => 'Usa-se Yamazumi e vídeo; a mistura de modelos muda a procura de competências, sobretudo quando células de viés absorvem overflow desportivo.',
                'examples' => [
                    ['example' => 'A engenharia industrial retemporizou o assento do calcanhar após vídeo mostrar 11 s de ociosidade após cada par duplo tamanho.', 'context' => 'engenharia-industrial'],
                ],
            ],
        ],
    ],
    [
        'key' => 'press-dwell-time',
        'domains' => ['machinery', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Press dwell time',
                'slug' => 'press-dwell-time',
                'short_definition' => 'Seconds under pressure and temperature for cement or thermo bonds to wet out without squeeze-out.',
                'full_definition' => 'Dwell interacts with platen parallelism and foam creep. Short dwell shows as cold bond; long dwell can telegraph adhesive squeeze onto visible edges.',
                'examples' => [
                    ['example' => 'Maintenance increased dwell 0.8s after IR thermography showed cold corners on the forepart platen.', 'context' => 'bonding-press'],
                ],
            ],
            'pt' => [
                'term' => 'Tempo de prensagem',
                'slug' => 'tempo-de-prensagem',
                'short_definition' => 'Segundos sob pressão e temperatura para a cola humedecer sem extravasar.',
                'full_definition' => 'O tempo interage com paralelismo da placa e fluência da espuma; prensagem curta dá colagem fria; longa extruda cola para bordos visíveis.',
                'examples' => [
                    ['example' => 'Manutenção aumentou 0,8 s após termografia IR mostrar cantos frios na placa da frente.', 'context' => 'prensa-colagem'],
                ],
            ],
        ],
    ],
    [
        'key' => 'hot-melt-adhesive',
        'domains' => ['materials', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Hot melt adhesive',
                'slug' => 'hot-melt-adhesive',
                'short_definition' => 'Thermoplastic adhesive applied molten for fast tack in lasting, strobel, or edge lamination.',
                'full_definition' => 'Hot melts trade open time for speed. Engineers match activation windows to line stops and ambient humidity.',
                'examples' => [
                    ['example' => 'Process swapped to a higher-open-time grade after summer humidity caused stringing on the collar lamination head.', 'context' => 'adhesive-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Cola quente (hot melt)',
                'slug' => 'cola-quente-hot-melt',
                'short_definition' => 'Adesivo termoplástico aplicado fundido para tack rápido em moldação, Strobel ou laminação de bordo.',
                'full_definition' => 'Troca-se tempo de aberto por velocidade; a janela de ativação alinha-se a paragens de linha e humidade ambiente.',
                'examples' => [
                    ['example' => 'O processo mudou para um grau com maior tempo de aberto após humidade de verão causar fios na cabeça de laminação do colarinho.', 'context' => 'lab-adesivos'],
                ],
            ],
        ],
    ],
    [
        'key' => 'stitch-density',
        'domains' => ['production', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'Stitch density',
                'slug' => 'stitch-density',
                'short_definition' => 'Stitches per inch (SPI) or centimeter along a seam, tuned for leather substance and seam function.',
                'full_definition' => 'Density affects tear propagation and needle heat. Technical packs specify SPI ranges per seam class with tolerance on sample seams.',
                'examples' => [
                    ['example' => 'QA rejected a welt run when SPI dropped 0.3 below spec after a needle change without reprogramming feed dogs.', 'context' => 'stitch-audit'],
                ],
            ],
            'pt' => [
                'term' => 'Densidade de pontos',
                'slug' => 'densidade-de-pontos',
                'short_definition' => 'Pontos por polegada ou centímetro ao longo da costura, ajustados à substância do couro e à função da costura.',
                'full_definition' => 'A densidade influencia propagação de rasgo e calor da agulha; a ficha define SPI por classe com tolerância em amostras.',
                'examples' => [
                    ['example' => 'O CQ rejeitou uma série de viés com SPI −0,3 face à especificação após troca de agulha sem reprogramar arrastadores.', 'context' => 'auditoria-costura'],
                ],
            ],
        ],
    ],
    [
        'key' => 'needle-cooling',
        'domains' => ['machinery', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Needle cooling',
                'slug' => 'needle-cooling',
                'short_definition' => 'Air or mist assist reducing needle heat on dense synthetics and coated threads.',
                'full_definition' => 'Heat spikes melt synthetic fibers and skip stitches. High-SPM post machines often add needle coolers when running bonded threads.',
                'examples' => [
                    ['example' => 'Maintenance enabled mist cooling on the post bed after thermal cameras showed needle tips exceeding 190 °C on TPU panels.', 'context' => 'sewing-maintenance'],
                ],
            ],
            'pt' => [
                'term' => 'Arrefecimento de agulha',
                'slug' => 'arrefecimento-de-agulha',
                'short_definition' => 'Ar ou névoa para reduzir calor da agulha em sintéticos densos e linhas revestidas.',
                'full_definition' => 'Picos de calor fundem fibras e saltam pontos; máquinas de alta cadência em coluna usam névoa com linhas ligadas.',
                'examples' => [
                    ['example' => 'Manutenção activou névoa após câmaras térmicas mostrarem pontas acima de 190 °C em painéis TPU.', 'context' => 'manutencao-costura'],
                ],
            ],
        ],
    ],
    [
        'key' => 'conveyor-guarding',
        'domains' => ['machinery', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Conveyor guarding',
                'slug' => 'conveyor-guarding',
                'short_definition' => 'Fixed and interlocked barriers around nip points, diverters, and heat tunnels on assembly lines.',
                'full_definition' => 'Guarding audits follow machinery directives and internal LOTO. Temporary removal for jam clearance must be logged with two-person rules.',
                'examples' => [
                    ['example' => 'EHS halted a tunnel line after a guard interlock was bypassed with zip ties during a night shift jam.', 'context' => 'ehs-audit'],
                ],
            ],
            'pt' => [
                'term' => 'Proteções em transportadores',
                'slug' => 'protecoes-em-transportadores',
                'short_definition' => 'Barreiras fixas e intertrancadas em pontos de estrangulamento, desvios e túneis térmicos.',
                'full_definition' => 'As auditorias seguem diretivas de máquinas e LOTO interno; remoção temporária por atolamento exige registo e regra de duas pessoas.',
                'examples' => [
                    ['example' => 'SSO parou um túnel após interlock contornado com abraçadeiras num atolamento de turno da noite.', 'context' => 'auditoria-sso'],
                ],
            ],
        ],
    ],
    [
        'key' => 'humidity-conditioning',
        'domains' => ['leather', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Humidity conditioning',
                'slug' => 'humidity-conditioning',
                'short_definition' => 'Controlled rest of leather or uppers before cutting or lasting to stabilize moisture content.',
                'full_definition' => 'Conditioning rooms target RH bands to reduce post-lasting shrink and adhesive blush. Logistics sequencing avoids dry-crisp hides entering humid lines.',
                'examples' => [
                    ['example' => 'Production delayed clicking 12h after hygrometers logged hides arriving cold-truck dry below the conditioning band.', 'context' => 'climate-room'],
                ],
            ],
            'pt' => [
                'term' => 'Condicionamento de humidade',
                'slug' => 'condicionamento-de-humidade',
                'short_definition' => 'Repouso controlado de peles ou cabedais antes do corte ou moldação para estabilizar humidade.',
                'full_definition' => 'As salas visam faixas de HR para reduzir encolhimento pós-moldação e embaciamento de cola; a logística evita peles secas a entrar em linhas húmidas.',
                'examples' => [
                    ['example' => 'A produção atrasou o clicking 12 h após higrómetros registarem peles secas de camião frigorífico abaixo da faixa.', 'context' => 'sala-climatica'],
                ],
            ],
        ],
    ],
    [
        'key' => 'pu-edge-seal',
        'domains' => ['finishing', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'PU edge seal',
                'slug' => 'pu-edge-seal',
                'short_definition' => 'Polyurethane edge coat used where paint films need flexibility on thin splits or coated splits.',
                'full_definition' => 'PU seals bridge split fiber more flexibly than rigid paints but yellow under UV unless stabilized. Application viscosity is temperature sensitive.',
                'examples' => [
                    ['example' => 'Finishing lowered booth temperature 2 °C after viscosity drift caused runners on belt tips.', 'context' => 'spray-booth'],
                ],
            ],
            'pt' => [
                'term' => 'Selagem PU de bordo',
                'slug' => 'selagem-pu-de-bordo',
                'short_definition' => 'Camada PU no bordo quando se precisa de flexibilidade em splits finos ou recouros revestidos.',
                'full_definition' => 'A PU flexiona melhor que tinta rígida mas amarela sob UV sem estabilizante; a viscosidade é sensível à temperatura.',
                'examples' => [
                    ['example' => 'Acabamentos baixou 2 °C na cabine após deriva de viscosidade criar escorrimentos nas pontas de cinto.', 'context' => 'cabine-spray'],
                ],
            ],
        ],
    ],
    [
        'key' => 'stain-guard-finish',
        'domains' => ['finishing', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Stain guard finish',
                'slug' => 'stain-guard-finish',
                'short_definition' => 'Topical fluorocarbon or hybrid spray that increases surface tension against water and oil-borne stains.',
                'full_definition' => 'Application weight interacts with breathability claims. Regulatory teams track C6 vs C0 chemistry by market.',
                'examples' => [
                    ['example' => 'Compliance blocked a C6 top spray for EU kids after the season’s chemical policy update.', 'context' => 'compliance'],
                ],
            ],
            'pt' => [
                'term' => 'Acabamento anti-manchas',
                'slug' => 'acabamento-anti-manchas',
                'short_definition' => 'Spray fluorado ou híbrido que aumenta tensão superficial contra água e manchas oleosas.',
                'full_definition' => 'O peso de aplicação cruza com alegações de respirabilidade; compliance segue química C6 vs C0 por mercado.',
                'examples' => [
                    ['example' => 'Compliance bloqueou spray C6 para criança UE após atualização da política química da temporada.', 'context' => 'compliance'],
                ],
            ],
        ],
    ],
    [
        'key' => 'eyelet-reinforcement',
        'domains' => ['hardware', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Eyelet reinforcement',
                'slug' => 'eyelet-reinforcement',
                'short_definition' => 'Hidden textile or heat-set film behind lace holes to resist pull-out and tear propagation.',
                'full_definition' => 'Reinforcement patterns follow lace load vectors. CAD adds washer outlines for automated cutting of reinforcement blanks.',
                'examples' => [
                    ['example' => 'Testing failed a hiking build when eyelet reinforcement missed the top speed-lace hole on the lateral panel.', 'context' => 'pull-test'],
                ],
            ],
            'pt' => [
                'term' => 'Reforço de ilhós',
                'slug' => 'reforco-de-ilhos',
                'short_definition' => 'Têxtil oculto ou película termoformada atrás dos furos de atacadores para resistir ao arranque.',
                'full_definition' => 'O padrão segue vetores de carga; o CAD acrescenta contornos de arruela para corte automático de reforços.',
                'examples' => [
                    ['example' => 'Ensaio falhou num trekking quando o reforço falhou o furo superior de atacador rápido no painel lateral.', 'context' => 'ensaio-arranque'],
                ],
            ],
        ],
    ],
    [
        'key' => 'rivet-setting',
        'domains' => ['hardware', 'belts', 'leather-goods'],
        'translations' => [
            'en' => [
                'term' => 'Rivet setting',
                'slug' => 'rivet-setting',
                'short_definition' => 'Press or hammer forming of tubular or bifurcated rivets through strap stacks with clinch height control.',
                'full_definition' => 'Rivet pull-out specs tie to post height and inner washer capture. Automated feeders reduce double hits that crack plating.',
                'examples' => [
                    ['example' => 'QC pulled a belt lot when rivet clinch height variance exceeded 0.2 mm across the tip cluster.', 'context' => 'hardware-qc'],
                ],
            ],
            'pt' => [
                'term' => 'Rebitagem',
                'slug' => 'rebitagem',
                'short_definition' => 'Formação em prensa ou martelo de rebites tubulares ou bifurcados em pacotes de tira com controlo de altura de repuxo.',
                'full_definition' => 'O arranque liga-se à altura do poste e captura da arruela; alimentadores automáticos reduzem duplos golpes que fendem galvanização.',
                'examples' => [
                    ['example' => 'O CQ retirou um lote de cintos quando a variância de altura de repuxo excedeu 0,2 mm no conjunto da ponta.', 'context' => 'cq-ferragens'],
                ],
            ],
        ],
    ],
    [
        'key' => 'zip-insertion',
        'domains' => ['hardware', 'leather-goods', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Zip insertion',
                'slug' => 'zip-insertion',
                'short_definition' => 'Sewing or welding a coil or molded zipper into a tape path with stop box alignment and slider pull spec.',
                'full_definition' => 'Insertion jigs control tape offset to prevent slider bite on lining. Critical on instep boots where flex cycles are high.',
                'examples' => [
                    ['example' => 'Engineering widened the tape offset after field claims of slider bite on the new matte coil.', 'context' => 'hardware-engineering'],
                ],
            ],
            'pt' => [
                'term' => 'Inserção de fecho de correr',
                'slug' => 'insercao-fecho-correr',
                'short_definition' => 'Costura ou soldagem de fecho espiral ou injetado na fita com alinhamento de caixa de fim e força do cursor.',
                'full_definition' => 'Os gabaritos controlam offset da fita para o cursor não morder o forro; crítico em botas de cano com alta flexão.',
                'examples' => [
                    ['example' => 'A engenharia alargou o offset após reclamações de mordida do cursor na nova espiral mate.', 'context' => 'engenharia-ferragens'],
                ],
            ],
        ],
    ],
    [
        'key' => 'belt-loop',
        'domains' => ['belts', 'leather-goods'],
        'translations' => [
            'en' => [
                'term' => 'Belt loop',
                'slug' => 'belt-loop',
                'short_definition' => 'Keeper strap or fixed loop that retains the free tail after buckle closure.',
                'full_definition' => 'Loop placement and stiffness affect wear comfort. Heat-set thickness and edge paint durability are validated on flex jigs.',
                'examples' => [
                    ['example' => 'Product moved the keeper 5 mm toward the tip after wear trials showed tail slip on slim waists.', 'context' => 'product-testing'],
                ],
            ],
            'pt' => [
                'term' => 'Passante',
                'slug' => 'passante',
                'short_definition' => 'Tira ou argola fixa que retém a cauda livre após fechar a fivela.',
                'full_definition' => 'Posição e rigidez afetam conforto; espessura termoformada e tinta de bordo validam-se em jig de flexão.',
                'examples' => [
                    ['example' => 'O produto deslocou o passante 5 mm para a ponta após ensaios de uso mostrarem escape da cauda em cinturas finas.', 'context' => 'teste-produto'],
                ],
            ],
        ],
    ],
    [
        'key' => 'buckle-tongue',
        'domains' => ['belts', 'hardware'],
        'translations' => [
            'en' => [
                'term' => 'Buckle tongue',
                'slug' => 'buckle-tongue',
                'short_definition' => 'The pivoting or fixed prong that engages punched holes to set waist tension.',
                'full_definition' => 'Tongue thickness and tip radius interact with hole spacing and strap substance. QA checks for burrs that cut holes under cyclic loading.',
                'examples' => [
                    ['example' => 'Supplier reworked die tips after burr checks showed hole elongation after 500 buckle cycles.', 'context' => 'supplier-quality'],
                ],
            ],
            'pt' => [
                'term' => 'Língua de fivela',
                'slug' => 'lingua-de-fivela',
                'short_definition' => 'Pino fixo ou pivotante que entra nos furos para definir a tensão na cintura.',
                'full_definition' => 'Espessura e raio da ponta cruzam com espaçamento de furos e substância da tira; o QC procura rebarbas que alongam furos em ciclos.',
                'examples' => [
                    ['example' => 'O fornecedor retificou pontas após rebarbas causarem alongamento dos furos às 500 manobras de fivela.', 'context' => 'qualidade-fornecedor'],
                ],
            ],
        ],
    ],
    [
        'key' => 'edge-creasing',
        'domains' => ['belts', 'finishing', 'leather-goods'],
        'translations' => [
            'en' => [
                'term' => 'Edge creasing',
                'slug' => 'edge-creasing',
                'short_definition' => 'Hot wheel or filleteer line that compresses a decorative groove parallel to the edge before paint.',
                'full_definition' => 'Creasing hides minor skive variance and sets light catch for edge paint. Temperature mismatch scorches light veg-tan straps.',
                'examples' => [
                    ['example' => 'The operator dropped wheel temp 10 °C after burn marks appeared on natural veg-tan belts.', 'context' => 'belt-finishing'],
                ],
            ],
            'pt' => [
                'term' => 'Frisagem',
                'slug' => 'frisagem',
                'short_definition' => 'Roda quente ou fileteadora que comprime um sulco decorativo paralelo ao bordo antes da tinta.',
                'full_definition' => 'A frisagem mascara pequenas variações de rebaixo e cria reflexo para a tinta; temperatura errada queima couros claros vegetais.',
                'examples' => [
                    ['example' => 'O operador baixou 10 °C da roda após marcas de queimadura em cintos de vegetal natural.', 'context' => 'acabamento-cintos'],
                ],
            ],
        ],
    ],
    [
        'key' => 'strap-tapering',
        'domains' => ['belts', 'pattern-making'],
        'translations' => [
            'en' => [
                'term' => 'Strap tapering',
                'slug' => 'strap-tapering',
                'short_definition' => 'Gradual width reduction from buckle end to tip to balance stiffness and buckle ergonomics.',
                'full_definition' => 'Taper angles affect hole tear strength and tip flex. CAD exports taper splines to plotters for hand-trim verification on first articles.',
                'examples' => [
                    ['example' => 'Pattern widened the taper start after tip flex tests cracked edge paint on the new 38 mm blank.', 'context' => 'pattern-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Afinamento de tira',
                'slug' => 'afinamento-de-tira',
                'short_definition' => 'Redução gradual de largura do lado da fivela à ponta para equilibrar rigidez e ergonomia.',
                'full_definition' => 'O ângulo afeta rasgo nos furos e flexão da ponta; o CAD exporta splines para plotter e verificação em primeira peça.',
                'examples' => [
                    ['example' => 'A malha alargou o início do afinamento após testes de flexão na ponta fenderem tinta no novo bruto de 38 mm.', 'context' => 'lab-malha'],
                ],
            ],
        ],
    ],
    [
        'key' => 'edge-folding',
        'domains' => ['leather-goods', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Edge folding',
                'slug' => 'edge-folding',
                'short_definition' => 'Turning a skived margin onto itself or onto a reinforcement tape before stitching.',
                'full_definition' => 'Folding machines use heated toes and tape tensioners. Mis-set tape causes telegraphing on light leathers.',
                'examples' => [
                    ['example' => 'Technicians rebalanced tape tension after light lines showed through on nappa wallets.', 'context' => 'folder-line'],
                ],
            ],
            'pt' => [
                'term' => 'Dobra de bordo',
                'slug' => 'dobra-de-bordo',
                'short_definition' => 'Virar a margem rebaixada sobre si ou sobre fita de reforço antes da costura.',
                'full_definition' => 'Máquinas de dobra usam biqueiras aquecidas e tensores; fita mal ajustada marca em couros claros.',
                'examples' => [
                    ['example' => 'Os técnicos rebalancearam a tensão da fita após linhas visíveis em carteiras de napa.', 'context' => 'linha-dobra'],
                ],
            ],
        ],
    ],
    [
        'key' => 'hook-and-loop-fastener',
        'domains' => ['hardware', 'footwear'],
        'translations' => [
            'en' => [
                'term' => 'Hook-and-loop fastener',
                'slug' => 'hook-and-loop-fastener',
                'short_definition' => 'Engageable textile closure system used on kids, safety, and adaptive footwear for fast adjust.',
                'full_definition' => 'Cycle life and peel values are specified per brand. Sew-on vs molded hooks change failure mode under mud and grit.',
                'examples' => [
                    ['example' => 'Lab downgraded a molded hook tape after 5k cycles lost 30% peel when contaminated with construction dust.', 'context' => 'materials-test'],
                ],
            ],
            'pt' => [
                'term' => 'Velcro (gancho e laço)',
                'slug' => 'velcro-gancho-e-laco',
                'short_definition' => 'Sistema têxtil engatavel usado em infantil, segurança e calçado adaptativo para ajuste rápido.',
                'full_definition' => 'Ciclos de vida e peel constam na ficha; ganchos cosidos vs injetados mudam o modo de falha com lama e poeira.',
                'examples' => [
                    ['example' => 'O laboratório desclassificou fita injetada após 5k ciclos perderem 30% de peel com poeira de obra.', 'context' => 'ensaio-materiais'],
                ],
            ],
        ],
    ],
    [
        'key' => 'quick-release-buckle',
        'domains' => ['belts', 'hardware'],
        'translations' => [
            'en' => [
                'term' => 'Quick release buckle',
                'slug' => 'quick-release-buckle',
                'short_definition' => 'Hardware that disengages strap tension with a single action for uniforms and tool belts.',
                'full_definition' => 'Release mechanisms are proof-loaded separately from tongue holes. Corrosion class must match sweat and wash cycles.',
                'examples' => [
                    ['example' => 'Military spec testing added salt fog cycles after latch corrosion appeared in tropical depot storage.', 'context' => 'hardware-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Fivela de liberação rápida',
                'slug' => 'fivela-de-liberacao-rapida',
                'short_definition' => 'Ferragem que liberta a tensão da tira com um único gesto, comum em uniformes e cintos de ferramenta.',
                'full_definition' => 'O mecanismo ensaia-se à prova separadamente dos furos; a classe de corrosão segue suor e lavagens.',
                'examples' => [
                    ['example' => 'Especificação militar acrescentou névoa salina após corrosão da trinca em armazém tropical.', 'context' => 'lab-ferragens'],
                ],
            ],
        ],
    ],
    [
        'key' => 'channel-stitching',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Channel stitching',
                'slug' => 'channel-stitching',
                'short_definition' => 'Stitching into a pre-cut channel on the insole or midsole to bury thread for welted builds.',
                'full_definition' => 'Channel depth and wall angle control thread protection and water path. Recutting channels is a controlled rework with bond reactivation steps.',
                'examples' => [
                    ['example' => 'Bench audit found shallow channels on size 45 left pairs, prompting a CNC channel depth offset.', 'context' => 'bench-quality'],
                ],
            ],
            'pt' => [
                'term' => 'Costura em canal',
                'slug' => 'costura-em-canal',
                'short_definition' => 'Costura num canal pré-cortado na entressola ou entressola intermédia para enterrar o fio em construções com viés.',
                'full_definition' => 'Profundidade e ângulo da parede protegem o fio e o percurso da água; reabrir canal é retrabalho controlado com reativação de cola.',
                'examples' => [
                    ['example' => 'Auditoria de bancada encontrou canais rasos no 45 esquerdo, levando a offset CNC de profundidade.', 'context' => 'cq-bancada'],
                ],
            ],
        ],
    ],
    [
        'key' => 'rand-stitching',
        'domains' => ['footwear', 'finishing'],
        'translations' => [
            'en' => [
                'term' => 'Rand stitching',
                'slug' => 'rand-stitching',
                'short_definition' => 'Visible stitch through rand or storm welt that secures welt to midsole or outsole on heavy boots.',
                'full_definition' => 'Rand lines set aesthetic rhythm and water management. Stitch sink and wax fill are graded on final audit photos.',
                'examples' => [
                    ['example' => 'Finishing rejected pairs when rand wax fill skipped voids under stitch sink on the waist curve.', 'context' => 'final-inspection'],
                ],
            ],
            'pt' => [
                'term' => 'Costura de rand',
                'slug' => 'costura-de-rand',
                'short_definition' => 'Costura visível através do rand ou viés de tempestade que fixa viés à entressola ou solado em botas pesadas.',
                'full_definition' => 'A linha define ritmo estético e gestão da água; afundamento de ponto e enchimento de cera avaliam-se em fotos de auditoria final.',
                'examples' => [
                    ['example' => 'Acabamentos rejeitou pares com vazios de cera sob o afundamento do ponto na curva da cintura.', 'context' => 'inspecao-final'],
                ],
            ],
        ],
    ],
    [
        'key' => 'opanka-construction',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Opanka construction',
                'slug' => 'opanka-construction',
                'short_definition' => 'Stitching where thread passes through upper edge, footbed, and sole wrap in moccasin-like load paths.',
                'full_definition' => 'Opanka variants differ in whether thread is decorative only or structural. QA focuses on abrasion where thread exits the sidewall.',
                'examples' => [
                    ['example' => 'Development added a sidewall bumper after abrasion testing exposed thread on sharp curb contacts.', 'context' => 'wear-lab'],
                ],
            ],
            'pt' => [
                'term' => 'Construção opanka',
                'slug' => 'construcao-opanka',
                'short_definition' => 'Costura em que o fio atravessa bainha, palmilha e envolvimento do solado em percursos tipo mocassim.',
                'full_definition' => 'As variantes diferem se o fio é decorativo ou estrutural; o QC foca abrasão na saída do fio na lateral.',
                'examples' => [
                    ['example' => 'Desenvolvimento acrescentou proteção lateral após abrasão expor o fio em contactos com cunhos urbanos.', 'context' => 'lab-uso'],
                ],
            ],
        ],
    ],
    [
        'key' => 'stacked-heel',
        'domains' => ['footwear', 'hardware', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Stacked heel',
                'slug' => 'stacked-heel',
                'short_definition' => 'Heel built from laminated leather or fibre lifts with nailed or screwed top lifts.',
                'full_definition' => 'Stack height drives pitch and shank placement. Finishers sand lifts to blend before top lift rubber is cemented.',
                'examples' => [
                    ['example' => 'The heel room changed nail pattern after lift shift caused squeak under torsional load in wear trials.', 'context' => 'heel-assembly'],
                ],
            ],
            'pt' => [
                'term' => 'Salto empilhado',
                'slug' => 'salto-empilhado',
                'short_definition' => 'Salto em camadas de couro ou fibra com prego ou parafuso no topo de borracha.',
                'full_definition' => 'A altura define inclinação e posição do shank; acabamentos lixam camadas antes de colar o topo de borracha.',
                'examples' => [
                    ['example' => 'A sala de saltos mudou o padrão de pregos após deslize de camada causar ranger em torção nos ensaios.', 'context' => 'montagem-saltos'],
                ],
            ],
        ],
    ],
    [
        'key' => 'inseam-stitch',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Inseam stitch',
                'slug' => 'inseam-stitch',
                'short_definition' => 'Internal seam closing the upper lining and margin before lasting, often hidden from external audit.',
                'full_definition' => 'Inseam integrity affects sand and grit ingress. High-end lines add waxed thread and higher SPI at the throat curve.',
                'examples' => [
                    ['example' => 'Closing added a second inseam tack at the throat after sand ingress claims on desert boots.', 'context' => 'closing-engineering'],
                ],
            ],
            'pt' => [
                'term' => 'Costura de entrecosto',
                'slug' => 'costura-de-entrecosto',
                'short_definition' => 'Costura interna que fecha forro e bainha antes da moldação, muitas vezes oculta à auditoria exterior.',
                'full_definition' => 'A integridade evita entrada de areia; linhas premium usam linha encerada e maior SPI na curva do peito de pé.',
                'examples' => [
                    ['example' => 'O fecho acrescentou segundo reforço no peito de pé após reclamações de areia em botas de deserto.', 'context' => 'engenharia-fecho'],
                ],
            ],
        ],
    ],
    [
        'key' => 'forepart-lasting',
        'domains' => ['footwear', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Forepart lasting',
                'slug' => 'forepart-lasting',
                'short_definition' => 'First-stage lasting focused on toe and vamp anchoring before side and heel pulls.',
                'full_definition' => 'Forepart tools set toe spring and asymmetry control. Mis-set pincers leave drag lines that show after burnish.',
                'examples' => [
                    ['example' => 'The line chief lowered forepart vacuum after toe spring read high on the last gauge for derby lasts.', 'context' => 'lasting-control'],
                ],
            ],
            'pt' => [
                'term' => 'Moldação da frente',
                'slug' => 'moldacao-da-frente',
                'short_definition' => 'Primeira fase da moldação focada na biqueira e fixação do vampão antes dos trações laterais e do calcanhar.',
                'full_definition' => 'As ferramentas da frente definem toe spring e controlo de assimetria; pinças mal reguladas deixam arranhões visíveis após brunimento.',
                'examples' => [
                    ['example' => 'O chefe de linha baixou o vácuo da frente após o toe spring sair alto no medidor de formas derby.', 'context' => 'controlo-moldacao'],
                ],
            ],
        ],
    ],
];
