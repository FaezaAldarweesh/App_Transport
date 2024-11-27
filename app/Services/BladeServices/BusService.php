<?php

namespace App\Services\BladeServices;

use App\Models\Bus;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;

class BusService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all buses 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Bus(){
        try {
            $bus = Bus::all();
            return $bus;
        } catch (\Exception $e) {
            Log::error('Error fetching bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى الباصات');
        } 
    }
    //========================================================================================================================
    /**
     * method to store a new bus
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_bus($data) {
        try {
            $bus = new Bus(); 

            $bus->name = $data['name'];
            $bus->number_of_seats = $data['number_of_seats'];
            $bus->save();
    
            return $bus; 
        } catch (\Exception $e) {
            Log::error('Error creating bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إضافة باص جديد');
        }
    }    
    //========================================================================================================================
    /**
     * method to update bus alraedy exist
     * @param  $data
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_bus($data, $bus_id){
        try {  
            $bus = Bus::findOrFail($bus_id);
            $bus->name = $data['name'] ?? $bus->name;
            $bus->number_of_seats = $data['number_of_seats'] ?? $bus->number_of_seats;

            $bus->save(); 
            return $bus;

        } catch (\Exception $e) {
            Log::error('Error updating bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة التعديل على الباص');
        }
    }
    //========================================================================================================================
    /**
     * method to soft delete bus alraedy exist
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_bus($bus_id)
    {
        try {  
            $bus = Bus::find($bus_id);
            $bus->trips()->delete();
            $bus->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف الباص');
        }
    }
    //========================================================================================================================
    /**
     * method to return all soft delete bus
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_bus()
    {
        try {  
            return Bus::onlyTrashed()->get();
        } catch (\Exception $e) {
            Log::error('Error fetching trashed bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى أرشيف الباصات');
        }
    }
    //========================================================================================================================
    /**
     * method to restore soft delete bus alraedy exist
     * @param   $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_bus($bus_id)
    {
        try {
            $bus = Bus::onlyTrashed()->findOrFail($bus_id);
            $bus->trips()->restore();
            $bus->restore();
            return true;
        } catch (\Exception $e) {
            Log::error('Error restoring bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إستعادة الباص');
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on bus that soft deleted before
     * @param   $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_bus($bus_id)
    {   
        try {
            $bus = Bus::onlyTrashed()->findOrFail($bus_id);
            $bus->trips()->forceDelete();
            return $bus->forceDelete();
        } catch (\Exception $e) {
            Log::error('Error force deleting bus: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف أرشيف الباص');
        }
    }
    //========================================================================================================================

}
