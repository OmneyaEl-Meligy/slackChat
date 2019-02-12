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
        return  view('createChannel');
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
        return view('chatChannel')->with('errorMsg',$errorMsg)->with('messages',$messages)->with('users',$users)->with('channelId',$channelId);
        
    }
    public function postNewChannel(Request $request){
        $channelName=$request->channelName;
        $response=$this->slackService->postNewChannel($channelName);
        if($response->ok){
            $channelId= $response->channel->id;
            return redirect('/?errorMsg=Created Successfully');//->with('messages',$messages)->with('users',$users);
            
        }
        else {
            $errorMsg="something went wrong please try later";
            if($response->error){
                $errorMsg=$response->error;
            }
            return redirect('/?errorMsg='.$errorMsg);         
        }
    }
    public function postMessage(Request $request){
        $message=$request->message;
        $channelId=$request->channelId;
        $response=$this->slackService->postMessage($channelId,$message);
       if ($response->ok){
            return response()->json($response->message);
       }
       //$error="Error";
        return ['error'=>1, 'errorMsg'=>$response->error];
    }
}
