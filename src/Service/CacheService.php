<?php

namespace App\Service;

use App\Kernel;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheService
{
    protected FilesystemAdapter $cache;

    public function __construct(Kernel $kernel)
    {
        $this->cache = new FilesystemAdapter('partkeepr', 0, $kernel->getCacheDir());
    }

    public function getCache(): AbstractAdapter
    {
        return $this->cache;
    }
}