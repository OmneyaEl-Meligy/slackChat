<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Slack;
use Redirect;
use App\Providers\SlackServiceProvider;
use  Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $session;
    protected $slack;
    protected $slackService;
    protected $isSlackAuth;

    public function __construct() {
        $this->session = new Session();

        if(!isset($this->slack)){
            $this->slack=new Slack();           
        }
        if($this->session->get('slack')){
            $this->slack=$this->session->get('slack');
        }else{
            $this->session->set('slack', $this->slack);
        }
        $this->slackService=new SlackServiceProvider();
    }
    public function index(Request $request) {
      
        $channels=[];
        $errorMsg='';
        if($request->error==1){
            $errorMsg="can't connect to Slack please try again later";
        }else{
            if($this->session->get('slack')->getAccessToken()){
                $channels=$this->slackService->getChannels();
            }else{
                $this->slackService->authenticate();
            }
        }      
        return view('welcome')->with('channels',$channels)->with('errorMsg',$errorMsg);            
      
    }

    public function stackAuthReturn() {
        $error=0;
        if(isset($_GET['code'])){
            $slack=$this->slackService->oAuth($_GET['code']);
            if ($slack instanceof Slack){
                $this->slack=$slack;
                $this->session->set('slack', $this->slack);
            }else{
                $error=1;
            }
        }else {
            $error=1;
        }
        return Redirect::route('index')->with('error', $error);
    }



}
