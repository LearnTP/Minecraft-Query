<?php
/////// TO DO ////////
/////// // Change all functions to $this-><function>!!!
/////// // [See getServerMotd for eg.]
    /*
    FILENAME: MCQ(T).php
    VERSION: Alpha 1.0
    AUTHOR(S): OLIVER SCASE // JAKE TAYLOR
    SHORT DESCRIPTION: GET INFORMATION FROM YOUR SERVER!
    
    
    */
    class query{
        ///////////////////////////////////
        // [][][] SETUP FUNCTIONS [][][] //
        ///////////////////////////////////
        // These are functions that wont be callable by people that downloaded the plugin.
        private $ip = "";
        
        function setIP($ip) {
            $this->ip = $ip;
        }
        function getIP() {
            return $this->ip;
        }
        
        function getMcApi($type){
            // This function gets the information from "mcapi.ca" if required
            
            $api_curl = curl_init("https://mcapi.ca/query/$this->ip/$type"); // Initialise  curl
            curl_setopt_array($api_curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_BINARYTRANSFER => 1)); // set curl options
            $api = curl_exec($api_curl); // execute curl, return information from page
            curl_close($api_curl); // close the curl instance
            return json_decode($api,true); //return the information from 
        }
        
        function getConfig(){
            // This function gets the config file "config.json"
            
            $path = "includes/config.json";
            $config = json_decode(file_get_contents($path), true);
            return $config;
        }
        
        
        //////////////////////////////////////
        // [][][] CALLABLE FUNCTIONS [][][] //
        //////////////////////////////////////
        // These functions are ones that people that download the plugin will use...
        
        function getServerStatus(){
            return $this->getMcApi("info")["status"];
        }
        
        function getPlayerCount(){
            // This function gets the player count and returns as an integer
            
            $info = $this->getMcApi('list');
            $players_online = $info["Players"]["online"];
            $max_players = $info["Players"]["max"];
            
            return "$players_online/$max_players";
        }
        
        function getPlayerList($player_names=true,$player_heads=false,$player_skins=false){
            // Gets a list of players and returns in format of 2d array:
            // $returned_value = [[$player_head,$player_name],...] || for every player on the server
            // if option is set to false: set value as null.
            
            $info = $this->getMcApi('list');
            $config = $this->getConfig();
            
            $no_players_msg = "There are currently no players online.";
            
            $players_list = $info["Players"]["list"];
            $list_of_players = [];
            if (!$players_list){
                $players_list = array($no_players_msg);
            }
            foreach ($players_list as $i) {
                $cur_name = null;
                $cur_head = null;
                $cur_skin = null;
                
                $tag = $config["players"][$i];
                $rank_id = $config["rank_id"][$tag];
                
                if ($player_names) {
                    if (in_array($i,array_keys($config["players"]))) {
                        $cur_name = "<span id='$i' class='username'>$i</span><span id='$i' class='staff-tag $rank_id'>$tag</span>";
                    }
                    else {
                        $cur_name = "<span id='$i' class='member'>$i</span>";
                    }
                }
                if ($player_heads) {
                    $cur_head = "<img src='https://mcapi.ca/avatar/2d/$i/100' alt='$i' class='head'>";
                }
                if ($player_skins) {
                    $cur_skin = "<img src='https://mcapi.ca/skin/2d/$i/100' alt='$i' class='skin'>";
                }
                $push_array = array_filter([$cur_skin,$cur_head,$cur_name]);
                array_push($list_of_players, $push_array);
            }
            return $list_of_players;
        }
        
        function getServerMotd(){
            return "<span class='motd'>".$this->getMcApi('motd')["motd"]."</span>";
        }
        function returnClass() {
            return $this;
        }
    }
        
        
    class buyCraft {
        
        private $secret_key;
        
        function setSecret_Key($secret_key) {
            $this->secret_key = $secret_key;
        }
        function getSecret_Key() {
            return $this->secret_key;
        }
        
        function getMcApi($type){
            // This function gets the information from "mcapi.ca" if required
            
            $api_curl = curl_init("https://mcapi.ca/buycraft/$this->secret_key/$type"); // Initialise  [client url]
    
            curl_setopt_array($api_curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_BINARYTRANSFER => 1)); // set curl options
            $api = curl_exec($api_curl); // execute curl, return information from page
            curl_close($api_curl); // close the curl instance
            return json_decode($api,true);
        }
        
        
        function packageInfo() {
            // Should return information about your BuyCraft packages
            
            return $this->getMcApi('packages')["payload"];
        }
        function topDonators($amount=3) {
            // Should return the top $amount donators on your server...
            
            $info = $this->getMcApi('payments')["payload"];
            
            usort($info, function($a, $b) {
                return intval($a['price']) - intval($b['price']);
            });
            $top_donators = [];
            foreach (range(0,$amount-1) as $i){
                array_push($top_donators, $info[$i]);
            }
            return $top_donators;
        }
    }

//////////////////////////////////////
// [][][] UNTESTED FUNCTIONS [][][] //
//////////////////////////////////////
// These functions should work in theory, but have yet to be tested....

// Well... Look at that! All the functions have been tested! :D
// This means that the file is likely ready to be transferred to MCQ.php!

/*
$test = new query;
$test->setIP("mc.jake.yt");
echo $test->getServerMotd()."<br>";
echo $test->getPlayerCount()."<br>";
echo $test->getServerStatus()."<br>";
var_dump($test->getPlayerList());
*/
?>