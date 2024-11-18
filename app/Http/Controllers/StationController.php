<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Services\StationService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\StationResources;
use App\Http\Requests\Station_Request\Store_Station_Request;
use App\Http\Requests\Station_Request\Update_Station_Request;

class StationController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $stationservices;
    /**
     * construct to inject Station Services 
     * @param StationService $stationservices
     */
    public function __construct(StationService $stationservices)
    {
        //security middleware
        $this->middleware('security');
        $this->stationservices = $stationservices;
    }
    //===========================================================================================================================
    /**
     * method to view all Stations 
     * @return /Illuminate\Http\JsonResponse
     * StationResources to customize the return responses.
     */
    public function index()
    {  
        $Stationes = $this->stationservices->get_all_Stations();
        return $this->success_Response(StationResources::collection($Stationes), "All Stationes fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Station
     * @param   Store_Station_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Station_Request $request)
    {
        $station = $this->stationservices->create_Station($request->validated());
        return $this->success_Response(new StationResources($station), "Station created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show stations alraedy exist
     * @param  $stations_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($stations_id)
    {
        $stations = $this->stationservices->view_Station($stations_id);

        // In case error messages are returned from the services section 
        if ($stations instanceof \Illuminate\Http\JsonResponse) {
            return $stations;
        }
            return $this->success_Response(new StationResources($stations), "stations viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update station alraedy exist
     * @param  Update_Station_Request $request
     * @param  $stationroom_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Station_Request $request, $stationRoom_id)
    {
        $station = $this->stationservices->update_Station($request->validated(), $stationRoom_id);

        // In case error messages are returned from the services section 
        if ($station instanceof \Illuminate\Http\JsonResponse) {
            return $station;
        }
            return $this->success_Response(new StationResources($station), "station updated successfully", 200);
    }    
    //===========================================================================================================================
    /**
     * method to soft delete station alraedy exist
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($station_id)
    {
        $station = $this->stationservices->delete_station($station_id);

        // In case error messages are returned from the services section 
        if ($station instanceof \Illuminate\Http\JsonResponse) {
            return $station;
        }
            return $this->success_Response(null, "station soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted stationes
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_station()
    {
        $stationes = $this->stationservices->all_trashed_station();
        return $this->success_Response(StationResources::collection($stationes), "All trashed stationes fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted station alraedy exist
     * @param   $station_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($station_id)
    {
        $restore = $this->stationservices->restore_station($station_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "station restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on station that soft deleted before
     * @param   $station_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($station_id)
    {
        $delete = $this->stationservices->forceDelete_station($station_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "station force deleted successfully", 200);
    }
        
    //========================================================================================================================
}
