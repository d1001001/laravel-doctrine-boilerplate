@extends('backend::layout')
@section('content')
    <div class="ui grid padded">
        <div class="four wide column centered">
            <div class="ui form" id=login-form>
                {{ Form::open(['url' => '']) }}
                <div class="field">
                {{ Form::text('test', null, ['placeholder' => 'Login']) }}
                </div>
                {{ Form::submit('Send', ['class' => 'ui primary submit button']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
