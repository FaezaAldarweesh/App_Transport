<?php

namespace App\Http\Controllers;

use App\Services\DriverService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\DriverResources;
use App\Http\Requests\Driver_Request\Store_Driver_Request;
use App\Http\Requests\Driver_Request\Update_Driver_Request;

class DriverController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $driverservices;
    /**
     * construct to inject driver Services 
     * @param DriverService $drivers
     */
    public function __construct(DriverService $driverservices)
    {
        //security middleware
        $this->middleware('security');
        $this->driverservices = $driverservices;
    }
    //===========================================================================================================================
    /**
     * method to view all drivers 
     * @return /Illuminate\Http\JsonResponse
     * driverResources to customize the return responses.
     */
    public function index()
    {  
        $drivers = $this->driverservices->get_all_Drivers();
        return $this->success_Response(DriverResources::collection($drivers),"drivers fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new driver
     * @param   Store_Driver_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Driver_Request $request)
    {
        $driver = $this->driverservices->create_Driver($request->validated());
        return $this->success_Response(new DriverResources($driver), "driver created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show drivers alraedy exist
     * @param  $drivers_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($drivers_id)
    {
        $drivers = $this->driverservices->view_Driver($drivers_id);

        // In case error messages are returned from the services section 
        if ($drivers instanceof \Illuminate\Http\JsonResponse) {
            return $drivers;
        }
            return $this->success_Response(new DriverResources($drivers), "drivers viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update driver alraedy exist
     * @param  Update_Driver_Request $request
     * @param  $driverroom_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Driver_Request $request, $driverRoom_id)
    {
        $driver = $this->driverservices->update_Driver($request->validated(), $driverRoom_id);

        // In case error messages are returned from the services section 
        if ($driver instanceof \Illuminate\Http\JsonResponse) {
            return $driver;
        }
            return $this->success_Response(new DriverResources($driver), "driver updated successfully", 200);
    }    
    //===========================================================================================================================
    /**
     * method to soft delete driver alraedy exist
     * @param  $driver_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($driver_id)
    {
        $driver = $this->driverservices->delete_driver($driver_id);

        // In case error messages are returned from the services section 
        if ($driver instanceof \Illuminate\Http\JsonResponse) {
            return $driver;
        }
            return $this->success_Response(null, "driver soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted drivers
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_driver()
    {
        $drivers = $this->driverservices->all_trashed_driver();
        return $this->success_Response(DriverResources::collection($drivers), "All trashed drivers fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted driver alraedy exist
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($driver_id)
    {
        $restore = $this->driverservices->restore_driver($driver_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "driver restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on driver that soft deleted before
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($driver_id)
    {
        $delete = $this->driverservices->forceDelete_driver($driver_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "driver force deleted successfully", 200);
    }
        
    //========================================================================================================================
}
