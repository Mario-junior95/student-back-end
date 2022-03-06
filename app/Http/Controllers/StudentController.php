<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Classe;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Student $students)
    {
        $students = Student::with('classes')->get();
        // return $this->paginate($student->with('classes'), 10, 200);

        return response()->json([
            'data' =>  $students
        ]);
        // return $this->successResponse($students , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        $data =  $request->all();

        $student = new Student;
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->date_of_birth = $request->date_of_birth;

        if ($data['image'] === "null") {
            unset($data['image']);
        } else {
            $student->image = custom_image($request);
        }

        $student->is_active = $request->is_active;

        $checkIfClassExists = Classe::where('id', $request->class_id)->exists();
        if ($checkIfClassExists) {
            $student->class_id = $request->class_id;
        } else {
            return $this->successResponse(['message' => 'Class_id does not exist!'], 200);
        }

        $student->save();
        return $this->successResponse(['message' => 'Student Created Successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return $this->showOne($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data =  $request->all();

        $student = Student::where('id' , $id)->first();
        $student->fill($data);


        if ($data['image'] === null) {
            unset($data['image']);
            $student->update($data);
        } else {
            $student->image = custom_image($request);
            $student->update($data);
        }
        $student->save();
        return $this->successResponse(['message' => 'Student Updated Successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return $this->successResponse(['message' => 'Student is Deleted Successfully!'], 200);
    }
}
