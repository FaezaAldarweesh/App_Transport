<?php

namespace App\Services;


use App\Models\Path;
use NotFoundHttpException;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class PathService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all paths 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Paths(){
        try {
            $path = Path::all();
            return $path;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche paths', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new path
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Path($data) {
        try {
            $path = new Path();
            $path->name = $data['name'];
            
            $path->save(); 
    
            return $path; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create path', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update path alraedy exist
     * @param  $data
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_path($data, $path_id){
        try {  
            $path = Path::find($path_id);
            if(!$path){
                throw new \Exception('path not found');
            }
            $path->name = $data['name'] ?? $path->name;

            $path->save(); 
            return $path;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view path', 400);}
    }
    //========================================================================================================================
    /**
     * method to show path alraedy exist
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Path($path_id) {
        try {    
            $path = Path::find($path_id);
            if(!$path){
                throw new \Exception('path not found');
            }
            return $path;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update path', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete path alraedy exist
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_path($path_id)
    {
        try {  
            $path = Path::find($path_id);
            if(!$path){
                throw new \Exception('path not found');
            }

            $path->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting path', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete paths
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_path()
    {
        try {  
            $paths = Path::onlyTrashed()->get();
            return $paths;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed path', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete path alraedy exist
     * @param   $path_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_path($path_id)
    {
        try {
            $path = Path::onlyTrashed()->find($path_id);
            if(!$path){
                throw new \Exception('path not found');
            }
            return $path->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore path', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on path that soft deleted before
     * @param   $path_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_path($path_id)
    {   
        try {
            $path = Path::onlyTrashed()->find($path_id);
            if(!$path){
                throw new \Exception('path not found');
            }
 
            return $path->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting path', 400);}
    }
    //========================================================================================================================

}
