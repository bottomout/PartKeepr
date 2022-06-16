<?php

namespace App\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class ExtModelName implements Annotation
{
    /** @var string*/
    public $value;

    /**
     * @param array<string> $params
     */
    public function __construct(array $params)
    {
        $this->value = $params['value'];
    }
}
