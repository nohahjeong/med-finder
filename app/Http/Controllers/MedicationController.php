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
            ->when($query !== '', fn($builder) => $builder->search($query))
            ->orderBy('name')
            ->orderBy('presentation')
            ->paginate(20)
            ->withQueryString();

        return view('medications.index', [
            'query' => $query,
            'medications' => $medications,
        ]);
    }

    public function show(Request $request, Medication $medication): View
    {
        return view('medications.show', [
            'medication' => $medication,
            'backUrl' => $this->backUrl($request),
            'canonicalUrl' => route('medications.show', $medication),
            'seoTitle' => "{$medication->name} — {$medication->presentation}",
            'seoDescription' => $this->seoDescription($medication),
            'jsonLd' => $this->jsonLd($medication),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => $medication->name, 'url' => route('medications.show', $medication)],
            ],
            'breadcrumbJsonLd' => $this->breadcrumbJsonLd($medication),
        ]);
    }

    private function backUrl(Request $request): string
    {
        $query = trim($request->string('q')->toString());

        if ($query !== '') {
            return route('home', array_filter([
                'q' => $query,
                'page' => $request->integer('page') > 1 ? $request->integer('page') : null,
            ]));
        }

        $previous = url()->previous();

        if ($previous !== $request->fullUrl() && str_starts_with($previous, url('/'))) {
            return $previous;
        }

        return route('home');
    }

    private function seoDescription(Medication $medication): string
    {
        $parts = [
            $medication->name,
            $medication->active_ingredient,
            $medication->manufacturer,
            $medication->presentation,
        ];

        if ($medication->price_max !== null) {
            $parts[] = 'PMC max R$ ' . number_format((float) $medication->price_max, 2, ',', '.');
        }

        $description = implode('. ', $parts) . '.';

        return mb_strlen($description) > 160 ? mb_substr($description, 0, 157) . '…' : $description;
    }

    /**
     * @return array<string, mixed>
     */
    private function jsonLd(Medication $medication): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Drug',
            'name' => $medication->name,
            'activeIngredient' => $medication->active_ingredient,
            'description' => $medication->presentation,
            'url' => route('medications.show', $medication),
            'manufacturer' => [
                '@type' => 'Organization',
                'name' => $medication->manufacturer,
            ],
        ];

        if ($medication->registration !== null) {
            $data['identifier'] = [
                '@type' => 'PropertyValue',
                'propertyID' => 'ANVISA registration',
                'value' => $medication->registration,
            ];
        }

        if ($medication->price_max !== null) {
            $data['offers'] = [
                '@type' => 'Offer',
                'price' => (string) $medication->price_max,
                'priceCurrency' => 'BRL',
                'availability' => 'https://schema.org/InStock',
            ];
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function breadcrumbJsonLd(Medication $medication): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $medication->name, 'item' => route('medications.show', $medication)],
            ],
        ];
    }
}
