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
        $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        $html = trim($html);
        $html = preg_replace('/\s*=\s*/', '=', $html);
        $html = preg_replace('/\n\s*\n/', "\n", $html);

        return $html;
    }
}
