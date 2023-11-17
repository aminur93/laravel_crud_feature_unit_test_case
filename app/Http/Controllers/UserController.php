<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = User::latest()->get();

        return \response()->json([
            'user' => $user
        ],Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                //create user
                $user = new User();

                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);

                $user->save();

                DB::commit();

                return response()->json([
                    'message' => 'User store successful'
                ],Response::HTTP_CREATED);

            }catch(Exception $e){
                DB::rollBack();

                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function findData()
    {
        $data = array(
            "id" => 1,
            "name" => "dhaka",
            "children" => array(
                [
                    "id" => 1,
                    "name" => "Dahaka north city corporation",
                    "children" => array(
                        [
                            "id" => 1,
                            "parent_id" => 1,
                            "name" => "dhanmondi"
                        ],
                        [
                            "id" => 2,
                            "parent_id" => 1,
                            "name" => "green road"
                        ],
                        [
                            "id" => 3,
                            "parent_id" => 1,
                            "name" => "kolabagan"
                        ],
                    ),
                    "office" => array()
                ],

                [
                    "id" => 2,
                    "name" => "Dahaka south city corporation",
                    "children" => array(
                        [
                            "id" => 1,
                            "parent_id" => 2,
                            "name" => "framgate"
                        ],
                        [
                            "id" => 2,
                            "parent_id" => 2,
                            "name" => "mirpur"
                        ],
                        [
                            "id" => 3,
                            "parent_id" => 2,
                            "name" => "gulshan"
                        ],
                    ),
                    "office" => array(
                        ["id" => 1, "assign_id" => 2, "name" => "office_1"]
                    )
                ],
            ),
            "office" => array()
        );

        $offices = array(
            ["id" => 1, "assign_id" => 2, "name" => "office_1"]
        );

        return $data;
    }
}
