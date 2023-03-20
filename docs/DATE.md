# php-helpers
An extensive set of PHP helper functions

## Date

### date_to_sql

A function to convert a date to SQL format

**Example #1 Date format dd/mm/YYYYY**
```php
<?php
    $date = '20/03/2023';
    $format = 'd/m/Y';
    $dateSql = Helpers::date_to_sql($date, $format);
    echo $dateSQL; // 2023-03-20
```
**Example #2 Date format dd.mm.YYYYY**
```php
<?php
    $date = '20.03.2023';
    $format = 'd.m.Y';
    $dateSql = Helpers::date_to_sql($date, $format);
    echo $dateSQL; // 2023-03-20
```



Visit the Webmonster community [Webmonster](https://webmonster.tech).

# Acknowledgements
Thanks to : WebplusM, SignedA