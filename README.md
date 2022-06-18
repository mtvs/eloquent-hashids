> Using hashids instead of integer ids in urls and list items can be more
appealing and clever. For more information visit [hashids.org](https://hashids.org/).

# Eloquent-Hashids ![Build Status](https://github.com/mtvs/eloquent-hashids/actions/workflows/build.yml/badge.svg)

This adds hashids to Laravel Eloquent models by encoding/decoding them on the fly
rather than persisting them in the database. So no need for another database column
and also higher performance by using primary keys in queries.

Features include:

* Generating hashids for models
* Resloving hashids to models
* Ability to customize hashid settings for each model
* Route binding with hashids (optional)

## Installation

Install the package with Composer:

```sh

$ composer require mtvs/eloquent-hashids

```

Also, publish the vendor config files to your application (necessary for the dependencies):

```sh
$ php artisan vendor:publish
```

## Setup

Base features are provided by using `HasHashid` trait then route binding with
hashids can be added by using `HashidRouting`.

```php

use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

Class Item extends Model
{
	use HasHashid, HashidRouting;
}

```

### Custom Hashid Settings

It's possible to customize hashids settings for each model by overwriting
`getHashidsConnection()`. It must return the name of a connection of 
[`vinkla/hashids`](https://github.com/vinkla/laravel-hashids) that provides
the desired settings.

## Usage

### Basics

```php

// Generating the model hashid based on its key
$item->hashid();

// Equivalent to the above but with the attribute style
$item->hashid;

// Finding a model based on the provided hashid or
// returning null on failure
Item::findByHashid($hashid);

// Finding a model based on the provided hashid or
// throwing a ModelNotFoundException on failure
Item::findByHashidOrFail($hashid);

// Decoding a hashid to its equivalent id 
$item->hashidToId($hashid);

// Encoding an id to its equivalent hashid
$item->idToHashid($id);

// Getting the name of the hashid connection
$item->getHashidsConnection();

```

### Add the hashid to the serialized model

Set it as default:

```php

use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;

class Item extends Model
{
    use HasHashid;
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['hashid'];
}

```

or specify it specificly:

`return $item->append('hashid')->toJson();`


### Route Binding

When `HashidRouting` trait is used, base `getRouteKey()` and `resolveRouteBinding()`
are overwritten to use hashids as route keys.
