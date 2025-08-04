<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePrescriptionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PrescriptionController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(Auth::user()->isAdmin);
        $this->authorize('viewAny', Prescription::class);
        $prescriptions = Prescription::paginate(10);
        return ApiResponse::success($prescriptions, 'Prescriptions retrieved successfully.', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrescriptionRequest $request)
    {
        //dd(Auth::user()->isAdmin);
        $this->authorize('create', Prescription::class);
        $validatedData = $request->validated();

        $prescription = Prescription::create([
            'patient_id' => Auth::user()->id,
            'prescription_image' => $validatedData['prescription_image']->store('prescriptions', 'public'),
            'notes' => $validatedData['notes'] ?? null,
            'prescription_date' =>  now(),
            'status' => 'pending',
        ]);

        return ApiResponse::success($prescription, 'Prescription created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);
        return ApiResponse::success($prescription, 'Prescription retrieved successfully.', 200);
    }


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Prescription $prescription)
{
    $this->authorize('update',  $prescription);

    $request->validate([
        'prescription_image' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'status' => 'sometimes|in:pending,approved,rejected',
        'notes' => 'sometimes',
    ]);

    if ($request->hasFile('prescription_image')) {
        if ($prescription->prescription_image) {
            Storage::disk('public')->delete($prescription->prescription_image);
        }
        $prescription->prescription_image = $request->file('prescription_image')->store('prescriptions', 'public');
    }
    $prescription->status = $request->status;
    $prescription->notes = $request->notes;
    $prescription->save();

    return ApiResponse::success($prescription, 'Prescription updated successfully.', 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription)
    {
        $this->authorize('delete',  $prescription);
        if($prescription->prescription_image) {Storage::disk('public')->delete($prescription->prescription_image);
    }
        $prescription->delete();

        return ApiResponse::success(null, 'Prescription deleted successfully.', 200);
    }
}
