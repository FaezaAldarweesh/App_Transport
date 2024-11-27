<?php

namespace App\Services\BladeServices;

use App\Models\Supervisor;
use Illuminate\Support\Facades\Log;

class SupervisorService {

    /**
     * method to view all Supervisors 
     * @return /view
     */
    public function get_all_Supervisors(){
        try {
            $Supervisor = Supervisor::all();
            return $Supervisor;
        } catch (\Exception $e) {
            Log::error('Error fetching supervisors: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى المشرف');
        }
    }
    //========================================================================================================================
    /**
     * method to store a new Supervisor
     * @param   $data
     * @return /view
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
        } catch (\Exception $e) {
            Log::error('Error creating supervisor: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إضافة مشرف جديد');
        }
    }    
    //========================================================================================================================
    /**
     * method to update Supervisor alraedy exist
     * @param  $data
     * @param  $Supervisor_id
     * @return /view
     */
    public function update_Supervisor($data,$Supervisor_id){
        try {  
            $Supervisor = Supervisor::findOrFail($Supervisor_id);
            $Supervisor->name = $data['name'] ?? $Supervisor->name;
            $Supervisor->username = $data['username'] ?? $Supervisor->username;
            $Supervisor->password = $data['password'] ?? $Supervisor->password;  
            $Supervisor->location = $data['location'] ?? $Supervisor->location;  
            $Supervisor->phone = $data['phone'] ?? $Supervisor->phone;  

            $Supervisor->save();  
            return $Supervisor;
            
        } catch (\Exception $e) {
            Log::error('Error updating supervisor: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة التعديل على المشرف');
        }
    }
    //========================================================================================================================
    /**
     * method to soft delete Supervisor alraedy exist
     * @param  Supervisor $Supervisor
     * @return /view
     */
    public function delete_Supervisor($Supervisor_id)
    {
        try {  
            $Supervisor = Supervisor::findOrFail($Supervisor_id);
            $Supervisor->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting supervisor: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف المشرف');
        }
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Supervisors
     * @return /view
     */
    public function all_trashed_Supervisor()
    {
        try {  
            return Supervisor::onlyTrashed()->get();
        } catch (\Exception $e) {
            Log::error('Error fetching trashed supervisor: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى أرشيف المشرفين');
        }
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Supervisor alraedy exist
     * @param   $Supervisor_id
     * @return /view
     */
    public function restore_Supervisor($Supervisor_id)
    {
        try {
            $Supervisor = Supervisor::onlyTrashed()->findOrFail($Supervisor_id);
            $Supervisor->restore();
            return $Supervisor;
        } catch (\Exception $e) {
            Log::error('Error restoring supervisor: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إستعادة المشرف');
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on Supervisor that soft deleted before
     * @param   $Supervisor_id
     * @return /view
     */
    public function forceDelete_Supervisor($Supervisor_id)
    {   
        try {
            $Supervisor = Supervisor::onlyTrashed()->findOrFail($Supervisor_id);
            $Supervisor->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error force deleting supervisor: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف أرشيف المشرف');
        }
    }
    //========================================================================================================================

}
