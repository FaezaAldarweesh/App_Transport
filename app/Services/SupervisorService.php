<?php

namespace App\Services;

use App\Models\Supervisor;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class SupervisorService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all Supervisors 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Supervisors(){
        try {
            $Supervisor = Supervisor::all();
            return $Supervisor;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche Supervisors', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new Supervisor
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Supervisor($data) {
        try {
            $Supervisor = new Supervisor();
            $Supervisor->name = $data['name'];
            $Supervisor->username = $data['username'];
            $Supervisor->password = $data['password'];
            $Supervisor->location = $data['location'];
            $Supervisor->phone = $data['phone'];
            
            $Supervisor->save(); 
    
            return $Supervisor; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create Supervisor', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update Supervisor alraedy exist
     * @param  $data
     * @param  $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Supervisor($data,$Supervisor_id){
        try {  
            $Supervisor = Supervisor::find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('Supervisor not found');
            }

            $Supervisor->name = $data['name'] ?? $Supervisor->name;
            $Supervisor->username = $data['username'] ?? $Supervisor->username;
            $Supervisor->password = $data['password'] ?? $Supervisor->password;  
            $Supervisor->location = $data['location'] ?? $Supervisor->location;  
            $Supervisor->phone = $data['phone'] ?? $Supervisor->phone;  

            $Supervisor->save();  
            return $Supervisor;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view supervisor', 400);}
    }
    //========================================================================================================================
    /**
     * method to show Supervisor alraedy exist
     * @param  $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Supervisor($Supervisor_id) {
        try {    
            $Supervisor = Supervisor::find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('Supervisor not found');
            }
            return $Supervisor;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update Supervisor', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete Supervisor alraedy exist
     * @param  Supervisor $Supervisor
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_Supervisor($Supervisor_id)
    {
        try {  
            $Supervisor = Supervisor::find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('Supervisor not found');
            }

            $Supervisor->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Supervisor', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Supervisors
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Supervisor()
    {
        try {  
            $Supervisors = Supervisor::onlyTrashed()->get();
            return $Supervisors;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed Supervisor', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Supervisor alraedy exist
     * @param   $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_Supervisor($Supervisor_id)
    {
        try {
            $Supervisor = Supervisor::onlyTrashed()->find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('Supervisor not found');
            }
            return $Supervisor->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore Supervisor', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on Supervisor that soft deleted before
     * @param   $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_Supervisor($Supervisor_id)
    {   
        try {
            $Supervisor = Supervisor::onlyTrashed()->find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('Supervisor not found');
            }

            return $Supervisor->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Supervisor', 400);}
    }
    //========================================================================================================================

}
