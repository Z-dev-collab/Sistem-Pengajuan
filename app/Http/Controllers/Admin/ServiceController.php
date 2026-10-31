<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceRequirement;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount(['requirements', 'submissions'])->orderBy('sort_order')->get();

        return view('admin.services.index', compact('services'));
    }

    public function edit(Service $service)
    {
        $service->load('requirements');

        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'tagline' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:3000'],
            'fee' => ['required', 'integer', 'min:0', 'max:10000000'],
            'fee_note' => ['nullable', 'string', 'max:255'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:30'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'form_title' => ['nullable', 'string', 'max:150'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $service->update([
            'name' => $validated['name'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'fee' => $validated['fee'],
            'fee_note' => $validated['fee_note'] ?? null,
            'estimated_days' => $validated['estimated_days'],
            'keywords' => $validated['keywords'] ?? null,
            'form_title' => $validated['form_title'] ?? $service->form_title,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Informasi layanan berhasil diperbarui.');
    }

    public function storeRequirement(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_mandatory' => ['nullable', 'boolean'],
        ]);

        $service->requirements()->create([
            'name' => $validated['name'],
            'note' => $validated['note'] ?? null,
            'is_mandatory' => $request->boolean('is_mandatory', true),
            'sort_order' => ($service->requirements()->max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'Persyaratan berhasil ditambahkan.');
    }

    public function updateRequirement(Request $request, Service $service, ServiceRequirement $requirement)
    {
        abort_unless($requirement->service_id === $service->id, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_mandatory' => ['nullable', 'boolean'],
        ]);

        $requirement->update([
            'name' => $validated['name'],
            'note' => $validated['note'] ?? null,
            'is_mandatory' => $request->boolean('is_mandatory'),
        ]);

        return back()->with('success', 'Persyaratan berhasil diperbarui.');
    }

    public function destroyRequirement(Service $service, ServiceRequirement $requirement)
    {
        abort_unless($requirement->service_id === $service->id, 404);
        $requirement->delete();

        return back()->with('success', 'Persyaratan berhasil dihapus.');
    }
}
