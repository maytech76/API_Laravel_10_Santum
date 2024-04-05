<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Departament;
use Iluminate\Support\Facades\Validator;
use DB;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of Employees.
     */
    public function index()
    {
        $employees = Employee::select('employees.*', 'departaments.name as departament')
        ->join('departaments', 'departaments.id', '=', 'employees.department_id')
        ->paginate(10);
        return response()->json($employees);
    }

    /**
     * Store a newly created new resoruce employye in storage.
     */
    public function store(Request $request)
    {
       $rules = [
        'name'  => 'required|string|min:1|max:100',
        'email' => 'required|email|max:80',
        'phone' => 'required|max:15',
        'department_id' => 'required|numeric'
       ];

       $validator =\Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' =>false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $employee = new Employee($request->input());
        $employee->save();

        return response()->json([
            'status'=> true,
            'message' => 'Employee Create Successfully'
        ], 200);
    }

    /**
     * Display the specified employee of resource.
     */
    public function show(Employee $employee)
    {
        return response()->json(['status' => true, 'data' => $employee]);
    }

    /**
     * Update the specified of employee resource in storage.
     */
    public function update(Request $request, Employee $employee)

      {
            $rules = [
                'name'  => 'required|string|min:1|max:100',
                'email' => 'required|email|max:80',
                'phone' => 'required|max:15',
                'department_id' => 'required|numeric'
            ];
        
            $validator =\Validator::make($request->input(),$rules);
                if($validator->fails()){
                    return response()->json([
                        'status' =>false,
                        'errors' => $validator->errors()->all()
                    ],400);
                }
                $employee ->update($request->input());
                return response()->json([
                    'status'=> true,
                    'message' => 'Employee Update Successfully'
                ], 200);
     }

    
   
    public function destroy(Employee $employee)
        {
            $employee->delete();
            return response()->json([
                'status'=> true,
                'message' => 'Employee Delete Successfully'

            ],200);
        }

        /*
        Nueva funcion para contabilizar el numero de Empleados por Departamento
        */

    public function EmployeesByDepartament(){
        $employees = Employee::select(DB::raw('count(employees.id) as count, departaments.name'))
        ->rightjoin('departaments', 'departaments.id', '=', 'employees.department_id')
        ->groupBy('departaments.name')->get();
          return response()->json($employees);
    }

    public function all(){
        $employees = Employee::select('employees.*', 'departaments.name as departament')
        ->join('departaments', 'departaments.id', '=', 'employees.department_id')->get();
          return response()->json($employees);    
    }
}
