<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class TransactionController extends Controller
{
    
    public function index(User $user)
    {
        // Fetch the user's transactions
        $transactions = $user->transactions;

        return view('transactions.index', compact('transactions', 'user'));
    }

    public function deposit(User $user){
        $depositTransactions = $user->transactions()->where('transaction_type', 'deposit')->get();
        return view('transactions.deposit', compact('depositTransactions', 'user'));
    }

    public function withdraw(User $user){
        $withdrawTransactions = $user->transactions()->where('transaction_type', 'withdraw')->get();
        return view('transactions.withdraw', compact('withdrawTransactions', 'user'));
    }

    public function createDeposit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Find the user by ID
        $user = User::findOrFail($request->input('user_id'));

        // Update the user's balance by adding the deposited amount
        $user->balance += $request->input('amount');
        $user->save();

        Transaction::create([
            'user_id' => auth()->user()->id, // Assuming the user is authenticated
            'transaction_type' => 'deposit',
            'amount' => $request->input('amount'),
            'fee' => 0, // You can adjust this if there's a fee
            'date' => now(), // or use a different date as needed
        ]);

        return redirect()->back()->with('success', 'Deposit successfully');
    }

    public function createWithdraw(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Retrieve user and determine account type
        $user = User::findOrFail($request->input('user_id'));
        $accountType = $user->account_type;

        // Calculate withdrawal fee based on account type
        if ($accountType === 'Individual') {
            $withdrawalRate = 0.015; // 0.015% for Individual accounts
        } elseif ($accountType === 'Business') {
            $withdrawalRate = 0.025; // 0.025% for Business accounts
        }

        $withdrawalAmount = $request->input('amount');
        $withdrawalFee = $withdrawalAmount * ($withdrawalRate / 100);

        // Apply free withdrawal conditions for Individual accounts
        $currentDayOfWeek = now()->dayOfWeek;
        if ($accountType === 'Individual' && $currentDayOfWeek === 5) {
            // Free withdrawal on Fridays
            $withdrawalFee = 0;
        }

        if ($accountType === 'Individual' && $withdrawalAmount <= 1000) {
            // Free for the first 1K
            $withdrawalFee = 0;
        } elseif ($accountType === 'Individual') {
            // Calculate the fee for the remaining amount (above 1K)
            $remainingAmount = $withdrawalAmount - 1000;
            $withdrawalFee = $remainingAmount * ($withdrawalRate / 100);
            
        }

        if ($accountType === 'Individual') {
            $currentMonthWithdrawals = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdraw')
                ->whereMonth('date', now()->month)
                ->sum('amount');

            if ($currentMonthWithdrawals + $withdrawalAmount <= 5000) {
                // Free for the first 5K each month
                $withdrawalFee = 0;
            } elseif ($currentMonthWithdrawals + $withdrawalAmount > 5000) {
                // Calculate the fee for the remaining amount (above 5K)
                $remainingAmount = $withdrawalAmount - 1000;
                $withdrawalFee = $remainingAmount * ($withdrawalRate / 100);
            }
        }
        if ($accountType === 'Business') {
            $totalWithdrawals = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdraw')
                ->sum('amount');
        
            if ($totalWithdrawals > 50000) {
                // Decrease the withdrawal fee rate to 0.015% for Business accounts
                $withdrawalRate = 0.015;
                $withdrawalFee = $withdrawalAmount * ($withdrawalRate / 100);
            }
        }

        // Update the user's balance
        $user->balance -= $withdrawalAmount+$withdrawalFee;
        $user->save();

        // Record the withdrawal transaction
        Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'withdraw',
            'amount' => $withdrawalAmount,
            'fee' => $withdrawalFee,
            'date' => now(),
        ]);


        return redirect()->back()->with('success', 'Withdraw successfully');
    }

}
