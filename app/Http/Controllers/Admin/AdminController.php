<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;


use App\Models\SiteInfo as Setting;
use App\Models\Applicant;
use App\Models\JobPosting;
use App\Models\Client;
use App\Models\Application;
use App\Models\Employee;
use App\Models\JobRequest;


use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function index(){
        $setting = Setting::first();
    
        if (!$setting || empty($setting->favicon) || empty($setting->site_name) || empty($setting->logo) || empty($setting->description)) {
            return view('admin.siteSettings', [
                'setting' => $setting
            ]);
        }
    
        return view('admin.home');
    }

    //GLOBAL SITE SETTINGS LOGIC
    public function siteSettings(){
        $setting = Setting::first();
        return view('admin.siteSettings', [
            'setting' => $setting,
        ]);
    }

    public function updateSiteInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'description' => 'nullable|string',
            'site_name' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }
    
        $siteInfo = new Setting;
        if(!empty($request->site_info_id) && !$siteInfo = Setting::find($request->site_info_id)){
            alert()->error('Oops', 'Invalid Site Information')->persistent('Close');
            return redirect()->back();
        }
    
        if (!empty($request->site_name)) {
            $siteInfo->site_name = $request->site_name;
        }
    
        if (!empty($request->description)) {
            $siteInfo->description = $request->description;
        }
    
        // Save logo
        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = 'uploads/siteInfo/' .'logo'.'.'.$request->file('logo')->getClientOriginalExtension();
            $logo = $request->file('logo')->move('uploads/siteInfo', $logoUrl);
            $siteInfo->logo = $logoUrl;
        }
    
        // Save favicon
        $faviconUrl = null;
        if ($request->hasFile('favicon')) {
            $faviconUrl = 'uploads/siteInfo/' .'favicon'.'.'.$request->file('favicon')->getClientOriginalExtension();
            $favicon = $request->file('favicon')->move('uploads/siteInfo', $faviconUrl);
            $siteInfo->favicon = $faviconUrl;
        }
    
        if($siteInfo->save()){
            alert()->success('Changes Saved', 'Site information changes saved successfully')->persistent('Close');
            return redirect()->back();
        }
    
        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }

    //APLICANT MANAGEMENT LOGIC
    public function applicants(){
        $applicants = Applicant::all();
        return view('admin.applicants', [
            'applicants' => $applicants,
        ]);
    }

    public function newApplicant(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:applicants,email',
            'password' => 'required|confirmed|min:6',
            'title' => 'nullable|string|max:10',
            'othernames' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other,other,male,female',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->last_name . '-' . $request->othernames)));
        $hashedFolder = md5(uniqid() . time());
        $folderPath = public_path("uploads/applicants/{$hashedFolder}");

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = 'profile.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folderPath, $imageName);
            $imagePath = "uploads/applicants/{$hashedFolder}/{$imageName}";
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvName = 'cv.' . $request->file('cv')->getClientOriginalExtension();
            $request->file('cv')->move($folderPath, $cvName);
            $cvPath = "uploads/applicants/{$hashedFolder}/{$cvName}";
        }

        $coverLetterPath = null;
        if ($request->hasFile('cover_letter')) {
            $coverLetterName = 'cover_letter.' . $request->file('cover_letter')->getClientOriginalExtension();
            $request->file('cover_letter')->move($folderPath, $coverLetterName);
            $coverLetterPath = "uploads/applicants/{$hashedFolder}/{$coverLetterName}";
        }

        $applicant = new Applicant([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'title' => $request->title,
            'othernames' => $request->othernames,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'gender' => strtolower($request->gender),
            'slug' => $slug,
            'upload_folder' => $hashedFolder,
            'image' => $imagePath,
            'cv' => $cvPath,
            'cover_letter' => $coverLetterPath,
        ]);

        if ($applicant->save()) {
            alert()->success('Success', 'Applicant created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create applicant')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateApplicant(Request $request){
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'title' => 'nullable|string|max:10',
            'othernames' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other,other,male,female',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);
    
        $applicant = Applicant::findOrFail($request->applicant_id);
    
        // Generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->last_name . '-' . $request->othernames)));
    
        // Create or reuse upload folder
        if (!$applicant->upload_folder) {
            $hashedFolder = md5($applicant->id . uniqid());
            $applicant->upload_folder = $hashedFolder;
            $applicant->save();
        } else {
            $hashedFolder = $applicant->upload_folder;
        }
    
        $folderPath = public_path("uploads/applicants/{$hashedFolder}");
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    
        // File handling
        $imageUrl = $applicant->image;
        if ($request->hasFile('image')) {
            $imageName = 'profile.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folderPath, $imageName);
            $imageUrl = "uploads/applicants/{$hashedFolder}/{$imageName}";
        }
    
        $cvUrl = $applicant->cv;
        if ($request->hasFile('cv')) {
            $cvName = 'cv.' . $request->file('cv')->getClientOriginalExtension();
            $request->file('cv')->move($folderPath, $cvName);
            $cvUrl = "uploads/applicants/{$hashedFolder}/{$cvName}";
        }
    
        $coverLetterUrl = $applicant->cover_letter;
        if ($request->hasFile('cover_letter')) {
            $coverLetterName = 'cover_letter.' . $request->file('cover_letter')->getClientOriginalExtension();
            $request->file('cover_letter')->move($folderPath, $coverLetterName);
            $coverLetterUrl = "uploads/applicants/{$hashedFolder}/{$coverLetterName}";
        }
    
        // Update and check for actual changes
        $applicant->fill([
            'title' => $request->title,
            'othernames' => $request->othernames,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'gender' => strtolower($request->gender),
            'image' => $imageUrl,
            'cv' => $cvUrl,
            'cover_letter' => $coverLetterUrl,
            'slug' => $slug,
        ]);
    
        if ($applicant->isDirty()) {
            if ($applicant->save()) {
                alert()->success('Success', 'Applicant updated successfully')->persistent('Close');
            } else {
                alert()->error('Oops!', 'Something went wrong while saving the changes')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }
    
        return redirect()->back();
    }
    
    public function viewApplicant($slug){
        $applicant = Applicant::where('slug', $slug)->firstOrFail();
    
        return view('admin.viewApplicant', [
            'applicant' => $applicant,
        ]);
    }
    

    public function deleteApplicant(Request $request){
        $validator = Validator::make($request->all(), [
            'applicant_id' => 'required',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }

        if(!$applicant = Applicant::find($request->applicant_id)){
            alert()->error('Oops', 'Invalid Applicant')->persistent('Close');
            return redirect()->back();
        }

        if($applicant->delete()) {
            alert()->success('Deleted', 'Applicant successfully deleted');
            return redirect()->back();
        }

        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }

    public function employees(){
        $employees = Employee::withTrashed()->with('jobPosting', 'client')->get();
        return view('admin.employees', [
            'employees' => $employees,
        ]);
    }
    
    //JOB POSTING MANAGEMENT LOGIC
    public function jobPosting(){
        $jobPostings = JobPosting::all();
        return view('admin.jobPostings', [
            'jobPostings' => $jobPostings,
        ]);
    }

    public function newJobPosting(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title))) . '-' . uniqid();
        $hashedFolder = md5($slug . time());
        $folderPath = public_path("uploads/job-postings/{$hashedFolder}");

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = $slug . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folderPath, $imageName);
            $imagePath = "uploads/job-postings/{$hashedFolder}/{$imageName}";
        }

        $job = new JobPosting([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => $request->status,
            'image' => $imagePath,
            'slug' => $slug,
            'upload_folder' => $hashedFolder,
        ]);

        if ($job->save()) {
            alert()->success('Success', 'Job posting created successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to create job posting')->persistent('Close');
        }

        return redirect()->back();
    }

    public function updateJobPosting(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_postings,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Validation Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $job = JobPosting::findOrFail($request->job_id);

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title))) . '-' . uniqid();

        if (!$job->upload_folder) {
            $hashedFolder = md5($job->id . uniqid());
            $job->upload_folder = $hashedFolder;
        } else {
            $hashedFolder = $job->upload_folder;
        }

        $folderPath = public_path("uploads/job-postings/{$hashedFolder}");
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $imagePath = $job->image;
        if ($request->hasFile('image')) {
            $imageName = $slug . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($folderPath, $imageName);
            $imagePath = "uploads/job-postings/{$hashedFolder}/{$imageName}";
        }

        $job->fill([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => $request->status,
            'image' => $imagePath,
            'slug' => $slug,
        ]);

        if ($job->isDirty()) {
            if ($job->save()) {
                alert()->success('Success', 'Job posting updated successfully')->persistent('Close');
            } else {
                alert()->error('Oops!', 'Something went wrong while saving changes')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function deleteJobPosting(Request $request){
        $request->validate([
            'job_id' => 'required|exists:job_postings,id',
        ]);

        $job = JobPosting::findOrFail($request->job_id);

        if ($job->delete()) {
            alert()->success('Deleted', 'Job posting deleted successfully')->persistent('Close');
        } else {
            alert()->error('Error', 'Failed to delete job posting')->persistent('Close');
        }

        return redirect()->back();
    }

    public function viewJobPosting($slug){
        $jobPosting = JobPosting::where('slug', $slug)->firstOrFail();

        return view('admin.viewJobPosting', [
            'jobPosting' => $jobPosting,
        ]);
    }

    public function setJobStatus(Request $request){
        $request->validate([
            'job_id' => 'required|exists:job_postings,id',
            'status' => 'required|in:open,closed',
        ]);

        $job = JobPosting::findOrFail($request->job_id);
        $job->status = $request->status;
        $job->save();

        return back()->with('success', 'Job status updated to ' . $request->status);
    }


    //CLIENT MANAGEMENT LOGIC
    public function clients(){
        $clients = Client::all();
        return view('admin.clients', [
            'clients' => $clients,
        ]);
    }

    public function newClient(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }
    
        $client = new Client();
        $client->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'company_address' => $request->company_address,
            'industry' => $request->industry,
            'password' => bcrypt($request->password),
        ]);
    
        if ($client->save()) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->company_name)));
            $hashedFolder = md5($client->id . uniqid());
    
            $client->slug = $slug;
            $client->upload_folder = $hashedFolder;
    
            $folderPath = public_path("uploads/clients/{$hashedFolder}");
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
    
            if ($request->hasFile('logo')) {
                $logoName = 'logo.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move($folderPath, $logoName);
                $client->logo = "uploads/clients/{$hashedFolder}/{$logoName}";
            }
    
            $client->save();
    
            alert()->success('Success', 'Client created successfully')->persistent('Close');
        } else {
            alert()->error('Oops!', 'Something went wrong while creating the client')->persistent('Close');
        }
    
        return redirect()->back();
    }
    

    public function viewClient($slug){
        $client = Client::where('slug', $slug)->firstOrFail();

        return view('admin.viewClient', [
            'client' => $client,
        ]);
    }

    public function deleteClient(Request $request){
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }

        if (!$client = Client::find($request->client_id)) {
            alert()->error('Oops', 'Invalid Client')->persistent('Close');
            return redirect()->back();
        }

        if ($client->delete()) {
            alert()->success('Deleted', 'Client successfully deleted');
            return redirect()->back();
        }

        alert()->error('Oops!', 'Something went wrong')->persistent('Close');
        return redirect()->back();
    }

    public function updateClient(Request $request){
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->first())->persistent('Close');
            return redirect()->back()->withInput();
        }

        $client = Client::findOrFail($request->client_id);

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->company_name)));

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

        $logoUrl = $client->logo;
        if ($request->hasFile('logo')) {
            $logoName = 'logo.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($folderPath, $logoName);
            $logoUrl = "uploads/clients/{$hashedFolder}/{$logoName}";
        }

        $client->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'company_address' => $request->company_address,
            'industry' => $request->industry,
            'logo' => $logoUrl,
            'slug' => $slug,
        ]);

        if ($client->isDirty()) {
            if ($client->save()) {
                alert()->success('Success', 'Client updated successfully')->persistent('Close');
            } else {
                alert()->error('Oops!', 'Something went wrong while saving the changes')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
    }

    public function applications(){
        $applications = Application::all();
        return view('admin.applications', [
            'applications' => $applications,
        ]);
    }
    

    public function setApplicationStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:applications,id',
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application = Application::with('applicant', 'jobPosting')->findOrFail($request->application_id);

        // Update status
        $application->status = $request->status;
        $application->save();

        // Graduate applicant to employee only if status is "accepted"
        if ($request->status === 'accepted') {
            $applicant = $application->applicant;
            $job = $application->jobPosting;

            // Avoid duplicate employee records
            $existingEmployee = Employee::where('email', $applicant->email)->first();
            if (!$existingEmployee) {
                $employee = Employee::create([
                    'title' => $applicant->title,
                    'othernames' => $applicant->othernames,
                    'last_name' => $applicant->last_name,
                    'email' => $applicant->email,
                    'dob' => $applicant->dob,
                    'phone' => $applicant->phone,
                    'address' => $applicant->address,
                    'city' => $applicant->city,
                    'state' => $applicant->state,
                    'gender' => $applicant->gender,
                    'image' => $applicant->image,
                    'cv' => $applicant->cv,
                    'cover_letter' => $applicant->cover_letter,
                    'upload_folder' => $applicant->upload_folder,
                    'job_posting_id' => $job->id,
                    // 'client_id' will be set later via a separate interface
                ]);

                // Send password reset notification
                $token = Password::broker('employees')->createToken($employee);
                $employee->sendPasswordResetNotification($token);
            }
        }

        alert()->success('Application status updated successfully.')->persistent('Close');
        return redirect()->back();
    }


    public function assignClient(){
        $employees = Employee::withTrashed()->with('jobPosting', 'client')->get();
        $clients = Client::all();
        $jobPostings = JobPosting::all();

        return view('admin.assignClient', [
            'employees' => $employees,
            'clients' => $clients,
            'jobPostings' => $jobPostings,
        ]);
    }

    public function assignClientToJob(Request $request){
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'client_id' => 'required|exists:clients,id',
            'job_id' => 'required|exists:job_postings,id',
        ]);

        $employee = Employee::withTrashed()->findOrFail($request->employee_id);
        $employee->client_id = $request->client_id;
        $employee->job_posting_id = $request->job_id;
        $employee->save();

        return redirect()->back()->with('success', 'Employee assigned to client successfully.');
    }

    public function engageEmployee(Request $request){
        $employee = Employee::withTrashed()->findOrFail($request->employee_id);
        $employee->restore();

        return redirect()->back()->with('success', 'Employee re-engaged successfully.');
    }

    public function disengageEmployee(Request $request){
        $employee = Employee::findOrFail($request->employee_id);
        $employee->delete();

        return redirect()->back()->with('success', 'Employee disengaged successfully.');
    }

    public function unassignJob(Request $request){
        $employee = Employee::findOrFail($request->employee_id);
        $employee->job_posting_id = null;
        $employee->client_id = null;
        $employee->save();

        return redirect()->back()->with('success', 'Employee unassigned from job.');
    }
    
    public function jobRequest(){
        $jobRequests = JobRequest::all();
        return view('admin.jobRequest', [
            'jobRequests' => $jobRequests,
        ]);
    }

    public function setJobRequestStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_requests,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            alert()->error('Invalid Request', 'Please provide valid data.')->persistent('Close');
            return redirect()->back();
        }

        $job = JobRequest::findOrFail($request->job_id);
        $oldStatus = $job->status;
        $newStatus = $request->status;

        $job->status = $newStatus;
        $job->save();

        $existingPosting = JobPosting::withTrashed()->where('slug', $job->slug)->first();

        if ($oldStatus === 'approved' && in_array($newStatus, ['pending', 'rejected'])) {
            // Soft delete the associated job posting if it exists
            if ($existingPosting && !$existingPosting->trashed()) {
                $existingPosting->delete();
            }
        }

        if ($newStatus === 'approved') {
            if ($existingPosting) {
                if ($existingPosting->trashed()) {
                    $existingPosting->restore();
                }
                // else: it's already active, no action needed
            } else {
                // Create new job posting from job request
                JobPosting::create([
                    'title'         => $job->job_title,
                    'description'   => $job->description,
                    'requirements'  => $job->requirements,
                    'status'        => 'open',
                    'image'         => $job->image,
                    'slug'          => $job->slug,
                    'upload_folder' => $job->upload_folder,
                ]);
            }
        }

        alert()->success('Status Updated', 'Job request status updated successfully.')->persistent('Close');
        return redirect()->back();
    }


    public function jobRequestToPosting(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_requests,id',
        ]);

        if ($validator->fails()) {
            alert()->error('Invalid Request', 'The job ID is missing or invalid.')->persistent('Close');
            return back();
        }

        $jobRequest = JobRequest::findOrFail($request->job_id);

        if ($jobRequest->status !== 'approved') {
            alert()->error('Not Approved', 'Only approved job requests can be converted to a job posting.')->persistent('Close');
            return back();
        }

        if (JobPosting::where('slug', $jobRequest->slug)->exists()) {
            alert()->error('Already Converted', 'This job request has already been converted to a posting.')->persistent('Close');
            return back();
        }

        JobPosting::create([
            'title'         => $jobRequest->job_title,
            'description'   => $jobRequest->description,
            'requirements'  => $jobRequest->requirements,
            'status'        => 'open', 
            'image'         => $jobRequest->image,
            'slug'          => $jobRequest->slug,
            'upload_folder' => $jobRequest->upload_folder,
        ]);

        alert()->success('Job Posting Created', 'The job request has been successfully converted to a job posting.')->persistent('Close');
        return redirect()->back();
    }


}
