<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Services\GradeService;
use App\Http\Resources\GradeResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Grade_Request\Store_Grade_Request;
use App\Http\Requests\Grade_Request\Update_Grade_Request;

class GradeController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $gradeservices;
    /**
     * construct to inject Grade Services 
     * @param GradeService $gradeservices
     */
    public function __construct(GradeService $gradeservices)
    {
        //security middleware
        $this->middleware('security');
        $this->gradeservices = $gradeservices;
    }
    //===========================================================================================================================
    /**
     * method to view all grades with a filter on name
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse
     * GradeResources to customize the return responses.
     */
    public function index(Request $request)
    {  
        $grades = $this->gradeservices->get_all_Grades($request->input('name'));
        return $this->success_Response(GradeResources::collection($grades), "All grades fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Grade
     * @param   Store_Grade_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Grade_Request $request)
    {
        $grade = $this->gradeservices->create_Grade($request->validated());
        return $this->success_Response(new GradeResources($grade), "Grade created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show grade alraedy exist
     * @param  $grade_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($grade_id)
    {
        $grade = $this->gradeservices->view_grade($grade_id);

        // In case error messages are returned from the services section 
        if ($grade instanceof \Illuminate\Http\JsonResponse) {
            return $grade;
        }
            return $this->success_Response(new GradeResources($grade), "grade viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update grade alraedy exist
     * @param  Update_Grade_Request $request
     * @param  Grade $grade
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Grade_Request $request, Grade $grade)
    {
        $grade = $this->gradeservices->update_Grade($request->validated(), $grade);
        return $this->success_Response(new GradeResources($grade), "Grade updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete grade alraedy exist
     * @param  $grade_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($grade_id)
    {
        $grade = $this->gradeservices->delete_grade($grade_id);

        // In case error messages are returned from the services section 
        if ($grade instanceof \Illuminate\Http\JsonResponse) {
            return $grade;
        }
            return $this->success_Response(null, "grade soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted grades
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_grade()
    {
        $grades = $this->gradeservices->all_trashed_grade();
        return $this->success_Response(GradeResources::collection($grades), "All trashed grades fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted grade alraedy exist
     * @param   $grade_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($grade_id)
    {
        $restore = $this->gradeservices->restore_grade($grade_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "grade restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on grade that soft deleted before
     * @param   $grade_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($grade_id)
    {
        $delete = $this->gradeservices->forceDelete_grade($grade_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "grade force deleted successfully", 200);
    }
        
    //========================================================================================================================
}
