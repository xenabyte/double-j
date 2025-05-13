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

use App\Models\SiteInfo as Setting;
use App\Models\Applicant;
use App\Models\JobPosting;
use App\Models\Client;


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
            'client_id' => 'required|exists:clients,id', // Validate incoming client ID
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
            $imageName = '$slug.' . $request->file('image')->getClientOriginalExtension();
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
            'client_id' => $request->client_id,
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
            'client_id' => 'required|exists:clients,id', // Now editable and validated
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
            $job->save();
        } else {
            $hashedFolder = $job->upload_folder;
        }

        $folderPath = public_path("uploads/job-postings/{$hashedFolder}");
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $imagePath = $job->image;
        if ($request->hasFile('image')) {
            $imageName = '$slug.' . $request->file('image')->getClientOriginalExtension();
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
            'client_id' => $request->client_id,
        ]);

        if ($job->isDirty()) {
            if ($job->save()) {
                alert()->success('Success', 'Job posting updated successfully')->persistent('Close');
            } else {
                alert()->error('Oops!', 'Something went wrong while saving the changes')->persistent('Close');
            }
        } else {
            alert()->info('No Changes', 'No updates were made')->persistent('Close');
        }

        return redirect()->back();
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




}
