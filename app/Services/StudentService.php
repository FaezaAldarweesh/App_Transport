<?php

namespace App\Services;


use App\Models\Student;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class StudentService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all students 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Students(){
        try {
            $student = Student::all();
            return $student;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche students', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new student
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Student($data) {
        try {
            $student = new Student();
            $student->name = $data['name'];
            $student->father_phone = $data['father_phone'];
            $student->mather_phone = $data['mather_phone'];
            $student->longitude = $data['longitude'];
            $student->latitude = $data['latitude'];
            $student->class_room_id = $data['class_room_id'];
            $student->user_id = $data['user_id'];
            $student->status = 1;

            $student->save(); 
    
            return $student; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create student', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update student alraedy exist
     * @param  $data
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_student($data, $student_id){
        try {  
            $student = Student::find($student_id);
            if(!$student){
                throw new \Exception('student not found');
            }
            $student->name = $data['name'] ?? $student->name;
            $student->father_phone = $data['father_phone'] ?? $student->father_phone;
            $student->mather_phone = $data['mather_phone'] ?? $student->mather_phone;
            $student->longitude = $data['longitude'] ?? $student->longitude;
            $student->latitude = $data['latitude'] ?? $student->latitude;
            $student->class_room_id = $data['class_room_id'] ?? $student->class_room_id;
            $student->user_id = $data['user_id'] ?? $student->user_id;
            $student->status = $data['status'] ?? $student->status;

            $student->save(); 
            return $student;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view student', 400);}
    }
    //========================================================================================================================
    /**
     * method to show studen alraedy exist
     * @param  $studen_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Student($studen_id) {
        try {    
            $studen = Student::find($studen_id);
            if(!$studen){
                throw new \Exception('student not found');
            }
            return $studen;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update student', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete student alraedy exist
     * @param  $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_student($student_id)
    {
        try {  
            $student = Student::find($student_id);
            if(!$student){
                throw new \Exception('student not found');
            }

            $student->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting student', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete students
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_student()
    {
        try {  
            $students = Student::onlyTrashed()->get();
            return $students;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed student', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete student alraedy exist
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_student($student_id)
    {
        try {
            $student = Student::onlyTrashed()->find($student_id);
            if(!$student){
                throw new \Exception('student not found');
            }
            return $student->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore student', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on student that soft deleted before
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_student($student_id)
    {   
        try {
            $student = Student::onlyTrashed()->find($student_id);
            if(!$student){
                throw new \Exception('student not found');
            }
 
            return $student->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting student', 400);}
    }
    //========================================================================================================================

}
