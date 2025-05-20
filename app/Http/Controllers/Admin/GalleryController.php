<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function manage()
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(20);
        return view('gallery.manage', compact('media'));
    }

    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('media')) {
                return redirect()->back()->with('error', 'Aucun fichier n\'a ?t? s?lectionn?.');
            }

            $mediaCount = 0;
            $files = $request->file('media');

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('gallery', 'public');
                    $type = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';
                    $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                    Media::create([
                        'title' => $fileName,
                        'description' => null,
                        'path' => $path,
                        'type' => $type,
                        'is_featured' => $request->has('is_featured')
                    ]);
                    $mediaCount++;
                }
            }

            if ($mediaCount === 0) {
                return redirect()->back()->with('error', 'Aucun fichier n\'a pu ?tre import?.');
            }

            $message = $mediaCount > 1 ? $mediaCount . ' m?dias ajout?s avec succ?s' : 'M?dia ajout? avec succ?s';
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'upload de fichiers : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'importation : ' . $e->getMessage());
        }
    }

    public function destroy(Media $media)
    {
        try {
            Storage::disk('public')->delete($media->path);
            $media->delete();
            return redirect()->back()->with('success', 'M?dia supprim? avec succ?s');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du m?dia : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression');
        }
    }

    public function toggleFeatured(Media $media)
    {
        try {
            $media->update(['is_featured' => !$media->is_featured]);
            return redirect()->back()->with('success', 'Statut mis ? jour avec succ?s');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise ? jour du statut : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise ? jour du statut');
        }
    }
}