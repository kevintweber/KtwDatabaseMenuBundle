<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class MenuItemRepository extends EntityRepository implements MenuItemRepositoryInterface
{
    protected $cacheLoaded;
    protected $nameArray;

    /**
     * Constructor.
     *
     * @param EntityManager $em    The EntityManager to use.
     * @param ClassMetadata $class The class descriptor.
     */
    public function __construct($em, ClassMetadata $class)
    {
        $this->cacheLoaded = false;
        $this->nameArray = array();

        parent::__construct($em, $class);
    }

    public function getMenuItemByName($name)
    {
        $this->populateCache();

        if (!array_key_exists($name, $this->nameArray)) {
            return null;
        }

        return $this->nameArray[$name];
    }

    /**
     * Will query all the menu items at and sort them for the cache.
     *
     * @todo Integration with LiipDoctrineCacheBundle
     */
    protected function populateCache()
    {
        if (!$this->cacheLoaded) {
            // Query three levels deep.
            $allMenuItems = $this->createQueryBuilder('m')
                ->addSelect('children')
                ->leftJoin('m.children', 'children')
                ->getQuery()
                ->getResult();

            foreach ($allMenuItems as $menuItem) {
                $this->nameArray[$menuItem->getName()] = $menuItem;
            }

            $this->cacheLoaded = true;
        }
    }
}
