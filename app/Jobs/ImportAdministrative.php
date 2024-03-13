<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\CommunesImport;
use App\Imports\DistrictsImport;
use App\Imports\ProvincesImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportAdministrative implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = "C:\laragon\www\web_laptop\public\Danh sách cấp tỉnh kèm theo quận huyện, phường xã ___10_03_2024.csv";
        Excel::import(new ProvincesImport(), $path);
        Excel::import(new DistrictsImport(), $path);
        Excel::import(new CommunesImport(), $path);
    }
}
