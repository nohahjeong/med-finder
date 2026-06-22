# MedFinder - Brazilian Medication Search

Search Brazilian medications by name or active ingredient and view manufacturer, presentation, and the government-regulated maximum price - on clean, SEO-friendly pages.

**Work in progress** — Laravel scaffold, sample data, and import are in place; search and detail pages are coming next.

## Features (planned)
- Search by medication name or active ingredient, with pagination
- Detail pages with manufacturer, presentation, regulated price
- SEO-friendly URLs + per-page meta tags + JSON-LD (`schema.org/Drug`)
- Data imported from Brazilian open data via `medications:import`

## Tech
- Laravel 13 (PHP 8.3) · Blade · MySQL

## Getting started
```bash
composer install
cp .env.example .env
php artisan key:generate
# configure DB in .env, then:
php artisan migrate
php artisan medications:import
npm install && npm run build
php artisan serve
```

## Data source

**CMED "Lista de Preços de Medicamentos" (PMC list)** — one official file covers the whole data model (product, substance, lab, presentation, registration, and regulated price). Published monthly by ANVISA/CMED.
Link: [CMED preços](https://www.gov.br/anvisa/pt-br/assuntos/medicamentos/cmed/precos).

- **Downloaded file:** `database/data/cmed_pmc_2026-06-10.xlsx` (25,434 rows, 74 cols) → published **2026-06-10**.
- **Cleaned file:** `database/data/medications.csv` (derived from the XLSX above; 25,392 rows; 21,154 with a consumer price).

**Normalization**
- The raw XLSX header is on **row 43** (rows 1–42 are legend/notes); data starts row 44.
- **`price_max` = PMC 18%** column (consumer max price at 18% ICMS, SP/RJ tax rate). Hospital-only meds have no PMC → `price_max` left blank.
- Prices converted from pt-BR (`11336,31`) to decimal (`11336.31`); accents preserved as UTF-8.

For demonstration only — not medical advice.

## License
MIT