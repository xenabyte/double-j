<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests;

use App\Mail\JobRequest\Submitted;

use App\Models\Client;
use App\Models\Employee;
use App\Models\JobPosting;
use App\Models\JobRequest;

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

    public function jobRequest(){
        $jobRequests = JobRequest::all();
        $clients = Auth::guard('client')->user();
        return view('client.jobRequest', [
            'jobRequests' => $jobRequests,
            'clients' => $clients
        ]);
    }


    public function newJobRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'vacancies' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->job_title))) . '-' . uniqid();
        $hashedFolder = md5($slug . time());
        $folderPath = public_path("uploads/job-requests/{$hashedFolder}");

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = $slug . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folderPath, $imageName);
            $imagePath = "uploads/job-requests/{$hashedFolder}/{$imageName}";
        }

        $job = new JobRequest([
            'client_id' => $request->client_id,
            'job_title' => $request->job_title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'vacancies' => $request->vacancies,
            'status' => 'pending',
            'image' => $imagePath,
            'slug' => $slug,
            'upload_folder' => $hashedFolder,
        ]);

        if ($job->save()) {
            // Send to admin
            Mail::to('admin@example.com')->send(new Submitted($job, 'admin'));

            // Send to client
            Mail::to($job->client->company_email)->send(new Submitted($job, 'client'));

            alert()->success('Success', 'Job request submitted successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to submit job request')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateJobRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_requests,id',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'vacancies' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $job = JobRequest::findOrFail($request->job_id);

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->job_title))) . '-' . uniqid();

        if (!$job->upload_folder) {
            $hashedFolder = md5($job->id . uniqid());
            $job->upload_folder = $hashedFolder;
        } else {
            $hashedFolder = $job->upload_folder;
        }

        $folderPath = public_path("uploads/job-requests/{$hashedFolder}");
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $imagePath = $job->image;
        if ($request->hasFile('image')) {
            $imageName = $slug . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folderPath, $imageName);
            $imagePath = "uploads/job-requests/{$hashedFolder}/{$imageName}";
        }

        $job->fill([
            'job_title' => $request->job_title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'vacancies' => $request->vacancies,
            'status' => $request->status,
            'image' => $imagePath,
            'slug' => $slug,
        ]);

        if ($job->isDirty()) {
            if ($job->save()) {
                alert()->success('Success', 'Job request updated successfully')->persistent('Close');
            } else {
                alert()->error('Oops!', 'Something went wrong while saving changes')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteJobRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_requests,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back();
        }

        $job = JobRequest::findOrFail($request->job_id);

        if ($job->delete()) {
            alert()->success('Deleted', 'Job request deleted successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to delete job request')->persistent('Close');
        }

        return redirect()->back();
    }

    

}
