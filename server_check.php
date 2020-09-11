<?php

/*

HOW TO USE:

set up and declare $ipaddr and $port, then include this file/script
 
ex. $ipaddr = "127.0.0.1";
    $port = "5121";
    @include $server_check.php
    if ($nwn_online) {
       echo "Server online!" 
       echo "Server name: $nwn_server";
    }

usable variables to include in your code are:

    $nwn_online     If this is TRUE then server can be considered online and responding.
    $nwn_country    Country name of the server by geoIP.
    $nwn_server     Server's name
    $nwn_module     Server's module name (usually the .mod filename)
    $nwn_desc       Server's description
    $nwn_type       Play type
    $nwn_pvp        PvP on/off
    $nwn_category   Game gategory/type
    $nwn_levels     Levels range
    $nwn_ver        Server's version (supports Neverwinter Nights 1 & 2)
    $nwn_vault      Characters Vault
    $nwn_party      Party Type
    $nwn_pause      Players Pause
    $nwn_ilr        ILR
    $nwn_legalchar  Legal Characters
    $nwn_pwd        Password required


*/

// set a default timeout if not set
if (!$timeout) {
	$timeout = 3; // seconds allowed for the scan to finish
}

// prevent futile SQL injections attempts
if (!is_valid_domain_name($ipaddr)) { $ipaddr = $_SERVER["REMOTE_ADDR"]; }
if (!is_numeric($port)) { $port = $port +0; }

// gamespy method

$connect = fsockopen( "udp://" . $ipaddr, $port, $errno, $errstr, $timeout );
socket_set_timeout( $connect, $timeout );
$send = "\xFE\xFD\x00\xE0\xEB\x2D\x0E\x14\x01\x0B\x01\x05\x08\x0A\x33\x34\x35\x13\x04\x36\x37\x38\x39\x14\x3A\x3B\x3C\x3D\x00\x00";
//$send = "\xFE\xFD\x00\x43\x4F\x52\x59\xFF\xFF\x00";
fwrite( $connect, $send );
$output = fread( $connect, 5000 );
fclose($connect);
  
  if ($output) {
	// Server is online!
    $nwn_online = TRUE;
    //$nwn_country = geoip_country_name_by_name ( $ipaddr ); // if you have GeoIP extension, you can uncomment this line.

    $lines = explode( "\x00", $output );

    $nwn_type = $lines[2];
    $nwn_module = str_replace("_"," ",$lines[4]);
    $nwn_server = $lines[3];

	if ( ! $nwn_server ) {
		$nwn_server = $nwn_module;
        }
        
    $nwn_players = $lines[5]."/".$lines[6];
    $nwn_players_online = intval($lines[5]);
    $nwn_players_max = intval($lines[6]);
        
    $nwn_desc_text = $lines[15];
    $nwn_desc_pre = explode( "\n", $nwn_desc_text );
    foreach ( $nwn_desc_pre as $dline ) {
		$nwn_desc .= $dline."<br>\n";
        }
        
    $nwn_category_id = $lines[16];      
    if ( $nwn_category_id == "274" ) {
		$nwn_category =  "Action" ;
        }
    elseif ( $nwn_category_id == "363" ) {
        $nwn_category =  "Story" ;
        }
    elseif ( $nwn_category_id == "364" ) {
        $nwn_category =  "Story Lite" ;
        }
    elseif ( $nwn_category_id == "275" or $nwn_category_id == "1871" ) {
        $nwn_category =  "Role Play" ;
        }
    elseif ( $nwn_category_id == "276" ) {
        $nwn_category =  "Team Play" ;
        }
    elseif ( $nwn_category_id == "365" ) {
        $nwn_category =  "Melee" ;
        }
    elseif ( $nwn_category_id == "366" ) {
        $nwn_category =  "Arena" ;
        }
    elseif ( $nwn_category_id == "277" ) {
        $nwn_category =  "Social" ;
        }
    elseif ( $nwn_category_id == "279" ) {
        $nwn_category =  "Alternative" ;
        }
    elseif ( $nwn_category_id == "278" ) {
        $nwn_category =  "PW Action" ;
        }
    elseif ( $nwn_category_id == "367" ) {
        $nwn_category =  "PW Story" ;
        }
    else {
        $nwn_category = "Generic";
        }
        
    $nwn_levels = $lines[7] . "-" . $lines[8];
        
    $nwn_pvp = ucwords(strtolower($lines[9]));
        
    if ( $lines[18] == '1' ) {
        $nwn_ilr = "Enabled";
        }
    else {
        $nwn_ilr = "Disabled";
        }
        
    if ( $lines[10] == '0' ) {
        $nwn_pwd = "Not required";
        }
    else {
        $nwn_pwd = "Required";
        }
        
    if ( $lines[14] == '8109' ) {
		$nwn_ver = "NWN1";
        if ( $lines[20] == '1' ) {
			$nwn_ver .= "+SoU";
			}
        elseif ( $lines[20] == '2' ) {
			$nwn_ver .= "+HotU";
			}
        elseif ( $lines[20] == '3' ) {
			$nwn_ver .= "+SoU+HotU";
			}
        }
    elseif ( $lines[14] == '1765' ) {
        $nwn_ver = "NWN2";
        }
    else {
        $nwn_ver = "NWN?";
        }
        
        
    if ( $lines[19] == '1' ) {
        $nwn_vault = "Local";
        }
    else {
        $nwn_vault = "Server";
        }
        
    if ( $lines[12] == '1' ) {
        $nwn_party = "Single Party";
        }
    else {
        $nwn_party = "Multiple parties";
        }
        
    if ( $lines[13] == '1' ) {
        $nwn_pause = "Players can pause";
        }
    else {
        $nwn_pause = "No player pause";
        }
        
    if ( $lines[17] == '1' ) {
        $nwn_legalchar = "Enforced";
        }
    else {
        $nwn_legalchar = "Not enforced";
        }
        
	}
    



// eriniel method (only if gamespy fails)

if (!$nwn_online) {
	// try to connect and get some data
	$connect = fsockopen( "udp://" . $ipaddr, $port, $errno, $errstr, $timeout );
	socket_set_timeout( $connect, $timeout );
	
	// BNES
	$send = "\x42\x4e\x45\x53\x00\x14\x00";
	fwrite( $connect, $send );
	$output = fread( $connect, 500 );
	
    $erin_server = substr(bin2hex($output),18,100);
	
	// BNXI
	$send = "\x42\x4e\x58\x49\x00\x14\x00";
	fwrite( $connect, $send );
	$output = fread( $connect, 500 );
  
    if (substr(bin2hex($output),13,1) == "c") {
        $nwn_ver = "NWN2"; // c
        } else {
        $nwn_ver = "NWN1"; // d
    }
    
	$erin_players = substr(bin2hex($output),20,2);
	$erin_players_max = substr(bin2hex($output),22,2);
	$erin_pvp = substr(bin2hex($output),26,2);
	$erin_mod = substr(bin2hex($output),40,100);
	
    /*switch (substr(bin2hex($output),37,1)) {
    case 0:
      break;
    case 2:
      $nwn_ver .= "+HotU";
      break;
    case 3:
      $nwn_ver .= "+SoU+HotU";
      break;
      }*/

	if ($output) {
		// server replied, save stuff and set online=1
		$nwn_server = hex2str($erin_server);
		$nwn_module = str_replace("_"," ",hex2str($erin_mod));
		if (!$nwn_server) $nwn_server = $nwn_module;
		
		$nwn_players_online = hexdec($erin_players);
		$nwn_players_max = hexdec($erin_players_max);
		$nwn_players = $nwn_players_online."/".$nwn_players_max;
		
		switch ($erin_pvp) {
        case 0:
        $nwn_pvp = "None";
        break;
         case 1:
        $nwn_pvp = "Party";
        break;
         case 2:
        $nwn_pvp = "Full PvP";
        break;
        }
		
		$nwn_desc = "This server is not enabled with SkyWing's Gamespy replacement server, 
		more info here: http://www.neverwinternights.info/builders_hosts.htm";
		$nwn_category = "Unknown"; $nwn_category_id = 0;
		
		$nwn_online = TRUE;
	        //$nwn_country = geoip_country_name_by_name ( $ipaddr ); // if you have GeoIP extension, you can uncomment this line.
		}
	
	}

    $nwn_check = date("d/m/y : H:i:s", time());
    $nwn_self_referer = get_headers("http://mira.braile.ch/nwncheck=".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

function hex2str($hex) {
    $str = '';
    for($i=0;$i<strlen($hex);$i+=2) $str .= chr(hexdec(substr($hex,$i,2)));
    return $str;
}

function is_valid_domain_name($domain_name)
{
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
}

?>
