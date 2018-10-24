# psnwn
**Pascal Simple Neverwinter Nights server scan**

This is the code used for years on www.iscandar.ch

![logo](http://www.iscandar.ch/nwn1.png)

The main difference between this script and all the other PHP scripts found everywhere, is that this code contains a second backup scan that works even if no GameSpy fixes have been enabled on the NWN server side. More info: http://www.neverwinternights.info/builders_hosts.htm


HOW TO USE:
===========

1) Download server_check.php

2) Set up and declare ``$ipaddr`` and ``$port``, then include this file/script
 
Example:

    $ipaddr = "127.0.0.1";
    $port = "5121";
    $timeout = 5;
    include "server_check.php";

     if ($nwn_online) {
        echo "Server online!";
        echo "Server name: $nwn_server";
        echo "Players Online: $nwn_players";
     } else {
        echo "Server offline!";
     }
     
Useful supported variables to include in your code are:

``$nwn_online``     If this is TRUE then server can be considered online and responding.

``$nwn_country``    Country name of the server by geoIP (needs to be manually turned on)

``$nwn_server``     Server's name

``$nwn_ver``        Server's version (supports Neverwinter Nights 1 & 2)

``$nwn_module``     Server's module name (usually the .mod filename)

``$nwn_desc``       Server's description

``$nwn_players``    Players logged in and players limit (ex. 10/30)

``$nwn_players_online`` Number of players online

``$nwn_players_max``    Max limit of players allowed to join

``$nwn_type``       Play type

``$nwn_pvp``        PvP on/off

``$nwn_category``   Game gategory/type

``$nwn_levels``     Levels range

``$nwn_vault``      Characters Vault

``$nwn_party``      Party Type

``$nwn_pause``      Players Pause

``$nwn_ilr``        ILR

``$nwn_legalchar``  Legal Characters

``$nwn_pwd``        Password required


**Be advised:**

There's **no caching** of server responses, please consider save the results yourself to not hammer the servers too much.

*Part of this code has been created by the legacy NWN community in 2004 and ported by Eriniel's C code.*
