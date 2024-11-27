<?php

namespace App\Services\BladeServices;


use App\Models\Path;
use Illuminate\Support\Facades\Log;

class PathService {
    /**
     * method to view all paths 
     * @return /view
     */
    public function get_all_Paths(){
        try {
            $path = Path::all();
            return $path;
        } catch (\Exception $e) {
            Log::error('Error fetching path: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى المسارات');
        }
    }
    //========================================================================================================================
    /**
     * method to store a new path
     * @param   $data
     * @return /view
     */
    public function create_Path($data) {
        try {
            $path = new Path();
            $path->name = $data['name'];
            
            $path->save(); 
            return $path;

        } catch (\Exception $e) {
            Log::error('Error creating path: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إضافة مسار جديد');
        }
    }    
    //========================================================================================================================
    /**
     * method to update path alraedy exist
     * @param  $data
     * @param  $path_id
     * @return /view
     */
    public function update_path($data, $path_id){
        try {  
            $path = Path::findOrFail($path_id);
            $path->name = $data['name'] ?? $path->name;

            $path->save(); 
            return $path;

        }catch (\Exception $e) {
            Log::error('Error updating path: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة التعديل على المسار');
        }
    }
    //========================================================================================================================
    /**
     * method to soft delete path alraedy exist
     * @param  $path_id
     * @return /view
     */
    public function delete_path($path_id)
    {
        try {  
            $path = Path::findOrFail($path_id);
            $path->stations()->delete();
            $path->trips()->delete();
            $path->delete();
            return true;
        }catch (\Exception $e) {
            Log::error('Error Deleting path: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف المسار');
        }
    }
    //========================================================================================================================
    /**
     * method to return all soft delete paths
     * @return /view
     */
    public function all_trashed_path()
    {
        try {  
            $paths = Path::onlyTrashed()->get();
            return $paths;
        }catch (\Exception $e) {
            Log::error('Error fetching trashed path: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى أرشيف المسارات');
        }
    }
    //========================================================================================================================
    /**
     * method to restore soft delete path alraedy exist
     * @param   $path_id
     * @return /view
     */
    public function restore_path($path_id)
    {
        try {
            $path = Path::onlyTrashed()->findOrFail($path_id);
            $path->stations()->restore();
            $path->trips()->restore();
            return $path->restore();
        } catch (\Exception $e) {
            Log::error('Error restoring path: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إستعادة المسار');
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on path that soft deleted before
     * @param   $path_id
     * @return /view
     */
    public function forceDelete_path($path_id)
    {   
        try {
            $path = Path::onlyTrashed()->findOrFail($path_id);
            $path->stations()->forceDelete();
            $path->trips()->forceDelete();
            return $path->forceDelete();
        }catch (\Exception $e) {
            Log::error('Error force deleting path: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    //========================================================================================================================

}
