<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Models\driver;
use Illuminate\Support\Facades\Request;

class DriverService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all drivers 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Drivers(){
        try {
            $driver = Driver::all();
            return $driver;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche drivers', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new driver
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an errorc
     */
    public function create_Driver($data) {
        try {
            $driver = new Driver();
            $driver->name = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];
            $driver->phone = $data['phone'];
            $driver->location = $data['location'];
            
            $driver->save(); 
    
            return $driver; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create driver', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update driver alraedy exist
     * @param  $data
     * @param  $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Driver($data, $driver_id){
        try {  
            $driver = Driver::find($driver_id);
            if(!$driver){
                throw new \Exception('driver not found');
            }
            if (isset($data['first_name']) && isset($data['last_name'])) {
                $driver->name = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];
            }
            $driver->phone = $data['phone'] ?? $driver->phone;
            $driver->location = $data['location'] ?? $driver->location;
            
            $driver->save(); 
            return $driver;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view driver', 400);}
    }
    //========================================================================================================================
    /**
     * method to show driver alraedy exist
     * @param  $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Driver($driver_id) {
        try {    
            $driver = Driver::find($driver_id);
            if(!$driver){
                throw new \Exception('driver not found');
            }
            return $driver;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update driver', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete driver alraedy exist
     * @param  $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_driver($driver_id)
    {
        try {  
            $driver = Driver::find($driver_id);
            if(!$driver){
                throw new \Exception('driver not found');
            }

            $driver->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting driver', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete drivers
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_driver()
    {
        try {  
            $drivers = Driver::onlyTrashed()->get();
            return $drivers;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed driver', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete station alraedy exist
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_driver($driver_id)
    {
        try {
            $driver = Driver::onlyTrashed()->find($driver_id);
            if(!$driver){
                throw new \Exception('driver not found');
            }
            return $driver->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore driver', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on driver that soft deleted before
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_driver($driver_id)
    {   
        try {
            $driver = Driver::onlyTrashed()->find($driver_id);
            if(!$driver){
                throw new \Exception('driver not found');
            }
 
            return $driver->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting driver', 400);}
    }
    //========================================================================================================================

}
