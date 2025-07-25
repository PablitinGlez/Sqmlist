<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo minificar en producción y para respuestas HTML
        if (
            app()->environment('production') &&
            $response instanceof \Illuminate\Http\Response &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html')
        ) {

            $content = $response->getContent();
            $minified = $this->minifyHtml($content);
            $response->setContent($minified);
        }

        return $response;
    }

    private function minifyHtml($html)
    {
        // Eliminar comentarios HTML (excepto los condicionales de IE)
        $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);

        // Eliminar espacios en blanco innecesarios
        $html = preg_replace('/\s+/', ' ', $html);

        // Eliminar espacios alrededor de etiquetas
        $html = preg_replace('/>\s+</', '><', $html);

        // Eliminar espacios al inicio y final
        $html = trim($html);

        // Eliminar espacios innecesarios en atributos
        $html = preg_replace('/\s*=\s*/', '=', $html);

        // Eliminar líneas vacías
        $html = preg_replace('/\n\s*\n/', "\n", $html);

        return $html;
    }
}
