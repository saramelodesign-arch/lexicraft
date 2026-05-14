<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

final class LearningSemanticController extends Controller
{
    public function __invoke(string $locale): View
    {
        $pageTitle = __('Semantic practice');
        $metaDescription = __(
            'Train synonym, broader/narrower, and related-term recognition using the live semantic graph.',
        );
        $canonical = route('learning.semantic', ['locale' => $locale], absolute: true);

        return view('pages.learning.semantic', [
            'locale' => $locale,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
