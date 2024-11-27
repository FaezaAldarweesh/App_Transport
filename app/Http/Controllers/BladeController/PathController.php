<?php

namespace App\Http\Controllers\BladeController;

use App\Services\BladeServices\PathService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\Path_Request\Store_Path_Request;
use App\Http\Requests\Path_Request\Update_Path_Request;
use App\Models\Path;

class PathController extends Controller
{
    protected $pathservices;
    /**
     * construct to inject Path Services 
     * @param PathService $pathservices
     */
    public function __construct(PathService $pathservices)
    {
        //security middleware
        //$this->middleware('security');
        $this->pathservices = $pathservices;
    }
    //===========================================================================================================================
    /**
     * method to view all paths 
     * @return /view
     */
    public function index()
    {  
        $paths = $this->pathservices->get_all_Paths();
        return view('paths.view', compact('paths'));
    }
    //===========================================================================================================================
    /**
     * method header to path create page 
     */
    public function create(){
        return view('paths.create');
    }
    //===========================================================================================================================
    /**
     * method to store a new Patth
     * @param   Store_Path_Request $request
     * @return /view
     */
    public function store(Store_Path_Request $request)
    {
        $path = $this->pathservices->create_Path($request->validated());
        session()->flash('success', 'تمت عملية إضافة المسار بنجاح');
        return redirect()->route('path.index');
    }
    
    //===========================================================================================================================
    /**
    * method header path to edit page
    */
    public function edit($path_id){
        $path = Path::findOrFail($path_id);
        return view('paths.update' , compact('path'));
    }
    //===========================================================================================================================
    /**
     * method to update path alraedy exist
     * @param  Update_Path_Request $request
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Path_Request $request, $path_id)
    {
        $path = $this->pathservices->update_Path($request->validated(), $path_id);
        session()->flash('success', 'تمت عملية التعديل على المسار بنجاح');
        return redirect()->route('path.index');
    }
    //===========================================================================================================================
    /**
     * method to soft delete path alraedy exist
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($path_id)
    {
        $path = $this->pathservices->delete_path($path_id);
        session()->flash('success', 'تمت عملية إضافة المسار للأرشيف بنجاح');
        return redirect()->route('path.index');
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted paths
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_path()
    {
        $paths = $this->pathservices->all_trashed_path();
        return view('paths.trashed', compact('paths'));
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted path alraedy exist
     * @param   $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($path_id)
    {
        $restore = $this->pathservices->restore_path($path_id);
        session()->flash('success', 'تمت عملية استعادة المسار بنجاح');
        return redirect()->route('all_trashed_path');
    }
    //========================================================================================================================
    /**
     * method to force delete on path that soft deleted before
     * @param   $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($path_id)
    {
        $delete = $this->pathservices->forceDelete_path($path_id);
        session()->flash('success', 'تمت عملية حذف المسار بنجاح');
        return redirect()->route('all_trashed_path');
    }
        
    //========================================================================================================================
}
