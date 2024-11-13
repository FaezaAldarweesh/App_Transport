<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Resources\TripResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Trip_Request\Store_Trip_Request;
use App\Http\Requests\Trip_Request\Update_Trip_Request;

class TripController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $Tripservices;
    /**
     * construct to inject Trip Services 
     * @param TripService $Tripservices
     */
    public function __construct(TripService $Tripservices)
    {
        //security middleware
        $this->middleware('security');
        $this->Tripservices = $Tripservices;
    }
    //===========================================================================================================================
    /**
     * method to view all Trips with a filter on role
     * @return /Illuminate\Http\JsonResponse
     * UserResources to customize the return responses.
     */
    public function index()
    {  
        $Trips = $this->Tripservices->get_all_Trips();
        return $this->success_Response(TripResources::collection($Trips), "All Trips fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Trip
     * @param   Store_Trip_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Trip_Request $request)
    {
        $Trip = $this->Tripservices->create_Trip($request->validated());
        return $this->success_Response(new TripResources($Trip), "Trip created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($Trip_id)
    {
        $Trip = $this->Tripservices->view_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(new TripResources($Trip), "Trip viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update Trip alraedy exist
     * @param  Update_Trip_Request $request
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Trip_Request $request, $Trip_id)
    {
        $Trip = $this->Tripservices->update_Trip($request->validated(), $Trip_id);
        
        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(new TripResources($Trip), "Trip updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($Trip_id)
    {
        $Trip = $this->Tripservices->delete_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(null, "Trip soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted Trips
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Trip()
    {
        $Trips = $this->Tripservices->all_trashed_Trip();
        return $this->success_Response(TripResources::collection($Trips), "All trashed Trips fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted Trip alraedy exist
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($Trip_id)
    {
        $delete = $this->Tripservices->restore_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "Trip restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on Trip that soft deleted before
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($Trip_id)
    {
        $delete = $this->Tripservices->forceDelete_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "Trip force deleted successfully", 200);
    }
        
    //========================================================================================================================
}