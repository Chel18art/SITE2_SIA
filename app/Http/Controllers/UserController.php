<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserJob;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
class UserController extends Controller
{
    use ApiResponser;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * Return the list of users
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->successResponse($users);
    }

    public function add(Request $request)
    {
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'jobid' => 'required|numeric|min:1|not_in:0',
        ];
        
        $this->validate($request, $rules);

        $userjob = UserJob::findOrFail($request->jobid);
        $user = User::create($request->all());

        return $this->successResponse($user, Response::HTTP_CREATED);
    }
    

    /**
     * Obtains and shows one user
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->successResponse($user);
    }

    /**
     * Update an existing user
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'jobid' => 'required|numeric|min:1|not_in:0',

        ];
        $this->validate($request, $rules);
        $userjob = UserJob::findOrFail(request->jobid);

        $user = User::findOrFail($id);

        $user->fill($request->all());

        if ($user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $user->save();

        return $this->successResponse($user);
    }

    /**
     * Remove an existing user
     * @return Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return $this->successResponse($user);
    }

}