<?php

namespace App\Services;


use App\Models\Bus;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;

class TripService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all Trips 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Trips(){
        try {
            $Trips = Trip::with('buses',)->get();
            return $Trips;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche Trips', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new Trip
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Trip($data) {
        try {
            $Trip = new Trip(); 
            $Trip->type = $data['type'];
            $Trip->path_id = $data['path_id'];
            $Trip->status = $data['status'];
            $Trip->save();
            
            foreach ($data['buses'] as $bus) {
                $bus = Bus::findOrFail($bus['id']);
                $Trip->buses()->attach($bus->id);
            }
            
            $Trip->save(); 

            return $Trip; 
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create TRip', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update Trip alraedy exist
     * @param  $data
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Trip($data, $Trip_id){
        try {  
            $Trip = Trip::find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }

            $Trip->type = $data['type'] ?? $Trip->type;
            $Trip->path_id = $data['path_id'] ?? $Trip->path_id;
            $Trip->status = $data['status'] ?? $Trip->status;
            $Trip->buses()->sync(array_column($data['buses'], 'id'));

            $Trip->save(); 
            return $Trip;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to show Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Trip($Trip_id) {
        try {    
            $Trip = Trip::find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }

            $Trip->load('buses');

            return $Trip;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_Trip($Trip_id)
    {
        try {  
            $Trip = Trip::find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }

            $Trip->buses()->updateExistingPivot($Trip->buses->pluck('id'), ['deleted_at' => now()]);     

            $Trip->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Trip
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Trip()
    {
        try {  
            $Trip = Trip::onlyTrashed()->get();
            return $Trip;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Trip alraedy exist
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_Trip($Trip_id)
    {
        try {
            $Trip = Trip::onlyTrashed()->find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }
            $Trip->restore();
            $Trip->buses()->withTrashed()->updateExistingPivot($Trip->buses->pluck('id'), ['deleted_at' => null]);

            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore Trip', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on Trip that soft deleted before
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_Trip($Trip_id)
    {   
        try {
            $Trip = Trip::onlyTrashed()->find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }
 
            return $Trip->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Trip', 400);}
    }
    //========================================================================================================================

}