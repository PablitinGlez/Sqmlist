<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyType;
use App\Models\Feature;

class PropertyTypeFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las características por su slug para un acceso eficiente
        $features = Feature::all()->keyBy('slug');

        // --- Tipos de Propiedad Residenciales ---
        $this->assignFeaturesToPropertyType('casa', $features, [
            // Características Generales
            ['slug' => 'num_recamaras', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_medios_banos', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'num_niveles', 'is_required_for_type' => false, 'order_for_type' => 40],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 50],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 60],
            ['slug' => 'tamano_terreno_m2', 'is_required_for_type' => true, 'order_for_type' => 70],
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 80],
            ['slug' => 'tamano_jardin_m2', 'is_required_for_type' => false, 'order_for_type' => 90],
            // Amenidades (ahora separadas)
            ['slug' => 'has_alberca', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'is_amueblado', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'has_jardines', 'is_required_for_type' => false, 'order_for_type' => 120],
            ['slug' => 'permite_mascotas', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'has_sotano', 'is_required_for_type' => false, 'order_for_type' => 140],
            ['slug' => 'has_terraza', 'is_required_for_type' => false, 'order_for_type' => 150],
            ['slug' => 'has_zona_privada', 'is_required_for_type' => false, 'order_for_type' => 160],
            ['slug' => 'has_chimenea', 'is_required_for_type' => false, 'order_for_type' => 170],
            // Servicios (ahora separados)
            ['slug' => 'has_cuarto_servicio', 'is_required_for_type' => false, 'order_for_type' => 180],
            ['slug' => 'has_gimnasio', 'is_required_for_type' => false, 'order_for_type' => 190],
            ['slug' => 'has_aire_acondicionado', 'is_required_for_type' => false, 'order_for_type' => 200],
            ['slug' => 'has_calefaccion', 'is_required_for_type' => false, 'order_for_type' => 210],
            ['slug' => 'has_cisterna', 'is_required_for_type' => false, 'order_for_type' => 220],
            ['slug' => 'has_gas_natural', 'is_required_for_type' => false, 'order_for_type' => 230],
            ['slug' => 'has_lavanderia', 'is_required_for_type' => false, 'order_for_type' => 240],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 250],
            ['slug' => 'has_telefonia', 'is_required_for_type' => false, 'order_for_type' => 260],
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 270],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 280],
            ['slug' => 'planta_emergencia', 'is_required_for_type' => false, 'order_for_type' => 290],
            ['slug' => 'sistema_contra_incendio', 'is_required_for_type' => false, 'order_for_type' => 300],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 310],
        ]);

        $this->assignFeaturesToPropertyType('departamento', $features, [
            // Características Generales
            ['slug' => 'num_recamaras', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_medios_banos', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 50],
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 60],
            // Amenidades
            ['slug' => 'has_alberca', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'is_amueblado', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'permite_mascotas', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'has_terraza', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'has_zona_privada', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'has_chimenea', 'is_required_for_type' => false, 'order_for_type' => 120],
            // Servicios
            ['slug' => 'has_cuarto_servicio', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'has_gimnasio', 'is_required_for_type' => false, 'order_for_type' => 140],
            ['slug' => 'has_aire_acondicionado', 'is_required_for_type' => false, 'order_for_type' => 150],
            ['slug' => 'has_calefaccion', 'is_required_for_type' => false, 'order_for_type' => 160],
            ['slug' => 'has_cisterna', 'is_required_for_type' => false, 'order_for_type' => 170],
            ['slug' => 'has_gas_natural', 'is_required_for_type' => false, 'order_for_type' => 180],
            ['slug' => 'has_lavanderia', 'is_required_for_type' => false, 'order_for_type' => 190],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 200],
            ['slug' => 'has_telefonia', 'is_required_for_type' => false, 'order_for_type' => 210],
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 220],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 230],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 240],
        ]);

        $this->assignFeaturesToPropertyType('terreno-habitacional', $features, [
            // Características Generales (principalmente tamaño y uso)
            ['slug' => 'tamano_terreno_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'ancho_m', 'is_required_for_type' => false, 'order_for_type' => 20],
            ['slug' => 'profundidad_m', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 50],
            // Amenidades y Servicios (si aplica, por ejemplo en un fraccionamiento)
            ['slug' => 'has_zona_privada', 'is_required_for_type' => false, 'order_for_type' => 60],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 80],
        ]);

        $this->assignFeaturesToPropertyType('casa-en-condominio', $features, [
            // Prácticamente las mismas que una Casa, pero con énfasis en las amenidades de condominio
            // Características Generales
            ['slug' => 'num_recamaras', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_medios_banos', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'num_niveles', 'is_required_for_type' => false, 'order_for_type' => 40],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 50],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 60],
            ['slug' => 'tamano_terreno_m2', 'is_required_for_type' => true, 'order_for_type' => 70],
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 80],
            ['slug' => 'tamano_jardin_m2', 'is_required_for_type' => false, 'order_for_type' => 90],
            // Amenidades (comunes en condominios)
            ['slug' => 'has_alberca', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'has_jardines', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'permite_mascotas', 'is_required_for_type' => false, 'order_for_type' => 120],
            ['slug' => 'has_terraza', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'has_zona_privada', 'is_required_for_type' => true, 'order_for_type' => 140], // Más relevante aquí
            ['slug' => 'has_chimenea', 'is_required_for_type' => false, 'order_for_type' => 150],
            // Servicios
            ['slug' => 'has_gimnasio', 'is_required_for_type' => false, 'order_for_type' => 160],
            ['slug' => 'has_aire_acondicionado', 'is_required_for_type' => false, 'order_for_type' => 170],
            ['slug' => 'has_calefaccion', 'is_required_for_type' => false, 'order_for_type' => 180],
            ['slug' => 'has_cisterna', 'is_required_for_type' => false, 'order_for_type' => 190],
            ['slug' => 'has_gas_natural', 'is_required_for_type' => false, 'order_for_type' => 200],
            ['slug' => 'has_lavanderia', 'is_required_for_type' => false, 'order_for_type' => 210],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => true, 'order_for_type' => 220], // Más relevante aquí
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 230],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 240],
        ]);

        $this->assignFeaturesToPropertyType('rancho-residencial', $features, [
            // Características Generales (enfocado en el tamaño del terreno y lo residencial)
            ['slug' => 'num_recamaras', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 30],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 40],
            ['slug' => 'tamano_terreno_m2', 'is_required_for_type' => true, 'order_for_type' => 50],
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 60],
            ['slug' => 'tamano_jardin_m2', 'is_required_for_type' => false, 'order_for_type' => 70], // Grandes áreas verdes
            ['slug' => 'has_cisterna', 'is_required_for_type' => false, 'order_for_type' => 80],
            // Amenidades (rurales/residenciales)
            ['slug' => 'has_alberca', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'has_jardines', 'is_required_for_type' => true, 'order_for_type' => 100],
            ['slug' => 'permite_mascotas', 'is_required_for_type' => false, 'order_for_type' => 110], // Más flexible
            ['slug' => 'has_terraza', 'is_required_for_type' => false, 'order_for_type' => 120],
            ['slug' => 'has_chimenea', 'is_required_for_type' => false, 'order_for_type' => 130],
            // Servicios
            ['slug' => 'has_lavanderia', 'is_required_for_type' => false, 'order_for_type' => 140],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 150],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 160],
        ]);

        // --- Tipos de Propiedad Comerciales ---
        $this->assignFeaturesToPropertyType('oficina', $features, [
            // Características Generales
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 40],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 50],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 60],
            // Amenidades (si aplica para oficinas, ej. sala de juntas, recepción, etc. - no en tu lista actual)
            // Servicios
            ['slug' => 'has_aire_acondicionado', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'has_telefonia', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'planta_emergencia', 'is_required_for_type' => false, 'order_for_type' => 120],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'has_zona_privada', 'is_required_for_type' => false, 'order_for_type' => 140], // En un edificio de oficinas
        ]);

        $this->assignFeaturesToPropertyType('terreno-comercial', $features, [
            // Características Generales
            ['slug' => 'tamano_terreno_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'ancho_m', 'is_required_for_type' => false, 'order_for_type' => 20],
            ['slug' => 'profundidad_m', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 50],
            // No suelen tener amenidades/servicios directamente en el terreno
        ]);

        $this->assignFeaturesToPropertyType('edificio', $features, [
            // Características Generales (aplicables a un edificio completo)
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_niveles', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 40],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 50],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 60],
            ['slug' => 'tipo_electricidad', 'is_required_for_type' => false, 'order_for_type' => 70],
            // Amenidades y Servicios (para el edificio en general)
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'planta_emergencia', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'sistema_contra_incendio', 'is_required_for_type' => false, 'order_for_type' => 120],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 130],
        ]);

        $this->assignFeaturesToPropertyType('local', $features, [
            // Características Generales
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 40],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 50],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 60],
            // Servicios
            ['slug' => 'has_aire_acondicionado', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'has_seguridad_privada', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 90],
        ]);

        $this->assignFeaturesToPropertyType('bodega-comercial', $features, [
            // Características Generales (para Bodega Comercial)
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'tamano_bodega_m2', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 30],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 50],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 60],
            ['slug' => 'ancho_m', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'alto_m', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'profundidad_m', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'andenes', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'area_maniobras', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'tipo_electricidad', 'is_required_for_type' => false, 'order_for_type' => 120],
            ['slug' => 'tipo_techo', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 140],
            // Servicios
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 150],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 160],
            ['slug' => 'planta_emergencia', 'is_required_for_type' => false, 'order_for_type' => 170],
            ['slug' => 'sistema_contra_incendio', 'is_required_for_type' => false, 'order_for_type' => 180],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 190],
        ]);

        // --- Tipos de Propiedad Industriales ---
        $this->assignFeaturesToPropertyType('terreno-industrial', $features, [
            // Características Generales
            ['slug' => 'tamano_terreno_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'ancho_m', 'is_required_for_type' => false, 'order_for_type' => 20],
            ['slug' => 'profundidad_m', 'is_required_for_type' => false, 'order_for_type' => 30],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 50],
            ['slug' => 'tipo_electricidad', 'is_required_for_type' => false, 'order_for_type' => 60], // Para uso industrial
        ]);

        $this->assignFeaturesToPropertyType('nave-industrial', $features, [
            // Características Generales
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'tamano_bodega_m2', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 30],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 50],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 60],
            ['slug' => 'ancho_m', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'alto_m', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'profundidad_m', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'andenes', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'area_maniobras', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'tipo_electricidad', 'is_required_for_type' => true, 'order_for_type' => 120], // Común en naves industriales
            ['slug' => 'tipo_techo', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 140],
            // Servicios
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 150],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 160],
            ['slug' => 'planta_emergencia', 'is_required_for_type' => false, 'order_for_type' => 170],
            ['slug' => 'sistema_contra_incendio', 'is_required_for_type' => false, 'order_for_type' => 180],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 190],
        ]);

        $this->assignFeaturesToPropertyType('bodega-industrial', $features, [
            // Muy similar a Bodega Comercial, pero quizás con más énfasis en lo industrial
            // Características Generales (para Bodega Industrial)
            ['slug' => 'tamano_construccion_m2', 'is_required_for_type' => true, 'order_for_type' => 10],
            ['slug' => 'tamano_bodega_m2', 'is_required_for_type' => true, 'order_for_type' => 20],
            ['slug' => 'num_banos', 'is_required_for_type' => true, 'order_for_type' => 30],
            ['slug' => 'num_estacionamientos', 'is_required_for_type' => true, 'order_for_type' => 40],
            ['slug' => 'anos_antiguedad', 'is_required_for_type' => false, 'order_for_type' => 50],
            ['slug' => 'uso_suelo', 'is_required_for_type' => true, 'order_for_type' => 60],
            ['slug' => 'ancho_m', 'is_required_for_type' => false, 'order_for_type' => 70],
            ['slug' => 'alto_m', 'is_required_for_type' => false, 'order_for_type' => 80],
            ['slug' => 'profundidad_m', 'is_required_for_type' => false, 'order_for_type' => 90],
            ['slug' => 'andenes', 'is_required_for_type' => false, 'order_for_type' => 100],
            ['slug' => 'area_maniobras', 'is_required_for_type' => false, 'order_for_type' => 110],
            ['slug' => 'tipo_electricidad', 'is_required_for_type' => true, 'order_for_type' => 120],
            ['slug' => 'tipo_techo', 'is_required_for_type' => false, 'order_for_type' => 130],
            ['slug' => 'vias_comunicacion', 'is_required_for_type' => false, 'order_for_type' => 140],
            // Servicios
            ['slug' => 'circuito_cerrado_tv', 'is_required_for_type' => false, 'order_for_type' => 150],
            ['slug' => 'fibra_optica', 'is_required_for_type' => false, 'order_for_type' => 160],
            ['slug' => 'planta_emergencia', 'is_required_for_type' => false, 'order_for_type' => 170],
            ['slug' => 'sistema_contra_incendio', 'is_required_for_type' => false, 'order_for_type' => 180],
            ['slug' => 'vigilancia', 'is_required_for_type' => false, 'order_for_type' => 190],
        ]);
    }

    /**
     * Helper function to assign features to a property type.
     */
    private function assignFeaturesToPropertyType(string $propertyTypeSlug, $features, array $featureDataArray): void
    {
        $propertyType = PropertyType::where('slug', $propertyTypeSlug)->first();

        if ($propertyType) {
            $syncData = [];
            foreach ($featureDataArray as $featureData) {
                if (isset($features[$featureData['slug']])) {
                    $syncData[$features[$featureData['slug']]->id] = [
                        'is_required_for_type' => $featureData['is_required_for_type'],
                        'order_for_type' => $featureData['order_for_type'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    $this->command->warn("Feature '{$featureData['slug']}' not found for property type '{$propertyTypeSlug}'.");
                }
            }
           
            $propertyType->features()->sync($syncData);
            $this->command->info("Características asignadas al tipo de propiedad '" . $propertyType->name . "'.");
        } else {
            $this->command->warn("Tipo de propiedad '{$propertyTypeSlug}' no encontrado. Saltando la asignación de características.");
        }
    }
}
