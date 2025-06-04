<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;

use Illuminate\Http\Request ;

class CustomerController extends Controller
{
    public function customerPage(){
        return view('pages.customer.customerPage');
    }
    public function customerList(Request $request)
    {
        // Fetch the customers from the database for the authenticated user
        $user_id = $request->header('user_id');
        $customers = Customer::where('user_id', $user_id)->get();
        return response()->json([
            'status'  => 'success',
            'message' => 'Customer List fetched successfully',
            'data'    => $customers,
        ], 200);
    }
    public function CustomerCreate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:customers,email,NULL,id,user_id,',
            'mobile' => 'required|string|max:15',
        ]);
        // Create a new customer record
        $existingCustomer = Customer::where('email', $request->input('email'))
            ->where('user_id', $request->header('user_id'))
            ->first();

        if ($existingCustomer) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer with this email already exists',
            ], 409);
        }

        // Create a new customer
        
        Customer::create([
            'user_id' => $request->header('user_id'),
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'mobile'  => $request->input('mobile'),
        ]);
        return response()->json([
            'status'  => 'success',
            'message' => 'Customer created successfully',
        ], 201);
        // return view('pages.customer.customerPage');
    }

    public function CustomerById(Request $request){
        $customerId = $request->input('id');
        $customer = Customer::where('id', $customerId)->where('user_id', $request->header('user_id'))->first();
        if ($customer) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Customer fetched successfully',
                'data'    => $customer,
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }
    }

    public function CustomerUpdate(Request $request)
    {
        $customerId = $request->input('id');
        $customer = Customer::where('id', $customerId)->where('user_id', $request->header('user_id'))->first();

        if ($customer) {
            $customer->update([
                'name'   => $request->input('name') ?? $customer->name,
                'email'  => $request->input('email') ?? $customer->email,
                'mobile' => $request->input('mobile')   ?? $customer->mobile,
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Customer updated successfully',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }
    }

    public function CustomerDelete(Request $request)
    {
        $customerId = $request->input('id');
        $customer = Customer::where('id', $customerId)->where('user_id', $request->header('user_id'))->first();

        if ($customer) {
            $customer->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Customer deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Customer not found',
            ], 404);
        }
    }
}
