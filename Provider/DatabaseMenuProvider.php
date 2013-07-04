<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Provider;

use Doctrine\ORM\EntityManager;
use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseMenuProvider implements MenuProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $menuItemEntityName;

    /**
     * @var array
     */
    protected $menuItems;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $em
     * @param string                 $menuItemEntityName
     */
    public function __construct(EntityManager $em,
                                $menuItemEntityName)
    {
        $this->em = $em;
        $this->menuItemEntityName = $menuItemEntityName;
        $this->menuItems = array();
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        // Query the cache first.
        if ($menuItem = $this->getMenuItemInCache($name)) {
            return $menuItem;
        }

        $menuItem = $this->em
            ->getRepository($this->menuItemEntityName)
            ->findOneBy(array('name' => $name));

        if ($menuItem === null) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        $this->cacheMenuItem($name, $menuItem);

        return $menuItem;
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array $options
     * @return boolean
     */
    public function has($name, array $options = array())
    {
        // Check cache first.
        if ($this->getMenuItemInCache($name) !== false) {
            return true;
        }

        $menuItem = $this->em
            ->getRepository($this->menuItemEntityName)
            ->findOneBy(array('name' => $name));

        if ($menuItem === null) {
            return false;
        }

        $this->cacheMenuItem($name, $menuItem);

        return true;
    }

    /**
     * Will store the menu item in memory.
     *
     * @param string $name
     * @param MenuItem $menuItem
     */
    protected function cacheMenuItem($name, MenuItem $menuItem)
    {
        if (!array_key_exists($name, $this->menuItems)) {
            $this->menuItems[$name] = $menuItem;
        }
    }

    /**
     * Will retrieve a cached menu item.
     *
     * @param string $name
     * @return MenuItem|false False if the named menu item is not in the cache.
     */
    protected function getMenuItemInCache($name)
    {
        if (array_key_exists($name, $this->menuItems)) {
            return $this->menuItems[$name];
        }

        return false;
    }
}