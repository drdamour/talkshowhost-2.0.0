<?php

////////////////////////////////////////////////////
//                                                //
// WhatPulse Account Data Retrieval Class.        //
//                                                //
// Author: Dean Reilly                            //
// URL:    http://www.datr.net/                   //
// E-mail: datr@datr.net                          //
// Date:   09/12/04 8:45PM                        //
//                                                //
// ---------------------------------------------- //
// Note:   This class is based on the original    //
//         class by Sean McCullough, Toydrum,     //
//         Inc.                                   //
//                                                //
//         URL:    http://www.toydrum.com/        //
//         E-Mail: sean@toydrum.com               //
// ---------------------------------------------- //
//                                                //
// whatpulse.inc.php                              //
//                                                //
////////////////////////////////////////////////////

class whatpulse {

   var $user;
   var $cache;
   var $expire;
   var $basedir;

   var $status;

   var $AccountName;
   var $Country;
   var $DateJoined;
   var $Homepage;
   var $LastPulse;
   var $Pulses;
   var $TotalKeyCount;
   var $TotalMouseClicks;
   var $AvKeysPerPulse;
   var $AvClicksPerPulse;
   var $AvKPS;
   var $AvCPS;
   var $Rank;
   var $TeamID;
   var $TeamName;
   var $TeamMembers;
   var $TeamKeys;
   var $TeamClicks;
   var $TeamDescription;
   var $TeamDateFormed;
   var $RankInTeam;

   var $data;

   function whatpulse( $user, $expire = 3600 ) {
      $this->user = $user;
      $this->expire = $expire;
      $this->basedir = "./"; // change this if this script isn't in the same directory as the one that uses it.

      $this->getcache();

      $this->parsecache();
   }

   function getcache() {
      $cachefile = $this->basedir . "cache/" . $this->user;
      clearstatcache();

      if ( ! file_exists($cachefile) or filesize($cachefile) == 0 or (time() - filemtime($cachefile)) > $this->expire ) {
         $user = $this->user;
         $fp = fopen("http://whatpulse.org/api/user.php?account=$user", "r");
         $cache = fread($fp, 4096);
         fclose ($fp);

         $handle = fopen($cachefile, 'w');
         fwrite($handle, $cache);
         fclose($handle);
      } else {
         $handle = fopen($cachefile, "r");
         $cache = fread($handle, filesize($cachefile));
         fclose($handle);
      }

      $this->cache = $cache;
   }

   function parsecache() {

      $cache = $this->cache;

      if( strstr($cache, "! Invalid AccountName.") ) {
         $this->status = false;
      } else {
         $this->status = true;
      }
      
      $cache = explode("\r\n", $cache);

      foreach($cache as $line) {

         if(!ereg("^(\#|\X)", $line) && $line != "") {

            $data = explode(" ", $line, 3);

            $key = $data[1];

            $value = trim($data[2]);

            $this->$data[1] = $value;

            $data_array[$key] = $value;

         }
      }

      $this->data = $data_array;
   }

   function refresh() {
      $expire = $this->expire;
      $this->expire = "0";

      $this->getcache();

      $this->parsecache();

      $this->expire = $expire;      
   }
}
