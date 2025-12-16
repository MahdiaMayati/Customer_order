<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {
  return response()->json(Customer::all());
 }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        // dd($request->all());
      $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'string|unique:customers,phone',
        ]);

        $customer = Customer::create($validated);
        return response()->json(['customer' => $customer], 201);
    }


    public function show(Customer $customer)
    {
    return response()->json($customer);
 }

    public function edit(Customer $customer)
    {
        //
    }


    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|unique:customers,phone,' . $customer->id,
        ]);

        $customer->update($validated);
        return response()->json(['customer' => $customer->refresh()]);

    }


    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['message' => 'Customer soft deleted successfully']);


}
}
