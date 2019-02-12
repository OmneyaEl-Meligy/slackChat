<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Requests;
use App\Slack;
use Symfony\Component\HttpFoundation\Session\Session;

class SlackServiceProvider extends ServiceProvider
{
    protected $apiUrl= "https://slack.com/api/";
    protected $headers = array('Accept' => 'application/json' );
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $slack;
    protected $token;

    public function __construct(){ 
        $this->client_id=\Config::get('services.slack.client_id');
        $this->client_secret=\Config::get('services.slack.client_secret');
        $this->redirect_uri=\Config::get('services.slack.redirect_uri');
        $session = new Session();
        $this->token=$session->get('slack')->getAccessToken();
        if($session->get('slack')){
            $this->slack=$session->get('slack');
        }else{
            $this->authenticate();
        }
    }

    public function oAuth($code){
      //  if(empty($this->slack->getAccessToken())){
            $methodPath='oauth.access';
            $options = array( 'auth' => array( $this->client_id, $this->client_secret ) );
            $data = array( 'code' => $code ,'redirect_uri'=>$this->redirect_uri);
            $response=$this->makeApiRequest('POST',$options,$data,$methodPath);
            if($response->ok){
                $this->slack->setAccessToken($response->access_token);
                $this->slack->setUserId($response->user_id);
                $this->slack->setTeamName($response->team_name);
                $this->slack->setTeamId($response->team_id);
                return $this->slack;
            }else{
                return ['error'=>1 , 'message'=>'not authorized'];
            }
        // }else{        
        //     return ['error'=>1 , 'message'=>'not authorized'];
        // }

    }
    public function authenticate(){
        redirect()->away('https://slack.com/oauth/authorize?scope=channels:read+channels:write+channels:history+chat:write:user+users:read+incoming-webhook&client_id='.$this->client_id.'&redirect_uri='.$this->redirect_uri)->send();

    }

    public function getChannels(){//$this->authenticate();
        $methodPath="/channels.list?token=".$this->token;
        $response=$this->makeApiRequest('GET',[],[],$methodPath);   
        if($response->ok){
            return $response->channels;
        }else{
            $this->authenticate();
        }
    }
    public function getChannelHistory($channelId){
        $methodPath="/channels.history?token=".$this->token."&channel=".$channelId;
        $response=$this->makeApiRequest('GET',[],[],$methodPath); 
        if($response->ok){
            $users=$this->getUsers();
            $response->users= $users;
        }
            return $response;
    }
    public function getUsers(){
        $methodPath="/users.list?token=".$this->token;
        $users=[];
        $response=$this->makeApiRequest('GET',[],[],$methodPath);  
        if(isset($response->members)){
            foreach($response->members as $member){
                $users[$member->id]=['name'=>$member->name ,'color'=>$member->color];
            }
        }
        return $users;
    }

    public function postNewChannel($channelName){
        $methodPath="/channels.create?token=".$this->token;
        $data = array( 'name' => $channelName );
        $response=$this->makeApiRequest('POST',[],$data,$methodPath); 
        if($response->ok){
            $users=$this->getUsers();
            $response->users= $users;
        }
            return $response;
      
    }
    public function  postMessage( $channelId,$message){
        $methodPath="/chat.postMessage?token=".$this->token;
        $data = array( 'channel' => $channelId ,'text' => $message );
        $response=$this->makeApiRequest('POST',[],$data,$methodPath); 
        return $response;  
    }
  
    public function makeApiRequest($requestType,$options,$data,$methodPath){

        if($requestType=="GET"){
            $response = Requests::get( $this->apiUrl  . $methodPath, $this->headers, $data, $options );

        }else{
            $response = Requests::post( $this->apiUrl  . $methodPath, $this->headers, $data, $options );
           }       
        $json_response = json_decode( $response->body );
        return $json_response;
    }
}
