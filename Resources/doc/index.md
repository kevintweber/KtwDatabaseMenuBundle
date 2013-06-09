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

Enable the bundle in your AppKernel:

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
### Step 3: Configure the bundle

@todo

### Step 4: Update your database schema

@todo

### Step 5: Clear your cache

``` bash
$ php app/console cache:clear
```

## Usage

@todo
