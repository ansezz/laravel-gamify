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

*Note:* It will generate migration for `points`, `badges`, `gamify_groups`, `pointables`, `badgables` tables, you will need to run `composer require doctrine/dbal` in order to support dropping and adding columns.

```bash
php artisan migrate
```

You can publish the config file:
```bash
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

## ⭐️Point 👑

**2.** Next step is to create a point.
> - The point class is option because we save the point in database.
> - You can create a point directly in your database without class.
> - Create the point class if you need to add a check before achieve the point or if you wanna define a dynamic point value.
```bash
php artisan gamify:point PostCreated
```
They will ask you if you wanna create the database badge record.

> class attribute in badges table will take the class with namespace in this case: `App\Gamify\Points\PostCreated`

It will create a Point class named `PostCreated` under `app/Gamify/Points/` folder.

```php
<?php

namespace App\Gamify\Points;

use Ansezz\Gamify\BasePoint;

class PostCreated extends BasePoint
{
       public function __invoke($point, $subject)
       {
           return true;
       }

}
```
in `__invoke` you can add any condition to check if user achieve the point else return `true` , esle we use `config('gamify.point_is_archived')` by default you can change it in you config file `gamify.php`.


### Give point to User

```php
$user = auth()->user();

$point = Point::find(1);

// or you can use facade function
Gamify::achievePoint($point);


// or via HasBadge trait method
$user->achievePoint($point);
```

### Undo a given point

In some cases you would want to undo a given point.

```php
$user = auth()->user();

$point = Point::find(1);

// or you can use facade function
Gamify::undoPoint($point);

// or via HasPoint trait method
$user->undoPoint($point);

```

You can also pass second argument as $event (Boolean) in function `achievePoint & undoPoint ($point, $event)`, default is `true`, to disable sending `PointsChanged` event.

**Pro Tip 👌** You could also hook into the Eloquent model event and give point on `created` event. Similarly, `deleted` event can be used to undo the point.

### Get total reputation

To get the total user points achieved you have `achieved_points` attribute available..

```php
// get integer point
$user->achieved_points; // 20
```

### Get points history

the package stores all the points event log so you can get the history of points via the following relation:

```php
foreach($user->points as $point) {
    // name of the point type 
    $point->name;
    
    // how many points
    $point->point;
}
``` 

### Get badges history

the package stores all the badges in database so you can get the history of badges via the following relation:

```php
foreach($user->badges as $badge) {
    // name of the point type 
    $point->name;
    
    // how many points
    $point->image;
}
``` 


#### Event on points changed

Whenever user point changes it fires `\Ansezz\Gamify\Events\PointsChanged` event which has the following payload:

```php
class PointsChanged implements ShouldBroadcast {
    
    ...
    public function __construct(Model $subject, int $point, bool $increment)
    {
        $this->subject = $subject;
        $this->point = $point;
        $this->increment = $increment;
    }
}
```

This event also broadcast in configured channel name so you can listen to it from your frontend via socket to live update points.

## 🏅 Achievement Badges 🏆

Similar to Point type you have badges. They can be given to users based on rank or any other criteria. You should define badge level in config file.

### Create a Badge

To generate a badge you can run following provided command:

They will ask you if you wanna create the database badge record.

> class attribute in badges table will take the class with namespace in this case: `App\Gamify\Badges\PostCreated`

```bash
php artisan gamify:badge PostCreated
```

It will create a BadgeType class named `PostCreated` under `app/Gamify/Badges/` folder.

For each level you need to define a function by level name to check if the subject is achieve the badge, esle we use `config('gamify.badge_is_archived')` by default you can change it in you config file `gamify.php`.

```php
<?php

namespace App\Gamify\Badges;

use Ansezz\Gamify\BaseBadge;

class PostCreated extends BaseBadge
{

    /**
       * @param $badge
       * @param $subject
       *
       * @return bool
       */
      public function beginner($badge, $subject)
      {
          return $subject->achieved_points >= 100;
      }
  
      /**
       * @param $badge
       * @param $subject
       *
       * @return bool
       */
      public function intermediate($badge, $subject)
      {
          return $subject->achieved_points >= 200;
      }
  
      /**
       * @param $badge
       * @param $subject
       *
       * @return bool
       */
      public function advanced($badge, $subject)
      {
          return $subject->achieved_points >= 300;
      }

}
```

```php
// to reset point back to zero
$user->resetPoint();
```

### Check if badge is Achieved by subject
```php
$badage = Badge::find(1);
$user =  auth()->user();

$badge->isAchieved($user);
```

### Sync All badges
```php
// sync all badges for current subject using Facade
Gamify::syncBadges($user);

// or via HasBadge trait method
$user->syncBadges();
```

### Sync One badge
```php
$badge = Badge::find(1);
// sync all badges for current subject using Facade
Gamify::syncBadge($badge, $user)

// or via HasBadge trait method
$user->syncBadge($badge);
```

#### Event on badge achieved

Whenever user point changes it fires `\Ansezz\Gamify\Events\BadgeAchieved` event which has the following payload:

```php
class BadgeAchieved implements ShouldBroadcast {
    
    ...
    public function __construct($subject, $badge)
    {
        $this->subject = $subject;
        $this->badge = $badge;
    }
}
```
 

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

    // All the levels for badge
    'badge_levels'                 => [
        'beginner'     => 1,
        'intermediate' => 2,
        'advanced'     => 3,
    ],

    // Default level
    'badge_default_level'          => 1,

    // Badge achieved vy default if check function not exit
    'badge_is_archived'            => false,

    // point achieved vy default if check function not exit
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
