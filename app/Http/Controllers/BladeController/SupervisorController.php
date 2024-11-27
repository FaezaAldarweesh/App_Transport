<?php

namespace App\Http\Controllers\BladeController;

use App\Services\BladeServices\SupervisorService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\Supervisor_Rqeuests\Store_Supervisor_Request;
use App\Http\Requests\Supervisor_Rqeuests\Update_Supervisor_Request;
use App\Models\Supervisor;

class SupervisorController extends Controller
{
    protected $supervisorservices;
    /**
     * construct to inject supervisor Services 
     * @param SupervisorService $supervisorservices
     */
    public function __construct(SupervisorService $supervisorservices)
    {
        //security middleware
        //$this->middleware('security');
        $this->supervisorservices = $supervisorservices;
    }
    //===========================================================================================================================
    /**
     * method to view all supervisors 
     * @return /view
     * UserResources to customize the return responses.
     */
    public function index()
    {  
        $supervisors = $this->supervisorservices->get_all_Supervisors();
        return view('supervisors.view', compact('supervisors'));

    }
    //===========================================================================================================================
    /**
     * method header to supervisor create page 
     */
    public function create(){
        $supervisors = Supervisor::all();
        return view('supervisors.create', compact('supervisors'));
    }
    //===========================================================================================================================
    /**
     * method to store a new supervisor
     * @param   Store_Supervisor_Request $request
     * @return /view
     */
    public function store(Store_Supervisor_Request $request)
    {
        $supervisor = $this->supervisorservices->create_Supervisor($request->validated());
        session()->flash('success', 'تمت عملية إضافة المشرف بنجاح');
        return redirect()->route('supervisor.index');
    }
    //===========================================================================================================================
    /**
    * method header user to edit page
    */
    public function edit($supervisor_id){
        $supervisor = Supervisor::find($supervisor_id);
        return view('supervisors.update' , compact('supervisor'));
    }
    //===========================================================================================================================
    /**
     * method to update supervisor alraedy exist
     * @param  Update_Supervisor_Request $request
     * @param  $supervisor_id
     * @return /view
     */
    public function update(Update_Supervisor_Request $request, $supervisor_id)
    {
        $supervisor = $this->supervisorservices->update_Supervisor($request->validated(), $supervisor_id);
        session()->flash('success', 'تمت عملية التعديل على المشرف بنجاح');
        return redirect()->route('supervisor.index');
    }
    //===========================================================================================================================
    /**
     * method to soft delete supervisor alraedy exist
     * @param  $supervisor_id
     * @return /view
     */
    public function destroy($supervisor_id)
    {
        $supervisor = $this->supervisorservices->delete_supervisor($supervisor_id);
        session()->flash('success', 'تمت عملية إضافة المشرف للأرشيف بنجاح');
        return redirect()->route('supervisor.index');
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted supervisors
     * @return /view 
     */
    public function all_trashed_supervisor()
    {
        $supervisors = $this->supervisorservices->all_trashed_supervisor();
        return view('supervisors.trashed', compact('students'));
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted supervisor alraedy exist
     * @param   $supervisor_id
     * @return /view
     */
    public function restore($supervisor_id)
    {
        $delete = $this->supervisorservices->restore_supervisor($supervisor_id);
        session()->flash('success', 'تمت عملية استعادة المشرف بنجاح');
        return redirect()->route('all_trashed_supervisor');
    }
    //========================================================================================================================
    /**
     * method to force delete on supervisor that soft deleted before
     * @param   $supervisor_id
     * @return /view
     */
    public function forceDelete($supervisor_id)
    {
        $delete = $this->supervisorservices->forceDelete_supervisor($supervisor_id);
        session()->flash('success', 'تمت عملية حذف المشرف بنجاح');
        return redirect()->route('all_trashed_supervisor');
    }
        
    //========================================================================================================================
}
