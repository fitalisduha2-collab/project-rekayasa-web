<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    // Menampilkan daftar project
    public function index()
    {
        $projects = Project::latest()->get();

        return view('admin.projects.index', compact('projects'));
    }

    // Menampilkan form tambah project
    public function create()
    {
        return view('admin.projects.create');
    }

    // Menyimpan project baru
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'teknologi'   => 'required|string|max:255',
            'status'      => 'required|in:active,archived',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;

        // Upload gambar
        if ($request->hasFile('image')) {
            File::ensureDirectoryExists(public_path('images'));

            $imageName = Str::uuid() . '.' .
                $request->file('image')->extension();

            $request->file('image')->move(
                public_path('images'),
                $imageName
            );

            $imagePath = $imageName;
        }

        // Simpan data project
        Project::create([
            'title'       => $request->title,
            'description' => $request->description,
            'teknologi'   => $request->teknologi,
            'image'       => $imagePath,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.projects.edit', compact('project'));
    }

    // Memperbarui project
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'teknologi'   => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'      => 'required|in:active,archived',
        ]);

        $project = Project::findOrFail($id);

        $imagePath = $project->image;

        // Upload gambar baru jika dipilih
        if ($request->hasFile('image')) {
            File::ensureDirectoryExists(public_path('images'));

            $imageName = Str::uuid() . '.' .
                $request->file('image')->extension();

            $request->file('image')->move(
                public_path('images'),
                $imageName
            );

            // Hapus gambar lama jika ada
            if ($project->image &&
                File::exists(public_path('images/' . $project->image))) {
                File::delete(public_path('images/' . $project->image));
            }

            $imagePath = $imageName;
        }

        $project->update([
            'title'       => $request->title,
            'description' => $request->description,
            'teknologi'   => $request->teknologi,
            'image'       => $imagePath,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    // Menghapus project
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // Hapus gambar project jika tersedia
        if ($project->image &&
            File::exists(public_path('images/' . $project->image))) {
            File::delete(public_path('images/' . $project->image));
        }

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    // Menghasilkan laporan PDF
    public function cetakPdf()
    {
        $projects = Project::latest()->get();

        $pdf = Pdf::loadView(
            'admin.projects.pdf',
            compact('projects')
        );

        return $pdf->stream('projects.pdf');
    }
}