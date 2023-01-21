<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Carbon;
use App\Models\User;
use Hash;
use Inertia\Inertia;


class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('ClientName', 'asc')->get();
        $payload = ['customers' => $customers];
        return response()->json($payload);
    }

    public function getAllCustomerNames()
    {
        $customerNames = DB::table('clients')
            ->select("ClientId", "ClientName")
            ->orderBy('ClientName', 'asc')->get();
        // $customers = customer::orderBy('customerDate', 'desc')->take(10)->get();
        $payload = ['customerNames' => $customerNames];
        return response()->json($payload);
    }

    public function getUserId() {
        return Auth::id();
    }

    public function editClientView($id) {
        $client = Customer::find($id);

        return Inertia::render('EditCustomer', ['client' => $client]);
    }

    public function editClient(Request $request) {
        $customer = Customer::find($request->clientId);

        $customer->ClientName = $request->clientName;

        if($request->clientAddress != null)
            $customer->ClientAddress = $request->clientAddress;
        else
            $customer->ClientAddress = "-";

        if($request->clientPhone != null)
            $customer->ClientPhone = $request->clientPhone;
        else
            $customer->ClientPhone = "-";

        if($request->clientEmail != null)
            $customer->ClientEmail = $request->clientEmail;
        else
            $customer->ClientEmail = "-";

        if($request->clientCUI != null)
            $customer->ClientVATNumber = $request->clientCUI;
        else
            $customer->ClientVATNumber = "-";

        if($request->clientCIF != null)
            $customer->ClientRegistrationNumber = $request->clientCIF;
        else
            $customer->ClientRegistrationNumber = "-";

        $customer->save();
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = new Customer;

        $customer->ClientName = $request->clientName;

        if($request->clientAddress != null)
            $customer->ClientAddress = $request->clientAddress;
        else
            $customer->ClientAddress = "-";

        if($request->clientPhone != null)
            $customer->ClientPhone = $request->clientPhone;
        else
            $customer->ClientPhone = "-";

        if($request->clientCUI != null)
            $customer->ClientVATNumber = $request->clientCUI;
        else
            $customer->ClientVATNumber = "-";

        if($request->clientCIF != null)
            $customer->ClientRegistrationNumber = $request->clientCIF;
        else
            $customer->ClientRegistrationNumber = "-";

        $customer->DateAdded = Carbon\Carbon::now()->toDateTimeString();
        $customer->accounts_AccountId = Auth::id();

        $customer->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function deleteClient(Request $request) {
        $customer = Customer::find($request->clientIdToDelete);

        if (Auth::id() == $request->userId) {
            $customer->delete();
        }
    }

    public function getAllUsers() {
        $users = User::all();

        $payload = ['users' => $users];
        return response()->json($payload);
    }

    public function addNewUser(Request $request) {
        if (Auth::user()->isAdmin) {
            $user = new User;

            $user->password = Hash::make('DeratDezin12345');
            $user->email = $request->userMail;
            $user->name = $request->userName;
            $user->isAdmin = 0;
            $user->current_team_id = 1;
            $user->save();
        }
        else {
            return abort(500, 'Nu aveti drepturile necesare.');
        }
    }

    public function deleteUser(Request $request) {
        $user = User::find($request->userId);

        if (Auth::user()->isAdmin) {
            $user->delete();
        }
        else {
            return abort(500, 'Nu aveti drepturile necesare.');
        }
    }
}
