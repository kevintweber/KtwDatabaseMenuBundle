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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
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
     * @OneToMany(targetEntity="MenuItem", mappedBy="parent")
     */
    protected $children;

    /**
     * Parent item
     *
     * @ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent = null;

    /**
     * Constructor
     */
    public function __construct($name, FactoryInterface $factory)
    {
        $this->children = new ArrayCollection();

        parent::__construct($name, $factory);
    }
}