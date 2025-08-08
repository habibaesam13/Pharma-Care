<?php

namespace App\Http\Controllers\Api;

use App\Models\RequestService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\StoreRequestServiceRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class RequestServiceController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function index()
{
    $this->authorize('viewAny', RequestService::class);

    $requests = RequestService::with(['user', 'nurse', 'service'])
        ->orderBy('id', 'desc')
        ->cursorPaginate(10); 

    return self::success($requests, 'All request services retrieved');
}


    public function store(StoreRequestServiceRequest $request)
    {
        $this->authorize('create', RequestService::class);

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $requestService = RequestService::create($data);

        return self::created($requestService, 'Request service created successfully');
    }

    public function show(RequestService $requestService)
    {
        $this->authorize('view', $requestService);

        return self::success($requestService, 'Request service details');
    }

    public function update(StoreRequestServiceRequest $request, RequestService $requestService)
    {
        $this->authorize('update', $requestService);

        $requestService->update($request->validated());

        return self::success($requestService, 'Request service updated successfully');
    }

    public function destroy(RequestService $requestService)
    {
        $this->authorize('delete', $requestService);

        $requestService->delete();

        return self::deleted('Request service deleted successfully');
    }
}
