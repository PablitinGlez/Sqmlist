<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * 
     *
     * @param string $slug El slug de la propiedad.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $slug)
    {
        $property = Property::where('slug', $slug)
            ->with([
                'propertyType.category',
                'images',
                'address',  
                'user',
                'featureValues.feature.featureSection'  
            ])
            ->firstOrFail();

        return view('properties.show', compact('property'));
    }
}
