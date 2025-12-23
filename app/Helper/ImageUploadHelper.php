<?php

namespace App\Helper;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadHelper
{
    private static ImageManager $imageManager;
    private static array $validationRules = [
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
    ];

    private static function getImageManager(): ImageManager
    {
        if (!isset(self::$imageManager)) {
            self::$imageManager = new ImageManager(new Driver());
        }
        return self::$imageManager;
    }

    public static function upload(UploadedFile $file, string $directory = 'images', array $options = [], ?int $seq = null): array|bool
    {
        return self::processImage($file, $directory, array_merge(['quality' => 90], $options), $seq);
    }


    private static function processImage(UploadedFile $file, string $directory, array $options, ?int $seq = null): array|bool
    {
        try {
            if (!self::validateImage($file)) {
                return false;
            }

            $filename = self::generateFilename($file);
            $filePath = trim($directory, '/') . '/' . $filename;

            $image = self::getImageManager()->read($file->getRealPath());

            // 회전 보정
            if ($options['auto_orientate'] ?? false) {
                self::correctOrientation($image);
            }

            // 리사이징
            self::resizeImage($image, $options['width'] ?? null, $options['height'] ?? null);

            // 크기 최적화
            $image = self::optimizeImage($image, $options['optimization'] ?? []);

            // 포맷으로 인코딩
            $format = $options['format'] ?? 'jpeg';
            $encodedImage = self::encodeImage($image, $format, $options['quality']);

            // 저장
            $disk = $options['disk'] ?? 'data';
            self::storeImage($filePath, $encodedImage, $disk);

            $data = [
                'seq' => $seq, // 기본값 255
                'file_path' => $filePath,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize() / 1024,
            ];

            if ($seq !== null) {
                $data['seq'] = $seq + 1;
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('이미지 처리 실패: ' . $e->getMessage());
            return false;
        }
    }

    private static function correctOrientation($image): void
    {
        $exif = $image->exif();
        if (isset($exif['Orientation'])) {
            $image->orientate();
        }
    }

    private static function resizeImage($image, ?int $width, ?int $height): void
    {
        if ($width && $height) {
            $image->resize($width, $height);
        } elseif ($width && $image->width() > $width) {
            $image->scale(width: $width);
        }
    }

    private static function optimizeImage($image, array $optimizationOptions): mixed
    {
        if ($optimizationOptions['strip_metadata'] ?? false) {
            $image->strip();
        }
        if ($optimizationOptions['auto_quality'] ?? false) {
            $targetSizeKB = $optimizationOptions['target_size'] ?? 500;
            $quality = 90;
            while ($image->toJpeg($quality)->length() / 1024 > $targetSizeKB && $quality > 50) {
                $quality -= 5;
            }
            $image->encode('jpeg', $quality);
        }
        return $image;
    }

    private static function encodeImage($image, string $format, int $quality): string
    {
        return match ($format) {
            'webp' => $image->toWebp($quality),
            'avif' => $image->toAvif($quality),
            default => $image->toJpeg($quality),
        };
    }

    private static function storeImage(string $filePath, string $encodedImage, string $disk): bool
    {
        return Storage::disk($disk)->put($filePath, $encodedImage);
    }

    private static function validateImage(UploadedFile $file): bool
    {
        return !Validator::make(['image' => $file], self::$validationRules)->fails();
    }

    private static function generateFilename(UploadedFile $file): string
    {
        return sprintf('%s_%s.%s', now()->timestamp, Str::random(10), $file->getClientOriginalExtension());
    }
}
