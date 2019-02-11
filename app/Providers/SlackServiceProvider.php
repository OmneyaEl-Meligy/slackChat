<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Requests;
use App\Slack;
use Symfony\Component\HttpFoundation\Session\Session;

class SlackServiceProvider extends ServiceProvider
{
    protected $apiUrl= "https://slack.com/api/";//env();
    protected $headers = array( 'Accept' => 'application/json' );
    protected $client_id="";//env();
    protected $client_secret="";//env();
    protected $redirect_uri="http://slackApp.tst/stackAuthReturn";//env
    protected $slack;
    protected $token;

    public function __construct(){
        $session = new Session();
        $this->token=$session->get('slack')->getAccessToken();
        if($session->get('slack')){
            $this->slack=$session->get('slack');
        }else{
            $this->authenticate();
        }
    }

    public function oAuth($code){
        if(empty($this->slack->getAccessToken())){
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
        }else{        
            return ['error'=>1 , 'message'=>'not authorized'];
        }

    }
    public function authenticate(){
        redirect()->away('https://slack.com/oauth/authorize?scope=channels:read+channels:write&client_id='.$this->client_id.'&redirect_uri='.$this->redirect_uri)->send();
     
    }

    public function getChannels(){
        $methodPath="/channels.list?token=".$this->token;
        $response=$this->makeApiRequest('GET',[],[],$methodPath);           
        // dd($response);
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
        //$data=['channel'=>$channelId];
        $response=$this->makeApiRequest('GET',[],[],$methodPath);  
        if(isset($response->members)){
            foreach($response->members as $member){
                $users[$member->id]=['name'=>$member->name ,'color'=>$member->color];
            }
        }
        return $users;
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
