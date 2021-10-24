<?php

namespace App\Http\Controllers;

use Image;
use Notification;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewCompanyAdded;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
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
        return CompanyResource::collection(DB::table('companies')->orderBy('id', 'DESC')->paginate(10));
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
            'name' => 'required|max:100',
            'email' => 'unique:companies',
            'website' => 'max:100'
        ]);

      //  return $request->photo;

        if($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];
            $name = time().".".$ext;
            // Check if Company Dir exists
            if (!Storage::disk('public')->exists('company'))
            {
                Storage::disk('public')->makeDirectory('company');
            }
            // Resize image for company and upload
            $companyImage = Image::make($request->photo)->resize(100,100)->stream();
            Storage::disk('public')->put('company/'. $name, $companyImage);
        } else {
            $name = null;
        }

        $company = Company::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'logo' => $name,
            'website' => $validatedData['website']
        ]);

        $companies = Company::all();

        Notification::send($companies, new NewCompanyAdded());

        if($company) {
            return [
                "message" => 'Company Record Successfully Created!'
            ];
        } else {
            return [
                "error" => 'Sorry Something went wrong!'
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
        return CompanyResource::collection(Company::where('id', '=', $id)->get());
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
            'name' => 'required|max:100',
            'email' => 'unique:companies,email,'.$id,
            'website' => 'max:100'
        ]);

        if($request->newLogo) {
            $position = strpos($request->newLogo, ';');
            $sub = substr($request->newLogo, 0, $position);
            $ext = explode('/', $sub)[1];
            $name = time().".".$ext;
            // Check if Company Dir exists
            if (!Storage::disk('public')->exists('company'))
            {
                Storage::disk('public')->makeDirectory('company');
            }
            // Resize image for company and upload
            $companyImage = Image::make($request->newLogo)->resize(100,100)->stream();
            Storage::disk('public')->put('company/'. $name, $companyImage);

            $company = Company::where('id', '=', $id)->first();
            if(!is_null($company->logo)) {
                Storage::disk('public')->delete("company/" . $company->logo);
            }

        } else if($request->logo) {
            $name = $request->logo;
        } else {
            $name = null;
        }

        $company = tap(DB::table('companies')->where('id', '=', $id))->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'logo' => $name,
            'website' => $validatedData['website']
        ])->first();

        if($company) {

            return [
                "message" => 'Company Record Successfully Updated!',
                "logo" => $company->logo
            ];

        } else{

            return [
                "error" => 'Sorry Something went wrong!'
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
        $employees = Employee::where('company_id', '=', $id)->get();
        if(count($employees) >= 1) {

            return [
                "error" => "Sorry you can not delete this company because it has employees"
            ];

        } else {
            $company = Company::where('id', '=', $id)->first();
            if(!is_null($company->logo)) {
                Storage::disk('public')->delete("company/" . $company->logo);
            }
            $companyDeleted = $company->delete();

            if($companyDeleted) {

                return [
                    "message" => 'Company Record Successfully Deleted!'
                ];

            } else {

                return [

                    "error" => 'Sorry Something went wrong'

                ];
            }

        }
    }

    /**
     * Get all the companies list.
     */
    public function companies() {

        return CompanyResource::collection(DB::table('companies')->get());

    }
}
