<?php 

use App\Models\Kindergarten;
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

function getKindergartenNameById($id)
{
    return Kindergarten::where('id', $id)->pluck('name')->first();
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