<?php
    
/* Establishing connection*/
    //Set variable to new query
    $myQuery = new query;
    //Set your ip
    $myQuery->setIP("my.server.ip");

/* Check connection. getServerStatus() returns a boolean; 'true' if a connection has been made, 'false' if not. */
    if($myQuery->getServerStatus()){
        //What to do if connection is present
    }
    else{
        //What to do if no connection
    }

/* Outputting Information */
    //Player Count (returns in '{current}/{max}' form).
    echo $myQuery->getPlayerCount();

    //Server MOTD
    echo $myQuery->getServerMotd();

    //Online Players
    /*This is the core function for listing online players */
    $myQuery->getPlayerList(true, true, false);
    /* Howerver, getPlayerList() returns many values, so you have to write an extra function to output that data to a webpage. Here is an example function, which you can use if you want */
    //Custom Function, with several paramaters
    function getPlayers($connection,$info=array("player_names"=>true, "player_heads"=>true),$max=20){
        $output = "";
        $count = 0;
        foreach(connection->getPlayerList($info["player_names"], $info["player_heads"]) as $i){
            if($count > $max) {
                break;
            }
            $output .= join("", $i) . "<br>";
            $count += 1;
        }
        $output = substr($output,0,-4);
        
        return $output;
    }
    //Calling getPlayers() function
    echo getPlayers($myQuery->returnClass());
    
    /* This example outputs player heads and names in a list */
?>