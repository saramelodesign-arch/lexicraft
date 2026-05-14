<?php

namespace Database\Seeders;

use App\Support\Learning\LearningQuizGenerator;
use App\Support\Locales;
use Illuminate\Database\Seeder;

class LearningQuizSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Locales::codes() as $locale) {
            LearningQuizGenerator::regenerateForLocale($locale);
        }
    }
}
