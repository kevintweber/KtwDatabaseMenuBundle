<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Menu;

use kevintweber\KtwDatabaseMenuBundle\Entity\MenuItem as KtwMenuItem;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Factory\CoreExtension;
use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\Silex\RoutingExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseMenuFactory implements FactoryInterface
{
    protected $menuItemEntityName;

    /**
     * @var \SplPriorityQueue|ExtensionInterface[]
     */
    protected $extensions;

    /**
     * Constructor
     */
    public function __construct(ExtensionInterface $routingExtension,
                                $menuItemEntityName)
    {
        $this->menuItemEntityName = $menuItemEntityName;
        $this->extensions = new \SplPriorityQueue();
        $this->addExtension(new CoreExtension(), -20);
        $this->addExtension($routingExtension, -10);
    }

    /**
     * Creates the menu item.
     *
     * @param string $name
     * @param array  $options
     */
    public function createItem($name, array $options = array())
    {
        foreach (clone $this->extensions as $extension) {
            $options = $extension->buildOptions($options);
        }

        $class = $this->menuItemEntityName;
        $item = new $class($name, $this);

        foreach (clone $this->extensions as $extension) {
            $extension->buildItem($item, $options);
        }

        return $item;
    }

    /**
     * Adds a factory extension
     *
     * @param ExtensionInterface $extension
     * @param integer $priority
     */
    public function addExtension(ExtensionInterface $extension, $priority = 0)
    {
        $this->extensions->insert($extension, $priority);
    }
}