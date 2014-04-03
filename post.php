<?

# $host includes host and path and filename
   # ex: "myserver.com/this/is/path/to/file.php"
# $query is the POST query data
   # ex: "a=thisstring&number=46&string=thatstring
# $others is any extra headers you want to send
   # ex: "Accept-Encoding: compress, gzip\r\n"
function post($host, $query, $others = ''){

   $path = explode('/',$host);
   $host = $path[0];
   unset($path[0]);
   $path = '/'.(implode('/',$path));
   $post = "POST $path HTTP/1.1\r\nHost: $host\r\nContent-type: application/x-www-form-urlencoded\r\n${others}User-Agent: Mozilla 4.0\r\nContent-length: " . strlen($query) . "\r\nConnection: close\r\n\r\n$query";
   $h = fsockopen($host, 80);
   fwrite($h, $post);
   for($a = 0, $r = ''; !$a; ){
       $b = fread($h,8192);
       $r .= $b;
       $a = ( ($b == '') ? 1 : 0 );
   }
   fclose($h);
   return $r;
}
