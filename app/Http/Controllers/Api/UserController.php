<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
//use Carbon\Carbon;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(){
        $users = User::all();
        //Carbon::now()->daysInMonth;
        return UserResource::collection($users);
    }
    public function show($id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return new UserResource($user);
    }
    public function update(Request $request, $id){
        $user = User::find(auth()->id);
       // dd($user);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $request->validate([
            'name' => 'required|string',
            'email' => 'email|unique:users,email,' . $user->id,
        ]);
        $user->update($request->all());
        return response()->json(['message' => 'User Updated successfully']);
    }
    public function destroy($id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
