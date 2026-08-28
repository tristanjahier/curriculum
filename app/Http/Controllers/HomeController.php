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

        // Last-resort safeguard for a state that the database disallows: "a default CV that is not published".
        if (isset($defaultCv) && ! $defaultCv->isPublished()) {
            $defaultCv = null;
        }

        return Inertia::render('Home')->with([
            'defaultCv' => isset($defaultCv) ? new CurriculumVitaeResource($defaultCv) : null,
        ]);
    }
}
