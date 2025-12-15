<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * Class Service
 * @package App\Services
 */
class Service
{

    protected array $jsonData = [];

    protected function encryptLogInArray(array $data, array $additionalKeys = []): array
    {
        $defaultKeys = ['password'];
        $keys = array_unique(array_merge($defaultKeys, $additionalKeys));

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data[$key] = Crypt::encryptString($data[$key]);
            }
        }

        return $data;
    }

    protected function json_encode(mixed $data): bool|string
    {
        return JsonEncode($data);
    }

    protected function setJsonData($data, $value = null) {
        if(!is_array($data)) {
            $this->jsonData[$data] = $value;
        } else {
            foreach ($data as $key => $val) {
                $this->jsonData[$key] = $val;
            }
        }
    }


    protected function returnJson() {
        return $this->jsonData;
    }

    protected function returnJsonData($data, $value = null)
    {
        $this->setJsonData($data, $value);
        return $this->returnJson();
    }

    protected function isImageType($mime_type) {
        if (Str::startsWith($mime_type, 'image/')) {
            return true;
        }

        return false;
    }

    protected function resizeImage(string $path, int $maxWidth = 1920)
    {
        $fullPath = Storage::path($path);
        $imageManager = new ImageManager(new Driver());
        $img = $imageManager->read($fullPath);

        if($img->width() > $maxWidth) {
            $img->scale(width : $maxWidth);
            $img->save($fullPath);
        }

        return $img;
    }

    protected function setImageThumb(ImageInterface $img, string $path, int $thumbWidth = 600) {
        $pathInfo = GetFolderFileName($path);
        $thumbImg = clone $img;

        if($thumbImg->width() > $thumbWidth) {
            $thumbImg->scale(width : $thumbWidth);
        }

        $thumbPath = $pathInfo['directory'] . '/thumb';

        Storage::makeDirectory($thumbPath);

        $thumbImg->save(Storage::path($thumbPath . '/' . $pathInfo['filename']));
    }

    protected function getFileName(UploadedFile $file, int $count) {
        $datetime = date('YmdHis');
        return $datetime . $count . '.'.$file->getClientOriginalExtension();
    }

    protected function uploadFile(UploadedFile $file, string $folderPath, int $thumbWidth = 0) : array
    {
        $data = [];
        if($file instanceof UploadedFile) {
            $fileName = $this->getFileName($file, 1);
            $path = $file->storeAs($folderPath, $fileName);
            $mime_type = $file->getMimeType();

            if($this->isImageType($mime_type)) {
                $img = $this->resizeImage($path);
                if($thumbWidth > 0) {
                    $this->setImageThumb($img, $path, $thumbWidth);
                }
            }

            if($path) {
                $data = [
                    'path' => $path,
                    'filename' => $file->getClientOriginalName(),
                    'mime_type' => $mime_type,
                    'file_size' => $file->getSize(),
                ];
            }
        }

        return $data;
    }

    protected function deleteStorageData(string $path) {
        if($path) {
            if(Storage::exists($path)) {
                Storage::delete($path);

                $thumbnailPath = GetThumbnailPath($path);
                if(Storage::exists($thumbnailPath)) {
                    Storage::delete($thumbnailPath);
                }

                return true;
            }
        }
        return false;
    }
}
