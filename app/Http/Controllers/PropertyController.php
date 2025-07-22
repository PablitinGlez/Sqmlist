<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importante: Asegúrate de que esta línea esté presente

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
                // Si la DB guarda nombres con espacios y mayúsculas, usar tal cual o normalizar si es necesario
                $stateName = trim($locationParts[0]);
                $query->whereHas('address', function ($q) use ($stateName) {
                    $q->where('state_name', $stateName);
                });
            }

            // Intentar aplicar filtro por colonia/municipio (segunda parte de la ubicación)
            if (isset($locationParts[1]) && !empty($locationParts[1])) {
                // Si la DB guarda nombres con espacios y mayúsculas, usar tal cual o normalizar si es necesario
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
            $propertyTypeSlugOrName = $request->input('tipo'); // Puede ser slug o nombre, según cómo lo mandes desde Blade
            $query->whereHas('propertyType', function ($q) use ($propertyTypeSlugOrName) {
                // Asumiendo que 'tipo' llega como slug y la columna en DB es 'slug'
                $q->where('slug', $propertyTypeSlugOrName);
                // Si 'tipo' llegara como nombre, usarías: $q->where('name', $propertyTypeSlugOrName);
            });
        }

        // Ordenar los resultados (ej. las más nuevas primero)
        $query->orderBy('created_at', 'desc');

        // Obtener los resultados paginados
        $properties = $query->paginate(12); // Define cuántas propiedades por página

        // Retornar la vista de listado de propiedades con los resultados
        return view('properties.index', compact('properties'));
    }

    /**
     * Muestra los detalles de una propiedad específica.
     *
     * @param string $slug El slug de la propiedad.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $slug)
    {
        // Busca la propiedad por su slug y carga todas las relaciones necesarias
        $property = Property::where('slug', $slug)
            ->with([
                'propertyType', // Aseguramos que la relación propertyType se cargue directamente
                'propertyType.category', // Y también su categoría si la necesitas para otras partes de la página
                'images',
                'address',
                'user',
                'featureValues.feature.featureSection'
            ])
            ->firstOrFail(); // Si no se encuentra, Laravel automáticamente lanza un 404

        return view('properties.show', compact('property'));
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
                    $params['ubicacion'] = $property->address->state_name; // Envía el nombre del estado
                }
                break;

            case 'neighborhood':
                // Estado + Colonia/Municipio
                if ($property->address->state_name) {
                    $locationParts = [$property->address->state_name];

                    if ($property->address->neighborhood_name) {
                        $locationParts[] = $property->address->neighborhood_name;
                    }

                    $params['ubicacion'] = implode(',', $locationParts); // Envía nombres separados por coma
                }
                break;

            case 'operation':
                // Estado + Colonia + Operación
                if ($property->address->state_name) {
                    $locationParts = [];
                    if ($property->address->state_name) {
                        $locationParts[] = $property->address->state_name;
                    }
                    if ($property->address->neighborhood_name) {
                        $locationParts[] = $property->address->neighborhood_name;
                    }

                    if (!empty($locationParts)) {
                        $params['ubicacion'] = implode(',', $locationParts);
                    }
                }

                // Agregar tipo de operación
                $params['operacion'] = $property->operation_type;
                break;

            case 'property_type':
                // Estado + Colonia + Operación + Tipo de Propiedad
                if ($property->address->state_name) {
                    $locationParts = [];
                    if ($property->address->state_name) {
                        $locationParts[] = $property->address->state_name;
                    }
                    if ($property->address->neighborhood_name) {
                        $locationParts[] = $property->address->neighborhood_name;
                    }

                    if (!empty($locationParts)) {
                        $params['ubicacion'] = implode(',', $locationParts);
                    }
                }

                // Agregar tipo de operación
                $params['operacion'] = $property->operation_type;

                // Agregar tipo de propiedad
                if ($property->propertyType) {
                    $params['tipo'] = $property->propertyType->slug; // Envía el slug del tipo de propiedad
                }
                break;
        }

        return route('properties.index', $params);
    }
}
