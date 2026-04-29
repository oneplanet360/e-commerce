<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ImageService
{
    /**
     * Upload an image to a specified folder in the public directory.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public static function upload(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('uploads/' . $folder);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        return 'uploads/' . $folder . '/' . $filename;
    }

    /**
     * Delete an image from the public directory.
     *
     * @param string|null $path
     * @return bool
     */
    public static function delete(?string $path): bool
    {
        if ($path && File::exists(public_path($path))) {
            return File::delete(public_path($path));
        }

        return false;
    }

    /**
     * Handle updating an image: delete old if new is provided.
     *
     * @param UploadedFile|null $file
     * @param string $folder
     * @param string|null $oldPath
     * @return string|null
     */
    public static function update(?UploadedFile $file, string $folder, ?string $oldPath): ?string
    {
        if (!$file) {
            return $oldPath;
        }

        // Delete old image
        self::delete($oldPath);

        // Upload new image
        return self::upload($file, $folder);
    }
}
