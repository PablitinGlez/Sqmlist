<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature; // Importa el modelo Feature
use App\Models\FeatureSection; // Importa el modelo FeatureSection para obtener IDs

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las secciones (asumiendo que FeatureSectionSeeder ya corrió)
        $caracteristicasGeneralesId = FeatureSection::where('slug', 'caracteristicas_generales')->firstOrFail()->id;
        $amenidadesId = FeatureSection::where('slug', 'amenidades')->firstOrFail()->id;
        $serviciosId = FeatureSection::where('slug', 'servicios')->firstOrFail()->id;

        // --- Características Generales (Asignadas a la sección 'caracteristicas_generales') ---
        Feature::firstOrCreate(
            ['slug' => 'num_recamaras'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Recámaras',
                'description' => 'Número de recámaras o habitaciones.',
                'input_type' => 'number',
                'data_type' => 'integer',
                'unit' => '',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 10,
                'icon' => 'fas fa-bed', // ✅ Icono para Recámaras
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'num_banos'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Baños',
                'description' => 'Número de baños completos.',
                'input_type' => 'number',
                'data_type' => 'integer',
                'unit' => '',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 20,
                'icon' => 'fas fa-bath', // ✅ Icono para Baños
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'num_medios_banos'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Medios Baños',
                'description' => 'Número de medios baños.',
                'input_type' => 'number',
                'data_type' => 'integer',
                'unit' => '',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 30,
                'icon' => 'fas fa-toilet', // ✅ Icono para Medios Baños
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'num_niveles'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Número de Niveles',
                'description' => 'Cantidad de pisos o niveles de la propiedad.',
                'input_type' => 'number',
                'data_type' => 'integer',
                'unit' => '',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 40,
                'icon' => 'fas fa-layer-group', // ✅ Icono para Niveles
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'num_estacionamientos'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Estacionamiento(s)',
                'description' => 'Número de cajones de estacionamiento.',
                'input_type' => 'number',
                'data_type' => 'integer',
                'unit' => '',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 50,
                'icon' => 'fas fa-car', // ✅ Icono para Estacionamientos
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'anos_antiguedad'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Años de Antigüedad',
                'description' => 'Antigüedad de la propiedad en años.',
                'input_type' => 'select',
                'data_type' => 'string',
                'options' => json_encode([
                    'nuevo' => 'Nuevo',
                    '0-4' => '0-4 años',
                    '4+ años' => '4+ años',
                    'No estoy seguro' => 'No estoy seguro',
                ]),
                'unit' => 'años',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 60,
                'icon' => 'fas fa-building', // ✅ Icono para Antigüedad
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'uso_suelo'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Uso de Suelo',
                'description' => 'Tipo de uso de suelo permitido.',
                'input_type' => 'text',
                'data_type' => 'string',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 70,
                'icon' => 'fas fa-landmark', // ✅ Icono para Uso de Suelo
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'tamano_terreno_m2'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Tamaño del Terreno',
                'description' => 'Superficie total del terreno en metros cuadrados.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm²',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 80,
                'icon' => 'fas fa-ruler-combined', // ✅ Icono para Tamaño de Terreno
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'tamano_construccion_m2'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Tamaño de Construcción',
                'description' => 'Superficie total construida en metros cuadrados.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm²',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 90,
                'icon' => 'fas fa-ruler-combined', // ✅ Icono para Tamaño de Construcción
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'tamano_jardin_m2'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Tamaño del Jardín',
                'description' => 'Superficie de jardín en metros cuadrados.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm²',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 100,
                'icon' => 'fas fa-tree', // ✅ Icono para Tamaño de Jardín
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'ancho_m'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Ancho',
                'description' => 'Ancho de la propiedad/terreno en metros.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 110,
                'icon' => 'fas fa-arrows-alt-h', // ✅ Icono para Ancho
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'alto_m'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Alto',
                'description' => 'Altura de la propiedad/techo en metros.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 120,
                'icon' => 'fas fa-arrows-alt-v', // ✅ Icono para Alto
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'profundidad_m'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Profundidad',
                'description' => 'Profundidad del terreno o construcción en metros.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 130,
                'icon' => 'fas fa-cube', // ✅ Icono para Profundidad
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'tamano_bodega_m2'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Tamaño de Bodega',
                'description' => 'Superficie de la bodega en metros cuadrados.',
                'input_type' => 'number',
                'data_type' => 'float',
                'unit' => 'm²',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 140,
                'icon' => 'fas fa-warehouse', // ✅ Icono para Tamaño de Bodega
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'andenes'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Andenes',
                'description' => '¿Cuenta con andenes de carga y descarga?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 150,
                'icon' => 'fas fa-truck-loading', // ✅ Icono para Andenes
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'area_maniobras'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Área de Maniobras',
                'description' => '¿Dispone de área para maniobras de vehículos pesados?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 160,
                'icon' => 'fas fa-truck-ramp-box', // ✅ Icono para Área de Maniobras
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'tipo_electricidad'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Tipo de Electricidad',
                'description' => 'Tipo de suministro eléctrico.',
                'input_type' => 'select',
                'data_type' => 'string',
                'options' => json_encode(['Bifásica', 'Trifásica', 'Monofásica', 'Alta Tensión']),
                'is_filterable' => true,
                'is_required' => false,
                'order' => 170,
                'icon' => 'fas fa-bolt', // ✅ Icono para Tipo de Electricidad
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'tipo_techo'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Tipo de Techo',
                'description' => 'Material del techo de la propiedad.',
                'input_type' => 'select',
                'data_type' => 'string',
                'options' => json_encode(['Asbesto', 'Concreto', 'Lámina', 'Panel Sándwich']),
                'is_filterable' => true,
                'is_required' => false,
                'order' => 180,
                'icon' => 'fas fa-roof', // ✅ Icono para Tipo de Techo
            ]
        );

        Feature::firstOrCreate(
            ['slug' => 'vias_comunicacion'],
            [
                'feature_section_id' => $caracteristicasGeneralesId,
                'name' => 'Vías de Comunicación',
                'description' => 'Proximidad a vías de comunicación importantes.',
                'input_type' => 'text',
                'data_type' => 'string',
                'is_filterable' => false,
                'is_required' => false,
                'order' => 190,
                'icon' => 'fas fa-road', // ✅ Icono para Vías de Comunicación
            ]
        );

        // --- Servicios (Asignadas a la NUEVA sección 'servicios') ---
        // Mueve las características de servicios a la nueva sección 'servicios'
        Feature::firstOrCreate(
            ['slug' => 'has_cuarto_servicio'],
            [
                'feature_section_id' => $serviciosId,
                'name' => 'Cuarto de Servicio',
                'description' => '¿Tiene cuarto de servicio?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 10,
                'icon' => 'fas fa-broom', // ✅ Icono para Cuarto de Servicio
            ]
        );

        // Puedes añadir más características de Amenidades y Servicios aquí con sus respectivos iconos.
        // Ejemplo para amenidades:
        Feature::firstOrCreate(
            ['slug' => 'has_alberca'],
            [
                'feature_section_id' => $amenidadesId,
                'name' => 'Alberca',
                'description' => '¿Cuenta con alberca?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 10,
                'icon' => 'fas fa-swimming-pool', // ✅ Icono para Alberca
            ]
        );
    
// --- Amenidades (Asignadas a la NUEVA sección 'amenidades') ---
// Mueve las características de amenidades a la nueva sección 'amenidades'
Feature::firstOrCreate(
            ['slug' => 'has_alberca'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Alberca',
                'description' => '¿Cuenta con alberca?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 10,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'is_amueblado'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Amueblado',
                'description' => '¿La propiedad se entrega amueblada?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 20,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_jardines'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Jardines',
                'description' => '¿Cuenta con jardines o áreas verdes?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 50, // Ajusta el orden si es necesario
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'permite_mascotas'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Mascotas',
                'description' => '¿Se permiten mascotas?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 60,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_sotano'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Sótano',
                'description' => '¿Tiene sótano?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 70,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_terraza'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Terraza',
                'description' => '¿Cuenta con terraza?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 80,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_zona_privada'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Zona Privada',
                'description' => '¿Es parte de una zona o fraccionamiento privado?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 90,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_chimenea'],
            [
                'feature_section_id' => $amenidadesId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Chimenea',
                'description' => '¿Cuenta con chimenea?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 120,
            ]
        );


        // --- Servicios (Asignadas a la NUEVA sección 'servicios') ---
        // Mueve las características de servicios a la nueva sección 'servicios'
        Feature::firstOrCreate(
            ['slug' => 'has_cuarto_servicio'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Cuarto de Servicio',
                'description' => '¿Tiene cuarto de servicio?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 10,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_gimnasio'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Gimnasio',
                'description' => '¿Cuenta con gimnasio?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 20,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_aire_acondicionado'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Aire Acondicionado',
                'description' => '¿Cuenta con aire acondicionado?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 30,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_calefaccion'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Calefacción',
                'description' => '¿Cuenta con calefacción?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 40,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_cisterna'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Cisterna',
                'description' => '¿Cuenta con cisterna?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 50,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_gas_natural'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Gas Natural',
                'description' => '¿Cuenta con instalación de gas natural?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 60,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_lavanderia'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Lavandería',
                'description' => '¿Tiene área de lavandería?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 70,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_seguridad_privada'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Seguridad Privada',
                'description' => '¿Cuenta con seguridad privada?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 80,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'has_telefonia'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Telefonía',
                'description' => '¿Dispone de conexión telefónica?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 90,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'circuito_cerrado_tv'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Circuito Cerrado de TV',
                'description' => '¿Cuenta con sistema de CCTV?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 100,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'fibra_optica'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Fibra Óptica',
                'description' => '¿Dispone de conexión de fibra óptica?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 110,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'planta_emergencia'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Planta de Emergencia',
                'description' => '¿Cuenta con planta de emergencia eléctrica?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 120,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'sistema_contra_incendio'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Sistema Contra Incendio',
                'description' => '¿Dispone de sistema contra incendio (rociadores, detectores)?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 130,
            ]
        );
        Feature::firstOrCreate(
            ['slug' => 'vigilancia'],
            [
                'feature_section_id' => $serviciosId, // ¡ASIGNADO A LA NUEVA SECCIÓN!
                'name' => 'Vigilancia',
                'description' => '¿Cuenta con servicio de vigilancia?',
                'input_type' => 'checkbox',
                'data_type' => 'boolean',
                'is_filterable' => true,
                'is_required' => false,
                'order' => 140,
            ]
        );

    }
}
    