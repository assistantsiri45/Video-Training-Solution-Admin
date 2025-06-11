<?php

namespace App\Http\Controllers;
use App\ScormPackage as AppScormPackage;
use ZipArchive;
use Illuminate\Http\Request;

class ScormController extends Controller
{
     public function showForm()
    {
        return view('upload');
    }

   public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'zip_file' => 'required|mimes:zip|max:1024000', 
        ]);

        $zip = $request->file('zip_file');
        $folderName = 'scorm_' . time();
        $extractPath = public_path('scorm_packages/' . $folderName);

        mkdir($extractPath, 0775, true);
        $zipPath = $extractPath . '/' . $zip->getClientOriginalName();
        $zip->move($extractPath, $zip->getClientOriginalName());

        $zipArchive = new ZipArchive;
        if ($zipArchive->open($zipPath)) {
            $zipArchive->extractTo($extractPath);
            $zipArchive->close();
            unlink($zipPath);
        }

        $manifestPath = $extractPath . '/imsmanifest.xml';
        $launchFile = null;

        if (file_exists($manifestPath)) {
            $xml = simplexml_load_file($manifestPath);
            $xml->registerXPathNamespace('ns', 'http://www.imsproject.org/xsd/imscp_rootv1p1p2');
            $resource = $xml->xpath('//ns:resource')[0] ?? null;

            if ($resource) {
                $base = (string) $resource['base'] ?? '';
                $href = (string) $resource['href'];
                $launchFile = $base ? ($base . '/' . $href) : $href;
            }
        }

        if (!$launchFile || !file_exists($extractPath . '/' . $launchFile)) {
            return back()->with('error', 'Launch file not found.');
        }

        AppScormPackage::create([
            'title' => $request->title,
            'folder_name' => $folderName,
            'launch_file' => $launchFile,
        ]);

    return back()->with('success', 'SCORM course uploaded ');    }

    public function view($id)
    {
        $package = AppScormPackage::findOrFail($id);
        $launchUrl = asset('scorm_packages/' . $package->folder_name . '/' . $package->launch_file);

        return view('view', ['launchUrl' => $launchUrl, 'title' => $package->title]);
    }
}
