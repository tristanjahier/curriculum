---
paths:
  - 'app/Filament/**'
---

# Filament

## Fix irregular plurals after running make:filament-* generators
Every Filament generator derives directories, namespaces and slugs from `Str::pluralStudly()`, which mangles irregular/Latin plurals (`CurriculumVitae` -> `CurriculumVitaes`, not `CurriculaVitae`). After generating a resource whose plural Laravel cannot infer, fix three things by hand:

1. Directory + namespace -> the correct plural (e.g. `App\Filament\Resources\CurriculaVitae`). Keep the Filament convention: plural for the directory, the List page and the Table class; singular for the Resource, Form, Infolist and Create/Edit/View pages.
2. `protected static ?string $pluralModelLabel = 'curricula vitae';` — drives navigation, breadcrumb, list-page title, bulk-delete confirmation and the table empty state.
3. `protected static ?string $slug = 'curricula-vitae';` — REQUIRED once (1) is applied. `Resource::resolveDefaultSlug()` compares `Str::pluralStudly(class basename)` against the containing directory name and, on mismatch, treats the directory as a nesting level, emitting a doubled slug like `curricula-vitae/curriculum-vitaes` in URLs and route names.

`$modelLabel` normally needs no override — it is kebab-cased from the class basename, and singular Latin survives the inflector. Write labels lowercase; Filament title-cases them for display.

The Eloquent model must name its table explicitly, since Laravel's guess is wrong for the same reason — either `protected $table = 'curricula_vitae';` or `#[Table('curricula_vitae')]`. Both work at runtime, but prefer the property: Larastan resolves model columns via `$modelInstance->getTable()` and does not read the attribute, so the attribute form makes it fall back to the guessed `curriculum_vitaes`, find no such table, and report *every* column on the model as an undefined property.

Same applies to `make:filament-relation-manager`, `make:filament-cluster` and `make:filament-widget`.

## Widgets: fix the view path too, not just the namespace
`make:filament-widget --resource=` repeats the comparison in `MakeWidgetCommand::configureResource()`: pluralized class basename before `Resource` against the containing directory name. On mismatch it nests the widget *under the resource class* rather than beside it, so fix both halves:

1. Namespace + directory -> `App\Filament\Resources\CurriculaVitae\Widgets`, not the generated `App\Filament\Resources\CurriculaVitae\CurriculumVitaeResource\Widgets`.
2. `protected string $view` -> `filament.resources.curricula-vitae.widgets.<widget>`, and move the Blade file to the matching `resources/views/filament/resources/curricula-vitae/widgets/`.

The generated view path doubles the segment (`curricula-vitae/curriculum-vitae-resource/widgets/...`). Nothing flags it — the view resolves either way — so it survives unless someone reads it. Fixing (1) without (2) leaves the class and its view disagreeing about where the resource lives.

## Name closure parameters to match Filament's injections
Filament's `evaluate()` resolves closure parameters by NAME first, then by type — and a typed parameter it cannot match falls through to `app()->make($class)` (vendor/filament/support/src/Concerns/EvaluatesClosures.php:85). So a `*Using()` callback whose parameter is named anything other than what Filament injects will try to build that class from the container.

Copy the parameter names from the default registration in `setUp()` of the component you are overriding. For `BaseFileUpload`: `saveUploadedFileUsing` injects `file`; `getUploadedFileUsing` injects `file` and `storedFileNames`; `deleteUploadedFileUsing` injects `file`.

Renaming `TemporaryUploadedFile $file` to `$f` made Filament call `app()->make(TemporaryUploadedFile::class)`, which cannot be constructed — the failure surfaced as "Allowed memory size exhausted" while rendering the error, not as a clear binding exception, and wrote a 137 MB log.

A parameter typed as the component's own class (e.g. `BaseFileUpload $component`) resolves regardless of its name, because `evaluate()` returns `$this` for that type. That is why only the file parameters break.

Also note `deleteUploadedFileUsing` is NOT registered by default: adding one opts into deleting the file the moment FilePond's remove button is clicked, before any save. Cleaning up replaced/removed files belongs in a model observer instead.
