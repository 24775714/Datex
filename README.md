# Datex
Simple date library for php 5.2

 This is an experimental simple date library.
 Consider it as alpha-stage, where many functions are missing and existing functions can be changed.

 It is different from php DateTime etc. objects, because it does not own any date value - 
 it just works with dates represented as string or integers (timestamp). Datex has only static functions.
 Basically, it is object wrapper for function strtotime(), date() and other functions.

 It was created for working with date in php 5.2 for my calendar application, but maybe can be useful even
 for newer php.

### Features


* Working with php 5.2.
* Date parameters can be timestamp (numeric) or any string which can be parsed by strtotime()
* Czech names of months and days

### Examples

```php
print Datex::format("2015-05-05", "d.m.Y"); // 05.05.2015
print Datex::addDays("2015-05-05", 30); //2015-06-04 00:00:00
print Datex::countDays( array("2015-05-05", "2015-06-01") ); //28
```