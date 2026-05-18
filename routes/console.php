<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Support\Editorial\SlugGovernance;
use App\Support\Editorial\WorkflowStatus;
use App\Support\Import\TerminologyImportPipeline;
use App\Support\Import\TerminologyImportRow;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lexicraft:import-terminology {file : CSV file path} {--dry-run : Validate rows without writing to DB}', function (): int {
    $file = (string) $this->argument('file');
    if (! is_file($file)) {
        $this->error('File not found: '.$file);

        return self::FAILURE;
    }

    $handle = fopen($file, 'rb');
    if ($handle === false) {
        $this->error('Unable to read file.');

        return self::FAILURE;
    }

    $rows = [];
    try {
        $headers = fgetcsv($handle);
        if (! is_array($headers)) {
            $this->error('CSV header row is required.');

            return self::FAILURE;
        }
        $headers = array_map(fn ($h) => trim((string) $h), $headers);

        while (($line = fgetcsv($handle)) !== false) {
            if (! is_array($line)) {
                continue;
            }

            $assoc = [];
            foreach ($headers as $i => $key) {
                $assoc[$key] = isset($line[$i]) ? (string) $line[$i] : null;
            }

            $domainField = trim((string) ($assoc['domains'] ?? ''));
            $domains = $domainField === ''
                ? []
                : collect(explode('|', $domainField))
                    ->map(fn (string $slug) => trim($slug))
                    ->filter()
                    ->values()
                    ->all();

            $rows[] = new TerminologyImportRow(
                locale: (string) ($assoc['locale'] ?? ''),
                term: trim((string) ($assoc['term'] ?? '')),
                slug: SlugGovernance::normalize((string) ($assoc['slug'] ?? '')),
                shortDefinition: (($v = trim((string) ($assoc['short_definition'] ?? ''))) === '') ? null : $v,
                fullDefinition: (($v = trim((string) ($assoc['full_definition'] ?? ''))) === '') ? null : $v,
                conceptStatus: (string) ($assoc['concept_status'] ?? WorkflowStatus::DRAFT),
                translationStatus: (string) ($assoc['translation_status'] ?? ($assoc['concept_status'] ?? WorkflowStatus::DRAFT)),
                domains: $domains,
                terminologyStatus: (($ts = trim((string) ($assoc['terminology_status'] ?? ''))) === '') ? null : $ts,
            );
        }
    } finally {
        fclose($handle);
    }

    $pipeline = app(TerminologyImportPipeline::class);
    $summary = $pipeline->import($rows, (bool) $this->option('dry-run'));

    $this->info('Rows processed: '.$summary['processed']);
    $this->line('Created concepts: '.$summary['created_concepts']);
    $this->line('Created translations: '.$summary['created_translations']);
    $this->line('Updated translations: '.$summary['updated_translations']);
    $this->line('Skipped: '.$summary['skipped']);
    if (isset($summary['warned'])) {
        $this->line('Warned rows: '.$summary['warned']);
    }

    if (($summary['warnings'] ?? []) !== []) {
        $this->newLine();
        $this->warn('Non-blocking governance warnings:');
        foreach ($summary['warnings'] as $warning) {
            $this->line('- '.$warning);
        }
    }

    if ($summary['errors'] !== []) {
        $this->newLine();
        $this->warn('Validation and governance errors:');
        foreach ($summary['errors'] as $error) {
            $this->line('- '.$error);
        }
    }

    return $summary['errors'] === [] ? self::SUCCESS : self::FAILURE;
})->purpose('Import terminology CSV with governance validation');
