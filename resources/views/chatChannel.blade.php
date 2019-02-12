@extends('layout')
@section('content')

<style>
.container{
    padding-bottom: 30px;
}
.message-box {
  background: #91948e;
  /* margin-left: 12%; */
  padding: 40px;
  max-width: 80%;
  margin-bottom: 3px;
  border-radius:10px;
}

.messaging {
  max-width: 70%;
  margin: 10px;
}

.message-text {
  margin-top: 5px;
  padding-bottom: 10px;
}


.message-name {
  
  display: inline-block;
}
</style>
<h2 style="margin:2%">Channel</h2>
<div class="container text-center" >
    <div class="messaging center-block">
        <div class="row">
            <div class="col-md-12">
            <div class="input-group">
                <input type="text" id="msgBox" class="form-control">
                <span class="input-group-btn">
                <button class="btn btn-default" type="button" style="margin=3%" onclick="sendMsg()">Send</button>
                </span>
            </div>
            </div>
        </div>
        </div>
    <div id="channelMsgs">
    @foreach ($messages as $message)
        @if(isset($message->user))
        @if(isset($users[$message->user]))
    
    <div class="message-box center-block" style='background:#{{$users[$message->user]["color"]}}'>
            @else
            <div class="message-box center-block">
            @endif
            @else
            <div class="message-box center-block">
            @endif
        <div class="row">
        <div class="col-xs-8 col-md-6">
            @if(isset($message->user))
            @if(isset($users[$message->user]))
            <h4 class="message-name"> {{ $users[$message->user]['name'] }}</h4>
            @else
            <h4 class="message-name">Unknown User</h4>
            @endif
            @else
            <h4 class="message-name">Unknown User</h4>
            @endif
        </div>
        <div class="col-xs-4 col-md-6 text-right message-date">{{date('Y/m/d H:i:s',$message->ts)}}</div>
        </div>
        <div class="row message-text ">
                {{ $message->text }}
        </div>
    </div>
    @endforeach
    </div>
</div>


<script>
    
   function sendMsg(){
    var channelId = {!! json_encode($channelId) !!};
    var message= message= document.getElementById("msgBox").value;
    
       if(message){
           
            $.ajax({
            url     : '/postMsg',
            method  : 'post',
            data    : {
            'message': message,
            'channelId':channelId
            },
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response){
                if(response.error==1){
                    alert (response.errorMsg);
                    }else{
                    message=response;
                    console.log(response); 
                    var users={!! json_encode($users) !!};
                    var msgHtml="";
                    if(message.user){
                        if(users[message.user]){
                            msgHtml+='<div class="message-box center-block" style="background:#'+users[message.user]["color"]+'">';
                        }else{
                            msgHtml+='<div class="message-box center-block">';
                        }
                    }else{
                        msgHtml+='<div class="message-box center-block">';
                    }
                    msgHtml+=' <div class="row"> <div class="col-xs-8 col-md-6">';
                    
                    if(message.user){
                        if(users[message.user]){
                            msgHtml+=' <h4 class="message-name">'+ users[message.user]['name']+'</h4>';
                        }else{
                            msgHtml+='<h4 class="message-name">Unknown User</h4>';
                        }
                    }else{
                        msgHtml+='<h4 class="message-name">Unknown User</h4>';
                    }
                    msgHtml+='</div><div class="col-xs-4 col-md-6 text-right message-date">';
                    var date= <?php echo json_encode(date('Y/m/d H:i:s',$message->ts)); ?> ;
                    msgHtml+= date +'</div></div> <div class="row message-text ">'+message.text+'</div> </div>';
                    oldMsgs=document.getElementById("channelMsgs").innerHTML;
                    document.getElementById("channelMsgs").innerHTML = msgHtml+oldMsgs;
                    document.getElementById("msgBox").value="";
                }
            },
            error : function(response){
             console.log(response);
            },
        });
       }
    }

   
</script>
@stop