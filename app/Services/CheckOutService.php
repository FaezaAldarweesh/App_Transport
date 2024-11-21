<?php

namespace App\Services;


use App\Models\CheckOut;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class CheckOutService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all CheckOuts 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_CheckOuts(){
        try {
            $CheckOut = CheckOut::all();
            return $CheckOut;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche CheckOuts', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new CheckOut
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an errorc
     */
    public function create_CheckOut($data) {
        try {
            $CheckOut = new CheckOut();
            $CheckOut->student_id = $data['student_id'];
            $CheckOut->trip_id = $data['trip_id'];
            $CheckOut->check_out = $data['check_out'];
            $CheckOut->note = $data['note'];
            
            $CheckOut->save(); 
    
            return $CheckOut; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create CheckOut', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update CheckOut alraedy exist
     * @param  $data
     * @param  $CheckOut_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_CheckOut($data, $CheckOut_id){
        try {  
            $CheckOut = CheckOut::findOrFail($CheckOut_id);
            $CheckOut->student_id = $data['student_id'] ?? $CheckOut->student_id;
            $CheckOut->trip_id = $data['trip_id'] ?? $CheckOut->trip_id;
            $CheckOut->check_out = $data['check_out'] ?? $CheckOut->check_out;
            $CheckOut->note = $data['note'] ?? $CheckOut->note;
            
            $CheckOut->save(); 
            return $CheckOut;

        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view CheckOut', 400);}
    }
    //========================================================================================================================
    /**
     * method to show CheckOut alraedy exist
     * @param  $CheckOut_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_CheckOut($CheckOut_id) {
        try {    
            $CheckOut = CheckOut::find($CheckOut_id);
            if(!$CheckOut){
                throw new \Exception('CheckOut not found');
            }
            return $CheckOut;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update CheckOut', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete CheckOut alraedy exist
     * @param  CheckOut $CheckOut
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_CheckOut($CheckOut_id)
    {
        try {  
            $CheckOut = CheckOut::find($CheckOut_id);
            if(!$CheckOut){
                throw new \Exception('CheckOut not found');
            }

            $CheckOut->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting CheckOut', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete CheckOuts
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_CheckOut()
    {
        try {  
            $CheckOut = CheckOut::onlyTrashed()->get();
            return $CheckOut;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed CheckOut', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete CheckOut alraedy exist
     * @param   $CheckOut_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_CheckOut($CheckOut_id)
    {
        try {
            $CheckOut = CheckOut::onlyTrashed()->find($CheckOut_id);
            if(!$CheckOut){
                throw new \Exception('CheckOut not found');
            }
            return $CheckOut->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore CheckOut', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on CheckOut that soft deleted before
     * @param   $CheckOut_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_CheckOut($CheckOut_id)
    {   
        try {
            $CheckOut = CheckOut::onlyTrashed()->find($CheckOut_id);
            if(!$CheckOut){
                throw new \Exception('CheckOut not found');
            }
 
            return $CheckOut->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting CheckOut', 400);}
    }
    //========================================================================================================================

}
