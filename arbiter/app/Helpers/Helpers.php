<?php

if (!function_exists('number_shorten')) {
    // Shortens a number and attaches K, M, B, etc. accordingly
    function number_shorten($number, $precision = 3, $divisors = null) {

      // Setup default $divisors if not provided
      if (!isset($divisors)) {
          $divisors = array(
              pow(1000, 0) => '', // 1000^0 == 1
              pow(1000, 1) => 'k', // Thousand
              pow(1000, 2) => 'm', // Million
              pow(1000, 3) => 'b', // Billion
              pow(1000, 4) => 'y', // Trillion
              pow(1000, 5) => 'qa', // Quadrillion
              pow(1000, 6) => 'qi', // Quintillion
          );
      }

      // Loop through each $divisor and find the
      // lowest amount that matches
      foreach ($divisors as $divisor => $shorthand) {
          if (abs($number) < ($divisor * 1000)) {
              // We found a match!
              break;
          }
      }

      // We found our match, or there were no matches.
      // Either way, use the last defined value for $divisor.
      if($number > 999) {
          return number_format($number / $divisor, $precision) . $shorthand;
      } else {
          return (int) $number;
      }
    }
}

if (!function_exists('short2num')) {
    // Shortens a number and attaches K, M, B, etc. accordingly
    function short2num($number) {

        $number = str_replace(",", "", $number);

        if(substr($number, -1) =='m') {
            $num = substr($number, 0, -1) * 1000000;
        } elseif( substr($number, -1) == 'k') {
            $num = substr($number, 0, -1) * 1000;
        } elseif(!is_numeric($number)) {
            return "Can't parse amount.";
        } else {
            return (int) $number;
        }

        return $num;
    }
}

if (!function_exists('array_orderby')) {

    function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
                }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}
