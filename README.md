PHP Calendar Library
====================

This repository holds interfaces defining a Calendar, whose purpose is to
manipulate (format, parse) php dates, and a few utilities and very basic
implementations.

Installation
------------

```bash
composer require popy/calendar
```

Usage
-----


```php
<?php

use Popy\Calendar\Calendar\GregorianCalendar;

$calendar = new GregorianCalendar();

echo $calendar->format(new DateTime(), 'Y-m-d');

var_dump(
    $calendar->parse('2000-01-01', 'Y-m-d')
);
?>
```

What's the point ?
------------------

A ```CalendarInterface``` basically reproduce the behaviour of DateTime::format and
DateTime::createFromFormat. the ```Popy\Calendar\Calendar\GregorianCalendar``` is only a decorator
around these methods, so, what's the point ?

First, ```CalendarInterface```, ```FormaterInterface``` and ```ParserInterface``` are implementable
interfaces, removing some responsibilities for classes handling dates. The ```PresetFormater``` and
```PresetParser``` even co a step further by handling themselves the format to use, so the classes
having to deal with dates (parsing or displaying) do no longer have to deal with format or locale.

But this package containes also other components which can be used to compose a ```CalendarInterface``` 
implementation, allowing to :

* Implement a non-gregorian calendar
* Implement another formating syntax
* Extend the actual formating syntax
* Handle cases not supported by the native functions, such as parsing a date using iso8601 week &
    year numbers (already implemented in the package), or parse 5+ digits years (Y10k bug)

Preset Formater
---------------

The preset formater is a helper object taking any formater and a format as
constructor parameter, allowing to be able to format a date without knowing
which format is expected.

Inject it in any service dealing with date representation means they no longer
have the responsibility to choose the format they are using (and not even the
calendar). That's a way to have application-wide date format.

```php
<?php

use Popy\Calendar\PresetFormater;

$formater = new PresetFormater($AnyCalendarOrFormaterImplementation, 'Y-m-d');

echo $formater->format(new DateTime());
?>
```

Preset Parser
---------------

The preset parser is a helper object taking any parse and a format as
constructor parameter, allowing to be able to parse a date without knowing
which format is expected.

Could be used, for instance, by a service hydrating data fetched from a
webservice, without having to know which calendar/format is used.

```php
<?php

use Popy\Calendar\PresetParser;

$formater = new PresetParser($AnyCalendarOrParserImplementation, 'Y-m-d');

var_dump($formater->parse('2017-05-01'));

?>
```

Other components
----------------

* [Converter](doc/converter.md) (outdated)
* Factory (coming soon)
