<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\SlackServiceProvider;

class ChannelController extends Controller
{
    public function __construct() {
        $this->slackService=new SlackServiceProvider();
    }

    public function createChannel(){
        dd('test');
    }

    public function channelDetails(Request $request){
        $channelId=$request->channelId;
        $errorMsg="";
        $messages=[];
        $users=[];
        $response=$this->slackService->getChannelHistory( $channelId);
        
        if(isset($response->error)){
            $errorMsg=$response->error;
        }else{
           $messages= $response->messages;
           if(!empty($response->users)){
               $users=$response->users;
           }
        }
        return view('chatChannel')->with('errorMsg',$errorMsg)->with('messages',$messages)->with('users',$users);
        
    }
    public function postMessage(Request $request){
        return 'test';
    }
}
