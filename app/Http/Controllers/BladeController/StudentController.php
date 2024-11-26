<?php

namespace App\Http\Controllers\BladeController;

use App\Http\Controllers\Controller;
use App\Services\BladeServices\StudentService;
use App\Http\Requests\Student_Request\Store_Student_Request;
use App\Http\Requests\Student_Request\Update_Student_Request;
use App\Models\Student;

class StudentController extends Controller
{
    protected $studentservices;
    /**
     * construct to inject Student Services 
     * @param StudentService $studentservices
     */
    public function __construct(StudentService $studentservices)
    {
        //security middleware
        $this->middleware('security');
        $this->studentservices = $studentservices;
    }
    //===========================================================================================================================
    /**
     * method to view all students
     * @return /view
     * StudentResources to customize the return responses.
     */
    public function index()
    {  
        $students = $this->studentservices->get_all_Students();
        return view('students.view', compact('students'));

    }
    //===========================================================================================================================
    /**
     * method header to student create page 
     */
    public function create(){
        return view('students.create');
    }
    //===========================================================================================================================
    /**
     * method to store a new Student
     * @param   Store_Student_Request $request
     * @return /view
     */
    public function store(Store_Student_Request $request)
    {
        $student = $this->studentservices->create_Student($request->validated());
        session()->flash('success', 'تمت عملية إضافة الطالب بنجاح');
        return redirect()->route('student.index');
    }
    //===========================================================================================================================
    /**
     * method to show student alraedy exist
     * @param  $student_id
     * @return /view
     */
    public function show($student_id)
    {
        $student = $this->studentservices->view_student($student_id);
        return view('students.show', compact('student'));
    }
    //===========================================================================================================================
    /**
    * method header user to edit page
    */
    public function edit($student_id){
        $student = Student::find($student_id);
        return view('students.update' , compact('student'));
    }
    //===========================================================================================================================
    /**
     * method to update student alraedy exist
     * @param  Update_Student_Request $request
     * @param  $student_id
     * @return /view
     */
    public function update(Update_Student_Request $request, $student_id)
    {
        $student = $this->studentservices->update_Student($request->validated(), $student_id);
        session()->flash('success', 'تمت عملية التعديل على الطالب بنجاح');
        return redirect()->route('student.index');
    }
    //===========================================================================================================================
    /**
     * method to soft delete student alraedy exist
     * @param  $student_id
     * @return /view
     */
    public function destroy($student_id)
    {
        $student = $this->studentservices->delete_student($student_id);
        session()->flash('success', 'تمت عملية إضافة الطالب للأرشيف بنجاح');
        return redirect()->route('student.index');
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted students
     * @return /view
     */
    public function all_trashed_student()
    {
        $students = $this->studentservices->all_trashed_student();
        return view('students.trashed', compact('students'));
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted student alraedy exist
     * @param   $student_id
     * @return /view
     */
    public function restore($student_id)
    {
        $restore = $this->studentservices->restore_student($student_id);
        session()->flash('success', 'تمت عملية استعادة الطالب بنجاح');
        return redirect()->route('all_trashed_student');
    }
    //========================================================================================================================
    /**
     * method to force delete on student that soft deleted before
     * @param   $student_id
     * @return /view
     */
    public function forceDelete($student_id)
    {
        $delete = $this->studentservices->forceDelete_student($student_id);
        session()->flash('success', 'تمت عملية حذف الطالب بنجاح');
        return redirect()->route('all_trashed_student');
    }
        
    //========================================================================================================================
}
