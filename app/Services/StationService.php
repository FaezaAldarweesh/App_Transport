<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Station;
use Illuminate\Support\Facades\Request;

class StationService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all stations 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Stations(){
        try {
            $station = Station::all();
            return $station;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche stations', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new station
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an errorc
     */
    public function create_Station($data) {
        try {
            $station = new Station();
            $station->name = $data['name'];
            $station->location = $data['location'];
            $station->path_id = $data['path_id'];
            
            $station->save(); 
    
            return $station; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create station', 400);}
    }    
    //========================================================================================================================
    /**
     * method to updstation alraedy exist
     * @param  $data
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Station($data, $station_id){
        try {  
            $station = Station::find($station_id);
            if(!$station){
                throw new \Exception('station not found');
            }
            $station->name = $data['name'] ?? $station->name;
            $station->location = $data['location'] ?? $station->location;
            $station->path_id = $data['path_id'] ?? $station->path_id;
            
            $station->save(); 
            return $station;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view station', 400);}
    }
    //========================================================================================================================
    /**
     * method to show station alraedy exist
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Station($station_id) {
        try {    
            $station = Station::find($station_id);
            if(!$station){
                throw new \Exception('station not found');
            }
            return $station;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update station', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete station alraedy exist
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_station($station_id)
    {
        try {  
            $station = Station::find($station_id);
            if(!$station){
                throw new \Exception('station not found');
            }

            $station->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting station', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete stations
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_station()
    {
        try {  
            $stations = Station::onlyTrashed()->get();
            return $stations;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed station', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete station alraedy exist
     * @param   $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_station($station_id)
    {
        try {
            $station = Station::onlyTrashed()->find($station_id);
            if(!$station){
                throw new \Exception('station not found');
            }
            return $station->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore station', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on station that soft deleted before
     * @param   $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_station($station_id)
    {   
        try {
            $station = Station::onlyTrashed()->find($station_id);
            if(!$station){
                throw new \Exception('station not found');
            }
 
            return $station->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting station', 400);}
    }
    //========================================================================================================================

}
