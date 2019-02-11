@extends('layout')
@section('content')

<style>

.message-box {
  background: #91948e;
  /* margin-left: 12%; */
  padding: 40px;
  max-width: 80%;
  margin-bottom: 3px;
  border-radius:10px;
}

.messaging {
  max-width: 80%;
  margin-top: 10px;
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
<div class="container text-center">
@foreach ($messages as $message)
    @if(isset($message->user))
    @if(isset($users[$message->user]))
 
  <div class="message-box center-block" style='background:#{{$users[$message->user]["color"]}}'>
        @endif
        @else
        <div class="message-box center-block">
        @endif
    <div class="row">
      <div class="col-xs-8 col-md-6">
          @if(isset($message->user))
          @if(isset($users[$message->user]))
        <h4 class="message-name"> {{ $users[$message->user]['name'] }}</h4>
        @endif
        @endif
      </div>
      <div class="col-xs-4 col-md-6 text-right message-date">{{date('Y/m/d H:i:s',$message->ts)}}</div>
    </div>
    <div class="row message-text ">
            {{ $message->text }}
    </div>
  </div>
  @endforeach
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
</div>


<script>
    
   function sendMsg(){
       if(message= document.getElementById("msgBox").value){
           
            $.ajax({
            url     : '/postMsg',
            method  : 'post',
            data    : {
            text: message
            },
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response){
alert(response);
            }
        });
       }
    }
</script>
@stop