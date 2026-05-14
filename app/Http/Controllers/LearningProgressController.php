<?php

namespace App\Http\Controllers;

use App\Support\Learning\RecordLearningProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class LearningProgressController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        $user = $request->user();

        $summary = RecordLearningProgress::summaryForUser($user);

        return view('pages.learning.progress', [
            'locale' => $locale,
            'summary' => $summary,
            'pageTitle' => __('Learning progress'),
            'metaDescription' => __('Your LexiCraft Glossary quiz completions, semantic reviews, and flashcard sessions.'),
            'canonical' => route('learning.progress', ['locale' => $locale], absolute: true),
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
