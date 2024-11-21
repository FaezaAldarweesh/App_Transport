<?php

namespace App\Http\Requests\Trip_Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Store_Trip_Request extends FormRequest
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
            'name' => 'required|string|in:delivery,school',
            'type' => 'required|string|in:go,back',
            'path_id' => 'required|integer|exists:paths,id',
            'status' => 'required|boolean'
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
            'name' => 'اسم الرحلة',
            'type' => 'نوع الرحلة',
            'path_id' => 'اسم المسار',
            'status'=> 'حالة الرحلة',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'required' => ' :attribute مطلوب',
            'name.in' => 'يأخذ الحقل :attribute فقط القيم إما ( delivery أو school )',
            'type.in' => 'يأخذ الحقل :attribute فقط القيم إما ( go أو back )',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'exists' => ':attribute غير موجود , يجب أن يكون :attribute موجود ضمن المسارات المخزنة سابقا',
            'boolean' => ' يجب أن تكون :attribute  قيمتها إما 1 أو 0',
        ];
    }
}
