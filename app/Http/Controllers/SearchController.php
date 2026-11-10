<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $services = collect();

        if (mb_strlen($query) >= 2) {
            $words = preg_split('/\s+/', mb_strtolower($query), -1, PREG_SPLIT_NO_EMPTY);

            $services = collect(ServiceController::catalog())
                ->filter(fn (array $service) => $this->matches($service, $words))
                ->sortBy('sort_order')
                ->values()
                ->map(fn (array $service) => (object) $service);
        }

        return view('pages.search', compact('query', 'services'));
    }

    /**
     * Cocokkan semua kata kunci dengan nama, tagline, deskripsi, biaya,
     * maupun daftar persyaratan layanan.
     */
    protected function matches(array $service, array $words): bool
    {
        $haystack = [
            $service['name'] ?? '',
            $service['tagline'] ?? '',
            $service['description'] ?? '',
            $service['fee_label'] ?? '',
            $service['fee_note'] ?? '',
            $service['estimate_label'] ?? '',
            $service['form_title'] ?? '',
            // Kata kunci bantu agar pencarian "biaya" tetap menemukan layanan.
            'biaya ' . ($service['fee_label'] ?? ''),
        ];

        foreach ($service['requirements'] ?? [] as $requirement) {
            $haystack[] = $requirement['name'] ?? '';
            $haystack[] = $requirement['note'] ?? '';
        }

        $haystack = mb_strtolower(implode(' ', $haystack));

        foreach ($words as $word) {
            if (! str_contains($haystack, $word)) {
                return false;
            }
        }

        return true;
    }
}
