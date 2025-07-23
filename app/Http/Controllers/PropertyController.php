<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Muestra una lista paginada de propiedades, permitiendo filtros
     * por ubicación (estado, colonia), tipo de operación y tipo de propiedad.
     *
     * @param Request $request La solicitud HTTP que contiene los parámetros de filtro.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Inicia la consulta de propiedades publicadas y carga relaciones necesarias
        $query = Property::published()->with('address', 'propertyType');

        // Aplicar filtro por ubicación si se proporciona
        // El parámetro 'ubicacion' puede venir como 'estado' o 'estado,colonia' (nombres sin slug)
        if ($request->filled('ubicacion')) {
            $locationString = $request->input('ubicacion');
            $locationParts = explode(',', $locationString);

            // Intentar aplicar filtro por estado (primera parte de la ubicación)
            if (isset($locationParts[0]) && !empty($locationParts[0])) {
                $stateName = trim($locationParts[0]);
                $query->whereHas('address', function ($q) use ($stateName) {
                    $q->where('state_name', $stateName);
                });
            }

            // Intentar aplicar filtro por colonia/municipio (segunda parte de la ubicación)
            if (isset($locationParts[1]) && !empty($locationParts[1])) {
                $neighborhoodName = trim($locationParts[1]);
                $query->whereHas('address', function ($q) use ($neighborhoodName) {
                    $q->where('neighborhood_name', $neighborhoodName);
                });
            }
        }

        // Aplicar filtro por tipo de operación ('operacion')
        if ($request->filled('operacion')) {
            $query->where('operation_type', $request->input('operacion'));
        }

        // Aplicar filtro por tipo de propiedad ('tipo')
        if ($request->filled('tipo')) {
            $propertyTypeSlugOrName = $request->input('tipo');
            $query->whereHas('propertyType', function ($q) use ($propertyTypeSlugOrName) {
                $q->where('slug', $propertyTypeSlugOrName);
            });
        }

        // Ordenar los resultados (ej. las más nuevas primero)
        $query->orderBy('created_at', 'desc');

        // Obtener los resultados paginados
        $properties = $query->paginate(12);

        // Retornar la vista de listado de propiedades con los resultados
        return view('properties.index', compact('properties'));
    }

    /**
     * Muestra los detalles de una propiedad específica y propiedades similares.
     *
     * @param string $slug El slug de la propiedad.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $slug)
    {
        // Busca la propiedad por su slug y carga todas las relaciones necesarias
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

        // --- Lógica CORREGIDA para obtener propiedades similares ---
        $similarProperties = collect();
        $limit = 8;

        if ($property->address) {
            $currentPropertyId = $property->id;
            $stateName = $property->address->state_name;
            $municipalityName = $property->address->municipality_name;
            $neighborhoodName = $property->address->neighborhood_name;

            // 1. PRIORIDAD MÁXIMA: Buscar por colonia exacta + municipio + estado
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

            // 2. SEGUNDA PRIORIDAD: Si no hay suficientes, buscar solo por municipio + estado
            if ($similarProperties->count() < $limit && $municipalityName && $stateName) {
                $remainingLimit = $limit - $similarProperties->count();
                $excludeIds = $similarProperties->pluck('id')->toArray();
                $excludeIds[] = $currentPropertyId; // También excluir la propiedad actual

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

            // 3. TERCERA PRIORIDAD: Si aún no hay suficientes, buscar solo por estado
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

            // ELIMINAMOS EL PASO 4 que agregaba propiedades aleatorias sin criterio de ubicación
            // Ahora solo mostramos propiedades que realmente coinciden con la ubicación
        }

        // Si no se encontraron propiedades similares, $similarProperties seguirá siendo una colección vacía
        // y eso está bien - es mejor mostrar "no hay propiedades similares" que mostrar propiedades no relacionadas

        return view('properties.show', compact('property', 'similarProperties'));
    }

    /**
     * Genera URLs para las migas de pan con los parámetros apropiados según el nivel
     *
     * @param Property $property
     * @param string $level - 'state', 'neighborhood', 'operation', 'property_type'
     * @return string
     */
    public function generateBreadcrumbUrl(Property $property, string $level): string
    {
        $params = [];

        switch ($level) {
            case 'state':
                // Solo parámetro de estado
                if ($property->address->state_name) {
                    $params['ubicacion'] = $property->address->state_name;
                }
                break;

            case 'neighborhood':
                // Estado + Colonia/Municipio
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
                // Estado + Colonia/Municipio + Operación
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
                // Estado + Colonia/Municipio + Operación + Tipo de Propiedad
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
