<?php

namespace App\Http\Requests\CheckOut_Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_CheckOut_Request extends FormRequest
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
            'student_id' => 'sometimes|nullable|exists:students,id',
            'trip_id' => 'sometimes|nullable|exists:trips,id',
            'check_out' => 'sometimes|nullable|boolean',
            'note' => 'sometimes|nullable|string',
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
            'student_id' => 'اسم الطالب',
            'trip_id' => 'اسم الرحلة',
            'check_out' => 'التفقد',
            'note' => 'الملاحظات',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'boolean' => ' يجب أن تكون :attribute  قيمتها إما 1 أو 0',
        ];
    }
}
