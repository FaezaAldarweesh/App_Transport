<?php

namespace App\Services;


use App\Models\Grade;
use NotFoundHttpException;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Request;

class ClassService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all classes 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Classes(){
        try {
            $class = ClassRoom::all();
            return $class;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche classes', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new class
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an errorc
     */
    public function create_Class($data) {
        try {
            $class = new ClassRoom();
            $class->name = $data['name'];
            $class->grade_id = $data['grade_id'];
            
            $class->save(); 
    
            return $class; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create class', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update class alraedy exist
     * @param  $data
     * @param  $classRoom_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Class($data, $classRoom_id){
        try {  
            $classRoom = ClassRoom::findOrFail($classRoom_id);
            $classRoom->name = $data['name'] ?? $classRoom->name;
            $classRoom->grade_id = $data['grade_id'] ?? $classRoom->grade_id;
            
            $classRoom->save(); 
            return $classRoom;

        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view class', 400);}
    }
    //========================================================================================================================
    /**
     * method to show class alraedy exist
     * @param  $class_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Class($class_id) {
        try {    
            $class = ClassRoom::find($class_id);
            if(!$class){
                throw new \Exception('class not found');
            }
            return $class;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update class', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete class alraedy exist
     * @param  ClassRoom $classroom
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_class($class_id)
    {
        try {  
            $class = ClassRoom::find($class_id);
            if(!$class){
                throw new \Exception('class not found');
            }

            $class->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting class', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete classes
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_class()
    {
        try {  
            $classes = ClassRoom::onlyTrashed()->get();
            return $classes;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed class', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete class alraedy exist
     * @param   $class_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_class($class_id)
    {
        try {
            $class = ClassRoom::onlyTrashed()->find($class_id);
            if(!$class){
                throw new \Exception('class not found');
            }
            return $class->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore class', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on class that soft deleted before
     * @param   $class_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_class($class_id)
    {   
        try {
            $class = ClassRoom::onlyTrashed()->find($class_id);
            if(!$class){
                throw new \Exception('class not found');
            }
 
            return $class->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting class', 400);}
    }
    //========================================================================================================================

}
