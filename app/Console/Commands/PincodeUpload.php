<?php

namespace App\Console\Commands;

use App\Models\Pincode;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class PincodeUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pincode-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pincode Upload';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $excel = FastExcel::import(public_path('indian_pincodes.xlsx'));

        $grouped = [];
        foreach ($excel as $row) {
            $state   = trim($row['statename']);
            $pincode = trim($row['pincode']);

            $grouped[$state][] = $pincode;
        }

        $this->insertGrouped($grouped);
    }

    private function insertGrouped(array $grouped): void
    {
        foreach ($grouped as $state => $pincodes) {
            Pincode::updateOrCreate(
                ['name' => $state],
                ['pincodes' => array_unique($pincodes)]
            );
        }
    }
}
