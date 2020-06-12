<?php

namespace App\Http\Controllers;


use App\CaseMessages;
use App\Cases;
use App\Requests;
use App\Role;
use App\User;
use App\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    public function toHome()
    {
        return view('home');
    }

    public function toSign()
    {
        return view('signup');
    }

    public static function toLog()
    {
        return view('login');
    }

    public function toCategories()
    {
        $categories = Categories::all();
        return view('categories',  ['categories' => $categories]);
    }

    public function toRequests($user_id)
    {
        $requests = DB::table('requests')
            ->join('category_roles', 'requests.category_id', '=', 'category_roles.category_id')
            ->join('employee_roles', 'category_roles.role_id', '=', 'employee_roles.role_id')
            ->select('requests.*')
            ->where('employee_roles.user_id', $user_id)
            ->get();
        return view('requests',  ['requests' => $requests]);
    }

    public function toAdminPanel()
    {
        $employees = User::where('role', 'employee')->get();
        $clients = User::where('role', 'client')->get();
        $categories = Categories::all();
        $roles = Role::all();

        $rolesList = DB::table('roles')
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                    ->from('category_roles')
                    ->whereRaw('category_roles.role_id = roles.id');
            })->get();


        return view('adminPanel',
            ['employees' => $employees,
             'clients' => $clients,
             'categories' => $categories,
             'roles' => $roles,
             'rolesList' =>  $rolesList]);
    }

    public function toProfile()
    {
        $cases = Cases::where('client_id', Auth::user()->id)
                ->orwhere('employee_id', Auth::user()->id)
                ->get();

        return view('profile',  ['cases' => $cases]);
    }

    public function toCase($case_id)
    {
        $case = Cases::where('id', $case_id)->first();
        $msgs = CaseMessages::where('case_id', $case_id)->orderBy('created_at')->get();
        return view('case', ['case' => $case, 'msgs' => $msgs]);
    }
}
