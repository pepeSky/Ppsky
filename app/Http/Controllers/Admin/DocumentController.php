<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())
            ->orderBy('name')
            ->paginate(15);

        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        Document::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'location' => $data['location'] ?? null,
        ]);

        return redirect()
            ->route('admin.documents.index')
            ->with('info', 'Documento registrado correctamente.');
    }

    public function show(Document $document)
    {
        $this->authorizeDocument($document);

        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $this->authorizeDocument($document);

        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $this->authorizeDocument($document);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $document->update($data);

        return redirect()
            ->route('admin.documents.index')
            ->with('info', 'Documento actualizado correctamente.');
    }

    public function destroy(Document $document)
    {
        $this->authorizeDocument($document);

        $document->delete();

        return redirect()
            ->route('admin.documents.index')
            ->with('info', 'Documento eliminado correctamente.');
    }

    private function authorizeDocument(Document $document): void
    {
        abort_unless(
            $document->user_id === auth()->id(),
            403
        );
    }
}
