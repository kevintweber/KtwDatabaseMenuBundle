<?php

namespace kevintweber\KtwDatabaseMenuBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Class MappingListener
 *
 * @author  emulienfou <david38sanchez@gmail.com>
 * @package kevintweber\KtwDatabaseMenuBundle\EventListener
 */
class MappingListener
{

    protected $tableName;

    /**
     * MappingListener constructor.
     *
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $table         = $classMetadata->table;

        // Update table name from configuration
        $table['name'] = $this->tableName;
        $classMetadata->setPrimaryTable($table);
    }
}