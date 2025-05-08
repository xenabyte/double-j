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

use SweetAlert;
use Alert;
use Log;
use Carbon\Carbon;

class ClientController extends Controller
{
    //
    public function index(){
        $client = Auth::guard('client')->user();

        return view('client.biodata', [
            'client' => $client
        ]);
    }
}
