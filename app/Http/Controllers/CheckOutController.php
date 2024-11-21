<?php

namespace App\Http\Controllers;

use App\Models\CheckOut;
use Illuminate\Http\Request;
use App\Services\CheckOutService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\CheckOutResources;
use App\Http\Requests\CheckOut_Request\Store_CheckOut_Request;
use App\Http\Requests\CheckOut_Request\Update_CheckOut_Request;

class CheckOutController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $CheckOutservices;
    /**
     * construct to inject CheckOut Services 
     * @param CheckOutService $CheckOutservices
     */
    public function __construct(CheckOutService $CheckOutservices)
    {
        //security middleware
        $this->middleware('security');
        $this->CheckOutservices = $CheckOutservices;
    }
    //===========================================================================================================================
    /**
     * method to view all CheckOuts with a filter on name
     * @return /Illuminate\Http\JsonResponse
     * CheckOutResources to customize the return responses.
     */
    public function index()
    {  
        $CheckOuts = $this->CheckOutservices->get_all_CheckOuts();
        return $this->success_Response(CheckOutResources::collection($CheckOuts), "All CheckOuts fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new CheckOut
     * @param   Store_CheckOut_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_CheckOut_Request $request)
    {
        $CheckOut = $this->CheckOutservices->create_CheckOut($request->validated());
        return $this->success_Response(new CheckOutResources($CheckOut), "CheckOut created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show CheckOut alraedy exist
     * @param  $CheckOut_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($CheckOut_id)
    {
        $CheckOut = $this->CheckOutservices->view_CheckOut($CheckOut_id);

        // In case error messages are returned from the services section 
        if ($CheckOut instanceof \Illuminate\Http\JsonResponse) {
            return $CheckOut;
        }
            return $this->success_Response(new CheckOutResources($CheckOut), "CheckOut viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update CheckOut alraedy exist
     * @param  Update_CheckOut_Request $request
     * @param  CheckOut $CheckOut
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_CheckOut_Request $request, CheckOut $CheckOut)
    {
        $CheckOut = $this->CheckOutservices->update_CheckOut($request->validated(), $CheckOut);
        return $this->success_Response(new CheckOutResources($CheckOut), "CheckOut updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete CheckOut alraedy exist
     * @param  $CheckOut_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($CheckOut_id)
    {
        $CheckOut = $this->CheckOutservices->delete_CheckOut($CheckOut_id);

        // In case error messages are returned from the services section 
        if ($CheckOut instanceof \Illuminate\Http\JsonResponse) {
            return $CheckOut;
        }
            return $this->success_Response(null, "CheckOut soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted CheckOuts
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_CheckOut()
    {
        $CheckOuts = $this->CheckOutservices->all_trashed_CheckOut();
        return $this->success_Response(CheckOutResources::collection($CheckOuts), "All trashed CheckOuts fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted CheckOut alraedy exist
     * @param   $CheckOut_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($CheckOut_id)
    {
        $restore = $this->CheckOutservices->restore_CheckOut($CheckOut_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "CheckOut restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on CheckOut that soft deleted before
     * @param   $CheckOut_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($CheckOut_id)
    {
        $delete = $this->CheckOutservices->forceDelete_CheckOut($CheckOut_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "CheckOut force deleted successfully", 200);
    }
        
    //========================================================================================================================
}
