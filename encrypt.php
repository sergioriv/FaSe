<?php

function encrypt($string) {
   $key = "M4nt1Zt3CHn0L0GY";
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   $return = base64_encode($result);
   return str_replace("=", "_", $return);
}

function decrypt($string) {
   $key = "M4nt1Zt3CHn0L0GY";
   $string = str_replace("_", "=", $string);
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}

?>