<?php

namespace App;


class Slack
{
   
    private $access_token;
    // private $client_id;
    // private $client_secret;
    protected $scope ;
    protected $user_id ;
    protected $team_id ;
    protected $team_name;
    // protected $apiUrl;
    // protected $headers;

    public function __construct(){
        // $this->apiUrl= "https://slack.com/api/";//env();
        // $this->headers = array( 'Accept' => 'application/json' );
        // $this->client_id="543858464513.544979783586";
        // $this->client_secret="9637423efac5659ff456ed741e304967";
    }
  
    public function getAccessToken(){
        return $this->access_token;
    }

    public function setAccessToken($access_token){
        $this->access_token=$access_token;
    }

    public function getUserId(){
        return $this->user_id;
    }

    public function setUserId($user_id){
        $this->user_id=$user_id;
    }

    public function getTeamName(){
        return $this->team_name;
    }

    public function setTeamName($team_name){
        $this->team_name=$team_name;
    }

    public function getTeamId(){
        return $this->team_id;
    }

    public function setTeamId($team_id){
        $this->team_id=$team_id;
    }

}
