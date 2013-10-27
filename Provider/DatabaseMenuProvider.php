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

use kevintweber\KtwDatabaseMenuBundle\Repository\MenuItemRepositoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;

class DatabaseMenuProvider implements MenuProviderInterface
{
    /**
     * @var MenuItemRepositoryInterface
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $em
     * @param string                 $menuItemEntityName
     */
    public function __construct(MenuItemRepositoryInterface $menuItemRepository)
    {
        $this->repository = $menuItemRepository;
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
        $menuItem = $this->repository->getMenuItemByName($name);

        if ($menuItem === null) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

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
        $menuItem = $this->repository->getMenuItemByName($name);

        if ($menuItem === null) {
            return false;
        }

        return true;
    }
}