<?php

namespace App\Services;

use App\Models\Grade;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class GradeService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all grades 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Grades($name){
        try {
            $grade = Grade::filter($name)->get();
            return $grade;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى الصفوف', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new grade
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Grade($data) {
        try {
            $grade = new Grade();
            $grade->name = $data['name'];
            
            $grade->save(); 
    
            return $grade; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إضافة صف جديد', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update grade alraedy exist
     * @param  $data
     * @param  Grade $grade
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_grade($data,Grade $grade){
        try {  
            $grade->name = $data['name'] ?? $grade->name;

            $grade->save(); 
            return $grade;

        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة التعديل على الصف', 400);}
    }
    //========================================================================================================================
    /**
     * method to show grade alraedy exist
     * @param  $grade_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Grade($grade_id) {
        try {    
            $grade = Grade::find($grade_id);
            if(!$grade){
                throw new \Exception('الصف المطلوب غير موجود');
            }
            return $grade;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة عرض الصف', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete grade alraedy exist
     * @param  $grade_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_grade($grade_id)
    {
        try {  
            $grade = Grade::find($grade_id);
            if(!$grade){
                throw new \Exception('الصف المطلوب غير موجود');
            }

            $grade->classRooms()->delete();
            $grade->delete();

            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف الصف', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete grades
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_grade()
    {
        try {  
            $grades = Grade::onlyTrashed()->get();
            return $grades;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى أرشيف الصفوف', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete grade alraedy exist
     * @param   $grade_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_grade($grade_id)
    {
        try {
            $grade = Grade::onlyTrashed()->find($grade_id);
            if(!$grade){
                throw new \Exception('الصف المطلوب غير موجود');
            }

            $grade->classRooms()->restore();
            return $grade->restore();

        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إستعادة الصف', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on grade that soft deleted before
     * @param   $grade_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_grade($grade_id)
    {   
        try {
            $grade = Grade::onlyTrashed()->find($grade_id);
            if(!$grade){
                throw new \Exception('الصف المطلوب غير موجود');
            }

            $grade->classRooms()->forceDelete();
            return $grade->forceDelete();

        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف أرشيف الصفوف', 400);}
    }
    //========================================================================================================================

}
