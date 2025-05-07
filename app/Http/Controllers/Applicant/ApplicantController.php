<?php

namespace App\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Applicant;

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class ApplicantController extends Controller
{
    //
    public function index(){

        $applicant = Auth::guard('applicant')->user();

        if (!$applicant->isBiodataComplete()) {
            return view('applicant.biodata', [
                'applicant' => $applicant
            ]);
        }

        return view('applicant.home', [
            'applicant' => $applicant
        ]);
    }

    public function biodata(){

        $applicant = Auth::guard('applicant')->user();

        return view('applicant.biodata', [
            'applicant' => $applicant
        ]); 
    }

    // public function updateBiodata(Request $request){

    //     $applicant = Auth::guard('applicant')->user();

    //     // Validate required fields
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'nullable|string|max:10',
    //         'othernames' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'dob' => 'required|date',
    //         'phone' => 'required|string|max:15',
    //         'address' => 'nullable|string',
    //         'city' => 'nullable|string|max:100',
    //         'state' => 'nullable|string|max:100',
    //         'gender' => 'required|in:Male,Female,Other',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
    //         'cover_letter' => 'nullable|mimes:pdf,doc,docx|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
    //         return redirect()->back();
    //     }

    //     // Generate slug for filenames
    //     $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $applicant->last_name . '-' . $applicant->othernames)));

    //     // Handle image upload
    //     $imageUrl = $applicant->image;
    //     if ($request->hasFile('image')) {
    //         $imageName = $slug . '.' . $request->file('image')->getClientOriginalExtension();
    //         $request->file('image')->move(public_path('uploads/applicants/' .$slug), $imageName);
    //         $imageUrl = 'uploads/applicants/images/' . $imageName;
    //     }

    //     // Handle CV upload
    //     $cvUrl = $applicant->cv;
    //     if ($request->hasFile('cv')) {
    //         $cvName = $slug . '-cv.' . $request->file('cv')->getClientOriginalExtension();
    //         $request->file('cv')->move(public_path('uploads/applicants/' .$slug), $cvName);
    //         $cvUrl = 'uploads/applicants/documents/' . $cvName;
    //     }

    //     // Handle cover letter upload
    //     $coverLetterUrl = $applicant->cover_letter;
    //     if ($request->hasFile('cover_letter')) {
    //         $coverLetterName = $slug . '-cover-letter.' . $request->file('cover_letter')->getClientOriginalExtension();
    //         $request->file('cover_letter')->move(public_path('uploads/applicants/' .$slug), $coverLetterName);
    //         $coverLetterUrl = 'uploads/applicants/documents/' . $coverLetterName;
    //     }

    //     // Update data
    //     $updateData = [
    //         'title' => $request->title,
    //         'othernames' => $request->othernames,
    //         'last_name' => $request->last_name,
    //         'dob' => $request->dob,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //         'city' => $request->city,
    //         'state' => $request->state,
    //         'gender' => $request->gender,
    //         'image' => $imageUrl,
    //         'cv' => $cvUrl,
    //         'cover_letter' => $coverLetterUrl,
    //         'slug' => $slug,
    //     ];

    //     if ($applicant->update($updateData)) {
    //         alert()->success('Success', 'Biodata updated successfully')->persistent('Close');
    //         return redirect()->back();
    //     }

    //     alert()->error('Oops!', 'Something went wrong while updating biodata')->persistent('Close');
    //     return redirect()->back();
    // }

    public function updateBiodata(Request $request){
        $applicant = Auth::guard('applicant')->user();

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:10',
            'othernames' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', $validator->messages()->all()[0])->persistent('Close');
            return redirect()->back();
        }

        // Generate slug and hashed folder name
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $applicant->last_name . '-' . $applicant->othernames)));
        $hashedFolder = md5($applicant->id . $slug); 

        $folderPath = public_path("uploads/applicants/{$hashedFolder}");

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Handle uploads
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

        $updateData = [
            'title' => $request->title,
            'othernames' => $request->othernames,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'gender' => $request->gender,
            'image' => $imageUrl,
            'cv' => $cvUrl,
            'cover_letter' => $coverLetterUrl,
            'slug' => $slug,
        ];

        if ($applicant->update($updateData)) {
            alert()->success('Success', 'Biodata updated successfully')->persistent('Close');
            return redirect()->back();
        }

        alert()->error('Oops!', 'Something went wrong while updating biodata')->persistent('Close');
        return redirect()->back();
    }
}
