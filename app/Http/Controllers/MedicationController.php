<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim($request->string('q')->toString());

        $medications = Medication::query()
            ->when($query !== '', fn ($builder) => $builder->search($query))
            ->orderBy('name')
            ->orderBy('presentation')
            ->paginate(20)
            ->withQueryString();

        return view('medications.index', [
            'query' => $query,
            'medications' => $medications,
        ]);
    }
}
