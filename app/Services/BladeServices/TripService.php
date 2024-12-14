<?php

namespace App\Services\BladeServices;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\BusTrip;
use App\Models\Student;
use App\Models\DriverTrip;
use App\Models\Supervisor;
use App\Models\StudentTrip;
use App\Models\SupervisorTrip;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\AllStudentsByTripTrait;

class TripService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait,AllStudentsByTripTrait;
    /**
     * method to view all Trips 
     * @return /view
     */
    public function get_all_Trips(){
        try {
            $Trips = Trip::all();
            return $Trips;
        } catch (\Exception $e) {
            Log::error('Error fetching Trip: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    //========================================================================================================================
    /**
     * method to store a new Trip
     * @param   $data
     * @return /view
     */
    public function create_Trip($data)
    {
        try {
            // التحقق من وجود رحلة توصيل بنفس المسار والباص
            if ($data['name'] === 'delivery') {
                $existingTripByPath = Trip::where('name', 'delivery')
                                          ->where('type', 'go')
                                          ->where('path_id', $data['path_id'])
                                          ->exists();
                if ($existingTripByPath) {
                    return redirect()->back()->withErrors(['error' => 'هذا المسار مرتبط برحلة توصيل أخرى مسبقاً']);
                }
    
                $existingTripByBus = Trip::where('name', 'delivery')
                                         ->where('type', 'go')
                                         ->where('bus_id', $data['bus_id'])
                                         ->exists();
                if ($existingTripByBus) {
                    return redirect()->back()->withErrors(['error' => 'هذا الباص مرتبط برحلة توصيل أخرى مسبقاً']);
                }
    
                $existingTrips = Trip::where('name', 'delivery')
                                     ->where('type', 'go')
                                     ->pluck('id');
    
                // التحقق من الطالب
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTrips)
                                              ->where('student_id', $data['students'])
                                              ->exists();
                if ($existingStudent) {
                    return redirect()->back()->withErrors(['error' => 'تم إضافة هذا الطالب إلى رحلة توصيل أخرى مسبقاً']);
                }
    
                // التحقق من المشرف
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTrips)
                                                    ->where('supervisor_id', $data['supervisors'])
                                                    ->exists();
                if ($existingSupervisor) {
                    return redirect()->back()->withErrors(['error' => 'تم إضافة هذا المشرف إلى رحلة توصيل أخرى مسبقاً']);
                }
    
                // التحقق من السائق
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTrips)
                                            ->where('driver_id', $data['drivers'])
                                            ->exists();
                if ($existingDriver) {
                    return redirect()->back()->withErrors(['error' => 'تم إضافة هذا السائق إلى رحلة توصيل أخرى مسبقاً']);
                }
            }
    
            // إنشاء رحلتي ذهاب وإياب
            $tripTypes = ['go', 'back'];
            foreach ($tripTypes as $type) {
                $trip = new Trip();
                $trip->name = $data['name'];
                $trip->type = $type;
                $trip->path_id = $data['path_id'];
                $trip->bus_id = $data['bus_id'];
                $trip->save();
    
                $trip->students()->attach($data['students']);
                $trip->supervisors()->attach($data['supervisors']);
                $trip->drivers()->attach($data['drivers']);
                $trip->save();
            }
    
            return redirect()->back()->with('success', 'تم إنشاء الرحلتين بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating trip: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الرحلة: ' . $e->getMessage()]);
        }
    } 
    //========================================================================================================================
    /**
     * method to show Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Trip($Trip_id) {
        try {    
            $Trip = Trip::findOrFail($Trip_id);
            $Trip->with('students','supervisors','drivers')->get();
            return $Trip;
        }  catch (\Exception $e) {
            Log::error('Error view trip: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    //========================================================================================================================
    /**
     * method to update Trip alraedy exist
     * @param  $data
     * @param  $Trip_id
     * @return /view
     */
    public function update_Trip($data,$id)
    {
        try {
            // جلب الرحلة الحالية
            $trip = Trip::findOrFail($id);
            
            // جلب رحلة الإياب المرتبطة (إذا كانت موجودة)
            $returnTrip = Trip::where('name', $trip->name)
                              ->where('type', $trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $trip->path_id)
                              ->where('bus_id', $trip->bus_id)
                              ->first();
    
            // التحقق من وجود رحلة بنفس المسار والباص
            if ($data['name'] === 'delivery') {
                $existingTripByPath = Trip::where('name', 'delivery')
                                          ->where('type', 'go')
                                          ->where('path_id', $data['path_id'])
                                          ->where('id', '!=', $id)
                                          ->exists();

                if ($existingTripByPath) {
                    return redirect()->back()->withErrors(['error' => 'هذا المسار مرتبط برحلة توصيل أخرى مسبقاً']);
                }
    
                $existingTripByBus = Trip::where('name', 'delivery')
                                         ->where('type', 'go')
                                         ->where('bus_id', $data['bus_id'])
                                         ->where('id', '!=', $id)
                                         ->exists();
                if ($existingTripByBus) {
                    return redirect()->back()->withErrors(['error' => 'هذا الباص مرتبط برحلة توصيل أخرى مسبقاً']);
                }
    
                $existingTrips = Trip::where('name', 'delivery')
                                     ->where('type', 'go')
                                     ->where('id', '!=', $id)
                                     ->pluck('id');
    
                // التحقق من الطالب
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTrips)
                                              ->where('student_id', $data['students'])
                                              ->exists();
                if ($existingStudent) {
                    return redirect()->back()->withErrors(['error' => 'تم إضافة هذا الطالب إلى رحلة توصيل أخرى مسبقاً']);
                }
    
                // التحقق من المشرف
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTrips)
                                                    ->where('supervisor_id', $data['supervisors'])
                                                    ->exists();
                if ($existingSupervisor) {
                    return redirect()->back()->withErrors(['error' => 'تم إضافة هذا المشرف إلى رحلة توصيل أخرى مسبقاً']);
                }
    
                // التحقق من السائق
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTrips)
                                            ->where('driver_id', $data['drivers'])
                                            ->exists();
                if ($existingDriver) {
                    return redirect()->back()->withErrors(['error' => 'تم إضافة هذا السائق إلى رحلة توصيل أخرى مسبقاً']);
                }
            }
    
            // تحديث معلومات رحلة الذهاب
            $trip->update([
                'name' => $data['name'],
                'path_id' => $data['path_id'],
                'bus_id' => $data['bus_id']
            ]);
    
            // تحديث علاقات رحلة الذهاب
            $trip->students()->sync($data['students']);
            $trip->supervisors()->sync($data['supervisors']);
            $trip->drivers()->sync($data['drivers']);
    
            // تحديث رحلة الإياب إذا كانت موجودة
            if ($returnTrip) {
                $returnTrip->update([
                    'name' => $data['name'],
                    'type' => $returnTrip->type,
                    'path_id' => $data['path_id'],
                    'bus_id' => $data['bus_id']
                ]);
    
                // تحديث علاقات رحلة الإياب
                $returnTrip->students()->sync($data['students']);
                $returnTrip->supervisors()->sync($data['supervisors']);
                $returnTrip->drivers()->sync($data['drivers']);
            }
    
            return redirect()->back()->with('success', 'تم تعديل الرحلة بنجاح مع رحلة الإياب');
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تعديل الرحلة: ' . $e->getMessage()]);
        }
    }    
    //========================================================================================================================
    /**
     * method to soft delete Trip alraedy exist
     * @param  $Trip_id
     * @return /view
     */
    public function delete_Trip($Trip_id)
    {
        try {  
            $Trip = Trip::findOrFail($Trip_id);

            $returnTrip = Trip::where('name', $Trip->name)
                              ->where('type', $Trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $Trip->path_id)
                              ->where('bus_id', $Trip->bus_id)
                              ->first();
            
            $Trip->students()->updateExistingPivot($Trip->students->pluck('id'), ['deleted_at' => now()]);     
            $Trip->supervisors()->updateExistingPivot($Trip->supervisors->pluck('id'), ['deleted_at' => now()]);     
            $Trip->drivers()->updateExistingPivot($Trip->drivers->pluck('id'), ['deleted_at' => now()]);  
            
            $returnTrip->students()->updateExistingPivot($returnTrip->students->pluck('id'), ['deleted_at' => now()]);     
            $returnTrip->supervisors()->updateExistingPivot($returnTrip->supervisors->pluck('id'), ['deleted_at' => now()]);     
            $returnTrip->drivers()->updateExistingPivot($returnTrip->drivers->pluck('id'), ['deleted_at' => now()]);  

            $Trip->delete();
            $returnTrip->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting trip: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Trip
     * @return /view
     */
    public function all_trashed_Trip()
    {
        try {  
            return Trip::onlyTrashed()->get();
        } catch (\Exception $e) {
            Log::error('Error Deleting trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف الرحلة');
        }
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Trip alraedy exist
     * @param   $Trip_id
     * @return /view
     */
    public function restore_Trip($Trip_id)
    {
        try {
            $Trip = Trip::onlyTrashed()->findOrFail($Trip_id);

            $returnTrip = Trip::where('name', $Trip->name)
                              ->where('type', $Trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $Trip->path_id)
                              ->where('bus_id', $Trip->bus_id)
                              ->first();

            $Trip->restore();
            $returnTrip->restore();

            $Trip->students()->withTrashed()->updateExistingPivot($Trip->students->pluck('id'), ['deleted_at' => null]);
            $Trip->supervisors()->withTrashed()->updateExistingPivot($Trip->supervisors->pluck('id'), ['deleted_at' => null]);
            $Trip->drivers()->withTrashed()->updateExistingPivot($Trip->drivers->pluck('id'), ['deleted_at' => null]);

            $returnTrip->students()->withTrashed()->updateExistingPivot($returnTrip->students->pluck('id'), ['deleted_at' => null]);
            $returnTrip->supervisors()->withTrashed()->updateExistingPivot($returnTrip->supervisors->pluck('id'), ['deleted_at' => null]);
            $returnTrip->drivers()->withTrashed()->updateExistingPivot($returnTrip->drivers->pluck('id'), ['deleted_at' => null]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting trip: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on Trip that soft deleted before
     * @param   $Trip_id
     * @return /view
     */
    public function forceDelete_Trip($Trip_id)
    {   
        try {
            $Trip = Trip::onlyTrashed()->findOrFail($Trip_id);

            $returnTrip = Trip::where('name', $Trip->name)
                              ->where('type', $Trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $Trip->path_id)
                              ->where('bus_id', $Trip->bus_id)
                              ->first();

            $Trip->forceDelete();
            $returnTrip->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف الرحلة');
        }
    }
    //========================================================================================================================


    

    

    // //========================================================================================================================
    // /**
    //  * method to bind  trip with bus , student , supervisor , driver
    //  * @param  $data
    //  * @return /Illuminate\Http\JsonResponse if have an error
    //  */
    // public function bind($data) {
    //     try { 

    //         $Trip = Trip::find($data['trip']);

    //         if(!$Trip){
    //             throw new \Exception('Trip not found');
    //         }


    //         if($Trip == 'delivery' ){
    //             if(count($data['buses']) > 1){
    //                 throw new \Exception('رحلة التوصيل يجب أن يكون لديها باص واحد فقط');
    //             }
    //         }




    //         if ($data['name'] == 'delivery' && $data['type'] == 'go') {
    //             $existingTrips = Trip::where('name', 'delivery')
    //                                  ->where('type', 'go')
    //                                  ->pluck('id'); 
            
    //             $existingBusLink = BusTrip::whereIn('trip_id', $existingTrips)
    //                                       ->where('bus_id', $data['bus_id']) 
    //                                       ->exists();
            
    //                 if ($existingBusLink) {
    //                     throw new \Exception('هذا الباص مرتبط مع رحلة توصيل  من نمط ذهاب سابقاً');
    //                 }            

    //         }else if ($data['name'] == 'delivery' && $data['type'] == 'back') {
    //             $existingTrips = Trip::where('name', 'delivery')
    //                                  ->where('type', 'back')
    //                                  ->pluck('id'); 
            
    //             $existingBusLink = BusTrip::whereIn('trip_id', $existingTrips)
    //                                       ->where('bus_id', $data['bus_id']) 
    //                                       ->exists();
            
    //                 if ($existingBusLink) {
    //                     throw new \Exception('هذا الباص مرتبط مع رحلة توصيل  من نمط إياب سابقاً');
    //                 }
    //         }




    //         if ($data['name'] == 'delivery' && $data['type'] == 'go') {
    //             $existingTrips = Trip::where('name', 'delivery')
    //                                  ->where('type', 'go')
    //                                  ->pluck('id'); 
            
    //             $existingBusLink = BusTrip::whereIn('trip_id', $existingTrips)
    //                                       ->where('bus_id', $data['bus_id']) 
    //                                       ->exists();
            
    //                 if ($existingBusLink) {
    //                     throw new \Exception('هذا الباص مرتبط مع رحلة توصيل  من نمط ذهاب سابقاً');
    //                 }            

    //         }else if ($data['name'] == 'delivery' && $data['type'] == 'back') {
    //             $existingTrips = Trip::where('name', 'delivery')
    //                                  ->where('type', 'back')
    //                                  ->pluck('id'); 
            
    //             $existingBusLink = BusTrip::whereIn('trip_id', $existingTrips)
    //                                       ->where('bus_id', $data['bus_id']) 
    //                                       ->exists();
            
    //                 if ($existingBusLink) {
    //                     throw new \Exception('هذا الباص مرتبط مع رحلة توصيل  من نمط إياب سابقاً');
    //                 }
    //         }


    //         return $Trip;
    //     } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update Trip', 400);}
    // }











    // //========================================================================================================================
    // public function list_of_students($trip_id, $latitude, $longitude)
    // {
    //     try {

    //     $students = $this->All_Students_By_Trip($trip_id);

    //     $students = $students->sortBy(function ($student) use ($latitude, $longitude) {
    //         return $student->distanceFrom($latitude, $longitude);
    //     });

    //     return $students;
    
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetching students', 400);}
    // }
    // //========================================================================================================================
    // public function update_trip_status($data,$trip_id)
    // {
    //     try {
    //         $Trip = Trip::find($trip_id);
    //         if(!$Trip){
    //             throw new \Exception('Trip not found');
    //         }
    //         $Trip->status = $data['status'] ?? $Trip->status;
    //         $Trip->save(); 

    //         return $Trip;

    //     }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetching Trip', 400);}
    // }
    // //========================================================================================================================
    // public function All_students_belong_to_specific_trip($trip_id)
    // {
    //     try {
    //         $Trip = Trip::find($trip_id);
    //         if(!$Trip){
    //             throw new \Exception('Trip not found');
    //         }

    //         $students = $this->All_Students_By_Trip($trip_id);

    //         return $students;

    //     }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetching Trip', 400);}
    // }
    // //========================================================================================================================

}
