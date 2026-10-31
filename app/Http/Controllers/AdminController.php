<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminProjectController extends Controller
{
    // Menampilkan data project
    public function index()
    {
        $projects = Project::latest()->get();

        return view('admin.projects.index', compact('projects'));
    }

    // Form tambah project
    public function create()
    {
        return view('admin.projects.create');
    }

    // Menyimpan project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teknologi' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'status' => 'required|in:active,archived',
            'kategori' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            File::ensureDirectoryExists(public_path('images'));

            $imagePath = time() . '_' .
                $request->file('image')->getClientOriginalName();

            $request->file('image')->move(
                public_path('images'),
                $imagePath
            );

            $validated['image'] = $imagePath;
        }

        Project::create($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    // Menampilkan detail project
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.projects.show', compact('project'));
    }

    // Form edit project
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.projects.edit', compact('project'));
    }

    // Memperbarui project
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teknologi' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'status' => 'required|in:active,archived',
            'kategori' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            File::ensureDirectoryExists(public_path('images'));

            $imagePath = time() . '_' .
                $request->file('image')->getClientOriginalName();

            $request->file('image')->move(
                public_path('images'),
                $imagePath
            );

            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        $project->update($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    // Menghapus project
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}