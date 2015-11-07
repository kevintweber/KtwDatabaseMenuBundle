Getting Started With KtwDatabaseMenuBundle
==========================================

## Requirements

* Symfony 2.1+ (or Symfony 3.0+)
* KnpMenu 2.0+
* Doctrine 2.2+
* DoctrineBundle 1.2+

## Installation

You can install this bundle in 5 easy steps.  Let's get started!

### Step 1: Download KtwDatabaseMenuBundle using Composer

Add KtwDatabaseMenuBundle into your composer.json

``` js
{
    "require": {
        "kevintweber/ktw-database-menu-bundle": "~0.5"
    }
}
```

Now tell Composer to download the bundle by running the command:

``` bash
$ php composer.phar update kevintweber/ktw-database-menu-bundle
```

Composer will install the bundle and it's dependencies to your project's
`vendor` directory.

### Step 2: Enable the bundle

Enable this bundle in your AppKernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new kevintweber\KtwDatabaseMenuBundle\KtwDatabaseMenuBundle(),
    );
}
```

### Step 3: Update your database schema

``` bash
$ php app/console doctrine:schema:update --force
```

This will add a new table to your database called `ktw_menu_items`.

### Step 4: Clear your cache

``` bash
$ php app/console cache:clear
```

### Step 5: Configure the bundle (optional)

KtwDatabaseMenuBundle will work just fine without any configuration, so
you can skip this step if you want.

All available configuration options are listed here with their default values:

``` yaml
# app/config/config.yml
ktw_database_menu:
    menu_item_entity: kevintweber\KtwDatabasemenuBundle\Entity\MenuItem
```

In case you want to extend the functionality of the MenuItem entity, you
case easily do so with the `menu_item_entity` option.  Just list the
fully qualified class name.  (Don't forget to run doctrine:schema:update.)

## Usage

In your fixtures, you can create menu items just like you would with
`KnpMenuBundle`:

```php
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadTestingData extends ContainerAware implements FixtureInterface
{
    public function load(ObjectManager $objectManager)
    {
        $factory = $this->container->get('ktw_database_menu.factory');

        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->addChild('About Me', array(
                            'route' => 'page_show',
                            'routeParameters' => array('id' => 42)));

        // Another way to add children ...
        $parentMenuItem = $factory->createItem('Parent', array('route' => 'parent_route'));
        $parentMenuItem->addChild('Grandchild', array('route' => 'grandchild_route'));

        $menu->addChild($parentMenuItem);

        $objectManager->persist($menu);
        $objectManager->flush();
    }
}
```

To render the menu, you would use the same procedures as with KnpMenuBundle:

```jinja
{{ knp_menu_render('root') }}
```
