Settings Package for Laravel 4
==============================

*** WIP ***

I borrowed ideas from several other config/preference/settings packages that exist for L4 that are
already out there, so this isn't really anything special and it is pretty specific to my needs.

- Store your config in the database
- Can still retrieve regular config items using the Settings::get() method as well
- Can overwrite config values using the ones that you've stored in the db.
- Other features. I hate doing readmes and it's mostly so I can remember.

###Using with L4

Start by adding the following line to your composer.php

```
'dberry37388/settings' => 'dev-master'
```

Open up your app/config/app.php file and add the following:

```
// under service providers add:
'Dberry37388\Settings\SettingsServiceProvider'

// under aliases add:
'Settings' => 'Dberry37388\Settings\Facades\SettingsFacade',

Now open up the command line and run:

```
php artisan migrate --package=dberry37388/settings
```

Now we're cooking with peanut oil... You should be all installed and ready to go now.


###Using the Settings Package
The best way to see what is going on is to open up the code and take a gander, but here is a really
quick cheat sheet.

## Retrieving a Setting

```
// no namespace
Settings::get("site.name");


// grouped with namespace
Settings::get('site::settings.name');


// you can also retrieve anything that you would using Config::get()
// for example if I wanted the app.timezone set in app/config/app.php
Settings::get('app.timezone');

```

That's all pretty simple :) Now on to setting "settings", that is a mouthful

## Setting Settings

// no namespace
Settings::set('site.name', 'My Site Name');


// grouped with namespace
Settings::set('site::settings.name', 'My Site Name');


// you can use standard convention to overwrite any config vars also.
// using our app.timezone example
//
// Note that after setting this, Config::get('app.timezone') will now
// be your new value. This does not overwrite the config file, but it's loaded into memory.

Settings::set('app.timezone', 'America/Chicago');


### Forgetting (Removing)

You guessed it, to forget or delete a setting just use:

```
Settings::forget('my.key.here');


That's it for now. Have fun and enjoy!

