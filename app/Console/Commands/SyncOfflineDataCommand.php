<?php

namespace App\Console\Commands;

use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncOfflineDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:sync {--push-only : Only push local sales to live} {--pull-only : Only pull catalog from live}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize local offline POS sales and catalog updates with live cloud database';

    /**
     * Execute the console command.
     */
    public function handle(SyncService $syncService): int
    {
        $this->info('Checking connection to live cloud server (' . config('sync.live_url') . ')...');

        $ping = $syncService->pingLive();
        if (!$ping['online']) {
            $this->error('Cannot sync: ' . $ping['message']);
            return Command::FAILURE;
        }

        $this->info('Live server is ONLINE and responsive.');

        if ($this->option('push-only')) {
            $this->info('Pushing pending offline orders and products...');
            $result = $syncService->pushToLive();
            $this->line($result['message']);
            return $result['success'] ? Command::SUCCESS : Command::FAILURE;
        }

        if ($this->option('pull-only')) {
            $this->info('Pulling catalog updates from live server...');
            $result = $syncService->pullFromLive();
            $this->line($result['message']);
            return $result['success'] ? Command::SUCCESS : Command::FAILURE;
        }

        $this->info('Starting full bidirectional sync...');
        $result = $syncService->syncAll();

        if ($result['success']) {
            $this->info($result['message']);
            return Command::SUCCESS;
        }

        $this->warn($result['message']);
        return Command::FAILURE;
    }
}
