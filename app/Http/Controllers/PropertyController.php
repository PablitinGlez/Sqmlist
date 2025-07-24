<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::published()->with('address', 'propertyType');

        if ($request->filled('ubicacion')) {
            $locationString = $request->input('ubicacion');
            $locationParts = explode(',', $locationString);

            if (isset($locationParts[0]) && !empty($locationParts[0])) {
                $stateName = trim($locationParts[0]);
                $query->whereHas('address', function ($q) use ($stateName) {
                    $q->where('state_name', $stateName);
                });
            }

            if (isset($locationParts[1]) && !empty($locationParts[1])) {
                $neighborhoodName = trim($locationParts[1]);
                $query->whereHas('address', function ($q) use ($neighborhoodName) {
                    $q->where('neighborhood_name', $neighborhoodName);
                });
            }
        }

        if ($request->filled('operacion')) {
            $query->where('operation_type', $request->input('operacion'));
        }

        if ($request->filled('tipo')) {
            $propertyTypeSlugOrName = $request->input('tipo');
            $query->whereHas('propertyType', function ($q) use ($propertyTypeSlugOrName) {
                $q->where('slug', $propertyTypeSlugOrName);
            });
        }

        $query->orderBy('created_at', 'desc');

        $properties = $query->paginate(12);

        return view('properties.index', compact('properties'));
    }

    public function show(string $slug)
    {
        $property = Property::where('slug', $slug)
            ->with([
                'propertyType',
                'propertyType.category',
                'images',
                'address',
                'user.profileDetails',
                'featureValues.feature.featureSection'
            ])
            ->firstOrFail();

        $similarProperties = collect();
        $limit = 8;

        if ($property->address) {
            $currentPropertyId = $property->id;
            $stateName = $property->address->state_name;
            $municipalityName = $property->address->municipality_name;
            $neighborhoodName = $property->address->neighborhood_name;

            if ($neighborhoodName && $municipalityName && $stateName) {
                $similarProperties = Property::published()
                    ->where('id', '!=', $currentPropertyId)
                    ->with(['address', 'propertyType', 'images'])
                    ->whereHas('address', function ($query) use ($neighborhoodName, $municipalityName, $stateName) {
                        $query->where('neighborhood_name', $neighborhoodName)
                            ->where('municipality_name', $municipalityName)
                            ->where('state_name', $stateName);
                    })
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get();
            }

            if ($similarProperties->count() < $limit && $municipalityName && $stateName) {
                $remainingLimit = $limit - $similarProperties->count();
                $excludeIds = $similarProperties->pluck('id')->toArray();
                $excludeIds[] = $currentPropertyId;

                $municipalityProperties = Property::published()
                    ->whereNotIn('id', $excludeIds)
                    ->with(['address', 'propertyType', 'images'])
                    ->whereHas('address', function ($query) use ($municipalityName, $stateName) {
                        $query->where('municipality_name', $municipalityName)
                            ->where('state_name', $stateName);
                    })
                    ->inRandomOrder()
                    ->limit($remainingLimit)
                    ->get();

                $similarProperties = $similarProperties->merge($municipalityProperties);
            }

            if ($similarProperties->count() < $limit && $stateName) {
                $remainingLimit = $limit - $similarProperties->count();
                $excludeIds = $similarProperties->pluck('id')->toArray();
                $excludeIds[] = $currentPropertyId;

                $stateProperties = Property::published()
                    ->whereNotIn('id', $excludeIds)
                    ->with(['address', 'propertyType', 'images'])
                    ->whereHas('address', function ($query) use ($stateName) {
                        $query->where('state_name', $stateName);
                    })
                    ->inRandomOrder()
                    ->limit($remainingLimit)
                    ->get();

                $similarProperties = $similarProperties->merge($stateProperties);
            }
        }

        return view('properties.show', compact('property', 'similarProperties'));
    }

    public function generateBreadcrumbUrl(Property $property, string $level): string
    {
        $params = [];

        switch ($level) {
            case 'state':
                if ($property->address->state_name) {
                    $params['ubicacion'] = $property->address->state_name;
                }
                break;

            case 'neighborhood':
                if ($property->address->state_name) {
                    $locationParts = [$property->address->state_name];

                    if ($property->address->neighborhood_name) {
                        $locationParts[] = $property->address->neighborhood_name;
                    } elseif ($property->address->municipality_name) {
                        $locationParts[] = $property->address->municipality_name;
                    }

                    $params['ubicacion'] = implode(',', $locationParts);
                }
                break;

            case 'operation':
                $locationParts = [];
                if ($property->address->state_name) {
                    $locationParts[] = $property->address->state_name;
                }
                if ($property->address->neighborhood_name) {
                    $locationParts[] = $property->address->neighborhood_name;
                } elseif ($property->address->municipality_name) {
                    $locationParts[] = $property->address->municipality_name;
                }

                if (!empty($locationParts)) {
                    $params['ubicacion'] = implode(',', $locationParts);
                }

                $params['operacion'] = $property->operation_type;
                break;

            case 'property_type':
                $locationParts = [];
                if ($property->address->state_name) {
                    $locationParts[] = $property->address->state_name;
                }
                if ($property->address->neighborhood_name) {
                    $locationParts[] = $property->address->neighborhood_name;
                } elseif ($property->address->municipality_name) {
                    $locationParts[] = $property->address->municipality_name;
                }

                if (!empty($locationParts)) {
                    $params['ubicacion'] = implode(',', $locationParts);
                }

                $params['operacion'] = $property->operation_type;

                if ($property->propertyType) {
                    $params['tipo'] = $property->propertyType->slug;
                }
                break;
        }

        return route('properties.index', $params);
    }
}
