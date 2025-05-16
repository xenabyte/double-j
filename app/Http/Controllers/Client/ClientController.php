<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Client;
use App\Models\Employee;
use App\Models\JobPosting;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class ClientController extends Controller
{
    //
    public function index(){

        $client = Auth::guard('client')->user();

        if (!$client->isBiodataComplete()) {
            return view('client.clientProfile', [
                'client' => $client
            ]);
        }

        return view('client.home', [
            'client' => $client
        ]);
    }

    public function biodata(){

        $client = Auth::guard('client')->user();

        return view('client.clientProfile', [
            'client' => $client
        ]); 
    }


    public function updateProfile(Request $request){
        $client = Auth::guard('client')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'company_address' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->company_name)));

        // Determine or create upload folder
        if (!$client->upload_folder) {
            $hashedFolder = md5($client->id . uniqid());
            $client->upload_folder = $hashedFolder;
            $client->save();
        } else {
            $hashedFolder = $client->upload_folder;
        }

        $folderPath = public_path("uploads/clients/{$hashedFolder}");

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Handle logo upload
        $logoUrl = $client->logo;
        if ($request->hasFile('logo')) {
            $logoName = 'logo.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($folderPath, $logoName);
            $logoUrl = "uploads/clients/{$hashedFolder}/{$logoName}";
        }

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'company_address' => $request->company_address,
            'industry' => $request->industry,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'logo' => $logoUrl,
            'slug' => $slug,
        ];

        if ($client->update($updateData)) {
            alert()->success('Success', 'Client profile updated successfully')->persistent('Close');
            return redirect()->back();
        }

        alert()->error('Oops!', 'Something went wrong while updating client profile')->persistent('Close');
        return redirect()->back();
    }

    public function employees(){
        $client = Auth::guard('client')->user(); 
        $employees = $client->employees()->whereNull('deleted_at')->with('jobPosting')->get();

        return view('client.employees', [
            'employees' => $employees
        ]);
    }

}
