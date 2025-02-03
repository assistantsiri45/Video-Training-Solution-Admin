<?php

namespace App\Imports;

use App\Models\Package;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PackageProfessorRevenueImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $i => $row) {
                if ($i != 0) {
                    $package = Package::find($row[0]);
                    $package->professor_revenue = $row[11];
                    $package->save();
                }
            }
        } catch (\Exception $exception) {

            $message = $exception->getMessage();

        }
    }
}
