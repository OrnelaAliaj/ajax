<?php

namespace App\Http\Controllers;
use Validator;

use App\Employee;
use Datatables;

use Illuminate\Http\Request;

class AjaxdataController extends Controller
{
    function index()
    {
     return view('employee.ajaxdata');
     
    
    }


    function getdata()
    {
     $employees = Employee::select('id','name', 'surname','age','position');
     return Datatables::of($employees)
     ->addColumn('action', function($employee){
        return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$employee->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
    })
    ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'surname'  => 'required',
            'age'  => 'required',
            'position'  => 'required',
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $employee = new Employee([
                    'name'    =>  $request->get('name'),
                    'surname'     =>  $request->get('surname'),
                    'age'     =>  $request->get('age'),
                    'position'     =>  $request->get('position')
                ]);
                $employee->save();
                $success_output = '<div class="alert alert-success">Data Inserted</div>';
            }

            if($request->get('button_action') == 'update')
            {
                $employee = Employee::find($request->get('employee_id'));
                $employee->name = $request->get('name');
                $employee->surname = $request->get('surname');
                $employee->age = $request->get('age');
                $employee->position = $request->get('position');
                $employee->save();
                $success_output = '<div class="alert alert-success">Data Updated</div>';
            }




        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }


    function fetchdata(Request $request)
    {
        $id = $request->input('id');
        $employee = Employee::find($id);
        $output = array(
            'name'    =>  $employee->name,
            'surname'     =>  $employee->surname,
            'age'     =>  $employee->age,
            'position'     =>  $employee->position
        );
        echo json_encode($output);
    }

    



}
