# php-helpers
An extensive set of PHP helper functions

## Date

* [Date SQL](DATE.md#date_to_sql-)
* [Date Diff](DATE.md#date_diff-)

### date_to_sql [🔗](DATE.md#date_to_sql-)

A function to convert a date to SQL format

**Example #1 Date format dd/mm/YYYYY**
```php
<?php
    $date = '20/03/2023';
    $dateSql = Helpers::date_to_sql($date);
    echo $dateSQL; // 2023-03-20
```
**Example #2 Date format dd.mm.YYYYY**
```php
<?php
    $date = '20.03.2023';
    $dateSql = Helpers::date_to_sql($date);
    echo $dateSQL; // 2023-03-20
```
**Example #3 Date format dd-mm-YYYYY**
```php
<?php
    $date = '20-03-2023';
    $dateSql = Helpers::date_to_sql($date);
    echo $dateSQL; // 2023-03-20
```
**Example #4 Date format dd/mm/YYYYY**
```php
<?php
    $date = '20/03/2023';
    $dateSql = Helpers::date_to_sql($date);
    echo $dateSQL; // 2023-03-20
```
[Top](#date-)

### date_diff [🔗](DATE.md#date_diff-)
A function to calculate the difference between two dates, in minute(s), hour(s), week(s), month(s)

**Example #1 Date diff minutes**
```php
<?php
    $date1 = '2023-03-19 15:05:00';
    $date2 = '2023-03-19 15:07:30';
    $dateDiff = Helpers::get_date_diff($date1, $date2, 'minutes');
    echo $dateDiff; // 2 minutes
```
**Example #2 Date diff hours**
```php
<?php
    $date1 = '2023-03-19 15:05:00';
    $date2 = '2023-03-19 16:04:30';
    $dateDiff = Helpers::get_date_diff($date1, $date2, 'hours');
    echo $dateDiff; // 59 minutes 30 seconds
```
**Example #3 Date diff days**
```php
<?php
    $date1 = '2023-03-19 15:05:00';
    $date2 = '2023-03-23 17:04:30';
    $dateDiff = Helpers::get_date_diff($date1, $date2, 'days');
    echo $dateDiff; // 4 days
```
**Example #4 Date diff weeks**
```php
<?php
    $date1 = '2023-03-20 15:05:00';
    $date2 = '2023-05-27 17:04:30';
    $dateDiff = Helpers::get_date_diff($date1, $date2, 'weeks');
    echo $dateDiff; // 9 weeks
```
**Example #5 Date diff months**
```php
<?php
    $date1 = '2023-03-20 15:05:00';
    $date2 = '2023-05-27 17:04:30';
    $dateDiff = Helpers::get_date_diff($date1, $date2, 'months');
    echo $dateDiff; // 2 months
```
[Top](#date-)

Visit the Webmonster community [Webmonster](https://webmonster.tech).

### Acknowledgements
Thanks to : WebplusM, SignedA
