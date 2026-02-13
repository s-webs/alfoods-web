<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateProductsFromPgsqlCommand extends Command
{
    protected $signature = 'products:migrate-from-pgsql
                            {--chunk=500 : Number of records per chunk}
                            {--truncate : Truncate local products before migration}';

    protected $description = 'Migrate products from remote PostgreSQL to local MySQL (IDs preserved)';

    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk');
        $truncate = $this->option('truncate');

        $this->info('Connecting to remote PostgreSQL...');

        try {
            DB::connection('pgsql_remote')->getPdo();
        } catch (\Throwable $e) {
            $this->error('Cannot connect to remote PostgreSQL: ' . $e->getMessage());
            $this->line('Set PG_REMOTE_HOST, PG_REMOTE_PORT, PG_REMOTE_DATABASE, PG_REMOTE_USERNAME, PG_REMOTE_PASSWORD in .env');
            return self::FAILURE;
        }

        if ($truncate) {
            if (!$this->confirm('Truncate local products table before migration?')) {
                return self::SUCCESS;
            }
            DB::connection()->table('products')->truncate();
            $this->info('Local products table truncated.');
        }

        $total = DB::connection('pgsql_remote')->table('products')->count();
        $this->info("Found {$total} products in remote PostgreSQL.");

        if ($total === 0) {
            $this->warn('Nothing to migrate.');
            return self::SUCCESS;
        }

        $usedSlugs = DB::connection()->table('products')->pluck('slug')->flip()->all();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $maxId = 0;
        $migrated = 0;

        DB::connection('pgsql_remote')
            ->table('products')
            ->orderBy('id')
            ->chunk($chunkSize, function ($rows) use (&$maxId, &$migrated, &$usedSlugs, $bar) {
                $inserts = [];
                foreach ($rows as $row) {
                    $id = (int) $row->id;
                    if ($id > $maxId) {
                        $maxId = $id;
                    }
                    $baseSlug = $row->slug ?? Str::slug($row->name ?? 'product-' . $id);
                    $slug = $baseSlug;
                    if (isset($usedSlugs[$slug])) {
                        $slug = $baseSlug . '-' . $id;
                    }
                    $usedSlugs[$slug] = true;

                    $inserts[] = [
                        'id' => $id,
                        'category_id' => $row->category_id ?? null,
                        'name' => $row->name ?? '',
                        'new_name' => $row->new_name ?? null,
                        'barcode' => $row->barcode ?? null,
                        'description' => $row->description ?? null,
                        'unit' => $row->unit ?? 'pcs',
                        'price' => (float) ($row->price_amount ?? 0),
                        'discount_price' => (float) ($row->sale_price_amount ?? 0),
                        'stock' => (float) ($row->quantity ?? 0),
                        'slug' => $slug,
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ];
                }

                if (!empty($inserts)) {
                    DB::connection()->table('products')->insert($inserts);
                    $migrated += count($inserts);
                }

                $bar->advance(count($rows));
            });

        $bar->finish();
        $this->newLine(2);

        if ($maxId > 0) {
            $nextAutoIncrement = (int) ($maxId + 1);
            DB::connection()->statement(
                'ALTER TABLE products AUTO_INCREMENT = ' . $nextAutoIncrement
            );
            $this->info("AUTO_INCREMENT set to {$nextAutoIncrement}.");
        }

        $this->info("Migrated {$migrated} products. IDs preserved as in remote PostgreSQL.");
        return self::SUCCESS;
    }
}
