<?php 

use App\Models\Children;
use App\Models\GroupChildren;
use App\Models\Kindergarten;
use App\Models\Setting;
use App\Models\StaffKindergarten;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

function getAllRouteNames()
{
    $routes = Route::getRoutes();
    $routeNames = [];

    foreach ($routes as $route) {
        $name = $route->action['as'] ?? null;
        if ($name && !in_array($name, [
            'sanctum.csrf-cookie', 'livewire.update', 'livewire.upload-file', 'livewire.preview-file', 'ignition.healthCheck', 'ignition.executeSolution', 'ignition.updateConfig', 'login', 'logout', 'register', 'password.request', 'password.email', 'password.reset', 'password.update', 'password.confirm'
        ])) {
            $routeNames[] = $name;
        }
    }
    return array_unique($routeNames);
}

function getUserNameById($id)
{
    return User::where('id', $id)->pluck('name')->first();
}

function getUserRoleById($id)
{
    $user = User::find($id);
    if ($user) {
        return @$user->getRoleNames()->first();
    }
    return NULL;
}

function getChildrenNameById($id)
{
    return Children::where('id', $id)->pluck('name')->first();
}

function getKindergartenNameById($id)
{
    return Kindergarten::where('id', $id)->pluck('name')->first();
}

function getKindergartenNamesById($ids)
{
    return Kindergarten::whereIn('id', $ids)->pluck('name')->toArray();
}

function uploadFile($file, $path, $extension = null)
{
    // $fileName = $file->store($path);
    // return explode('public/', $fileName)[1];
    if (isset($extension)) {
        $filename = Str::random(40) . '.' . $extension;
    } else {
        $filename = $file->getClientOriginalName();
    }
    // $filename = Str::random(40) . '.' . $extension;
    $filePath = $file->storeAs($path, $filename, 'public');
    return $filePath;
}

function getStaffKindergarten($userId, $kindergartenId)
{
    return StaffKindergarten::where(['user_id' => $userId, 'kindergarten_id' => $kindergartenId])->first();
}

function getGroupChildrens($docId, $childId)
{
    return GroupChildren::where(['children_documentation_id' => $docId, 'children_id' => $childId])->first();
}

function authKindergartens()
{
    if (Auth::user()->hasRole(['manager', 'therapist'])) {
        $kindergartenIds = Auth::user()->staffKindergartens->pluck('kindergarten_id')->toArray();
        $kindergarten = Kindergarten::whereIn('id', $kindergartenIds)->select('id as key', 'name as value')->orderBy('name', 'ASC')->get()->toArray();
    } else {
        $kindergarten = Kindergarten::select('id as key', 'name as value')->orderBy('name', 'ASC')->get()->toArray();
    }
    return $kindergarten;
}

function getDocGroupChildDetail($docId, $childId)
{
    return GroupChildren::select('id', 'participated', 'reason', 'description')->where(['children_documentation_id' => $docId, 'children_id' => $childId])->first();
}

function getCurrentLang()
{
    return Setting::where('key', 'lang')->pluck('value')->first();;
}