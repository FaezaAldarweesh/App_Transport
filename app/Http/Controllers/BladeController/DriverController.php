<?php

namespace App\Http\Controllers\BladeController;

use App\Models\Driver;
use App\Services\BladeServices\DriverService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\Driver_Request\Store_Driver_Request;
use App\Http\Requests\Driver_Request\Update_Driver_Request;

class DriverController extends Controller
{
    protected $driverservices;
    /**
     * construct to inject driver Services 
     * @param DriverService $drivers
     */
    public function __construct(DriverService $driverservices)
    {
        //security middleware
        //$this->middleware('security');
        $this->driverservices = $driverservices;
    }
    //===========================================================================================================================
    /**
     * method to view all drivers 
     * @return /view
     */
    public function index()
    {  
        $drivers = $this->driverservices->get_all_Drivers();
        return view('drivers.view', compact('drivers'));
    }
    //===========================================================================================================================
    /**
     * method header to driver create page 
     */
    public function create(){
        return view('drivers.create');
    }
    //===========================================================================================================================
    /**
     * method to store a new driver
     * @param   Store_Driver_Request $request
     * @return /view
     */
    public function store(Store_Driver_Request $request)
    {
        $driver = $this->driverservices->create_Driver($request->validated());
        session()->flash('success', 'تمت عملية إضافة السائق بنجاح');
        return redirect()->route('driver.index');
    }
    
    //===========================================================================================================================
    /**
    * method header driver to edit page
    */
    public function edit($driver_id){
        $driver = Driver::find($driver_id);
        return view('drivers.update' , compact('driver'));
    }
    //===========================================================================================================================
    /**
     * method to update driver alraedy exist
     * @param  Update_Driver_Request $request
     * @param  $driverroom_id
     * @return /view
     */
    public function update(Update_Driver_Request $request, $driverRoom_id)
    {
        $driver = $this->driverservices->update_Driver($request->validated(), $driverRoom_id);
        session()->flash('success', 'تمت عملية التعديل على السائق بنجاح');
        return redirect()->route('driver.index');
    }    
    //===========================================================================================================================
    /**
     * method to soft delete driver alraedy exist
     * @param  $driver_id
     * @return /view
     */
    public function destroy($driver_id)
    {
        $driver = $this->driverservices->delete_driver($driver_id);
        session()->flash('success', 'تمت عملية إضافة السائق للأرشيف بنجاح');
        return redirect()->route('driver.index');
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted drivers
     * @return /view 
     */
    public function all_trashed_driver()
    {
        $drivers = $this->driverservices->all_trashed_driver();
        return view('drivers.trashed', compact('drivers'));
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted driver alraedy exist
     * @param   $driver_id
     * @return /view
     */
    public function restore($driver_id)
    {
        $restore = $this->driverservices->restore_driver($driver_id);
        session()->flash('success', 'تمت عملية استعادة السائق بنجاح');
        return redirect()->route('all_trashed_driver');
    }
    //========================================================================================================================
    /**
     * method to force delete on driver that soft deleted before
     * @param   $driver_id
     * @return /view
     */
    public function forceDelete($driver_id)
    {
        $delete = $this->driverservices->forceDelete_driver($driver_id);
        session()->flash('success', 'تمت عملية حذف السائق بنجاح');
        return redirect()->route('all_trashed_driver');
    }
        
    //========================================================================================================================
}
