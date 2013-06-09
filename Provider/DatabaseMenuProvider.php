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

use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseMenuProvider implements MenuProviderInterface
{
    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $repositoryName = $this->container()->getParameter('ktw_database_menu.menu_item_repository');

        $menuItem = $this->container->get('doctrine')
            ->getRepository($repositoryName)
            ->findOneBy(array('name' => $name));

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
     * @return bool
     */
    public function has($name, array $options = array())
    {
        $repositoryName = $this->container()->getParameter('ktw_database_menu.menu_item_repository');

        $menuItem = $this->container->get('doctrine')
            ->getRepository($repositoryName)
            ->findOneBy(array('name' => $name));

        return $menuItem !== null;
    }
}