<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();

        if (count($customers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);

            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
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
     * Store a newly created resource in storage tabel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate  = FacadesValidator::make($storeData, [
            'nama_customer' => 'required|alpha_spaces|unique:customers',
            'membership' => ['required', Rule::in(['Bronze', 'Platinum', 'Gold']),],
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'no_telp' => 'required|numeric|digits_between:11,13|starts_with:08'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $customers = Customer::create($storeData);
        return response([
            'message' => 'Add customers Success',
            'data' => $customers
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customers = Customer::find($id);

        if (!is_null($customers)) {
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customers
            ], 200);
        }

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customers = Customer::find($id);
        if (is_null($customers)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = FacadesValidator::make($updateData, [
            'nama_customer' => ['required', 'alpha_spaces', Rule::unique('customers')->ignore($customers)],
            'membership' => ['required', Rule::in(['Bronze', 'Platinum', 'Gold']),],
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'no_telp' => 'required|numeric|starts_with:08|digits_between:11,13'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $customers->nama_customer = $updateData['nama_customer'];
        $customers->membership = $updateData['membership'];
        $customers->alamat = $updateData['alamat'];
        $customers->tgl_lahir = $updateData['tgl_lahir'];
        $customers->no_telp = $updateData['no_telp'];

        if ($customers->save()) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $customers
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customers = Customer::find($id);

        if (is_null($customers)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        if ($customers->delete()) {
            return response([
                'message' => 'Delete Customer Success',
                'data' => $customers
            ], 200);
        }

        return response([
            'message' => 'Delete Customer Failed',
            'data' => null
        ], 400);
    }
}