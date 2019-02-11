@extends('layout')
@section('content')
<div class="content">
                <div class="title m-b-md">
                   Chatting With Slack
                </div>
                <div class="form-group">
                Choose Channel:

                    <form style:"display:flex" class="form" action= {{ route('channeldetails') }} role="form" method="post" accept-charset="UTF-8">
                        
                        <select name="channelId" class="custom-select" style="width:70%">
                         @foreach($channels as $channel)
                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                         @endforeach
                        </select>
                        <button class="btn btn-secondary" type="submit" style="width:20%">Submit</button>
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                    </form>
                </div>
                <div class="title m-b-md">
                OR:
                </div>
                <div class="form-group">
                <a type="button" class="btn btn-secondary"  href="/createChannel">Create Channel</a>
                </div>           
             
             
            </div>

@stop