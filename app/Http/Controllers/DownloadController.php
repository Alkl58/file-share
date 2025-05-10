<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class DownloadController extends Controller
{
    /**
     * Download File 
     * /download/nutzername/datei.png
     * /download/<crypted>/<crypted>
     */
    public function download(string $filePathHash, string $fileNameHash)
    {
        try {
            // Decrypt Hash
            $filePath = Crypt::decryptString($filePathHash);
            $fileName = Crypt::decryptString($fileNameHash);

            if (! Storage::disk('public')->exists($filePath)) {
                abort(404);
            }

            return response()->stream(
                function () use ($filePath) {
                    $stream = Storage::disk('public')->readStream($filePath);
                    fpassthru($stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                },
                200,
                [
                    'Content-Type' => Storage::disk('public')->mimeType($filePath),
                    'Content-Length' => Storage::disk('public')->size($filePath),
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                ]
            );
        } catch (DecryptException $e) {
        }

        abort(404);
    }

    public function downloadShare(string $uuid)
    {
        $share = Share::where('code', $uuid)->firstOrFail();
        $file = File::where('id', $share->file_id)->firstOrFail();
        $filePath = $file->path;

        if (! Storage::disk('public')->exists($file->path)) {
            abort(404);
        }

        return response()->stream(
            function () use ($filePath) {
                $stream = Storage::disk('public')->readStream($filePath);
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            },
            200,
            [
                'Content-Type' => Storage::disk('public')->mimeType($filePath),
                'Content-Length' => Storage::disk('public')->size($filePath),
                'Content-Disposition' => 'attachment; filename="' . $file->filename . '"',
            ]
        );


        abort(404);
    }
}
