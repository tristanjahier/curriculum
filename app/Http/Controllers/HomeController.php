<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurriculumVitaeResource;
use App\Models\CurriculumVitae;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $defaultCv = CurriculumVitae::findDefault();

        if (isset($defaultCv) && ! $defaultCv->isPublished()) {
            $defaultCv = null;
        }

        return Inertia::render('Home')->with([
            'defaultCv' => isset($defaultCv) ? new CurriculumVitaeResource($defaultCv) : null,
        ]);
    }
}
