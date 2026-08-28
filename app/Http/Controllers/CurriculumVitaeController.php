<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurriculumVitaeResource;
use App\Models\CurriculumVitae;
use Inertia\Inertia;
use Inertia\Response;

class CurriculumVitaeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(CurriculumVitae $curriculumVitae): Response
    {
        abort_unless($curriculumVitae->isPublished(), 404);

        return Inertia::render('CurriculumVitae/Show')
            ->with('cv', new CurriculumVitaeResource($curriculumVitae));
    }
}
