
@extends('layout')
@section('content')
<div class="content">
                <div class="title m-b-md">
                        Create New Channel
                </div>
                <div class="form-group">
                
                    <form style:"display:flex" class="form" action= {{ route('postNewChannel') }} role="form" method="post" accept-charset="UTF-8">
                        
                        <input name="channelName" class="custom-select" style="width:70%">
                       
                        <button class="btn btn-secondary" type="submit" style="width:20%">Create</button>
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                    </form>
                </div>        
             
             
            </div>

@stop