<?php

/** 
* An experimental simple date library. Basically, it is object wrapper for function strtotime().
* - Working with php 5.2.
* - Date parameters can be timestamp (numeric) or any strtotime date string
* - Czech names of months and days
* Example: print Datex::format("2015-05-05", "d.m.Y");
*/
class Datex {

const DAY = 86400;

protected static $langStrings = array(
  'Monday' => 'Pondìlí', 'Tuesday' => 'Úterı', 'Wednesday' => 'Støeda',
  'Thursday' => 'Ètvrtek', 'Friday' => 'Pátek', 'Saturday' => 'Sobota',
  'Sunday' => 'Nedìle', 'January' => 'Leden', 'February' => 'Únor', 'March' => 'Bøezen',
  'April' => 'Duben', 'May' => 'Kvìten', 'June' => 'Èerven', 'July' => 'Èervenec',
  'August' => 'Srpen', 'September' => 'Záøí', 'October' => 'Øíjen',
  'November' => 'Listopad', 'December' => 'Prosinec',
  );


public static function getTimeStamp($date) {
  return is_numeric($date)? $date : strtotime($date);
}

public static function getWeekRange($date) {
  $tm = self::getTimeStamp($date);
  list($year,$week) = explode(',', date("o,W", $tm));
  return array(
    self::format(strtotime("$year-W$week-1", $tm)),
    self::format(strtotime("$year-W$week-7", $tm) + self::DAY-1),
  );
}

public static function getMonthRange($date) {
  $tm = self::getTimeStamp($date);
  return array(
    self::format($tm, "Y-m-01"),
    self::format($tm, "Y-m-t 23:59:59"),
  );
}

public static function getDayRange($date) {
  $tm = self::getTimeStamp($date);
  return array(
    self::format($tm, "Y-m-d 00:00:00"),
    self::format($tm, "Y-m-d 23:59:59"),
  );
}

public static function countSeconds($range) {
  if (!self::hasTimePart($range[1])) $range[1] .= ' 23:59:59';
  return self::getTimeStamp($range[1]) - self::getTimeStamp($range[0]);
}

public static function countDays($range) {
    //Round? Funguje s DST
  return round(self::countSeconds($range) / self::DAY);
}

public static function isInRange($date, $range) {
  $tmStart = self::getTimeStamp($range[0]);
  $tmEnd = self::getTimeStamp($range[1]);
  $tm = self::getTimeStamp($date);
  return (($tm >= $tmStart) and ($tm <= $tmEnd));
}

public static function format($date, $format = "Y-m-d H:i:s") {
  $tm = self::getTimeStamp($date);
  return strtr(date($format, $tm), self::$langStrings);
}

public static function getTimePart($date) {
  list($dt, $time) = explode(' ', $date);
  return $time;
}

public static function hasTimePart($date) {
  $d = self::getDateArray($date);
  return ($d['seconds'] or $d['minutes'] or $d['hours']);
}

public static function addDays($date, $days, $format = "Y-m-d H:i:s") {
  return self::format(self::getTimeStamp($date)+$days*self::DAY, $format);
}

public static function getDateArray($date) {
  $tm = self::getTimeStamp($date);
  $d = getdate($tm);
  $d['wday'] = ($d['wday'] == 0)? 6:$d['wday']-1;
  $d['week'] = date('W', $tm);
  return $d;
}

//Vrati pole datumu v rozsahu $range po krocich $step
public static function period($range, $step, $format = "Y-m-d") {
  $tm = self::getTimeStamp($range[0]);
  $tm1 = self::getTimeStamp($range[1]);
  $period = array();
  while ($tm < $tm1) {
    $period[] = self::format($tm, $format);
    $tm += $step;
  }
  return $period;
}

public static function now() {
  return date("Y-m-d H:i:s");
}

public static function today() {
  return date("Y-m-d");
}

public static function isToday($date) {
  return (self::format($date, 'Y-m-d') == date('Y-m-d'));
}

} //Datex

?>