<?php

namespace App\Console\Commands;

use App\Models\Medication;
use Illuminate\Console\Command;
use RuntimeException;

class ImportMedicationsCommand extends Command
{
    protected $signature = 'medications:import {path? : Path to the CSV file} {--fresh : Truncate the medications table before importing}';

    protected $description = 'Import medications from a CMED-derived CSV file';

    private const int CHUNK_SIZE = 500;

    private const array COLUMNS = [
        'name',
        'active_ingredient',
        'manufacturer',
        'presentation',
        'price_max',
        'registration',
        'slug',
    ];

    public function handle(): int
    {
        $path = $this->argument('path') ?? base_path('database/data/medications.csv');

        if (! is_readable($path)) {
            $this->components->error("File not found or not readable: {$path}");

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            Medication::query()->delete();
            $this->components->info('Cleared existing medications.');
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            $this->components->error("Unable to open file: {$path}");

            return self::FAILURE;
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);
            $this->components->error('CSV file is empty.');

            return self::FAILURE;
        }

        $this->validateHeader($header);

        $imported = 0;
        $skipped = 0;
        $chunk = [];
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            if ($row === [null] || $row === []) {
                continue;
            }

            $data = $this->mapRow($header, $row);

            if ($data === null) {
                $skipped++;

                continue;
            }

            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $chunk[] = $data;

            if (count($chunk) >= self::CHUNK_SIZE) {
                $this->upsertChunk($chunk);
                $imported += count($chunk);
                $chunk = [];
            }
        }

        fclose($handle);

        if ($chunk !== []) {
            $this->upsertChunk($chunk);
            $imported += count($chunk);
        }

        $this->components->info("Imported {$imported} medications.");

        if ($skipped > 0) {
            $this->components->warn("Skipped {$skipped} invalid rows.");
        }

        return self::SUCCESS;
    }

    /**
     * @param  list<string|null>  $header
     */
    private function validateHeader(array $header): void
    {
        $missing = array_diff(self::COLUMNS, $header);

        if ($missing !== []) {
            throw new RuntimeException(
                'CSV is missing required columns: '.implode(', ', $missing)
            );
        }
    }

    /**
     * @param  list<string|null>  $header
     * @param  list<string|null>  $row
     * @return array<string, mixed>|null
     */
    private function mapRow(array $header, array $row): ?array
    {
        if (count($row) !== count($header)) {
            return null;
        }

        /** @var array<string, string|null> $data */
        $data = array_combine($header, $row);

        if ($data === false) {
            return null;
        }

        $name = trim($data['name'] ?? '');
        $slug = trim($data['slug'] ?? '');
        $registration = trim($data['registration'] ?? '');

        if ($name === '' || $slug === '' || $registration === '') {
            return null;
        }

        $priceMax = trim($data['price_max'] ?? '');

        return [
            'name' => $name,
            'active_ingredient' => trim($data['active_ingredient'] ?? ''),
            'manufacturer' => trim($data['manufacturer'] ?? ''),
            'presentation' => trim($data['presentation'] ?? ''),
            'price_max' => $priceMax !== '' ? $priceMax : null,
            'registration' => $registration,
            'slug' => $slug,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $chunk
     */
    private function upsertChunk(array $chunk): void
    {
        Medication::upsert(
            $chunk,
            ['registration'],
            [
                'name',
                'active_ingredient',
                'manufacturer',
                'presentation',
                'price_max',
                'slug',
                'updated_at',
            ],
        );
    }
}
