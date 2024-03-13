<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportAdministrative;

class ImportAdministrativeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-administrative-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new ImportAdministrative());
    }
}
