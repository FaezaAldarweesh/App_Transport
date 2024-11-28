<?php

namespace App\Http\Controllers\BladeController;

use App\Models\Path;
use App\Models\Station;
use App\Services\BladeServices\StationService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\Station_Request\Store_Station_Request;
use App\Http\Requests\Station_Request\Update_Station_Request;

class StationController extends Controller
{
    protected $stationservices;
    /**
     * construct to inject Station Services 
     * @param StationService $stationservices
     */
    public function __construct(StationService $stationservices)
    {
        //security middleware
        //$this->middleware('security');
        $this->stationservices = $stationservices;
    }
    //===========================================================================================================================
    /**
     * method to view all Stations 
     * @return /view
     */
    public function index()
    {  
        $stations = $this->stationservices->get_all_Stations();
        return view('stations.view', compact('stations'));
    }
    //===========================================================================================================================
    /**
     * method header to station create page 
     */
    public function create(){
        $paths = Path::all();
        return view('stations.create', compact('paths'));
    }
    //===========================================================================================================================
    /**
     * method to store a new Station
     * @param   Store_Station_Request $request
     * @return /view
     */
    public function store(Store_Station_Request $request)
    {
        $station = $this->stationservices->create_Station($request->validated());
        session()->flash('success', 'تمت عملية إضافة المحطة بنجاح');
        return redirect()->route('station.index');
    }
    
    //===========================================================================================================================
    /**
    * method header station to edit page
    */
    public function edit($station_id){
        $station = Station::findOrFail($station_id);
        $paths = Path::all();
        return view('stations.update' , compact('station','paths'));
    }
    //===========================================================================================================================
    /**
     * method to update station alraedy exist
     * @param  Update_Station_Request $request
     * @param  $stationroom_id
     * @return /view
     */
    public function update(Update_Station_Request $request, $stationRoom_id)
    {
        $station = $this->stationservices->update_Station($request->validated(), $stationRoom_id);
        session()->flash('success', 'تمت عملية التعديل على المحطة بنجاح');
        return redirect()->route('station.index');
    }    
    //===========================================================================================================================
    /**
     * method to soft delete station alraedy exist
     * @param  $station_id
     * @return /view
     */
    public function destroy($station_id)
    {
        $station = $this->stationservices->delete_station($station_id);
        session()->flash('success', 'تمت عملية إضافة المحطة للأرشيف بنجاح');
        return redirect()->route('station.index');
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted stationes
     * @return /view
     */
    public function all_trashed_station()
    {
        $stations = $this->stationservices->all_trashed_station();
        return view('stations.trashed', compact('stations'));
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted station alraedy exist
     * @param   $station_id
     * @return /view
     */
    public function restore($station_id)
    {
        $restore = $this->stationservices->restore_station($station_id);
        session()->flash('success', 'تمت عملية استعادة المحطة بنجاح');
        return redirect()->route('all_trashed_station');
    }
    //========================================================================================================================
    /**
     * method to force delete on station that soft deleted before
     * @param   $station_id
     * @return /view
     */
    public function forceDelete($station_id)
    {
        $delete = $this->stationservices->forceDelete_station($station_id);
        session()->flash('success', 'تمت عملية حذف المحطة بنجاح');
        return redirect()->route('all_trashed_station');
    }
        
    //========================================================================================================================
}
