<?php

namespace App\Service;

use App\Annotation\ByReference;
use App\Annotation\ExtModelName;
use App\Annotation\TargetService;
use App\Annotation\VirtualField;
use App\Annotation\VirtualOneToMany;
use App\Entity\AbstractCategory;
use App\Kernel;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem;

class ReflectionService
{
    public const EntityReverseMapCacheKey = 'app.service.reflection_service.entity_reverse_map';

    protected EntityManagerInterface $em;
    protected $templateEngine;
    protected Reader $reader;
    protected AbstractAdapter $cache;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container, Reader $reader, CacheService $cacheService)
    {
        $this->templateEngine = $container->get('twig');
        $this->em = $em;
        $this->reader = $reader;
        $this->cache = $cacheService->getCache();
    }

    public function rebuildEntityReverseMap()
    {
        $this->cache->delete(self::EntityReverseMapCacheKey);
    }

    public function getReverseEntityMap(): array
    {
        return $this->cache->get(self::EntityReverseMapCacheKey, function ($item) {
            $entities = [];
            foreach ($this->em->getMetadataFactory()->getAllMetadata() as $cm) {
                $entities[$this->convertPHPToExtJSClassMetadata($cm)] = $cm->getName();
            }
            return $entities;
        });
    }

    /**
     * Returns a list of all registered entities, converted to the ExtJS naming scheme (. instead of \).
     *
     * @return array
     */
    public function getEntities()
    {
        $entities = [];

        $meta = $this->em->getMetadataFactory()->getAllMetadata();

        foreach ($meta as $m) {
            /* @var ClassMetadata $m */
            $entities[] = $this->convertPHPToExtJSClassMetadata($m);
        }

        return $entities;
    }

    /**
     * Returns the ExtJS Model contents for a given entity.
     *
     * @param $entity string The ExtJS class name
     *
     * @return string The ExtJS model code
     */
    public function getEntity($entity)
    {
        $bTree = false;

        $parentClass = 'PartKeepr.data.HydraModel';

        $entity = $this->convertExtJSToPHPClassName($entity);

        $cm = $this->em->getClassMetadata($entity);

        if ($cm->getReflectionClass()->isSubclassOf(AbstractCategory::class)) {
            $parentClass = 'PartKeepr.data.HydraTreeModel';
            $bTree = true;
        }

        $fieldMappings = [];

        $fieldMappings = array_merge($fieldMappings, $this->getVirtualFieldMappings($cm));
        $fieldMappings = array_merge($fieldMappings, $this->getDatabaseFieldMappings($cm));

        $associationMappings = $this->getDatabaseAssociationMappings($cm, $bTree);

        $associationMappings["ONE_TO_MANY"] = array_merge(
            $associationMappings["ONE_TO_MANY"],
            $this->getVirtualOneToManyRelationMappings($cm)
        );

        $renderParams = [
            'fields'       => $fieldMappings,
            'associations' => $associationMappings,
            'className'    => $this->convertPHPToExtJSClassMetadata($cm),
            'parentClass'  => $parentClass,
        ];

        $targetService = $this->reader->getClassAnnotation($cm->getReflectionClass(), TargetService::class);

        if ($targetService !== null) {
            $renderParams['uri'] = $targetService->uri;
        }

        $ignoreIds = $this->reader->getClassAnnotation(
            $cm->getReflectionClass(),
            "App\Annotation\IgnoreIds"
        );

        if ($ignoreIds !== null) {
            $renderParams['ignoreIds'] = true;
        } else {
            $renderParams['ignoreIds'] = false;
        }

        return $this->templateEngine->render('model.js.twig', $renderParams);
    }

    /**
     * Returns association mapping for a given entity.
     *
     * @param ClassMetadata $cm
     * @param bool|false    $bTree
     *
     * @return array
     */
    protected function getDatabaseAssociationMappings(ClassMetadata $cm, $bTree = false)
    {
        $associations = $cm->getAssociationMappings();
        $byReferenceMappings = $this->getByReferenceMappings($cm);

        $associationMappings = [
            "ONE_TO_ONE"   => [],
            "MANY_TO_ONE"  => [],
            "ONE_TO_MANY"  => [],
            "MANY_TO_MANY" => [],
        ];

        foreach ($associations as $association) {
            $getterPlural = false;
            $associationType = $association['type'];

            switch ($association['type']) {
                case ClassMetadataInfo::MANY_TO_MANY:
                    $associationType = 'MANY_TO_MANY';
                    $getterPlural = true;
                    break;
                case ClassMetadataInfo::MANY_TO_ONE:
                    $associationType = 'MANY_TO_ONE';
                    $getterPlural = false;
                    break;
                case ClassMetadataInfo::ONE_TO_MANY:
                    $associationType = 'ONE_TO_MANY';
                    $getterPlural = true;
                    break;
                case ClassMetadataInfo::ONE_TO_ONE:
                    $associationType = 'ONE_TO_ONE';
                    $getterPlural = false;
                    break;
            }

            $getter = 'get'.ucfirst($association['fieldName']);
            $getterField = lcfirst($cm->getReflectionClass()->getShortName()).str_replace(
                '.',
                '',
                $this->convertPHPToExtJSClassName($association['targetEntity'])
            );

            if ($getterPlural) {
                $getterField .= 's';
            }

            $propertyAnnotations = $this->reader->getPropertyAnnotations(
                $cm->getReflectionProperty($association['fieldName'])
            );

            $nullable = true;

            foreach ($propertyAnnotations as $propertyAnnotation) {
                $filter = "Symfony\\Component\\Validator\\Constraints\\NotNull";

                if (substr(get_class($propertyAnnotation), 0, strlen($filter)) === $filter) {
                    $nullable = false;
                }
            }

            // The self-referencing association may not be written for trees, because ExtJS can't load all nodes
            // in one go.
            if (!($bTree && $association['targetEntity'] == $cm->getName())) {
                $byReference = false;

                if (in_array($association['fieldName'], $byReferenceMappings)) {
                    $byReference = true;
                }
                $associationMappings[$associationType][] = [
                    'name'        => $association['fieldName'],
                    'nullable'    => $nullable,
                    'target'      => $this->convertPHPToExtJSClassName($association['targetEntity']),
                    'byReference' => $byReference,
                    'getter'      => $getter,
                    'getterField' => $getterField,
                ];
            }
        }

        return $associationMappings;
    }

    /**
     * Returns all virtual field mappings.
     *
     * @param ClassMetadata $cm
     *
     * @return array
     */
    protected function getVirtualFieldMappings(ClassMetadata $cm)
    {
        $fieldMappings = [];

        foreach ($cm->getReflectionClass()->getProperties() as $property) {
            $virtualFieldAnnotation = $this->reader->getPropertyAnnotation($property, VirtualField::class);

            if ($virtualFieldAnnotation !== null) {
                $fieldMappings[] = [
                    'persist' => true,
                    'name'    => $property->getName(),
                    'type'    => $this->getExtJSFieldMapping($virtualFieldAnnotation->type),
                ];
            }
        }

        return $fieldMappings;
    }

    /**
     * Returns all virtual relations mappings.
     *
     * @param ClassMetadata $cm
     *
     * @return array
     */
    protected function getVirtualOneToManyRelationMappings(ClassMetadata $cm)
    {
        $virtualRelationMappings = [];

        foreach ($cm->getReflectionClass()->getProperties() as $property) {
            $virtualOneToManyRelation = $this->reader->getPropertyAnnotation($property, VirtualOneToMany::class);

            if ($virtualOneToManyRelation !== null) {
                $virtualRelationMappings[] =
                    [
                        'name'   => $property->getName(),
                        'target' => $this->convertPHPToExtJSClassName($virtualOneToManyRelation->target),
                    ];
            }
        }

        return $virtualRelationMappings;
    }

    /**
     * Returns all by-reference associations.
     *
     * @param ClassMetadata $cm
     *
     * @return array
     */
    protected function getByReferenceMappings(ClassMetadata $cm)
    {
        $byReferenceMappings = [];

        foreach ($cm->getReflectionClass()->getProperties() as $property) {
            $byReferenceAnnotation = $this->reader->getPropertyAnnotation($property, ByReference::class);

            if ($byReferenceAnnotation !== null) {
                $byReferenceMappings[] = $property->getName();
            }
        }

        return $byReferenceMappings;
    }

    /**
     * Returns database field mappings.
     *
     * @param ClassMetadata $cm
     *
     * @throws \Doctrine\ORM\Mapping\MappingException
     *
     * @return array
     */
    protected function getDatabaseFieldMappings(ClassMetadata $cm)
    {
        $fieldMappings = [];
        $fields = $cm->getFieldNames();

        foreach ($fields as $field) {
            $currentMapping = $cm->getFieldMapping($field);

            $asserts = $this->getExtJSAssertMappings($cm, $field);

            if ($currentMapping['fieldName'] == 'id') {
                $currentMapping['fieldName'] = '@id';
                $currentMapping['type'] = 'string';
            }

            if (!array_key_exists("nullable", $currentMapping)) {
                $currentMapping["nullable"] = false;
            }

            $fieldMappings[] = [
                'name'       => $currentMapping['fieldName'],
                'type'       => $this->getExtJSFieldMapping($currentMapping['type']),
                'nullable'   => $currentMapping['nullable'],
                'validators' => json_encode($asserts),
                'persist'    => $this->allowPersist($cm, $field),
            ];
        }

        return $fieldMappings;
    }

    /**
     * Converts a doctrine/PHP type to the ExtJS type.
     *
     * @param $type string the PHP/doctrine type
     *
     * @return string The ExtJS type
     */
    protected function getExtJSFieldMapping($type)
    {
        switch ($type) {
            case 'integer':
                return 'int';
                break;
            case 'string':
                return 'string';
                break;
            case 'text':
                return 'string';
                break;
            case 'datetime':
                return 'date';
                break;
            case 'boolean':
                return 'boolean';
                break;
            case 'float':
                return 'number';
                break;
            case 'decimal':
                return 'number';
                break;
            case 'array':
                return 'array';
                break;
        }

        return 'undefined';
    }

    public function getExtJSAssertMapping(Constraint $assert)
    {
        switch (get_class($assert)) {
            case "Symfony\\Component\\Validator\\Constraints\\NotBlank":
                /**
                 * @var NotBlank
                 */
                return ["type" => "presence", "message" => $assert->message];
                break;
            default:
                return false;
        }
    }

    public function getExtJSAssertMappings(ClassMetadata $cm, $field)
    {
        $asserts = [];
        $propertyAnnotations = $this->reader->getPropertyAnnotations($cm->getReflectionProperty($field));

        foreach ($propertyAnnotations as $propertyAnnotation) {
            $filter = "Symfony\\Component\\Validator\\Constraints\\";

            if (substr(get_class($propertyAnnotation), 0, strlen($filter)) === $filter) {
                $assertMapping = $this->getExtJSAssertMapping($propertyAnnotation);

                if ($assertMapping !== false) {
                    $asserts[] = $assertMapping;
                }
            }
        }

        return $asserts;
    }

    public function allowPersist(ClassMetadata $cm, $field)
    {
        $groupsAnnotation = $this->reader->getPropertyAnnotation(
            $cm->getReflectionProperty($field),
            'Symfony\Component\Serializer\Annotation\Groups'
        );

        if ($groupsAnnotation !== null) {
            if (in_array("readonly", $groupsAnnotation->getGroups())) {
                return false;
            }
        }

        return true;
    }

    public function convertPHPToExtJSClassMetadata(ClassMetadata $cm): string
    {
        $extModelName = $this->reader->getClassAnnotation($cm->getReflectionClass(), ExtModelName::class);
        return $extModelName !== null ? $extModelName->value : str_replace('\\', '.', $cm->getName());
    }

    /**
     * Converts a PHP class name with namespaces to an ExtJS class name with namespaces.
     */
    public function convertPHPToExtJSClassName($className)
    {
        return $this->convertPHPToExtJSClassMetadata($this->em->getClassMetadata($className));
    }

    /**
     * Converts an ExtJS class name with namespaces to a PHP class name with namespaces.
     *
     * @param $className
     *
     * @return string
     */
    public function convertExtJSToPHPClassName($className)
    {
        return isset($this->getReverseEntityMap()[$className]) ? $this->getReverseEntityMap()[$className] : str_replace('.', '\\', $className);
    }

    public function createCache($cacheDir)
    {
        $fs = new Filesystem();
        $fs->remove($cacheDir);
        $fs->mkdir($cacheDir);

        $this->rebuildEntityReverseMap();
        $entities = $this->getEntities();

        foreach ($entities as $entity) {
            $model = $this->getEntity($entity);

            $this->writeCacheFile($cacheDir.'/'.$entity.'.js', $model);
        }
    }

    protected function writeCacheFile($file, $content)
    {
        $tmpFile = tempnam(dirname($file), basename($file));
        if (false !== @file_put_contents($tmpFile, $content) && @rename($tmpFile, $file)) {
            @chmod($file, 0666 & ~umask());

            return;
        }

        throw new \RuntimeException(sprintf('Failed to write cache file "%s".', $file));
    }
}
