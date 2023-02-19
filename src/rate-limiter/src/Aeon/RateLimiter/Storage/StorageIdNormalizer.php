<?php declare(strict_types=1);

namespace Aeon\RateLimiter\Storage;

trait StorageIdNormalizer
{
    private function normalize(string $id) : string
    {
        return \mb_strtolower($id);
    }
}
