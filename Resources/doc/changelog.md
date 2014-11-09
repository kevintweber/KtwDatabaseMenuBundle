Changelog
=========

### 0.4 (2014-xx-xx)

* Modified stability of KnpMenu.  Now at ~2.0. Woot!
* Added __toString method to MenuItem entity.  (Merge from resilva)

### 0.3.2 (2014-06-21)

* Removed an unnecessary dependency from composer.json.
* KnpMenu was tag with a beta version we can use.  Modified stability of
  KnpMenu to beta.  (Hopefully, 2.0 will be released soon.)
* Updated documentation.
* Silex routing extension was deprecated.  Upgraded to the symfony routing
  extension.  (Merge from tubssp)

### 0.3.1 (2013-12-03)

* Removed some constraints in the composer.json requirements.

### 0.3 - Oct 26, 2013

* Added MenuItem repository.  In most circumstances, all menus can be
  generated using only one query now.

### 0.2 (2013-07-07)

* Upgraded to mirror changes in KnpMenu.  Now requires KnpMenu 2.0.*@dev.
  As more stable versions are released, I will change the stability
  requirements.
* [BC break] Renamed configuration option `menu_item_repository` to `menu_item_entity`
* Removed deprecated tests, and added a few simple tests.
* [BC break] Removed (mostly) useless preload option.

### 0.1 (2013-06-22)

* First workable version.
* Added tests.
