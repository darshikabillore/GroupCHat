<?php

namespace App\Http\Controllers;

use App\Events\GroupCreated;
use App\Group;
use App\GroupUser;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function store()
    {
        try{
            $group = Group::create(['name' => request('name')]);

            $users = collect(request('users'));
            $users->push(auth()->user()->id);

            $group->users()->attach($users);

            broadcast(new GroupCreated($group))->toOthers();

            return $group;
        } catch (\Exception $e) {
            \Log::error($e);  
            return $e;
            throw new \Exception("Unable to get users.");         
        }
    }

    public function removeuser($grpid,$userid)
    {
        try{
            $group = GroupUser::where('group_id',$grpid)->where('user_id',$userid)->delete();
        } catch (\Exception $e) {
            \Log::error($e);  
            return $e;
            throw new \Exception("Unable to get users.");         
        }
    }
}
