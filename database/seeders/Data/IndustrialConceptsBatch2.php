<?php

/**
 * LexiCraft Glossary concepts (batch 2 of 3).
 *
 * @return list<array<string, mixed>>
 */
return [
    [
        'key' => 'pull-up-leather',
        'featured' => true,
        'domains' => ['leather', 'materials', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Pull-up leather',
                'slug' => 'pull-up-leather',
                'short_definition' => 'Aniline-type leather with waxes/oils that lighten when stretched, showing intentional color movement on flex.',
                'full_definition' => 'Pull-up hides are specified for casual boots and belts where patina is a feature. Cutting direction and panel pairing matter because light lines appear along tension vectors.',
                'examples' => [
                    ['example' => 'Merchandising signed the bulk hide after confirming pull-up amplitude matched the showroom strike-off under plant lighting.', 'context' => 'leather-selection'],
                ],
            ],
            'pt' => [
                'term' => 'Couro pull-up',
                'slug' => 'couro-pull-up',
                'short_definition' => 'Couro tipo anilina com ceras/óleos que clareiam ao esticar, mostrando movimento de cor na flexão.',
                'full_definition' => 'O pull-up especifica-se para botas casuais e cintos onde a pátina é valor. O sentido de corte e o emparelhamento de painéis importam porque linhas claras seguem vetores de tração.',
                'examples' => [
                    ['example' => 'O comercial homologou o lote após confirmar que a amplitude de pull-up casava com o strike-off sob a luz da fábrica.', 'context' => 'selecao-peles'],
                ],
            ],
            'es' => [
                'term' => 'Cuero pull-up',
                'slug' => 'cuero-pull-up',
                'short_definition' => 'Cuero tipo anilina con ceras/aceites que aclaran al tensar.',
                'full_definition' => 'Se usa en botas y cinturones donde la patina es deseable; la dirección de corte marca líneas claras bajo tensión.',
                'examples' => [['example' => 'Aprobaron el lote tras comparar el pull con la muestra piloto bajo luz de taller.', 'context' => 'curtidos']],
            ],
        ],
    ],
    [
        'key' => 'pure-aniline',
        'domains' => ['leather', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'Pure aniline',
                'slug' => 'pure-aniline',
                'short_definition' => 'Minimal pigment finish that keeps grain clarity while trading some stain resistance.',
                'full_definition' => 'Pure aniline is chosen for high-end casuals where natural marks remain visible. QC tightens on light fastness and rub tests versus pigmented splits.',
                'examples' => [
                    ['example' => 'Lab downgraded a supplier after dry rub transferred dye onto white socks at half the claimed double rubs.', 'context' => 'testing'],
                ],
            ],
            'pt' => [
                'term' => 'Anilina pura',
                'slug' => 'anilina-pura',
                'short_definition' => 'Acabamento com pigmento mínimo que preserva o grão trocando parte da resistência a manchas.',
                'full_definition' => 'A anilina pura serve calçado premium onde marcas naturais são aceites; o QC reforça solidez à luz e ensaios de fricção versus splits pigmentados.',
                'examples' => [
                    ['example' => 'O laboratório desclassificou um fornecedor após fricção seca transferir cor para meias brancas abaixo do valor declarado.', 'context' => 'ensaio'],
                ],
            ],
        ],
    ],
    [
        'key' => 'drum-dyeing',
        'domains' => ['leather', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Drum dyeing',
                'slug' => 'drum-dyeing',
                'short_definition' => 'Exhaust dyeing of crust or wet-blue in rotating drums with controlled liquor ratio and temperature ramps.',
                'full_definition' => 'Drum recipes set strike depth and handle. Tannery QA correlates lab dips to production lots before footwear bulk cutting.',
                'examples' => [
                    ['example' => 'The tannery held a split lot after drum sensors logged a 3 °C overshoot during the fatliquor phase.', 'context' => 'tannery'],
                ],
            ],
            'pt' => [
                'term' => 'Tingimento em tambor',
                'slug' => 'tingimento-em-tambor',
                'short_definition' => 'Tingimento por exaustão de crus ou wet-blue em tambores rotativos com razão de licor e rampas térmicas controladas.',
                'full_definition' => 'As receitas definem profundidade de batida e tacto; o QC da curtume correlaciona dips de laboratório com lotes antes do corte massivo em calçado.',
                'examples' => [
                    ['example' => 'A curtume reteve um lote após sensores registarem +3 °C na fase de fluoretação.', 'context' => 'curtume'],
                ],
            ],
        ],
    ],
    [
        'key' => 'splitting-leather',
        'domains' => ['leather', 'machinery'],
        'translations' => [
            'en' => [
                'term' => 'Splitting',
                'slug' => 'splitting-leather',
                'short_definition' => 'Band-knife reduction of substance to target thickness before clicking or skiving.',
                'full_definition' => 'Splitting evens substance for linings and belts. Feed angle and knife dullness create diagonal grain waves visible after finish.',
                'examples' => [
                    ['example' => 'Technicians trued the band after caliper maps showed a 0.15 mm wedge across a crust side.', 'context' => 'splitting-room'],
                ],
            ],
            'pt' => [
                'term' => 'Folheado / desdobramento',
                'slug' => 'folheado-desdobramento',
                'short_definition' => 'Redução por faca contínua da espessura até valor alvo antes do clicking ou rebaixamento.',
                'full_definition' => 'O desdobramento uniformiza substância para forros e tiras; ângulo de alimentação e faca desgastada criam ondulações diagonais visíveis após acabamento.',
                'examples' => [
                    ['example' => 'Afinaram a faca contínua após mapas de paquímetro mostrarem cunha de 0,15 mm num flank de crus.', 'context' => 'sala-folheado'],
                ],
            ],
        ],
    ],
    [
        'key' => 'leather-yield',
        'domains' => ['leather', 'cad-cam', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Leather yield',
                'slug' => 'leather-yield',
                'short_definition' => 'Square meters of cut parts recovered per hide or per weighted average hide equivalent.',
                'full_definition' => 'Yield ties clicking nests to costing. Merchandising models defect clusters by supplier and shade batch to avoid optimistic nesting assumptions.',
                'examples' => [
                    ['example' => 'Costing dropped yield 3% after the new supplier’s vein map shifted median nest rotation time upward.', 'context' => 'costing'],
                ],
            ],
            'pt' => [
                'term' => 'Rendimento de pele',
                'slug' => 'rendimento-de-pele',
                'short_definition' => 'Metros quadrados de peças cortadas recuperados por pele ou equivalente médio ponderado.',
                'full_definition' => 'O rendimento liga nesting do clicking ao custo; o modelo comercial incorpora clusters de defeito por fornecedor e lote de tonalidade.',
                'examples' => [
                    ['example' => 'O custeio baixou o rendimento 3% após o mapa de veios do novo fornecedor aumentar o tempo médio de rotação do nest.', 'context' => 'custos'],
                ],
            ],
        ],
    ],
    [
        'key' => 'nesting',
        'featured' => true,
        'domains' => ['cad-cam', 'production', 'leather'],
        'translations' => [
            'en' => [
                'term' => 'Nesting',
                'slug' => 'nesting',
                'short_definition' => 'Algorithmic or manual placement of pattern pieces on hides or rolls to maximize yield under constraints.',
                'full_definition' => 'Nesting engines honor grain direction, pair symmetry, and defect masks. Planners reconcile marker efficiency with cutting room throughput.',
                'examples' => [
                    ['example' => 'The CAM operator locked vamp pairs after the auto-nest flipped grain on the lateral quarter.', 'context' => 'cam-office'],
                ],
            ],
            'pt' => [
                'term' => 'Aninhamento (nesting)',
                'slug' => 'aninhamento-nesting',
                'short_definition' => 'Colocação algorítmica ou manual de peças em peles ou rolos para maximizar rendimento sob restrições.',
                'full_definition' => 'Os motores de nesting respeitam direção de grão, simetria do par e máscaras de defeito; o planeamento equaciona aproveitamento com cadência da sala de corte.',
                'examples' => [
                    ['example' => 'O operador CAM fixou pares de vampão após o auto-nest inverter o grão no quarto lateral.', 'context' => 'escritorio-cam'],
                ],
            ],
            'fr' => [
                'term' => 'Placement (nesting)',
                'slug' => 'placement-nesting',
                'short_definition' => 'Disposition des pièces sur peaux ou rouleaux pour maximiser le rendement.',
                'full_definition' => 'Le nesting respecte le sens du grain, la symétrie paire et les masques de défauts.',
                'examples' => [['example' => 'L’opérateur a verrouillé les paires après inversion du grain.', 'context' => 'DAO']],
            ],
        ],
    ],
    [
        'key' => 'marker-efficiency',
        'domains' => ['cad-cam', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'Marker efficiency',
                'slug' => 'marker-efficiency',
                'short_definition' => 'Ratio of used material area to bounding rectangle or hide outline after nesting.',
                'full_definition' => 'Efficiency KPIs feed supplier chargebacks and CAD tuning. Low efficiency on small sizes often signals grading increments misaligned with hide shape.',
                'examples' => [
                    ['example' => 'Weekly yield reports flagged marker 14B after efficiency fell 4 points without a hide quality change.', 'context' => 'planning'],
                ],
            ],
            'pt' => [
                'term' => 'Aproveitamento de marcas',
                'slug' => 'aproveitamento-de-marcas',
                'short_definition' => 'Rácio entre área útil ocupada e o contorno da pele ou retângulo envolvente após nesting.',
                'full_definition' => 'KPIs de aproveitamento alimentam penalizações a fornecedores e afinação CAD; queda nos tamanhos pequenos sugere increments de graduação fora da forma da pele.',
                'examples' => [
                    ['example' => 'Relatórios semanais sinalizaram a marca 14B após queda de 4 pontos sem alteração de qualidade de pele.', 'context' => 'planeamento'],
                ],
            ],
        ],
    ],
    [
        'key' => 'cad-digitizing',
        'domains' => ['cad-cam', 'pattern-making'],
        'translations' => [
            'en' => [
                'term' => 'CAD digitizing',
                'slug' => 'cad-digitizing',
                'short_definition' => 'Capturing physical patterns into vector CAD with seam lines, notches, and drill points preserved.',
                'full_definition' => 'Digitizing bridges legacy paper and new seasons. Operators verify scale bars and joint alignments before releasing to grading servers.',
                'examples' => [
                    ['example' => 'QA rejected a digitized collar piece after the scale bar drifted 0.3% against the master scanner tape.', 'context' => 'digitizing-table'],
                ],
            ],
            'pt' => [
                'term' => 'Digitalização CAD',
                'slug' => 'digitalizacao-cad',
                'short_definition' => 'Captura de malhas físicas para CAD vetorial preservando linhas de costura, entalhes e pontos de furação.',
                'full_definition' => 'A digitalização liga papel legado a novas coleções; verifica-se barra de escala e alinhamento de juntas antes de libertar para servidores de graduação.',
                'examples' => [
                    ['example' => 'O QC rejeitou uma peça digitalizada do colarinho após deriva de 0,3% na barra de escala face à fita mestre.', 'context' => 'mesa-digitalizacao'],
                ],
            ],
            'it' => [
                'term' => 'Digitalizzazione CAD',
                'slug' => 'digitalizzazione-cad',
                'short_definition' => 'Acquisizione di cartamodello fisico in CAD vettoriale.',
                'full_definition' => 'Controllo barra di scala e allineamenti di giunti prima del rilascio in grading.',
                'examples' => [['example' => 'Scartata una tomaia digitalizzata per deriva dello scanner.', 'context' => 'cad']],
            ],
        ],
    ],
    [
        'key' => 'plotter-marking',
        'domains' => ['cad-cam', 'pattern-making'],
        'translations' => [
            'en' => [
                'term' => 'Plotter marking',
                'slug' => 'plotter-marking',
                'short_definition' => 'Ink-jet or pen plotting of nests onto hides or paper for manual or assisted cutting.',
                'full_definition' => 'Plotter alignment uses camera registration on hide defects. Misregistration wastes high-yield areas and triggers recuts.',
                'examples' => [
                    ['example' => 'The plotter team re-zeroed cameras after drift caused a 4 mm shift on double-size hides.', 'context' => 'plotter-room'],
                ],
            ],
            'pt' => [
                'term' => 'Marcação em plotter',
                'slug' => 'marcacao-em-plotter',
                'short_definition' => 'Traçado a jato de tinta ou caneta de nests em peles ou papel para corte manual ou assistido.',
                'full_definition' => 'O alinhamento usa câmaras de registo em defeitos de pele; desalinhamento desperdiça zonas de alto rendimento e gera recortes.',
                'examples' => [
                    ['example' => 'A equipa do plotter fez zero às câmaras após deriva de 4 mm em peles duplo tamanho.', 'context' => 'sala-plotter'],
                ],
            ],
        ],
    ],
    [
        'key' => 'cad-layer-convention',
        'domains' => ['cad-cam', 'design'],
        'translations' => [
            'en' => [
                'term' => 'CAD layer convention',
                'slug' => 'cad-layer-convention',
                'short_definition' => 'Named layer rules separating net lines, sew lines, grading points, and machine reference geometry.',
                'full_definition' => 'Conventions prevent CAM errors when files round-trip between brands and factories. Locked layers protect joint geometry from accidental edits.',
                'examples' => [
                    ['example' => 'The PLM admin enforced a new layer color for drill holes after a contractor merged them into net geometry.', 'context' => 'plm'],
                ],
            ],
            'pt' => [
                'term' => 'Convenção de camadas CAD',
                'slug' => 'convencao-camadas-cad',
                'short_definition' => 'Regras de camadas nomeadas para linhas líquidas, linhas de costura, pontos de grade e geometria de referência de máquina.',
                'full_definition' => 'As convenções evitam erros CAM em ida-e-volta entre marca e fábrica; camadas bloqueadas protegem juntas de edições acidentais.',
                'examples' => [
                    ['example' => 'O admin PLM impôs cor nova para furos após um subcontratante fundi-los na geometria líquida.', 'context' => 'plm'],
                ],
            ],
        ],
    ],
    [
        'key' => 'tech-pack',
        'featured' => true,
        'domains' => ['design', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'Tech pack',
                'slug' => 'tech-pack',
                'short_definition' => 'Controlled document set: BOM, construction sequence, tolerances, test methods, and labeled photos.',
                'full_definition' => 'Tech packs are the legal-technical handshake between brand and factory. Revision tables must track adhesive changes and hardware swaps that affect bond tests.',
                'examples' => [
                    ['example' => 'Revision C added a secondary pull test on the new TPU eyelet after field returns on cold embrittlement.', 'context' => 'product-development'],
                ],
            ],
            'pt' => [
                'term' => 'Ficha técnica (tech pack)',
                'slug' => 'ficha-tecnica-tech-pack',
                'short_definition' => 'Conjunto documental: lista de materiais, sequência de construção, tolerâncias, métodos de ensaio e fotos legendadas.',
                'full_definition' => 'A ficha técnica é o contrato técnico entre marca e fábrica; a tabela de revisões tem de acompanhar mudanças de cola ou ferragens que afetem ensaios de aderência.',
                'examples' => [
                    ['example' => 'A revisão C acrescentou ensaio de arranque secundário no novo ilhó TPU após devoluções de campo por embritamento a frio.', 'context' => 'desenvolvimento-produto'],
                ],
            ],
        ],
    ],
    [
        'key' => 'spec-clarification',
        'domains' => ['design', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'Specification clarification',
                'slug' => 'spec-clarification',
                'short_definition' => 'Formal engineering responses to ambiguous callouts on patterns, labels, or test limits.',
                'full_definition' => 'Clarifications are logged with effective PO ranges. Ambiguity on edge paint thickness often originates from mismatched macro photos versus section views.',
                'examples' => [
                    ['example' => 'Engineering issued clarification ECN-118 limiting edge paint build to 0.4 mm on the strap radius.', 'context' => 'engineering-change'],
                ],
            ],
            'pt' => [
                'term' => 'Esclarecimento de especificação',
                'slug' => 'esclarecimento-especificacao',
                'short_definition' => 'Respostas formais da engenharia a chamadas ambíguas em malhas, rótulos ou limites de ensaio.',
                'full_definition' => 'Os esclarecimentos ficam registados com intervalos de PO efetivos; ambiguidade na espessura de tinta de bordo costuma vir de macros desalinhadas com cortes.',
                'examples' => [
                    ['example' => 'A engenharia emitiu ECN-118 limitando a tinta de bordo a 0,4 mm no raio da tira.', 'context' => 'mudanca-tecnica'],
                ],
            ],
        ],
    ],
    [
        'key' => 'colorway-traceability',
        'domains' => ['design', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Colorway traceability',
                'slug' => 'colorway-traceability',
                'short_definition' => 'Lot-level linkage from dye drums and hardware batches to carton labels and retailer claims.',
                'full_definition' => 'Traceability closes the loop on shade banding and hardware plating variance. RFID or 2D carton codes feed into warranty analytics.',
                'examples' => [
                    ['example' => 'Customer service traced a navy shift to drum lot 44C after three regions reported hue drift within the same PO.', 'context' => 'traceability'],
                ],
            ],
            'pt' => [
                'term' => 'Rastreabilidade de colorway',
                'slug' => 'rastreabilidade-colorway',
                'short_definition' => 'Ligação a nível de lote entre tambores de tinta, lotes de ferragens e rótulos de caixa ou reclamações de retalho.',
                'full_definition' => 'A rastreabilidade fecha o ciclo em banding de tom e variância de galvanização; códigos 2D nas caixas alimentam análise de garantia.',
                'examples' => [
                    ['example' => 'O SAC rastreou um desvio de azul ao lote 44C após três regiões reportarem deriva de tom na mesma PO.', 'context' => 'rastreio'],
                ],
            ],
        ],
    ],
    [
        'key' => 'strike-off',
        'domains' => ['materials', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Strike-off',
                'slug' => 'strike-off',
                'short_definition' => 'Pilot finish sample on production leather used to sign color and handle before bulk.',
                'full_definition' => 'Strike-offs include light booth sign-off and flex crease evaluation. Deviations trigger drum recipe tweaks, not just spray line tweaks.',
                'examples' => [
                    ['example' => 'Design rejected the strike-off after crease whitening exceeded the brand’s casual line standard under 60k flex.', 'context' => 'lab-sign-off'],
                ],
            ],
            'pt' => [
                'term' => 'Amostra piloto (strike-off)',
                'slug' => 'amostra-piloto-strike-off',
                'short_definition' => 'Amostra de acabamento em pele de produção para homologar cor e tacto antes do volume.',
                'full_definition' => 'O strike-off inclui cabine de luz e avaliação de branqueamento em pregas; desvios levam a ajustes de receita de tambor, não só de linha de spray.',
                'examples' => [
                    ['example' => 'O design rejeitou o strike-off após branqueamento na prega exceder o standard casual da marca aos 60k flexões.', 'context' => 'homologacao-lab'],
                ],
            ],
        ],
    ],
    [
        'key' => 'pattern-notching',
        'domains' => ['pattern-making', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Pattern notching',
                'slug' => 'pattern-notching',
                'short_definition' => 'Small cuts or punches on margins that register paired pieces during closing or lasting.',
                'full_definition' => 'Notch schemes differ between mirror pairs and single-layer nests. Missing notches create downstream twist faults that are expensive to rework.',
                'examples' => [
                    ['example' => 'Closing stopped a bundle after laser QC found mirrored quarters missing the throat alignment notch.', 'context' => 'closing'],
                ],
            ],
            'pt' => [
                'term' => 'Entalhe de padrão',
                'slug' => 'entalhe-de-padrao',
                'short_definition' => 'Pequenos cortes ou furos nas margens para alinhar peças emparelhadas no fecho ou na moldação.',
                'full_definition' => 'Os esquemas de entalhe diferem entre pares espelhados e nests de camada única; falta de entalhe gera torções caras de retrabalhar.',
                'examples' => [
                    ['example' => 'O fecho parou um molde após QC laser detetar quartos espelhados sem o entalhe de alinhamento do peito de pé.', 'context' => 'fecho'],
                ],
            ],
        ],
    ],
    [
        'key' => 'notch-alignment',
        'domains' => ['production', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'Notch alignment',
                'slug' => 'notch-alignment',
                'short_definition' => 'Operator verification that pattern notches meet within tolerance before critical seams.',
                'full_definition' => 'Alignment checks protect spiral seams and inset panels. Digital work aids project notch targets onto cylinder beds.',
                'examples' => [
                    ['example' => 'The supervisor rejected a spiral seam setup when throat notches misaligned by 2 mm under clamp tension.', 'context' => 'closing-floor'],
                ],
            ],
            'pt' => [
                'term' => 'Alinhamento de entalhes',
                'slug' => 'alinhamento-de-entalhes',
                'short_definition' => 'Verificação de que entalhes da malha coincidem dentro de tolerância antes de costuras críticas.',
                'full_definition' => 'O alinhamento protege costuras em espiral e painéis embutidos; auxílios digitais projetam alvos no braço cilíndrico.',
                'examples' => [
                    ['example' => 'O supervisor recusou a montagem de costura em espiral com entalhes do peito de pé desfasados 2 mm sob tensão de garra.', 'context' => 'chao-fecho'],
                ],
            ],
        ],
    ],
    [
        'key' => 'footwear-reinforcements',
        'domains' => ['footwear', 'materials'],
        'translations' => [
            'en' => [
                'term' => 'Footwear reinforcements',
                'slug' => 'footwear-reinforcements',
                'short_definition' => 'Family of stiffeners and inserts controlling forepart height, heel collapse, and torsional behavior.',
                'full_definition' => 'This family spans toe puffs, counters, shanks, and composite boards. Stack height interacts with lasting margin and collar foam compression.',
                'examples' => [
                    ['example' => 'R&D grouped puff, counter, and shank trials under one reinforcement DOE for the new hiking last.', 'context' => 'rnd'],
                ],
            ],
            'pt' => [
                'term' => 'Reforços de calçado',
                'slug' => 'reforcos-de-calcado',
                'short_definition' => 'Família de escarpins, ponteiros e insertos que controlam altura da frente, colapso do calcanhar e torção.',
                'full_definition' => 'Agrupa ponteiros, escarpins, shanks e cartões compósitos; a altura do pacote interage com margem de moldação e compressão do colarinho.',
                'examples' => [
                    ['example' => 'A I&D agrupou ensaios de ponteira, escarpim e shank num DOE único para a nova forma de trekking.', 'context' => 'i-d'],
                ],
            ],
        ],
    ],
    [
        'key' => 'aql-sampling',
        'featured' => true,
        'domains' => ['quality-control', 'production'],
        'translations' => [
            'en' => [
                'term' => 'AQL sampling',
                'slug' => 'aql-sampling',
                'short_definition' => 'Acceptance sampling by lot size and inspection level to decide shipment release against defect tables.',
                'full_definition' => 'AQL plans trade consumer risk against inspection cost. Footwear plants often tighten footwear-specific defects like asymmetry and bond peel beyond generic garment tables.',
                'examples' => [
                    ['example' => 'QC tightened to level III on the first bulk of winter boots after a sole chalking defect cluster in the pilot.', 'context' => 'final-audit'],
                ],
            ],
            'pt' => [
                'term' => 'Amostragem AQL',
                'slug' => 'amostragem-aql',
                'short_definition' => 'Amostragem por tamanho de lote e nível de inspeção para libertar expedições segundo tabelas de defeitos.',
                'full_definition' => 'Os planos AQL equilibram risco ao consumidor e custo de inspeção; em calçado reforça-se asimetria e descolagem para além de tabelas genéricas de vestuário.',
                'examples' => [
                    ['example' => 'O CQ endureceu para nível III na primeira série de botas de inverno após cluster de gizagem na sola no piloto.', 'context' => 'auditoria-final'],
                ],
            ],
        ],
    ],
    [
        'key' => 'nonconformance-ticket',
        'domains' => ['quality-control', 'production'],
        'translations' => [
            'en' => [
                'term' => 'Nonconformance ticket',
                'slug' => 'nonconformance-ticket',
                'short_definition' => 'Documented defect record with disposition, root cause, and containment actions tied to lot or serial.',
                'full_definition' => 'NC tickets feed supplier scorecards and CAPA. Photos and measurement data must survive retailer audits months later.',
                'examples' => [
                    ['example' => 'Line 3 opened NC-2041 for welt stitch skip after the vision system logged three consecutive alarms on size 41 left.', 'context' => 'capa'],
                ],
            ],
            'pt' => [
                'term' => 'Ficha de não conformidade',
                'slug' => 'ficha-nao-conformidade',
                'short_definition' => 'Registo de defeito com disposição, causa raiz e contenção associados a lote ou série.',
                'full_definition' => 'As NC alimentam scorecards de fornecedores e CAPA; fotos e medições têm de suportar auditorias de retalho meses depois.',
                'examples' => [
                    ['example' => 'A linha 3 abriu NC-2041 por salto de ponto no viés após o sistema de visão gerar três alarmes seguidos no 41 esquerdo.', 'context' => 'capa'],
                ],
            ],
        ],
    ],
    [
        'key' => 'tolerance-stack',
        'domains' => ['quality-control', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Tolerance stack-up',
                'slug' => 'tolerance-stack',
                'short_definition' => 'Accumulated variation across laminated stacks, seams, and tooling that can consume design margin.',
                'full_definition' => 'Stacks are modeled worst-case and RSS. A small foam tolerance shift can close instep volume when paired with a last girth change.',
                'examples' => [
                    ['example' => 'Engineering reopened the stack model after collar foam +0.2 mm and lining −0.1 mm both landed on the hot side.', 'context' => 'dimensional-engineering'],
                ],
            ],
            'pt' => [
                'term' => 'Acúmulo de tolerâncias',
                'slug' => 'acumulo-de-tolerancias',
                'short_definition' => 'Variação acumulada em pacotes laminados, costuras e ferramental que pode consumir a margem de desenho.',
                'full_definition' => 'Modela-se pior caso e RSS; um pequeno desvio na espuma pode fechar o volume do peito de pé quando somado a alteração de perímetro na forma.',
                'examples' => [
                    ['example' => 'A engenharia reabriu o modelo de pacote após espuma do colarinho +0,2 mm e forro −0,1 mm caírem no lado quente.', 'context' => 'engenharia-dimensional'],
                ],
            ],
        ],
    ],
    [
        'key' => 'thickness-gauging',
        'domains' => ['quality-control', 'leather'],
        'translations' => [
            'en' => [
                'term' => 'Thickness gauging',
                'slug' => 'thickness-gauging',
                'short_definition' => 'Caliper or ultrasound checks of substance against supplier bands before clicking or splitting.',
                'full_definition' => 'Gauging maps are stored by hide quadrant for dispute resolution. Drift in median substance often precedes tannery drum issues.',
                'examples' => [
                    ['example' => 'Incoming QC flagged hides when flank median exceeded the PO band by 0.25 mm across five consecutive rolls.', 'context' => 'incoming-qc'],
                ],
            ],
            'pt' => [
                'term' => 'Medição de espessura',
                'slug' => 'medicao-de-espessura',
                'short_definition' => 'Controlo com paquímetro ou ultrassom da substância face às faixas do fornecedor antes do corte ou folheado.',
                'full_definition' => 'Os mapas guardam-se por quadrante para litígios; deriva da mediana antecipa problemas de tambor na curtume.',
                'examples' => [
                    ['example' => 'O CQ à entrada marcou peles quando a mediana do flank excedeu a faixa da PO em 0,25 mm em cinco rolos seguidos.', 'context' => 'cq-entrada'],
                ],
            ],
        ],
    ],
    [
        'key' => 'skiving-depth-tolerance',
        'domains' => ['quality-control', 'machinery'],
        'translations' => [
            'en' => [
                'term' => 'Skiving depth tolerance',
                'slug' => 'skiving-depth-tolerance',
                'short_definition' => 'Allowed deviation from nominal skive thickness along a path, often ±0.1 mm on critical featherlines.',
                'full_definition' => 'Depth tolerances tie to bond area and tear strength. First-piece checks each shift capture knife wear curves.',
                'examples' => [
                    ['example' => 'First-piece failed when throat skive averaged 0.18 mm over nominal, triggering knife swap before release.', 'context' => 'machine-setup'],
                ],
            ],
            'pt' => [
                'term' => 'Tolerância de profundidade de rebaixo',
                'slug' => 'tolerancia-profundidade-rebaixo',
                'short_definition' => 'Desvio permitido face à espessura nominal ao longo do traçado, frequentemente ±0,1 mm em penas críticas.',
                'full_definition' => 'A tolerância liga-se à área colada e ao rasgo; as primeiras peças por turno captam curvas de desgaste da faca.',
                'examples' => [
                    ['example' => 'A primeira peça falhou com rebaixo do peito de pé +0,18 mm face ao nominal, obrigando a troca de faca antes da libertação.', 'context' => 'preparacao-maquina'],
                ],
            ],
        ],
    ],
    [
        'key' => 'walk-test-cycle',
        'domains' => ['footwear', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'Walk test cycle',
                'slug' => 'walk-test-cycle',
                'short_definition' => 'Protocol of steps, surfaces, and duration used to validate pressure, slip, and noise before sign-off.',
                'full_definition' => 'Walk cycles are scripted for category risk: dress vs trail. Instrumented pressure insoles complement subjective fit notes.',
                'examples' => [
                    ['example' => 'Validation extended the walk cycle on wet tile after slip coefficients came in borderline on the new rubber blend.', 'context' => 'wear-test'],
                ],
            ],
            'pt' => [
                'term' => 'Ciclo de teste de marcha',
                'slug' => 'ciclo-teste-marcha',
                'short_definition' => 'Protocolo de passos, superfícies e duração para validar pressão, escorregamento e ruído antes da homologação.',
                'full_definition' => 'Os ciclos seguem risco de categoria; plantares instrumentados complementam notas subjetivas de calce.',
                'examples' => [
                    ['example' => 'A validação alongou o ciclo em cerâmica molhada após coeficientes de escorregamento limítrofes na nova mistura de borracha.', 'context' => 'teste-uso'],
                ],
            ],
        ],
    ],
    [
        'key' => 'flex-zone',
        'domains' => ['footwear', 'design'],
        'translations' => [
            'en' => [
                'term' => 'Flex zone',
                'slug' => 'flex-zone',
                'short_definition' => 'Engineered region where upper and outsole bend together without cracking finishes or over-compressing foam.',
                'full_definition' => 'Flex zones align vamp break, strobel line, and outsole notches. Misalignment accelerates surface cracking in cold flex.',
                'examples' => [
                    ['example' => 'Tooling shifted the secondary notch 2 mm aft after cold chamber flex showed cracking ahead of the vamp break.', 'context' => 'tooling'],
                ],
            ],
            'pt' => [
                'term' => 'Zona de flexão',
                'slug' => 'zona-de-flexao',
                'short_definition' => 'Região onde cabedal e solado dobram em conjunto sem fender acabamentos nem comprimir espuma em excesso.',
                'full_definition' => 'A zona alinha quebra do vampão, linha Strobel e entalhes da sola; desalinhamento acelera fendas em flexão a frio.',
                'examples' => [
                    ['example' => 'O molde deslocou o segundo entalhe 2 mm para trás após fendas à frente da quebra do vampão em câmara fria.', 'context' => 'ferramental'],
                ],
            ],
        ],
    ],
    [
        'key' => 'wip-buffer',
        'domains' => ['production', 'quality-control'],
        'translations' => [
            'en' => [
                'term' => 'WIP buffer',
                'slug' => 'wip-buffer',
                'short_definition' => 'Controlled semi-finished inventory between cells to absorb variation without starving downstream takt.',
                'full_definition' => 'Buffers are sized from cycle time variance and defect fallout. Over-buffering hides quality problems; under-buffering starves lasting during heel fit rework spikes.',
                'examples' => [
                    ['example' => 'Planning cut the WIP rack cap from 120 to 80 pairs after age analysis showed color rub on aged uppers.', 'context' => 'lean-planning'],
                ],
            ],
            'pt' => [
                'term' => 'Stock intermédio (WIP)',
                'slug' => 'stock-intermediario-wip',
                'short_definition' => 'Inventário de semi-acabados entre células para absorver variação sem matar o takt a jusante.',
                'full_definition' => 'Dimensiona-se pela variância de tempos e refugo; excesso esconde falhas de qualidade; défice deixa a moldação sem pares nos picos de retrabalho de calcanhar.',
                'examples' => [
                    ['example' => 'O planeamento reduziu o teto do carro WIP de 120 para 80 pares após análise de idade mostrar fricção de cor em cabedais envelhecidos.', 'context' => 'lean'],
                ],
            ],
        ],
    ],
];
