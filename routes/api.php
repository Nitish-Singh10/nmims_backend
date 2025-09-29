<?php

use App\Models\Enquiries;
use App\Models\Registrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/api/enquiries', function (Request $request) {
    $enquiries = Enquiries::all();

    return response()
        ->json($enquiries)
        ->header('Access-Control-Allow-Origin', 'https://nmims.asbtechnologies.com');
});

Route::get('/api/registrations', function (Request $request) {
    $registrations = Registrations::all();

    return response()
        ->json($registrations)
        ->header('Access-Control-Allow-Origin', 'https://nmims.asbtechnologies.com');
});

Route::post('/api/enquiries', function (Request $request) {
    // Validate inputs
    $validator = Validator::make($request->all(), [
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'email' => 'required|email|max:150',
        'mobile' => 'required|digits_between:10,15',
    ]);

    if ($validator->fails()) {
        return response()
            ->json(['errors' => $validator->errors()], 422)
            ->header('Access-Control-Allow-Origin', 'https://nmims.asbtechnologies.com');
    }

    // Save enquiry (combine first + last name if your table has `name`)
    $enquiry = Enquiries::create([
        'name' => $request->firstName . ' ' . $request->lastName,
        'email' => $request->email,
        'phone' => $request->mobile,
    ]);

    return response()
        ->json([
            'message' => 'Enquiry submitted successfully',
            'data' => $enquiry
        ])
        ->header('Access-Control-Allow-Origin', 'https://nmims.asbtechnologies.com');
});


Route::post('/api/registrations', function (Request $request) {
    // Validate inputs
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:200',
        'email' => 'required|email|max:150',
        'mobile' => 'required|digits_between:10,15',   // coming as "mobile" from React
        'course' => 'required|string|max:150',         // coming as "course" from React
        'qualification' => 'required|string|max:150',
        'terms' => 'accepted',                        // validate checkbox
    ]);

    if ($validator->fails()) {
        return response()
            ->json(['errors' => $validator->errors()], 422)
            ->header('Access-Control-Allow-Origin', 'https://nmims.asbtechnologies.com');
    }

    // Save registration (map React keys -> DB keys)
    $registration = Registrations::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->mobile,   // store as phone in DB
        'program' => $request->course,   // store as program in DB
        'qualification' => $request->qualification,
    ]);

    return response()
        ->json([
            'message' => 'Registration submitted successfully',
            'data' => $registration
        ])
        ->header('Access-Control-Allow-Origin', 'https://nmims.asbtechnologies.com');
});