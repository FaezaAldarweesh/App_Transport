<?php

namespace App\Http\Controllers;

use App\Services\SupervisorService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\SupervisorResources;
use App\Http\Requests\Supervisor_Rqeuests\Store_Supervisor_Request;
use App\Http\Requests\Supervisor_Rqeuests\Update_Supervisor_Request;

class SupervisorController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $supervisorservices;
    /**
     * construct to inject supervisor Services 
     * @param SupervisorService $supervisorservices
     */
    public function __construct(SupervisorService $supervisorservices)
    {
        //security middleware
        $this->middleware('security');
        $this->supervisorservices = $supervisorservices;
    }
    //===========================================================================================================================
    /**
     * method to view all supervisors with a filter on role
     * @return /Illuminate\Http\JsonResponse
     * UserResources to customize the return responses.
     */
    public function index()
    {  
        $supervisors = $this->supervisorservices->get_all_Supervisors();
        return $this->success_Response(SupervisorResources::collection($supervisors), "All supervisors fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new supervisor
     * @param   Store_Supervisor_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Supervisor_Request $request)
    {
        $supervisor = $this->supervisorservices->create_Supervisor($request->validated());
        return $this->success_Response(new SupervisorResources($supervisor), "supervisor created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show supervisor alraedy exist
     * @param  $supervisor_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($supervisor_id)
    {
        $supervisor = $this->supervisorservices->view_supervisor($supervisor_id);

        // In case error messages are returned from the services section 
        if ($supervisor instanceof \Illuminate\Http\JsonResponse) {
            return $supervisor;
        }
            return $this->success_Response(new SupervisorResources($supervisor), "supervisor viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update supervisor alraedy exist
     * @param  Update_Supervisor_Request $request
     * @param  $supervisor_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Supervisor_Request $request, $supervisor_id)
    {
        $supervisor = $this->supervisorservices->update_Supervisor($request->validated(), $supervisor_id);
        
        // In case error messages are returned from the services section 
        if ($supervisor instanceof \Illuminate\Http\JsonResponse) {
            return $supervisor;
        }
            return $this->success_Response(new SupervisorResources($supervisor), "supervisor updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete supervisor alraedy exist
     * @param  $supervisor_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($supervisor_id)
    {
        $supervisor = $this->supervisorservices->delete_supervisor($supervisor_id);

        // In case error messages are returned from the services section 
        if ($supervisor instanceof \Illuminate\Http\JsonResponse) {
            return $supervisor;
        }
            return $this->success_Response(null, "supervisor soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted supervisors
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_supervisor()
    {
        $supervisors = $this->supervisorservices->all_trashed_supervisor();
        return $this->success_Response(SupervisorResources::collection($supervisors), "All trashed supervisors fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted supervisor alraedy exist
     * @param   $supervisor_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($supervisor_id)
    {
        $delete = $this->supervisorservices->restore_supervisor($supervisor_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "supervisor restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on supervisor that soft deleted before
     * @param   $supervisor_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($supervisor_id)
    {
        $delete = $this->supervisorservices->forceDelete_supervisor($supervisor_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "supervisor force deleted successfully", 200);
    }
        
    //========================================================================================================================
}
