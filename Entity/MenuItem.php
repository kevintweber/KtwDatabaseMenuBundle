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
 * @ORM\Entity(repositoryClass="kevintweber\KtwDatabaseMenuBundle\Repository\MenuItemRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ktw_menu_items",
 *     indexes={@ORM\Index(name="uri_idx", columns={"uri"})})
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
     * Uri to use in the anchor tag
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $uri = null;

    /**
     * Array-type data
     *
     * @ORM\Column(type="array")
     */
    protected $data = array();

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
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
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

        $this->data = array('attributes' => array(),
                            'linkAttributes' => array(),
                            'childrenAttributes' => array(),
                            'labelAttributes' => array(),
                            'extras' => array());

        parent::__construct($name, $factory);
    }

    /**
     * Getter for 'id'.
     *
     * @return mixed The value of 'id'.
     */
    public function getId()
    {
        return $this->id;
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
     * Note: Unlike PHP arrays which are quite light-weight, Doctrine arrays
     * are quite heavy-weight in the database.  Therefore, we are trying here
     * to combine several PHP arrays into one Doctrine array.
     */

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->data['attributes'];
    }

    /**
     * {@inheritDoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->data['attributes'] = $attributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        if (isset($this->data['attributes'][$name])) {
            return $this->data['attributes'][$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute($name, $value)
    {
        $this->data['attributes'][$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLinkAttributes()
    {
        return $this->data['linkAttributes'];
    }

    /**
     * {@inheritDoc}
     */
    public function setLinkAttributes(array $linkAttributes)
    {
        $this->data['linkAttributes'] = $linkAttributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLinkAttribute($name, $default = null)
    {
        if (isset($this->data['linkAttributes'][$name])) {
            return $this->data['linkAttributes'][$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setLinkAttribute($name, $value)
    {
        $this->data['linkAttributes'][$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenAttributes()
    {
        return $this->data['childrenAttributes'];
    }

    /**
     * {@inheritDoc}
     */
    public function setChildrenAttributes(array $childrenAttributes)
    {
        $this->data['childrenAttributes'] = $childrenAttributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenAttribute($name, $default = null)
    {
        if (isset($this->data['childrenAttributes'][$name])) {
            return $this->data['childrenAttributes'][$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setChildrenAttribute($name, $value)
    {
        $this->data['childrenAttributes'][$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabelAttributes()
    {
        return $this->data['labelAttributes'];
    }

    /**
     * {@inheritDoc}
     */
    public function setLabelAttributes(array $labelAttributes)
    {
        $this->data['labelAttributes'] = $labelAttributes;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLabelAttribute($name, $default = null)
    {
        if (isset($this->data['labelAttributes'][$name])) {
            return $this->data['labelAttributes'][$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setLabelAttribute($name, $value)
    {
        $this->data['labelAttributes'][$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtras()
    {
        return $this->data['extras'];
    }

    /**
     * {@inheritDoc}
     */
    public function setExtras(array $extras)
    {
        $this->data['extras'] = $extras;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtra($name, $default = null)
    {
        if (isset($this->data['extras'][$name])) {
            return $this->data['extras'][$name];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtra($name, $value)
    {
        $this->data['extras'][$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray($depth = null)
    {
        $array = array(
            'name' => $this->name,
            'label' => $this->label,
            'uri' => $this->uri,
            'attributes' => $this->getAttributes(),
            'labelAttributes' => $this->getLabelAttributes(),
            'linkAttributes' => $this->getLinkAttributes(),
            'childrenAttributes' => $this->getChildrenAttributes(),
            'extras' => $this->getExtras(),
            'display' => $this->display,
            'displayChildren' => $this->displayChildren,
        );

        // export the children as well, unless explicitly disabled
        if (0 !== $depth) {
            $childDepth = (null === $depth) ? null : $depth - 1;
            $array['children'] = array();
            foreach ($this->children as $key => $child) {
                $array['children'][$key] = $child->toArray($childDepth);
            }
        }

        return $array;
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
     * @return mixed
     */
    public function getDisplay()
    {
        return $this->display;
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

    public function __toString()
    {
        return $this->getName();
    }
}
