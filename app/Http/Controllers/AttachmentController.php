<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'file' => 'required|file|max:' . config('wiki.upload.max_size'),
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Validate file type
        if (!in_array($extension, config('wiki.upload.allowed_types'))) {
            return back()->with('error', 'File type not allowed.');
        }

        // Store file
        $path = $file->store('attachments', 'public');

        // Create attachment record
        Attachment::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'original_name' => $originalName,
            'file_name' => basename($path),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'file_size' => $file->getSize(),
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }

    public function download(Attachment $attachment)
    {
        $attachment->incrementDownloads();

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }

    public function destroy(Attachment $attachment)
    {
        $this->authorize('update', $attachment->document);

        $attachment->delete();

        return redirect()->back()->with('success', 'Attachment deleted successfully!');
    }
}
