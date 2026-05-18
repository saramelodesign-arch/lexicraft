<?php

namespace App\Support;

/**
 * Minimal schematic SVGs for footwear construction concepts (not photographic assets).
 */
final class TechnicalFootwearDiagramSvg
{
    public const string VARIANT_WORKFLOW = 'workflow';

    public const string VARIANT_CUTAWAY = 'cutaway';

    /**
     * @return list<string>
     */
    public static function variantsForConcept(string $conceptKey): array
    {
        return match ($conceptKey) {
            'lasting', 'strobel-stitch', 'outsole', 'goodyear-welt' => [
                self::VARIANT_WORKFLOW,
                self::VARIANT_CUTAWAY,
            ],
            default => [self::VARIANT_WORKFLOW, self::VARIANT_CUTAWAY],
        };
    }

    public static function render(string $conceptKey, string $variant): string
    {
        $body = match ($conceptKey) {
            'lasting' => $variant === self::VARIANT_CUTAWAY
                ? self::lastingCutaway()
                : self::lastingWorkflow(),
            'welt' => $variant === self::VARIANT_CUTAWAY
                ? self::weltCutaway()
                : self::weltWorkflow(),
            'goodyear-welt' => $variant === self::VARIANT_CUTAWAY
                ? self::goodyearCutaway()
                : self::goodyearWorkflow(),
            'strobel-stitch' => $variant === self::VARIANT_CUTAWAY
                ? self::strobelCutaway()
                : self::strobelWorkflow(),
            'outsole' => $variant === self::VARIANT_CUTAWAY
                ? self::outsoleCutaway()
                : self::outsoleWorkflow(),
            'toe-puff' => $variant === self::VARIANT_CUTAWAY
                ? self::toePuffCutaway()
                : self::toePuffWorkflow(),
            'heel-counter' => $variant === self::VARIANT_CUTAWAY
                ? self::counterCutaway()
                : self::counterWorkflow(),
            'upper' => $variant === self::VARIANT_CUTAWAY
                ? self::upperCutaway()
                : self::upperWorkflow(),
            'heel-seat' => $variant === self::VARIANT_CUTAWAY
                ? self::heelSeatCutaway()
                : self::heelSeatWorkflow(),
            'shank-reinforcement' => $variant === self::VARIANT_CUTAWAY
                ? self::shankCutaway()
                : self::shankWorkflow(),
            default => self::genericWorkflow($conceptKey),
        };

        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 960 600" role="img" aria-hidden="true">'
            .'<rect width="960" height="600" fill="#f8fafc"/>'
            .'<g stroke="#334155" stroke-width="2" fill="none" font-family="ui-sans-serif,system-ui,sans-serif">'
            .$body
            .'</g></svg>';
    }

    private static function box(float $x, float $y, float $w, float $h, string $label, string $fill = '#e2e8f0'): string
    {
        $tx = $x + $w / 2;
        $ty = $y + $h / 2 + 5;

        return '<rect x="'.(int) $x.'" y="'.(int) $y.'" width="'.(int) $w.'" height="'.(int) $h.'" rx="6" fill="'.$fill.'"/>'
            .'<text x="'.(int) $tx.'" y="'.(int) $ty.'" text-anchor="middle" font-size="15" fill="#0f172a">'.$label.'</text>';
    }

    private static function arrow(float $x1, float $y1, float $x2, float $y2): string
    {
        return '<line x1="'.(int) $x1.'" y1="'.(int) $y1.'" x2="'.(int) $x2.'" y2="'.(int) $y2.'" marker-end="url(#arrow)"/>';
    }

    private static function markers(): string
    {
        return '<defs><marker id="arrow" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">'
            .'<path d="M0,0 L6,3 L0,6 Z" fill="#334155"/></marker></defs>';
    }

    private static function lastingWorkflow(): string
    {
        return self::markers()
            .self::box(40, 220, 150, 70, 'Upper release')
            .self::arrow(190, 255, 230, 255)
            .self::box(230, 220, 130, 70, 'Toe pull', '#dbeafe')
            .self::arrow(360, 255, 400, 255)
            .self::box(400, 220, 130, 70, 'Side pull', '#dbeafe')
            .self::arrow(530, 255, 570, 255)
            .self::box(570, 220, 130, 70, 'Seat pull', '#dbeafe')
            .self::arrow(700, 255, 740, 255)
            .self::box(740, 220, 180, 70, 'Bottoming handoff', '#dcfce7')
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Lasting station sequence</text>';
    }

    private static function lastingCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Forepart tension cross-section</text>'
            .'<path d="M120 480 L120 200 Q480 120 840 200 L840 480 Z" fill="#e2e8f0"/>'
            .'<path d="M200 460 L200 240 Q480 180 760 240 L760 460" stroke="#2563eb" stroke-width="3"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Upper margin tuck · toe-spring target · waist preload</text>';
    }

    private static function weltWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Welt perimeter stitch path</text>'
            .self::box(80, 240, 160, 70, 'Channel prep', '#dbeafe')
            .self::arrow(240, 275, 290, 275)
            .self::box(290, 240, 160, 70, 'Holdfast lock', '#dbeafe')
            .self::arrow(450, 275, 500, 275)
            .self::box(500, 240, 160, 70, 'Welt stitch', '#dbeafe')
            .self::arrow(660, 275, 710, 275)
            .self::box(710, 240, 170, 70, 'Outsole lock', '#dcfce7');
    }

    private static function weltCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Welt · rib · upper margin</text>'
            .'<rect x="160" y="160" width="640" height="320" fill="#e2e8f0"/>'
            .'<rect x="220" y="220" width="80" height="200" fill="#94a3b8"/>'
            .'<rect x="300" y="260" width="40" height="160" fill="#2563eb"/>'
            .'<rect x="340" y="300" width="360" height="40" fill="#16a34a"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Stitch sink in prepared groove</text>';
    }

    private static function goodyearWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Goodyear welt chain</text>'
            .self::box(60, 250, 140, 60, 'Rib attach', '#dbeafe')
            .self::arrow(200, 280, 240, 280)
            .self::box(240, 250, 120, 60, 'Gemming', '#dbeafe')
            .self::arrow(360, 280, 400, 280)
            .self::box(400, 250, 140, 60, 'Holdfast', '#dbeafe')
            .self::arrow(540, 280, 580, 280)
            .self::box(580, 250, 140, 60, 'Cork fill', '#dbeafe')
            .self::arrow(720, 280, 760, 280)
            .self::box(760, 250, 140, 60, 'Channel stitch', '#dcfce7');
    }

    private static function goodyearCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Goodyear layered section</text>'
            .'<rect x="200" y="180" width="120" height="260" fill="#94a3b8"/>'
            .'<rect x="320" y="240" width="50" height="200" fill="#2563eb"/>'
            .'<rect x="370" y="280" width="60" height="120" fill="#ca8a04"/>'
            .'<rect x="430" y="320" width="330" height="50" fill="#16a34a"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Upper · welt · cork · outsole</text>';
    }

    private static function strobelWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Strobel to lasting handoff</text>'
            .self::box(120, 250, 200, 70, 'Strobel board stitch', '#dbeafe')
            .self::arrow(320, 285, 380, 285)
            .self::box(380, 250, 200, 70, 'Lasting pull', '#dbeafe')
            .self::arrow(580, 285, 640, 285)
            .self::box(640, 250, 200, 70, 'Cemented bottoming', '#dcfce7');
    }

    private static function strobelCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Strobel sock seam envelope</text>'
            .'<ellipse cx="480" cy="320" rx="300" ry="140" fill="#e2e8f0"/>'
            .'<path d="M200 320 Q480 200 760 320" stroke="#2563eb" stroke-width="3" stroke-dasharray="8 6"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Board-to-upper seam · flex zone control</text>';
    }

    private static function outsoleWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Outsole attachment routes</text>'
            .self::box(80, 200, 180, 60, 'Rough / prime', '#dbeafe')
            .self::arrow(260, 230, 310, 230)
            .self::box(310, 200, 150, 60, 'Cement', '#dbeafe')
            .self::arrow(460, 230, 510, 230)
            .self::box(510, 200, 150, 60, 'Activation', '#dbeafe')
            .self::arrow(660, 230, 710, 230)
            .self::box(710, 200, 170, 60, 'Press / stitch', '#dcfce7')
            .self::box(310, 320, 300, 60, 'Welt route: channel stitch lock', '#fef3c7');
    }

    private static function outsoleCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Bond-line &amp; tread zone</text>'
            .'<rect x="180" y="300" width="600" height="80" fill="#16a34a"/>'
            .'<rect x="220" y="260" width="520" height="40" fill="#fde68a"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Perimeter pressure map · heel seat vs forepart</text>';
    }

    private static function toePuffWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Toe puff activation chain</text>'
            .self::box(140, 250, 180, 70, 'Heat activation', '#dbeafe')
            .self::arrow(320, 285, 380, 285)
            .self::box(380, 250, 200, 70, 'Forepart lasting', '#dbeafe')
            .self::arrow(580, 285, 640, 285)
            .self::box(640, 250, 180, 70, 'Toe-spring check', '#dcfce7');
    }

    private static function toePuffCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Toe reinforcement stack</text>'
            .'<path d="M200 420 L300 180 L420 420 Z" fill="#94a3b8"/>'
            .'<path d="M260 400 L320 220 L380 400 Z" fill="#2563eb"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Puff · vamp · margin before pull</text>';
    }

    private static function counterWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Counter molding sequence</text>'
            .self::box(120, 250, 170, 70, 'Skive edge', '#dbeafe')
            .self::arrow(290, 285, 350, 285)
            .self::box(350, 250, 170, 70, 'Mold / activate', '#dbeafe')
            .self::arrow(520, 285, 580, 285)
            .self::box(580, 250, 200, 70, 'Seat-lasting capture', '#dcfce7');
    }

    private static function counterCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Rearfoot counter section</text>'
            .'<rect x="360" y="160" width="240" height="300" fill="#94a3b8"/>'
            .'<path d="M400 200 L560 200 L520 420 L440 420 Z" fill="#2563eb"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Counter · lining · heel-seat platform</text>';
    }

    private static function upperWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Upper assembly workflow</text>'
            .self::box(80, 250, 150, 70, 'Clicking', '#e2e8f0')
            .self::arrow(230, 285, 280, 285)
            .self::box(280, 250, 150, 70, 'Closing', '#dbeafe')
            .self::arrow(430, 285, 480, 285)
            .self::box(480, 250, 170, 70, 'Reinforcements', '#dbeafe')
            .self::arrow(650, 285, 700, 285)
            .self::box(700, 250, 180, 70, 'Lasting release', '#dcfce7');
    }

    private static function upperCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Panel &amp; seam stack</text>'
            .'<rect x="200" y="200" width="200" height="260" fill="#94a3b8"/>'
            .'<rect x="400" y="220" width="160" height="240" fill="#cbd5e1"/>'
            .'<rect x="560" y="240" width="200" height="220" fill="#94a3b8"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Vamp · quarter · lining alignment</text>';
    }

    private static function heelSeatWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Heel-seat preparation</text>'
            .self::box(140, 250, 200, 70, 'Contour match', '#dbeafe')
            .self::arrow(340, 285, 400, 285)
            .self::box(400, 250, 160, 70, 'Leveling', '#dbeafe')
            .self::arrow(560, 285, 620, 285)
            .self::box(620, 250, 200, 70, 'Attachment gate', '#dcfce7');
    }

    private static function heelSeatCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Heel-seat platform profile</text>'
            .'<path d="M200 400 L480 180 L760 400 Z" fill="#e2e8f0"/>'
            .'<line x1="480" y1="180" x2="480" y2="420" stroke="#2563eb" stroke-width="2"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Planarity · contour · load transfer</text>';
    }

    private static function shankWorkflow(): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">Shank reinforcement placement</text>'
            .self::box(160, 250, 200, 70, 'Waist zone seat', '#dbeafe')
            .self::arrow(360, 285, 420, 285)
            .self::box(420, 250, 200, 70, 'Torsion check', '#dbeafe')
            .self::arrow(620, 285, 680, 285)
            .self::box(680, 250, 120, 70, 'Footbed stack', '#dcfce7');
    }

    private static function shankCutaway(): string
    {
        return '<text x="480" y="60" text-anchor="middle" font-size="20" fill="#0f172a">Internal waist reinforcement</text>'
            .'<rect x="320" y="200" width="320" height="260" fill="#e2e8f0"/>'
            .'<rect x="430" y="240" width="100" height="180" fill="#2563eb"/>'
            .'<text x="480" y="520" text-anchor="middle" font-size="14" fill="#475569">Shank · insole board · sockliner stack</text>';
    }

    private static function genericWorkflow(string $conceptKey): string
    {
        return self::markers()
            .'<text x="480" y="80" text-anchor="middle" font-size="20" fill="#0f172a">'.htmlspecialchars($conceptKey, ENT_XML1).' process</text>'
            .self::box(300, 250, 360, 80, 'Industrial process schematic', '#dbeafe');
    }
}
