<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use App\Models\Employee;
use Illuminate\Http\Request;
use Iluminate\Support\Facades\Validator;

class DepartamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Departament::all();
        return response()->json($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
      {
        $rules = ['name' => 'required|string|min:1|max:100'];
        $validator = \Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors' =>$validator->errors()->all()
            ],400);
        }

        $department = new Departament($request->input());
        $department->save();
        return response()->json([
            'status' => true,
            'menssage' => 'Department create successfully'
        ],200); 

      }

    /**
     * Display the specified resource.
     */
    public function show(Departament $departament)
    {
       return response()->json(['status' =>true, 'data' =>$departament]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departament $departament)

      {
            $rules = [
                'name'  => 'required|string|min:1|max:100'   
            ];
        
            $validator =\Validator::make($request->input(),$rules);
                if($validator->fails()){
                    return response()->json([
                        'status' =>false,
                        'errors' => $validator->errors()->all()
                    ],400);
                }
                $departament->update($request->input());
                return response()->json([
                    'status'=> true,
                    'message' => 'Departament Update Successfully'
                ], 200);
     }



    public function destroy(Departament $departament)
    {

        if (Employee::where('department_id', $departament->id)->exists()) {
            return response()->json([
                'status' => false,
                'errors' => ['The department is busy']
            ],400);
        }
        $departament->delete();
        return response()->json([
            'status' => true,
            'message' => 'Department Delete successfully'
        ],200);
    }
}
