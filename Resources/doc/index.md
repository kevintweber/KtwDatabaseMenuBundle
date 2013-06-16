Getting Started With KtwDatabaseMenuBundle
==========================================

## Requirements

* Symfony 2.1+
* KnpMenuBundle
* Doctrine

## Installation

You can install this bundle in 5 easy-ish steps.  Let's get started!

### Step 1: Download KtwDatabaseMenuBundle using Composer

Add KtwDatabaseMenuBundle into your composer.json

``` js
{
    "require": {
        "kevintweber/ktw-database-menu-bundle": "*"
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

In addition to `KnpMenuBundle`, enable this bundle in your AppKernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
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

## Usage

@todo

## Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
# app/config/config.yml
ktw_database_menu:
    menu_item_repository: kevintweber\KtwDatabasemenuBundle\Entity\MenuItem
```
