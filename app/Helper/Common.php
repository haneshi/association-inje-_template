<?php

use Illuminate\Http\Exceptions\HttpResponseException;

// ========================= System ==========================
if(!function_exists('GetRouteName')) {
    function GetRouteName() {
        return match(request()->segment(1)) {
            'admin' => 'admin',
            default => 'web',
        };
    }
}

if(!function_exists('RedirectUrl')) {
    function RedirectUrl(mixed $url = null, array $withMessage = []) {
        if(empty($withMessage)) {
            if($url) {
                throw new HttpResponseException(redirect($url));
            }

            throw new HttpResponseException(redirect()->back());
        }

        if($url) {
            throw new HttpResponseException(redirect($url)->with($withMessage));
        }
        throw new HttpResponseException(redirect()->back()->with($withMessage));
    }
}

if (!function_exists('RedirectRoute')) {
    function RedirectRoute(mixed $route = null, mixed $params = null, array $withMessage = []): void
    {
        if (empty($withMessage)) {
            if ($route) {
                if (!$params) {
                    throw new HttpResponseException(redirect()->route($route));
                } else {
                    throw new HttpResponseException(redirect()->route($route, $params));
                }
            }

            throw new HttpResponseException(redirect()->back());
        }

        if ($route) {
            if (!$params) {
                throw new HttpResponseException(redirect()->route($route)->with($withMessage));
            } else {
                throw new HttpResponseException(redirect()->route($route, $params)->with($withMessage));
            }
        }

        throw new HttpResponseException(redirect()->back()->with($withMessage));
    }
}

if (!function_exists('GetFolderFileName')) {
    function GetFolderFileName(string $path) {
        $pathInfo = pathinfo($path);
        return [
            'directory' => $pathInfo['dirname'],
            'filename' => $pathInfo['basename']
        ];
    }
}

if(!function_exists('GetThumbnailPath')) {
    function GetThumbnailPath(string $path, string $folderName = 'thumb') {
        $pathInfo = GetFolderFileName($path);
        return $pathInfo['directory'] . '/' . $folderName .'/'.$pathInfo['filename'];
    }
}

// ========================= Helper ==========================
if (!function_exists('JsonEncode')) {
    function JsonEncode(mixed $data): bool|string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}


