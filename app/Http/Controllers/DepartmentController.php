<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class DepartmentController extends ApiController
{
    # Verify and return custom slug string
    public function slugify($text)
    {
        # remove ? mark from string
        $slug = preg_replace('/\?/u', ' ', trim($text));
        $slug = preg_replace('/\s+/u', '-', trim($slug));

        # slug repeat check inside departments Table
        $latestDepartmentSlug = Department::whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$'")->pluck('slug');

        $getValueAfterDash = array();

        for ($i = 0; $i < count($latestDepartmentSlug); $i++) {
            $getValueAfterDash[] = (int)substr($latestDepartmentSlug[$i], strrpos($latestDepartmentSlug[$i], '-') + 1);
        }

        for ($i = 0; $i < count($latestDepartmentSlug); $i++) {
            //check if slug exist inside table departments
            if (Str::contains($latestDepartmentSlug[$i], max($getValueAfterDash))) {
                if ($latestDepartmentSlug[$i]) {
                    $pieces = explode('-', $latestDepartmentSlug[$i]);
                    $number = intval(end($pieces));
                    $slug .= '-' . ($number + 1);
                    return $slug;
                };
            } else if (max($getValueAfterDash) === 0) {
                $pieces =  explode('-', $latestDepartmentSlug[$i]);
                $number = intval(end($pieces));
                $slug .= '-' . ($number + 1);
                return $slug;
            }
        }
        return $slug;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allDepatments = Department::where('parent_id', null)->with('childs:id,name,slug,description,parent_id')->get();
        return response()->json([
            'parents' => $allDepatments
        ]);
    }

    public function getAllDepartments()
    {
        $allDepartments = Department::get(['id', 'name']);

        return response()->json([
            'parents' => $allDepartments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        $input = $request->all();
        $input['parent_id'] = $input['parent_id'];
        $input['slug'] = $this->slugify($input['name']);

        //check if parent exist 
        $checkIfDepartmentExists = Department::where('id', $input['parent_id'])->exists();

        if (!$checkIfDepartmentExists && !empty($input['parent_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Department does not exists!'
            ]);
        }

        Department::create($input);
        return $this->successResponse(['message' => 'Department added successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::where('id', $id)->get();
        return $this->successResponse(['department' => $department], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, $id)
    {
        $checkIfDepartmentExist =  Department::where('id', $id);

        if (!$checkIfDepartmentExist->exists()) {
            return $this->successResponse(['message' => 'Department does not exists!'], 200);
        }

        $getDepartmentById = $checkIfDepartmentExist->first();

        //check if parent exist 
        $checkIfDepartmentExists = Department::where('id', $request->parent_id)->exists();

        if (!$checkIfDepartmentExists && !empty($request->parent_id)) {
            return $this->successResponse(['message' => 'Department does not exists!'], 200);
        }

        $parentId = $getDepartmentById->parent_id;

        Department::where('parent_id', $id)->update(['parent_id' => $parentId]);

        $getDepartmentById->name = $request->name;
        $getDepartmentById->slug = $this->slugify($request->name);
        $getDepartmentById->description = $request->description;
        // $getDepartmentById->parent_id = $request->parent_id;
        $getDepartmentById->save();

        return $this->successResponse(['message' => 'Department Updated successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check if exist
        $checkIfDepartmentExist = Department::where('id', $id);

        if ($checkIfDepartmentExist->exists()) {
            $checkIfDepartmentExist->delete();
            return $this->successResponse(['message' => 'Department Deleted successfully!'], 200);
        }
        return $this->successResponse(['message' => 'Department Does not exist'], 200);
    }
}
