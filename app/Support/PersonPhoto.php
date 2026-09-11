<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

use function Illuminate\Filesystem\join_paths;

final class PersonPhoto
{
    public const DISK = 'public';

    public const DIRECTORY = 'photos';

    private function __construct() {}

    public static function path(?string $fileName): ?string
    {
        return $fileName !== null
            ? join_paths(self::DIRECTORY, $fileName)
            : null;
    }

    public static function url(?string $fileName): ?string
    {
        return $fileName !== null
            ? Storage::disk(self::DISK)->url(self::path($fileName))
            : null;
    }

    public static function delete(string $fileName): void
    {
        Storage::disk(self::DISK)->delete(self::path($fileName));
    }
}
