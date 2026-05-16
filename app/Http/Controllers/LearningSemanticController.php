<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use Illuminate\Contracts\View\View;

final class LearningSemanticController extends Controller
{
    public function __invoke(string $locale): View
    {
        $pageTitle = __('ui.semantic_practice');
        $metaDescription = __('learning.semantic_meta_description');
        $canonical = route('learning.semantic', ['locale' => $locale], absolute: true);
        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = route('learning.semantic', ['locale' => $code], absolute: true);
        }
        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        return view('pages.learning.semantic', [
            'locale' => $locale,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
