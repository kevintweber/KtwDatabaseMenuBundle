<?php

/*
 * This file is part of the KtwDatabaseMenuBundle package.
 *
 * (c) Kevin T. Weber <https://github.com/kevintweber/KtwDatabaseMenuBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kevintweber\KtwDatabaseMenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem as KnpMenuItem;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ktw_menu_items")
 */
class MenuItem extends KnpMenuItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name of this menu item (used for id by parent menu)
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $name = null;

    /**
     * Label to output, name is used by default
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $label = null;

    /**
     * Attributes for the item link
     *
     * @ORM\Column(type="array")
     */
    protected $linkAttributes = array();

    /**
     * Attributes for the children list
     *
     * @ORM\Column(type="array")
     */
    protected $childrenAttributes = array();

    /**
     * Attributes for the item text
     *
     * @ORM\Column(type="array")
     */
    protected $labelAttributes = array();

    /**
     * Uri to use in the anchor tag
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $uri = null;

    /**
     * Attributes for the item
     *
     * @ORM\Column(type="array")
     */
    protected $attributes = array();

    /**
     * Extra stuff associated to the item
     *
     * @ORM\Column(type="array")
     */
    protected $extras = array();

    /**
     * Whether the item is displayed
     *
     * @ORM\Column(type="boolean")
     */
    protected $display = true;

    /**
     * Whether the children of the item are displayed
     *
     * @ORM\Column(type="boolean")
     */
    protected $displayChildren = true;

    /**
     * Child items
     *
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent", cascade={"all"})
     */
    protected $children;

    /**
     * Parent item
     *
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children", cascade={"all"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent = null;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Constructor
     */
    public function __construct($name, FactoryInterface $factory)
    {
        $this->children = new ArrayCollection();

        parent::__construct($name, $factory);
    }

    /**
     * Getter for 'created'.
     *
     * @return mixed The value of 'created'.
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Getter for 'updated'.
     *
     * @return mixed The value of 'updated'.
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = new \DateTime;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime;
    }

    /*
     * Note: Since Knp\Menu uses an array for children, while we use an
     * ArrayCollection, we must adapt all references to children.
     */

    /**
     * {@inheritDoc}
     */
    public function addChild($child, array $options = array())
    {
        if (!($child instanceof ItemInterface)) {
            $child = $this->factory->createItem($child, $options);
        } elseif (null !== $child->getParent()) {
            throw new \InvalidArgumentException('Cannot add menu item as child, it already belongs to another menu (e.g. has a parent).');
        }

        $child->setParent($this);

        $this->children->set($child->getName(), $child);

        return $child;
    }

    /**
     * {@inheritDoc}
     */
    public function getChild($name)
    {
        return $this->children->get($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return $this->children->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function setChildren(array $children)
    {
        $this->children = new ArrayCollection($children);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild($name)
    {
        $name = $name instanceof ItemInterface ? $name->getName() : $name;

        $child = $this->getChild($name);
        if ($child !== null) {
            $child->setParent(null);
            $this->children->remove($name);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstChild()
    {
        return $this->children->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastChild()
    {
        return $this->children->last();
    }

    /**
     * {@inheritDoc}
     */
    public function moveChildToPosition(ItemInterface $child, $position)
    {
        $name = $child->getName();
        $order = $this->children->getKeys();

        $oldPosition = array_search($name, $order);
        unset($order[$oldPosition]);

        $order = array_values($order);

        array_splice($order, $position, 0, $name);
        $this->reorderChildren($order);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function reorderChildren($order)
    {
        if (count($order) != $this->count()) {
            throw new \InvalidArgumentException('Cannot reorder children, order does not contain all children.');
        }

        $newChildren = array();

        foreach ($order as $name) {
            if (!$this->children->containsKey($name)) {
                throw new \InvalidArgumentException('Cannot find children named ' . $name);
            }

            $child = $this->getChild($name);
            $newChildren[$name] = $child;
        }

        $this->setChildren($newChildren);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function copy()
    {
        $newMenu = clone $this;
        $newMenu->children = new ArrayCollection();
        $newMenu->setParent(null);
        foreach ($this->getChildren() as $child) {
            $newMenu->addChild($child->copy());
        }

        return $newMenu;
    }
}