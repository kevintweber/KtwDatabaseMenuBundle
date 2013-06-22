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

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseMenuProvider implements MenuProviderInterface
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * @var array
     */
    protected $menuItems;

    /**
     * @var boolean
     */
    protected $preloaded;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->menuItems = array();
        $this->preloaded = false;
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

        // Check if all menu items are already preloaded.
        if ($this->preloaded) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        // Get the repository name.
        $repositoryName = $this->container
            ->getParameter('ktw_database_menu.menu_item_repository');

        // Check if we need to preload now.
        if ($this->container->getParameter('ktw_database_menu.preload_menus')) {
            $this->loadAllMenuItems($repositoryName);

            if ($menuItem = $this->getMenuItemInCache($name)) {
                return $menuItem;
            }

            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        // If here, then $preload == false and $name is not cached.  Let's look for it.
        $menuItem = $this->container->get('doctrine')
            ->getRepository($repositoryName)
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

        if ($this->preloaded) {
            return false;
        }

        // Get the repository name.
        $repositoryName = $this->container
            ->getParameter('ktw_database_menu.menu_item_repository');

        // Check if we need to preload now.
        if ($this->container->getParameter('ktw_database_menu.preload_menus')) {
            $this->loadAllMenuItems($repositoryName);

            return $this->getMenuItemInCache($name) !== false;
        }

        // If here, then $preload == false and $name is not cached.  Let's look for it.
        $menuItem = $this->container->get('doctrine')
            ->getRepository($repositoryName)
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

    /**
     * Will load all the menu items into the cache array.
     *
     * @param string $repositoryName
     */
    protected function loadAllMenuItems($repositoryName)
    {
        // Clear the cache array.
        $this->menuItems = array();

        // Get all the menu items.
        $menuItems = $this->container->get('doctrine')
            ->getRepository($repositoryName)
            ->findAll();

        // Put the items into the cache array.
        foreach ($menuItems as $item) {
            $this->menuItems[$item->getName()] = $item;
        }

        // Set the preloaded flag.
        $this->preloaded = true;
    }
}