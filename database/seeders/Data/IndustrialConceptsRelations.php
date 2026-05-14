<?php

/**
 * Semantic relations between concepts (keys = English slug keys from concept rows).
 *
 * @return list<array{from: string, to: string, type: string}>
 */
return [
    ['from' => 'lasting', 'to' => 'cementing', 'type' => 'related'],
    ['from' => 'lasting', 'to' => 'shoe-last', 'type' => 'related'],
    ['from' => 'skiving', 'to' => 'feather-edge', 'type' => 'related'],
    ['from' => 'skiving', 'to' => 'edge-beveling', 'type' => 'synonym'],
    ['from' => 'pull-up-leather', 'to' => 'leather-finishes-family', 'type' => 'broader'],
    ['from' => 'pure-aniline', 'to' => 'leather-finishes-family', 'type' => 'broader'],
    ['from' => 'toe-puff', 'to' => 'footwear-reinforcements', 'type' => 'broader'],
    ['from' => 'heel-counter', 'to' => 'footwear-reinforcements', 'type' => 'broader'],
    ['from' => 'shank-spring', 'to' => 'footwear-reinforcements', 'type' => 'broader'],
    ['from' => 'goodyear-welt', 'to' => 'welt', 'type' => 'related'],
    ['from' => 'blake-stitch', 'to' => 'outsole', 'type' => 'related'],
    ['from' => 'nesting', 'to' => 'marker-efficiency', 'type' => 'related'],
    ['from' => 'grading', 'to' => 'size-run', 'type' => 'related'],
    ['from' => 'aql-sampling', 'to' => 'nonconformance-ticket', 'type' => 'related'],
    ['from' => 'tech-pack', 'to' => 'spec-clarification', 'type' => 'related'],
    ['from' => 'vamp', 'to' => 'seam-allowance', 'type' => 'related'],
    ['from' => 'clicking', 'to' => 'die-cutting', 'type' => 'synonym'],
    ['from' => 'edge-painting', 'to' => 'burnishing', 'type' => 'related'],
    ['from' => 'cad-digitizing', 'to' => 'plotter-marking', 'type' => 'related'],
    ['from' => 'belt-loop', 'to' => 'rivet-setting', 'type' => 'related'],
];
