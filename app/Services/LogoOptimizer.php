<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Brand-logo pipeline (public disk):
 *  - SVG: sanitised in place (scripts, event handlers, external/JS references removed).
 *  - PNG/JPG/WebP: original capped at 800px and responsive WebP variants generated (160w, 320w) when GD is available.
 * Returns the variant map stored on the brand ({"160": path, "320": path}) — empty when nothing was generated.
 */
class LogoOptimizer
{
    private const WIDTHS = [160, 320];

    public function process(string $path): array
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return [];
        }

        try {
            if (str_ends_with(strtolower($path), '.svg')) {
                $disk->put($path, $this->sanitizeSvg($disk->get($path)));

                return [];
            }

            return $this->rasterVariants($disk->path($path), $path);
        } catch (Throwable) {
            return []; // Never block a save because optimisation failed.
        }
    }

    public function sanitizeSvg(string $svg): string
    {
        $svg = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $svg);
        $svg = preg_replace('#<foreignObject\b[^>]*>.*?</foreignObject>#is', '', $svg);
        $svg = preg_replace('#\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $svg);
        $svg = preg_replace('#(href|xlink:href)\s*=\s*("|\')\s*(javascript:|data:text/html|https?:)[^"\']*\2#i', '', $svg);

        return (string) $svg;
    }

    private function rasterVariants(string $absolute, string $path): array
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            return [];
        }

        $source = @imagecreatefromstring((string) file_get_contents($absolute));
        if (! $source) {
            return [];
        }

        [$width, $height] = [imagesx($source), imagesy($source)];
        $variants = [];
        $base = preg_replace('/\.[a-z0-9]+$/i', '', $path);

        foreach (self::WIDTHS as $target) {
            if ($width <= $target) {
                continue;
            }
            $resized = $this->resize($source, $width, $height, $target);
            $variantPath = "{$base}-{$target}w.webp";
            imagewebp($resized, Storage::disk('public')->path($variantPath), 85);
            imagedestroy($resized);
            $variants[(string) $target] = $variantPath;
        }

        // Cap the original so nobody serves a 4000px logo.
        if ($width > 800) {
            $capped = $this->resize($source, $width, $height, 800);
            match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
                'png' => imagepng($capped, $absolute, 8),
                'webp' => imagewebp($capped, $absolute, 85),
                default => imagejpeg($capped, $absolute, 85),
            };
            imagedestroy($capped);
        }

        imagedestroy($source);

        return $variants;
    }

    private function resize($source, int $width, int $height, int $target)
    {
        $newHeight = (int) round($height * $target / $width);
        $canvas = imagecreatetruecolor($target, $newHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $target, $newHeight, $width, $height);

        return $canvas;
    }
}
