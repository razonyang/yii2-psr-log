Yii2 PSR Logger Adapter
=======================

[![Build Status](https://travis-ci.org/razonyang/yii2-psr-log.svg?branch=master)](https://travis-ci.org/razonyang/yii2-psr-log)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/razonyang/yii2-psr-log/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/razonyang/yii2-psr-log/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/razonyang/yii2-psr-log/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/razonyang/yii2-psr-log/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/razonyang/yii2-psr-log.svg)](https://packagist.org/packages/razonyang/yii2-psr-log)
[![Total Downloads](https://img.shields.io/packagist/dt/razonyang/yii2-psr-log.svg)](https://packagist.org/packages/razonyang/yii2-psr-log)
[![LICENSE](https://img.shields.io/github/license/razonyang/yii2-psr-log)](LICENSE)

Installation
------------

```
composer require razonyang/yii2-psr-log
```

Usage
-----

```php
$categoryParam = '__CATEGORY__';

$logger = new \RazonYang\Yii2\Psr\Log\Logger($categoryParam);
$logger->error('hello {name}', [$categoryParam => __METHOD__, 'name' => 'foo']);
// equals to
Yii::error('hello foo', __METHOD__);
```
