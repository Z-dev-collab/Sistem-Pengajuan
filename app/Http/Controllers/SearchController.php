<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $services = collect();

        if (mb_strlen($query) >= 2) {
            $services = Service::active()
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('tagline', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('keywords', 'like', "%{$query}%")
                        ->orWhereHas('requirements', fn ($r) => $r->where('name', 'like', "%{$query}%"));
                })
                ->orderBy('sort_order')
                ->get();
        }

        return view('pages.search', compact('query', 'services'));
    }
}
