<?php

namespace App\Http\Controllers\BladeController;

use App\Models\Bus;
use App\Services\BladeServices\BusService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\Bus_Request\Store_Bus_Request;
use App\Http\Requests\Bus_Request\Update_Bus_Request;

class BusController extends Controller
{
    protected $busservices;
    /**
     * construct to inject bus Services 
     * @param BusService $busservices
     */
    public function __construct(BusService $busservices)
    {
        //security middleware
        //$this->middleware('security');
        $this->busservices = $busservices;
    }
    //===========================================================================================================================
    /**
     * method to view all buses
     * @return /view
     */
    public function index()
    {  
        $buses = $this->busservices->get_all_Bus();
        return view('buses.view', compact('buses'));
    }
    //===========================================================================================================================
    /**
     * method header to driver create page 
     */
    public function create(){
        return view('buses.create');
    }
    //===========================================================================================================================
    /**
     * method to store a new bus
     * @param   Store_Bus_Request $request
     * @return /view
     */
    public function store(Store_Bus_Request $request)
    {
        $buses = $this->busservices->create_bus($request->validated());
        session()->flash('success', 'تمت عملية إضافة الباص بنجاح');
        return redirect()->route('bus.index');
    }
    
    //===========================================================================================================================
    /**
    * method header bus to edit page
    */
    public function edit($bus_id){
        $bus = Bus::findOrFail($bus_id);
        return view('buses.update' , compact('bus'));
    }
    //===========================================================================================================================
    /**
     * method to update bus alraedy exist
     * @param  Update_Bus_Request $request
     * @param  $bus_id
     * @return /view
     */
    public function update(Update_Bus_Request $request, $bus_id)
    {
        $bus = $this->busservices->update_bus($request->validated(), $bus_id);
        session()->flash('success', 'تمت عملية التعديل على الباص بنجاح');
        return redirect()->route('bus.index');
    }
    //===========================================================================================================================
    /**
     * method to soft delete bus alraedy exist
     * @param  $bus_id
     * @return /view
     */
    public function destroy($bus_id)
    {
        $bus = $this->busservices->delete_bus($bus_id);
        session()->flash('success', 'تمت عملية إضافة الباص للأرشيف بنجاح');
        return redirect()->route('bus.index');
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted bus
     * @return /view
     */
    public function all_trashed_bus()
    {
        $buses = $this->busservices->all_trashed_bus();
        return view('buses.trashed', compact('buses'));
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted bus alraedy exist
     * @param   $bus_id
     * @return /view
     */
    public function restore($bus_id)
    {
        $restore = $this->busservices->restore_bus($bus_id);
        session()->flash('success', 'تمت عملية استعادة الباص بنجاح');
        return redirect()->route('all_trashed_bus');
    }
    //========================================================================================================================
    /**
     * method to force delete on bus that soft deleted before
     * @param   $bus_id
     * @return /view
     */
    public function forceDelete($bus_id)
    {
        $delete = $this->busservices->forceDelete_bus($bus_id);
        session()->flash('success', 'تمت عملية حذف الباص بنجاح');
        return redirect()->route('all_trashed_bus');
    }
        
    //========================================================================================================================
}
