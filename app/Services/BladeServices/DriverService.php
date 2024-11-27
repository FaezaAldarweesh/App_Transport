<?php

namespace App\Services\BladeServices;

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
     * @return /view
     */
    public function get_all_Drivers(){
        try {
            $driver = Driver::all();
            return $driver;
        } catch (\Exception $e) {
            Log::error('Error fetching driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى السائقين');
        } 
    }
    //========================================================================================================================
    /**
     * method to store a new driver
     * @param   $data
     * @return /view
     */
    public function create_Driver($data) {
        try {
            $driver = new Driver();
            $driver->name =  $data['name'];
            $driver->phone = $data['phone'];
            $driver->location = $data['location'];
            
            $driver->save(); 
    
            return $driver; 
        } catch (\Exception $e) {
            Log::error('Error creating driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إضافة سائق جديد');
        }
    }    
    //========================================================================================================================
    /**
     * method to update driver alraedy exist
     * @param  $data
     * @param  $driver_id
     * @return /view
     */
    public function update_Driver($data, $driver_id){
        try {  
            $driver = Driver::findOrFail($driver_id);
            $driver->name = $data['name'];
            $driver->phone = $data['phone'] ?? $driver->phone;
            $driver->location = $data['location'] ?? $driver->location;
            
            $driver->save(); 
            return $driver;

        } catch (\Exception $e) {
            Log::error('Error updating driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة التعديل على السائق');
        }
    }
    //========================================================================================================================
    /**
     * method to soft delete driver alraedy exist
     * @param  $driver_id
     * @return /view
     */
    public function delete_driver($driver_id)
    {
        try {  
            $driver = Driver::findOrFail($driver_id);
            $driver->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف السائق');
        }
    }
    //========================================================================================================================
    /**
     * method to return all soft delete drivers
     * @return /view
     */
    public function all_trashed_driver()
    {
        try {  
            return Driver::onlyTrashed()->get();
        }catch (\Exception $e) {
            Log::error('Error fetching trashed driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى أرشيف السائقين');
        }
    }
    //========================================================================================================================
    /**
     * method to restore soft delete station alraedy exist
     * @param   $driver_id
     * @return /view
     */
    public function restore_driver($driver_id)
    {
        try {
            $driver = Driver::onlyTrashed()->findOrFail($driver_id);
            return $driver->restore();
        } catch (\Exception $e) {
            Log::error('Error restoring driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إستعادة السائق');
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on driver that soft deleted before
     * @param   $driver_id
     * @return /view
     */
    public function forceDelete_driver($driver_id)
    {   
        try {
            $driver = Driver::onlyTrashed()->findOrFail($driver_id);
            return $driver->forceDelete();
        } catch (\Exception $e) {
            Log::error('Error force deleting driver: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف أرشيف السائق');
        }
    }
    //========================================================================================================================

}
