<?php

namespace App\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class TargetService implements Annotation
{
    /**
     * @var string
     */
    public $uri;
}
