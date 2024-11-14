<?php

namespace App\Services;


use App\Models\Bus;
use App\Models\Driver;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class BusService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all buses 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Bus(){
        try {
            $bus = Bus::with('students','supervisors','drivers')->get();
            return $bus;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche bus', 400);}
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

            if (count($data['students']) > $data['number_of_seats']) {
                throw new \Exception('عدد الطلاب يجب أن يساوي عدد المقاعد المدخل');
            }

            $bus->name = $data['name'];
            $bus->number_of_seats = $data['number_of_seats'];
            $bus->save();

            foreach ($data['students'] as $student) {
                $student = Student::findOrFail($student['id']);
                $bus->students()->attach($student->id);
            }
            
            foreach ($data['supervisors'] as $supervisor) {
                $supervisor = Supervisor::findOrFail($supervisor['id']);
                $bus->supervisors()->attach($supervisor->id);
            }

            foreach ($data['drivers'] as $driver) {
                $driver = Driver::findOrFail($driver['id']);
                $bus->drivers()->attach($driver->id);
            }
            $bus->save(); 
    
            return $bus; 
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create bus', 400);}
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
            $bus = Bus::find($bus_id);
            if(!$bus){
                throw new \Exception('bus not found');
            }

            if (count($data['students']) != ($data['number_of_seats'] ?? $bus->number_of_seats)) {
                throw new \Exception('عدد الطلاب يجب أن يساوي عدد المقاعد المدخل');
            }

            $bus->name = $data['name'] ?? $bus->name;
            $bus->number_of_seats = $data['number_of_seats'] ?? $bus->number_of_seats;
            $bus->students()->sync(array_column($data['students'], 'id'));
            $bus->supervisors()->sync(array_column($data['supervisors'], 'id'));
            $bus->drivers()->sync(array_column($data['drivers'], 'id'));

            $bus->save(); 
            return $bus;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view bus', 400);}
    }
    //========================================================================================================================
    /**
     * method to show bus alraedy exist
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_bus($bus_id) {
        try {    
            $bus = Bus::find($bus_id);
            if(!$bus){
                throw new \Exception('bus not found');
            }

            $bus->load('students', 'supervisors', 'drivers');

            return $bus;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update bus', 400);}
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
            if(!$bus){
                throw new \Exception('bus not found');
            }

            $bus->students()->updateExistingPivot($bus->students->pluck('id'), ['deleted_at' => now()]);    
            $bus->supervisors()->updateExistingPivot($bus->supervisors->pluck('id'), ['deleted_at' => now()]);    
            $bus->drivers()->updateExistingPivot($bus->drivers->pluck('id'), ['deleted_at' => now()]); 

            $bus->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting bus', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete bus
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_bus()
    {
        try {  
            $bus = Bus::onlyTrashed()->get();
            return $bus;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed bus', 400);}
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
            $bus = Bus::onlyTrashed()->find($bus_id);
            if(!$bus){
                throw new \Exception('bus not found');
            }
            $bus->restore();
            $bus->students()->withTrashed()->updateExistingPivot($bus->students->pluck('id'), ['deleted_at' => null]);
            $bus->supervisors()->withTrashed()->updateExistingPivot($bus->supervisors->pluck('id'), ['deleted_at' => null]);
            $bus->drivers()->withTrashed()->updateExistingPivot($bus->drivers->pluck('id'), ['deleted_at' => null]);

            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore bus', 400);
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
            $bus = Bus::onlyTrashed()->find($bus_id);
            if(!$bus){
                throw new \Exception('bus not found');
            }
 
            return $bus->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting bus', 400);}
    }
    //========================================================================================================================

}
