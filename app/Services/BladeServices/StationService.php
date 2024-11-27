<?php

namespace App\Services\BladeServices;

use Illuminate\Support\Facades\Log;
use App\Models\Station;
use Illuminate\Support\Facades\Request;

class StationService {
    /**
     * method to view all stations 
     * @param   Request $request
     * @return /view
     */
    public function get_all_Stations(){
        try {
            $station = Station::all();
            return $station;
        } catch (\Exception $e) {
            Log::error('Error fetching station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى المحطة');
        }
    }
    //========================================================================================================================
    /**
     * method to store a new station
     * @param   $data
     * @return /view
     */
    public function create_Station($data) {
        try {
            $station = new Station();
            $station->name = $data['name'];
            $station->path_id = $data['path_id'];
            
            $station->save(); 
    
            return $station; 
        } catch (\Exception $e) {
            Log::error('Error creating station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إضافة محطة جديدة');
        }
    }    
    //========================================================================================================================
    /**
     * method to updstation alraedy exist
     * @param  $data
     * @param  $station_id
     * @return /view
     */
    public function update_Station($data, $station_id){
        try {  
            $station = Station::findOrFail($station_id);
            $station->name = $data['name'] ?? $station->name;
            $station->path_id = $data['path_id'] ?? $station->path_id;
            
            $station->save(); 
            return $station;

        } catch (\Exception $e) {
            Log::error('Error updating station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة التعديل على المحطة');
        }
    }
    //========================================================================================================================
    /**
     * method to soft delete station alraedy exist
     * @param  $station_id
     * @return /view
     */
    public function delete_station($station_id)
    {
        try {  
            $station = Station::findOrFail($station_id);
            $station->delete();
            return true;
        }catch (\Exception $e) {
            Log::error('Error Deleting station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف المحطة');
        }
    }
    //========================================================================================================================
    /**
     * method to return all soft delete stations
     * @return /view
     */
    public function all_trashed_station()
    {
        try {  
            return Station::onlyTrashed()->get();
        } catch (\Exception $e) {
            Log::error('Error fetching trashed station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى أرشيف المحطات');
        }
    }
    //========================================================================================================================
    /**
     * method to restore soft delete station alraedy exist
     * @param   $station_id
     * @return /view
     */
    public function restore_station($station_id)
    {
        try {
            $station = Station::onlyTrashed()->findOrFail($station_id);
            return $station->restore();
        }catch (\Exception $e) {
            Log::error('Error restoring station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إستعادة المحطة');
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on station that soft deleted before
     * @param   $station_id
     * @return /view
     */
    public function forceDelete_station($station_id)
    {   
        try {
            $station = Station::onlyTrashed()->findOrFail($station_id);
            return $station->forceDelete();
        } catch (\Exception $e) {
            Log::error('Error force deleting station: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف أرشيف المحطات');
        }
    }
    //========================================================================================================================

}
