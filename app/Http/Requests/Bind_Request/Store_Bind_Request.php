<?php

namespace App\Http\Requests\Bind_Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Store_Bind_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;    
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trip' => 'required|integer|exists:trips,id',

            'buses' => 'required|array',
            'buses.*.id' => 'required|integer|exists:buses,id',

            'students' => 'required|array',
            'students.*.id' => 'required|integer|exists:students,id',

            'supervisors' => 'required|array',
            'supervisors.*.id' => 'required|integer|exists:supervisors,id',

            'drivers' => 'required|array',
            'drivers.*.id' => 'required|integer|exists:drivers,id',
        ];
    }
    //===========================================================================================================================
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => 'error 422',
            'message' => 'فشل التحقق يرجى التأكد من المدخلات',
            'errors' => $validator->errors(),
        ]));
    }
    //===========================================================================================================================
    protected function passedValidation()
    {
        //تسجيل وقت إضافي
        Log::info('تمت عملية التحقق بنجاح في ' . now());

    }
    //===========================================================================================================================
    public function attributes(): array
    {
        return [
            'trip' => 'اسم الرحلة',
            'bus_id' => 'اسم الباص',
            'student' => 'اسم الطالب',
            'supervisor' => 'اسم المشرفة',
            'driver' => 'اسم السائق',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'required' => ' :attribute مطلوب',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'trip.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'buses.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'students.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'supervisors.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'drivers.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
        ];
    }
}
