<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ClassService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Resources\ClassResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\class_Request\Store_Class_Request;
use App\Http\Requests\class_Request\Update_Class_Request;

class ClassRoomController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $classservices;
    /**
     * construct to inject Class Services 
     * @param ClassService $classservices
     */
    public function __construct(ClassService $classservices)
    {
        //security middleware
        $this->middleware('security');
        $this->classservices = $classservices;
    }
    //===========================================================================================================================
    /**
     * method to view all classes
     * @return /Illuminate\Http\JsonResponse
     * ClassResources to customize the return responses.
     */
    public function index()
    {  
        $classes = $this->classservices->get_all_Classes();
        return $this->success_Response(ClassResources::collection($classes), "تمت عملية الوصول للشعب بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Class room
     * @param   Store_Class_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Class_Request $request)
    {
        $class = $this->classservices->create_Class($request->validated());
        return $this->success_Response(new ClassResources($class), "تمت عملية إضافة الشعبة بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show classes alraedy exist
     * @param  $classs_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($classs_id)
    {
        $classs = $this->classservices->view_Class($classs_id);

        // In case error messages are returned from the services section 
        if ($classs instanceof \Illuminate\Http\JsonResponse) {
            return $classs;
        }
            return $this->success_Response(new ClassResources($classs), "تمت عملية عرض الشعبة بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update class alraedy exist
     * @param  Update_Class_Request $request
     * @param  $classroom_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Class_Request $request, $classRoom_id)
    {
      //  dd($classRoom_id);
        $class = $this->classservices->update_Class($request->validated(), $classRoom_id);
        return $this->success_Response(new ClassResources($class), "تمت عملية التعديل على الشعبة بنجاح", 200);
    }    
    //===========================================================================================================================
    /**
     * method to soft delete class alraedy exist
     * @param  $class_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($class_id)
    {
        $class = $this->classservices->delete_class($class_id);

        // In case error messages are returned from the services section 
        if ($class instanceof \Illuminate\Http\JsonResponse) {
            return $class;
        }
            return $this->success_Response(null, "تمت عملية إضافة الشعبة للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted classes
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_class()
    {
        $classes = $this->classservices->all_trashed_class();
        return $this->success_Response(ClassResources::collection($classes), "تمت عملية الوصول لأرشيف الشعب بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted class alraedy exist
     * @param   $class_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($class_id)
    {
        $restore = $this->classservices->restore_class($class_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "تمت عملية استعادة الشعبة بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on class that soft deleted before
     * @param   $class_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($class_id)
    {
        $delete = $this->classservices->forceDelete_class($class_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف الشعبة بنجاح", 200);
    }
        
    //========================================================================================================================
}
