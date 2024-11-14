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

Route::inertia('/finished', 'Finished')->name('finished');

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

Route::get('getTotalNumberOfAllHouseholds', function () {
    $total = DB::table('households')
        ->count();
    return response()->json($total);
})->name('getTotalNumberOfAllHouseholds');

// submit household and return the id of the household added [name, community_id]
Route::post('submitHousehold', function () {
    $data = request()->validate([
        'name' => 'required',
        'community_id' => 'required',
    ]);

        $household = DB::table('households')
            ->insertGetId([
                'name' => $data['name'],
                'community_id' => $data['community_id'],
        ]);

    return response()->json($household);
})->name('submitHousehold');

// update beneficiary to reflect the household id, community id and facility id
Route::post('updateBeneficiary', function () {
    $data = request()->validate([
        'id' => 'required',
        'household_id' => 'required',
        'community_id' => 'required',
        'facility_id' => 'nullable',
    ]);

    $beneficiary = DB::table('beneficiaries')
        ->where('id', $data['id'])
        ->update([
            'household_id' => $data['household_id'],
            'community_id' => $data['community_id'],
            'health_facility_id' => $data['facility_id'] ?? null,
        ]);

    return response()->json($beneficiary);
})->name('updateBeneficiary');


Route::post('submitVerification', function () {
    $data = request()->validate([
        'local_government' => 'required',
        'ward' => 'required',
        'community' => 'required',
        'selected_beneficiary' => 'required',
        'sighted_beneficiary' => 'required',
        'reason_for_not_sighting_beneficiary' => 'nullable',
        'new_location' => 'nullable',
        'beneficiary_has_nin' => 'nullable',
        'nin' => 'nullable',
        'tracking_id' => 'nullable',
        'psr_number' => 'nullable',
        'kschma_number' => 'nullable',
        'facility_id' => 'nullable',
        'trekkable' => 'nullable',
        'transport_fare' => 'nullable',
        'want_to_change_facility' => 'nullable',
        'reason_for_facility_change' => 'nullable',
        'ever_accessed_service_in_assigned_phc' => 'nullable',
        'educated_about_bhcpf' => 'nullable',
    ]);

    $verification = DB::table('verifications')
        ->insert([
            'beneficiary_id' => $data['selected_beneficiary'],
            'are_you_able_to_sight_the_beneficiary' => $data['sighted_beneficiary'] ?? null,
            'reason_for_not_sighting_beneficiary' => $data['reason_for_not_sighting_beneficiary'] ?? null,
            'new_location_of_beneficiary' => $data['new_location'] ?? null,
            'did_the_beneficiary_has_nin' => $data['beneficiary_has_nin'] ?? null,
            'is_the_distance_to_assigned_phc_trekkable' => $data['trekkable'] ?? null,
            'transport_fare_to_facility' => $data['transport_fare'] ?? null,
            'does_the_beneficiary_want_to_change_their_assigned_facility' => $data['want_to_change_facility'] ?? null,
            'what_is_the_primary_reason_for_the_change_of_facility' => $data['reason_for_facility_change'] ?? null,
            'beneficiary_ever_accessed_healthcare_services_in_assigned_phc' => $data['ever_accessed_service_in_assigned_phc'] ?? null,
            'did_you_educate_the_beneficiary_about_bhcpf_during_the_exercise' => $data['educated_about_bhcpf'] ?? null,
        ]);

    return response()->json($verification);
    // return to_route('finished');
    // return redirect()
    // ->route('finished');
})->name('submitVerification');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
