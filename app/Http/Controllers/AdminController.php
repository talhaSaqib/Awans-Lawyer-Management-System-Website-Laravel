<?php

namespace App\Http\Controllers;

use App\Categories;
use App\CategoryRole;
use App\EmployeeRole;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'user_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','User ID missing');
        }

        $user_id = $request['user_id'];

        if(User::where('id', $user_id)->delete())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred. Try again later.');
        }

    }

    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'category_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Category ID missing');
        }

        $category_id = $request['category_id'];

        $role = DB::table('roles')
            ->join('category_roles', 'roles.id', '=', 'category_roles.role_id')
            ->select('roles.*')
            ->where('category_roles.category_id', $category_id)
            ->first();

        if(Role::where('id', $role->id)->delete() && Categories::where('id', $category_id)->delete())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred. Try again later.');
        }

    }

    public function deleteRole(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'role_id' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Role ID missing');
        }

        $role_id = $request['role_id'];

        $category = DB::table('categories')
            ->join('category_roles', 'categories.id', '=', 'category_roles.category_id')
            ->select('categories.*')
            ->where('category_roles.role_id', $role_id)
            ->first();

        if(Categories::where('id', $category->id)->delete() && Role::where('id', $role_id)->delete())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred. Try again later.');
        }

    }

    public function addEmployee(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|email|unique:users',
                'emp_name' => 'required|max:120',
                'emp_pass' => 'required|min:5',
                'emp_roles' => 'required'
            ]);

        $employee = new User();
        $employee->email = $request['email'];
        $employee->fullname = $request['emp_name'];
        $employee->password =  bcrypt($request['emp_pass']);
        $employee->role = "employee";

        $emp_roles = $request->input('emp_roles');
        if($employee->save())
        {
            foreach ($emp_roles as $er)
            {
                $emp_role = new EmployeeRole();
                $emp_role->role_id = $er;
                $emp_role->user_id = $employee->id;
                if(!$emp_role->save())
                {
                    return redirect()->back()->with('message', 'An error occurred in assiging employee roles. Try again later.');
                }
            }
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred. Try again later.');
        }

    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'title' => 'required|unique:categories',
                'cat_description' => 'required',
                'cat_role' => 'required'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','All fields are required in Add Category.');
        }

        $category = new Categories();
        $category->title = $request['title'];
        $category->description = $request['cat_description'];

        if($category->save())
        {
            $cat_role = new CategoryRole();
            $cat_role->category_id = $category->id;
            $cat_role->role_id = $request['cat_role'];

            if($cat_role->save())
            {
                return redirect()->back();
            }
            else
            {
                return redirect()->back()->with('message','An error occurred in assigning role. Try again later.');
            }

        }
        else
        {
            return redirect()->back()->with('message','An error occurred. Try again later.');
        }

    }

    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'title' => 'required|unique:roles'
            ]);

        if($validator->fails())
        {
            return redirect()->back()->with('message','Role Title was missing.');
        }

        $role = new Role();
        $role->title = $request['title'];

        if($role->save())
        {
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('message','An error occurred. Try again later.');
        }

    }

}
