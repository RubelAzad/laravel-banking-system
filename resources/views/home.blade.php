@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                  <h1>{{$userBalance->name}} , Welcome to Your Account</h1>
                  <p>Your Current Account Balance : <strong>{{$userBalance->balance}}</strong></p>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
