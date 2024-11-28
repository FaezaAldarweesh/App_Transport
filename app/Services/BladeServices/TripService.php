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
            $Trips = Trip::with('buses')->get();
            return $Trips;
        } catch (\Exception $e) {
            Log::error('Error fetching Trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة الوصول إلى الرحلات');
        }
    }
    //========================================================================================================================
    /**
     * method to store a new Trip
     * @param   $data
     * @return /view
     */
    public function create_Trip($data) {
        try {

            if ($data['name'] == 'delivery' && $data['type'] == 'go') {
                $existingTrip = Trip::where('name', 'delivery')
                                    ->where('type', 'go')
                                    ->where('path_id', $data['path_id'])
                                    ->exists();
        
                if ($existingTrip) {
                    throw new \Exception('هذا المسار مرتبط برحلة توصيل أخرى مسبقاً');
                }

            } 

            //---------------------------------------------------------------------------------------------


            if ($data['name'] == 'delivery' && $data['type'] == 'go') {
                $existingTrip = Trip::where('name', 'delivery')
                                    ->where('type', 'go')
                                    ->where('bus_id', $data['bus_id'])
                                    ->exists();
        
                if ($existingTrip) {
                    throw new \Exception('هذا الباص مرتبط برحلة توصيل أخرى مسبقاً');
                }

            }
            
            //---------------------------------------------------------------------------------------------

            
            if ($data['name'] == 'delivery' && $data['type'] == 'go') {
                $existingTrips = Trip::where('name', 'delivery')
                                        ->where('type', 'go')
                                        ->pluck('id'); 
            
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTrips)
                                            ->where('student_id', $data['students']) 
                                            ->exists();
            
                    if ($existingStudent) {
                        throw new \Exception('تم إضافة هذا الطالب إلى رحلة توصيل أخرى مسبقاً');
                    }            
            }


            //---------------------------------------------------------------------------------------------

            
            if ($data['name'] == 'delivery' && $data['type'] == 'go') {
                $existingTrips = Trip::where('name', 'delivery')
                                        ->where('type', 'go')
                                        ->pluck('id'); 
            
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTrips)
                                            ->where('supervisor_id', $data['supervisors']) 
                                            ->exists();
            
                    if ($existingSupervisor) {
                        throw new \Exception('تم إضافة هذا المشرف إلى رحلة توصيل أخرى مسبقاً');
                    }            
            }

            
            //---------------------------------------------------------------------------------------------


            if ($data['name'] == 'delivery' && $data['type'] == 'go') {
                $existingTrips = Trip::where('name', 'delivery')
                                        ->where('type', 'go')
                                        ->pluck('id'); 
            
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTrips)
                                            ->where('driver_id', $data['drivers']) 
                                            ->exists();
            
                    if ($existingDriver) {
                        throw new \Exception('تم إضافة هذا السائق إلى رحلة توصيل أخرى مسبقاً');
                    }            
            }

            $Trip = new Trip();
            $Trip->name = $data['name'];
            $Trip->type = $data['type'];
            $Trip->path_id = $data['path_id'];
            $Trip->bus_id = $data['bus_id'];
            $Trip->status = $data['status'];
            $Trip->save();

            foreach ($data['students'] as $student) {
                $student = Student::findOrFail($student['id']);
                $Trip->students()->attach($student->id);
            }
            
            foreach ($data['supervisors'] as $supervisor) {
                $supervisor = Supervisor::findOrFail($supervisor['id']);
                $Trip->supervisors()->attach($supervisor->id);
            }

            foreach ($data['drivers'] as $driver) {
                $driver = Driver::findOrFail($driver['id']);
                $Trip->drivers()->attach($driver->id);
            }
            $Trip->save();

            return $Trip; 
        } catch (\Exception $e) {
            Log::error('Error creating trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة إضافة رحلة جديدة');
        }
    }    
    //========================================================================================================================
    /**
     * method to update Trip alraedy exist
     * @param  $data
     * @param  $Trip_id
     * @return /view
     */
    public function update_Trip($data, $Trip_id){
        try {  
            $Trip = Trip::find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }

            if (isset($data['name'], $data['type'], $data['path_id'])) {
                if ($data['name'] == 'delivery' && $data['type'] == 'go') {
                    $existingTrip = Trip::where('name', 'delivery')
                                        ->where('type', 'go')
                                        ->where('path_id', $data['path_id'])
                                        ->where('id', '!=', $Trip_id) 
                                        ->exists();

                    if ($existingTrip) {
                        throw new \Exception('هذا المسار مرتبط برحلة توصيل أخرى من نمط ذهاب.');
                    }
                } elseif ($data['name'] == 'delivery' && $data['type'] == 'back') {
                    $existingTrip = Trip::where('name', 'delivery')
                                        ->where('type', 'back')
                                        ->where('path_id', $data['path_id'])
                                        ->where('id', '!=', $Trip_id) 
                                        ->exists();

                    if ($existingTrip) {
                        throw new \Exception('هذا المسار مرتبط برحلة توصيل أخرى من نمط إياب.');
                    }
                }
            }

            $Trip->name = $data['name'] ?? $Trip->name;
            $Trip->type = $data['type'] ?? $Trip->type;
            $Trip->path_id = $data['path_id'] ?? $Trip->path_id;
            $Trip->status = $data['status'] ?? $Trip->status;
           // $Trip->buses()->sync(array_column($data['buses'], 'id'));

            $Trip->save(); 
            return $Trip;

        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة التعديل على الرحلة');
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
            $Trip->buses()->updateExistingPivot($Trip->buses->pluck('id'), ['deleted_at' => now()]);     

            $Trip->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف الرحلة');
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
            $Trip->restore();
            $Trip->buses()->withTrashed()->updateExistingPivot($Trip->buses->pluck('id'), ['deleted_at' => null]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error Deleting trip: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء محاولة حذف الرحلة');
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
            return $Trip->forceDelete();
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
