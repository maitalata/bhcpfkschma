<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\LocalGovernment;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LocalGovernmentResource;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'LocalGovernments' => LocalGovernmentResource::collection(LocalGovernment::all()),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('fetchWards/{local_government}', function ($local_government_id) {
    $options = DB::table('wards')
        ->where('local_government_id', $local_government_id)
        ->get();

    return response()->json($options);
})->name('fetchWards');

Route::get('fetchCommunites/{ward_id}', function ($ward_id) {
    $options = DB::table('communities')
        ->where('ward_id', $ward_id)
        ->get();
    return response()->json($options);
})->name('fetchCommunites');

Route::get('fetchBeneficiaries/{ward_id}', function ($ward_id) {
    $options = DB::table('beneficiaries')
        ->where('ward_id', $ward_id)
        ->get();
    // $options = [];
    return response()->json($options);
})->name('fetchBeneficiaries');

Route::get('fetchFacilities/{ward_id}', function ($ward_id) {

    //select all wards in the local government of the selected ward
    $ward = DB::table('wards')
        ->where('id', $ward_id)
        ->first();

    $local_government_id = $ward->local_government_id;
    $wards = DB::table('wards')
        ->where('local_government_id', $local_government_id)
        ->get();
    
    // dd($wards);

    // create array of the wards in the local government
    $ward_ids = [];
    foreach ($wards as $ward) {
        array_push($ward_ids, $ward->id);
    }

    //select all health facilities in the wards
    $options = DB::table('health_facilities')
        ->whereIn('ward_id', $ward_ids)
        ->get();


    // $options = DB::table('health_facilities')
    //     ->where('ward_id', $ward_id)
    //     ->get();
    // $options = [];
    return response()->json($options);
})->name('fetchFacilities');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
