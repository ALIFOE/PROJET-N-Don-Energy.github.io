<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(12);
        return view('gallery.index', compact('media'));
    }

    public function manage()
    {
        $this->authorize('manage-gallery');
        $media = Media::orderBy('created_at', 'desc')->paginate(20);
        return view('gallery.manage', compact('media'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage-gallery');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:102400',
            'is_featured' => 'boolean'
        ]);

        $path = $request->file('media')->store('gallery', 'public');
        $type = str_starts_with($request->file('media')->getMimeType(), 'video/') ? 'video' : 'image';

        Media::create([
            'title' => $request->title,
            'description' => $request->description,
            'path' => $path,
            'type' => $type,
            'is_featured' => $request->is_featured ?? false
        ]);

        return redirect()->back()->with('success', 'Média ajouté avec succès');
    }

    public function destroy(Media $media)
    {
        $this->authorize('manage-gallery');
        
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return redirect()->back()->with('success', 'Média supprimé avec succès');
    }

    public function toggleFeatured(Media $media)
    {
        $this->authorize('manage-gallery');
        
        $media->update(['is_featured' => !$media->is_featured]);
        return redirect()->back()->with('success', 'Statut mis à jour avec succès');
    }
}
