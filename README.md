# MedFinder - Brazilian Medication Search

Search Brazilian medications by name or active ingredient and view manufacturer, presentation, and the government-regulated maximum price - on clean, SEO-friendly pages.

## Features
- Search by medication name or active ingredient, with pagination
- Detail pages with manufacturer, presentation, regulated price, and ANVISA registration
- SEO-friendly URLs (`/medications/{slug}`) + per-page meta tags + Open Graph + JSON-LD (`schema.org/Drug`)
- Data imported from Brazilian open data via `medications:import`

## Tech
- Laravel 13 (PHP 8.3) · Blade · MySQL · Tailwind CSS · Vite

## Getting started

**Requires:** PHP 8.3+, Composer, Node.js, MySQL

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

Re-import after updating the CSV (upserts by ANVISA registration):

```bash
php artisan medications:import --fresh
```

## Data source

**CMED "Lista de Preços de Medicamentos" (PMC list)** — one official file covers the whole data model (product, substance, lab, presentation, registration, and regulated price). Published monthly by ANVISA/CMED.
Link: [CMED preços](https://www.gov.br/anvisa/pt-br/assuntos/medicamentos/cmed/precos).

- **Downloaded file:** `database/data/cmed_pmc_2026-06-10.xlsx` (25,434 rows, 74 cols) → published **2026-06-10**.
- **Cleaned file:** `database/data/medications.csv` (derived from the XLSX above; 25,392 rows; 21,154 with a consumer price). Columns: `name`, `active_ingredient`, `manufacturer`, `presentation`, `price_max`, `registration`, `slug`.

**Normalization**
- The raw XLSX header is on **row 43** (rows 1–42 are legend/notes); data starts row 44.
- **`price_max` = PMC 18%** column (consumer max price at 18% ICMS, SP/RJ tax rate). Hospital-only meds have no PMC → `price_max` left blank.
- Prices converted from pt-BR (`11336,31`) to decimal (`11336.31`); accents preserved as UTF-8.

For demonstration only — not medical advice.

## License
MIT