<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{

    public function __construct() {

        $this->middleware(['auth:sanctum'])->only(['store','update','destroy']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EmployeeResource::collection(DB::table('employees')->orderBy('id', 'DESC')->paginate(10));
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
        $validatedData = $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'unique:employees',
            'phone' => 'max:100',
            'company_id' => ''
        ]);

        $employee = Employee::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'company_id' => $validatedData['company_id']
        ]);

        if($employee) {

            return [
                'message' => 'Employee Record Successfully Created'
            ];

        } else {

            return [
                'error' => 'Something went wrong'
            ];

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return EmployeeResource::collection(Employee::where('id', '=', $id)->get());
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
        $validatedData = $request->validate([
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'unique:employees,email,'.$id,
            'phone' => 'max:100',
            'company_id' => ''
        ]);

        $employee = Employee::where('id','=', $id)->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'company_id' => $validatedData['company_id']
        ]);

        if($employee) {

            return [
                'message' => 'Employee Record Successfully Updated'
            ];

        } else {

            return [
                'error' => 'Something went wrong'
            ];

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::where('id', '=', $id)->delete();

        if($employee) {

            return [
                'message' => 'Employee Record Successfully Deleted!'
            ];

        } else {

            return [
                'error' => 'Something went wrong'
            ];

        }

    }
}
