@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
                <button type="button" class="btn btn-primary btn-right mb-2" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Deposit</button>
                <table id="transactions-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($depositTransactions as $deposit)
                        <tr>
                            <td>{{ $deposit->id }}</td>
                            <td>{{ $deposit->transaction_type }}</td>
                            <td>{{ $deposit->amount }}</td>
                            <td>{{ $deposit->fee }}</td>
                            <td>{{ $deposit->date }}</td>
                        </tr>
                    @endforeach
                        
                    </tbody>
                    
                </table>
                    
                </div>
            </div>
        </div>
        
        
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deposit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form method="POST" action="{{ route('user.createdeposit') }}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Deposit Amount:</label>
                            <input type="text" name="amount" id="amount" class="form-control" required>
                        </div>
                        
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Deposit</button>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
