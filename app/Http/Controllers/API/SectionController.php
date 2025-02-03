<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\SectionPackage;
use App\Models\SubjectPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function group()
    {
        $sectionID = request()->input('section_id');
        $isSortable = 'sortable-tbody';

        if (request()->filled('is_sortable')) {
            $isSortable = request()->input('is_sortable') == 'true' ? 'sortable-tbody' : '';
        }

        $sectionPackageIDs = SectionPackage::where('section_id', $sectionID)->get()->pluck('package_id')->toArray();

        $selectedSectionPackages = request()->input('sectionPackages');

        if (! $selectedSectionPackages) {
            return null;
        }

        $allSectionPackageIDs = array_unique (array_merge ($sectionPackageIDs, $selectedSectionPackages));

        $implodedIDs = implode(',', $allSectionPackageIDs);

        $packages = Package::whereIn('id', $allSectionPackageIDs)->orderByRaw(DB::raw("FIELD(id, $implodedIDs)"))->get();

        $response = '';

        $response .=
            '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-left">PACKAGE</th>
                        <th scope="col" class="text-right">ORDER</th>
                    </tr>
                </thead>
                <tbody class="' . $isSortable . '">';
        foreach ($packages as $index => $package) {
            $response .=
                '<tr>
                        <td class="text-left">' . $package->name . '</td>
                        <td class="text-right">
                            <span class="order">' . ($index + 1) . '</span>
                            <input type="hidden" name="package_id[]" value="' . $package->id .'">
                            <input type="hidden" name="package_order[]" class="package-order" value="' . ($index + 1) .'">
                        </td>
                    </tr>';
        }
        $response .=
            '</tbody>
            </table>';

        return $response;
    }
}
