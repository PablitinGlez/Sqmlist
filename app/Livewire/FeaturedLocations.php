<?php

namespace App\Livewire;

use Livewire\Component;

class FeaturedLocations extends Component
{
    // Propiedad pública para la pestaña activa
    public string $activeTab = 'ciudades-principales';

    // Datos de las ubicaciones destacadas
    // Se definen aquí para que el componente Livewire los maneje
    // y la vista pueda renderizarlos dinámicamente.
    public array $locationsData = [
        'ciudades-principales' => [
            'name' => 'Ciudades Principales',
            'venta' => [
                'Ciudad de México' => ['Casas en venta en Ciudad de México', 'Departamentos en venta en Ciudad de México'],
                'Querétaro' => ['Casas en venta en Querétaro', 'Departamentos en venta en Querétaro'],
                'Monterrey' => ['Casas en venta en Monterrey', 'Departamentos en venta en Monterrey'],
                'Mérida' => ['Casas en venta en Mérida', 'Departamentos en venta en Mérida'],
                'Zapopan' => ['Casas en venta en Zapopan', 'Departamentos en venta en Zapopan'],
                'Huixquilucan' => ['Casas en venta en Huixquilucan', 'Departamentos en venta en Huixquilucan'],
                'Cuernavaca' => ['Casas en venta en Cuernavaca', 'Departamentos en venta en Cuernavaca'],
                'Guadalajara' => ['Casas en venta en Guadalajara', 'Departamentos en venta en Guadalajara'],
                'Cancún' => ['Casas en venta en Cancún', 'Departamentos en venta en Cancún'],
                'San Pedro Garza García' => ['Casas en venta en San Pedro Garza García', 'Departamentos en venta en San Pedro Garza García'],
            ],
            'renta' => [
                'Ciudad de México' => ['Casas en renta en Ciudad de México', 'Departamentos en renta en Ciudad de México'],
                'Querétaro' => ['Casas en renta en Querétaro', 'Departamentos en renta en Querétaro'],
                'Monterrey' => ['Casas en renta en Monterrey', 'Departamentos en renta en Monterrey'],
                'Mérida' => ['Casas en renta en Mérida', 'Departamentos en renta en Mérida'],
                'Zapopan' => ['Casas en renta en Zapopan', 'Departamentos en renta en Zapopan'],
                'Huixquilucan' => ['Casas en renta en Huixquilucan', 'Departamentos en renta en Huixquilucan'],
                'Cuernavaca' => ['Casas en renta en Cuernavaca', 'Departamentos en renta en Cuernavaca'],
                'Guadalajara' => ['Casas en renta en Guadalajara', 'Departamentos en renta en Guadalajara'],
                'Cancún' => ['Casas en renta en Cancún', 'Departamentos en renta en Cancún'],
                'San Pedro Garza García' => ['Casas en renta en San Pedro Garza García', 'Departamentos en renta en San Pedro Garza García'],
            ],
        ],
        'estado-de-mexico' => [
            'name' => 'Estado de México',
            'venta' => [
                'Estado de México' => ['Casas en venta en Estado de México', 'Departamentos en venta en Estado de México'],
                'Huixquilucan' => ['Casas en venta en Huixquilucan', 'Departamentos en venta en Huixquilucan'],
                'Naucalpan de Juárez' => ['Casas en venta en Naucalpan de Juárez', 'Departamentos en venta en Naucalpan de Juárez'],
                'Atizapán de Zaragoza' => ['Casas en venta en Atizapán de Zaragoza', 'Departamentos en venta en Atizapán de Zaragoza'],
                'Tlalnepantla de Baz' => ['Casas en venta en Tlalnepantla de Baz', 'Departamentos en venta en Tlalnepantla de Baz'],
                'Metepec' => ['Casas en venta en Metepec', 'Departamentos en venta en Metepec'],
                'Toluca' => ['Casas en venta en Toluca', 'Departamentos en venta en Toluca'],
                'Cuautitlán Izcalli' => ['Casas en venta en Cuautitlán Izcalli', 'Departamentos en venta en Cuautitlán Izcalli'],
                'Ecatepec de Morelos' => ['Casas en venta en Ecatepec de Morelos', 'Departamentos en venta en Ecatepec de Morelos'],
                'Valle de Bravo' => ['Casas en venta en Valle de Bravo', 'Departamentos en venta en Valle de Bravo'],
            ],
            'renta' => [
                'Estado de México' => ['Casas en renta en Estado de México', 'Departamentos en renta en Estado de México'],
                'Huixquilucan' => ['Casas en renta en Huixquilucan', 'Departamentos en renta en Huixquilucan'],
                'Naucalpan de Juárez' => ['Casas en renta en Naucalpan de Juárez', 'Departamentos en renta en Naucalpan de Juárez'],
                'Atizapán de Zaragoza' => ['Casas en renta en Atizapán de Zaragoza', 'Departamentos en renta en Atizapán de Zaragoza'],
                'Tlalnepantla de Baz' => ['Casas en renta en Tlalnepantla de Baz', 'Departamentos en renta en Tlalnepantla de Baz'],
                'Metepec' => ['Casas en renta en Metepec', 'Departamentos en renta en Metepec'],
                'Toluca' => ['Casas en renta en Toluca', 'Departamentos en renta en Toluca'],
                'Cuautitlán Izcalli' => ['Casas en renta en Cuautitlán Izcalli', 'Departamentos en renta en Cuautitlán Izcalli'],
                'Ecatepec de Morelos' => ['Casas en renta en Ecatepec de Morelos', 'Departamentos en renta en Ecatepec de Morelos'],
                'Valle de Bravo' => ['Casas en renta en Valle de Bravo', 'Departamentos en renta en Valle de Bravo'],
            ],
        ],
        'estados-principales' => [
            'name' => 'Estados Principales',
            'venta' => [
                'Jalisco' => ['Casas en venta en Jalisco', 'Departamentos en venta en Jalisco'],
                'Nuevo León' => ['Casas en venta en Nuevo León', 'Departamentos en venta en Nuevo León'],
                'Querétaro' => ['Casas en venta en Querétaro', 'Departamentos en venta en Querétaro'],
                'Quintana Roo' => ['Casas en venta en Quintana Roo', 'Departamentos en venta en Quintana Roo'],
                'Morelos' => ['Casas en venta en Morelos', 'Departamentos en venta en Morelos'],
                'Puebla' => ['Casas en venta en Puebla', 'Departamentos en venta en Puebla'],
                'Yucatán' => ['Casas en venta en Yucatán', 'Departamentos en venta en Yucatán'],
                'Guanajuato' => ['Casas en venta en Guanajuato', 'Departamentos en venta en Guanajuato'],
                'Veracruz' => ['Casas en venta en Veracruz', 'Departamentos en venta en Veracruz'],
                'Aguascalientes' => ['Casas en venta en Aguascalientes', 'Departamentos en venta en Aguascalientes'],
            ],
            'renta' => [
                'Jalisco' => ['Casas en renta en Jalisco', 'Departamentos en renta en Jalisco'],
                'Nuevo León' => ['Casas en renta en Nuevo León', 'Departamentos en renta en Nuevo León'],
                'Querétaro' => ['Casas en renta en Querétaro', 'Departamentos en renta en Querétaro'],
                'Quintana Roo' => ['Casas en renta en Quintana Roo', 'Departamentos en renta en Quintana Roo'],
                'Morelos' => ['Casas en renta en Morelos', 'Departamentos en renta en Morelos'],
                'Puebla' => ['Casas en renta en Puebla', 'Departamentos en renta en Puebla'],
                'Yucatán' => ['Casas en renta en Yucatán', 'Departamentos en renta en Yucatán'],
                'Guanajuato' => ['Casas en renta en Guanajuato', 'Departamentos en renta en Guanajuato'],
                'Veracruz' => ['Casas en renta en Veracruz', 'Departamentos en renta en Veracruz'],
                'Aguascalientes' => ['Casas en renta en Aguascalientes', 'Departamentos en renta en Aguascalientes'],
            ],
        ],
        'ciudad-de-mexico' => [
            'name' => 'Ciudad de México',
            'venta' => [
                'Miguel Hidalgo' => ['Casas en venta en Miguel Hidalgo', 'Departamentos en venta en Miguel Hidalgo'],
                'Benito Juárez' => ['Casas en venta en Benito Juárez', 'Departamentos en venta en Benito Juárez'],
                'Cuauhtémoc' => ['Casas en venta en Cuauhtémoc', 'Departamentos en venta en Cuauhtémoc'],
                'Álvaro Obregón' => ['Casas en venta en Álvaro Obregón', 'Departamentos en venta en Álvaro Obregón'],
                'Cuajimalpa de Morelos' => ['Casas en venta en Cuajimalpa de Morelos', 'Departamentos en venta en Cuajimalpa de Morelos'],
                'Coyoacán' => ['Casas en venta en Coyoacán', 'Departamentos en venta en Coyoacán'],
                'Tlalpan' => ['Casas en venta en Tlalpan', 'Departamentos en venta en Tlalpan'],
                'Gustavo A. Madero' => ['Casas en venta en Gustavo A. Madero', 'Departamentos en venta en Gustavo A. Madero'],
                'Azcapotzalco' => ['Casas en venta en Azcapotzalco', 'Departamentos en venta en Azcapotzalco'],
                'Iztapalapa' => ['Casas en venta en Iztapalapa', 'Departamentos en venta en Iztapalapa'],
            ],
            'renta' => [
                'Miguel Hidalgo' => ['Casas en renta en Miguel Hidalgo', 'Departamentos en renta en Miguel Hidalgo'],
                'Benito Juárez' => ['Casas en renta en Benito Juárez', 'Departamentos en renta en Benito Juárez'],
                'Cuauhtémoc' => ['Casas en renta en Cuauhtémoc', 'Departamentos en renta en Cuauhtémoc'],
                'Álvaro Obregón' => ['Casas en renta en Álvaro Obregón', 'Departamentos en renta en Álvaro Obregón'],
                'Cuajimalpa de Morelos' => ['Casas en renta en Cuajimalpa de Morelos', 'Departamentos en renta en Cuajimalpa de Morelos'],
                'Coyoacán' => ['Casas en renta en Coyoacán', 'Departamentos en renta en Coyoacán'],
                'Tlalpan' => ['Casas en renta en Tlalpan', 'Departamentos en renta en Tlalpan'],
                'Gustavo A. Madero' => ['Casas en renta en Gustavo A. Madero', 'Departamentos en renta en Gustavo A. Madero'],
                'Azcapotzalco' => ['Casas en renta en Azcapotzalco', 'Departamentos en renta en Azcapotzalco'],
                'Iztapalapa' => ['Casas en renta en Iztapalapa', 'Departamentos en renta en Iztapalapa'],
            ],
        ],
    ];

    /**
     * Cambia la pestaña activa.
     *
     * @param string $tab El identificador de la nueva pestaña activa.
     */
    public function selectTab(string $tab): void
    {
        if (array_key_exists($tab, $this->locationsData)) {
            $this->activeTab = $tab;
        }
    }

    /**
     * Genera la URL para un enlace de propiedad.
     *
     * @param string $location La ubicación (ciudad, estado, etc.).
     * @param string $propertyType El tipo de propiedad ('Casas' o 'Departamentos').
     * @param string $operationType El tipo de operación ('venta' o 'renta').
     * @return string La URL generada.
     */
    public function generateUrl(string $location, string $propertyType, string $operationType): string
    {
        // Limpia la ubicación para usarla en la URL (ej. "Ciudad de México" -> "ciudad-de-mexico")
        $locationSlug = \Illuminate\Support\Str::slug($location);
        $propertyTypeSlug = \Illuminate\Support\Str::slug($propertyType);

        // Asumiendo que tu ruta de propiedades acepta filtros de ubicación y tipo de operación/propiedad
        // Ejemplo: /propiedades?ubicacion=ciudad-de-mexico&tipo=casas&operacion=venta
        return route('properties.index', [
            'ubicacion' => $location, // Envía el nombre completo para el filtro
            'tipo' => str_contains($propertyType, 'Casas') ? 'casa' : 'departamento', // Ajusta a 'casa' o 'departamento'
            'operacion' => $operationType
        ]);
    }

    public function render()
    {
        return view('livewire.featured-locations');
    }
}
