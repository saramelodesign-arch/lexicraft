<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    public function run(): void
    {
        $supported = config('locales.supported', []);

        foreach ($supported as $code => $meta) {
            Language::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $meta['name'] ?? strtoupper($code),
                    'native_name' => $meta['native'] ?? $meta['name'] ?? strtoupper($code),
                    'flag_icon' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}
