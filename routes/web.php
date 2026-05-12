<?php

use App\Support\Locales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if ($request->filled('locale')) {
        $locale = $request->string('locale')->toString();
        if (Locales::isSupported($locale)) {
            session(['locale' => $locale]);
        }

        return redirect()->route('home');
    }

    if (session()->has('locale')) {
        app()->setLocale(session('locale'));
    }

    return view('pages.home');
})->name('home');

Route::get('{locale}/glossary/{letter}', function (string $locale, string $letter) {
    abort_unless(Locales::isSupported($locale), 404);
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return view('pages.glossary-letter', [
        'letter' => strtoupper($letter),
        'locale' => $locale,
    ]);
})->where([
    'locale' => '[a-z]{2}',
    'letter' => '[A-Za-z]',
])->name('glossary.letter');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
