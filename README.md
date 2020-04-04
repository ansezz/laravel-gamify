## 🏆 Laravel Gamify  🕹
Laravel Gamify: Gamification System with Points & Badges support

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ansezz/laravel-gamify.svg)](https://packagist.org/packages/ansezz/laravel-gamify)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ansezz/laravel-gamify/master.svg)](https://travis-ci.org/ansezz/laravel-gamify)
[![Total Downloads](https://img.shields.io/packagist/dt/ansezz/laravel-gamify.svg)](https://packagist.org/packages/ansezz/laravel-gamify)

[![Latest Version on Packagist](https://repository-images.githubusercontent.com/245541946/0a641100-742a-11ea-8362-41f2d6d52974)](https://packagist.org/packages/ansezz/laravel-gamify)


Use `ansezz/laravel-gamify` to quickly add point &amp; badges in your Laravel app.

### Installation

**1** - You can install the package via composer:

```bash
$ composer require ansezz/laravel-gamify
```

**2** - If you are installing on Laravel 5.4 or lower you will be needed to manually register Service Provider by adding it in `config/app.php` providers array.

```php
'providers' => [
    //...
    Ansezz\Gamify\GamifyServiceProvider::class
]
```

In Laravel 5.5 and above the service provider automatically.

**3** - Now publish the migration for gamify tables:

```
php artisan vendor:publish --provider="Ansezz\Gamify\GamifyServiceProvider" --tag="migrations"
```

*Note:* It will generate migration for `reputations`, `badges` and `user_badges` tables along with add reputation field migration for `users` table to store the points, you will need to run `composer require doctrine/dbal` in order to support dropping and adding columns.

```
php artisan migrate
```

You can publish the config file:
```
php artisan vendor:publish --provider="Ansezz\Gamify\GamifyServiceProvider" --tag="config"
```

If your payee (model who will be getting the points) model is `App\User` then you don't have to change anything in `config/gamify.php`.

### Getting Started

**1.** After package installation now add the **Gamify** trait on `App\User` model or any model who acts as **user** in your app.

```php
use Ansezz\Gamify\Gamify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Gamify;
```

## ⭐️ Reputation Point 👑

**2.** Next step is to create a point.

```bash
php artisan gamify:point PostCreated
```

It will create a Point class named `PostCreated` under `app/Gamify/Points/` folder.

```php
<?php

namespace App\Gamify\Points;

use Ansezz\Gamify\BasePoint;

class PostCreated extends BasePoint
{

    public function __invoke($badge, $subject)
    {
        return $subject->point_sum >= 100;
    }

}
```

### Give point to User

Now in your Controller where a Post is created you can give points like this:

```php
$user = $request->user();
$post = $user->posts()->create($request->only(['title', 'body']));

// you can use helper function
givePoint(new PostCreated($post));

// or via HasReputation trait method
$user->givePoint(new PostCreated($post));
```

### Undo a given point

In some cases you would want to undo a given point, for example, a user deletes his post.

```php
// via helper function
undoPoint(new PostCreated($post));
$post->delete();

// or via HasReputation trait method
$user->undoPoint(new PostCreated($post));
$post->delete();
```

You can also pass second argument as $user in helper function `givePoint(new PostCreated($post, $user))`, default is auth()->user().

**Pro Tip 👌** You could also hook into the Eloquent model event and give point on `created` event. Similarly, `deleted` event can be used to undo the point.

### Get total reputation

To get the total user reputation you have `$user->getPoints($formatted = false)` method available. Optioally you can pass `$formatted = true` to get reputation as 1K+, 2K+ etc.

```php
// get integer point
$user->point_sum; // 20
```

### Get points history

Since package stores all the points event log so you can get the history of reputation via the following relation:

```php
foreach($user->points as $point) {
    // name of the point type 
    $point->name
    
    // how many points
    $point->point
}
``` 

#### Point qualifier

This is an optional method which returns boolean if its true then this point will be given else it will be ignored. 
It's will be helpful if you want to determine the qualification for point dynamically.


#### Event on reputation changed

Whenever user point changes it fires `\Ansezz\Gamify\Events\PointsChanged` event which has the following payload:

```php
class PointsChanged implements ShouldBroadcast {
    
    ...
    public function __construct(Model $user, int $point)
    {
        $this->user = $user;
        $this->point = $point;
    }
}
```

This event also broadcast in configured channel name so you can listen to it from your frontend via socket to live update reputation points.

## 🏅 Achievement Badges 🏆

Similar to Point type you have badges. They can be given to users based on rank or any other criteria. You should define badge level in database.

### Create a Badge

To generate a badge you can run following provided command:

```bash
php artisan gamify:badge FirstContribution
```

It will create a BadgeType class named `FirstContribution` under `app/Gamify/Badges/` folder.

```php
<?php

namespace App\Gamify\Badges;

use Ansezz\Gamify\BaseBadge;

class FirstContribution extends BaseBadge
{

    public function __invoke($badge, $subject)
    {
        return $subject->point_sum >= 100;
    }

}
```

```php
// to reset point back to zero
$user->resetPoint();
```

You dont need to generate point class for this.  

### Config Gamify

```php
<?php

return [
    // Reputation model
    'point_model'                  => '\Ansezz\Gamify\Point',

    // Broadcast on private channel
    'broadcast_on_private_channel' => true,

    // Channel name prefix, user id will be suffixed
    'channel_name'                 => 'user.reputation.',

    // Badge model
    'badge_model'                  => '\Ansezz\Gamify\Badge',

    // Where all badges icon stored
    'badge_icon_folder'            => 'images/badges/',

    // Extention of badge icons
    'badge_icon_extension'         => '.svg',
    'badge_is_archived'            => false,
    'point_is_archived'            => true,
];

```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Testing

The package contains some integration/smoke tests, set up with Orchestra. The tests can be run via phpunit.

```bash
$ composer test
```

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ansezzouaine@gmail.com instead of using the issue tracker.

### Credits

- [Anass Ez-zouaine](https://github.com/ansezz) (Author)


### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
