@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
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
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->transaction_type }}</td>
                            <td>{{ $transaction->amount }}</td>
                            <td>{{ $transaction->fee }}</td>
                            <td>{{ $transaction->date }}</td>
                        </tr>
                    @endforeach
                        
                    </tbody>
                    
                </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
