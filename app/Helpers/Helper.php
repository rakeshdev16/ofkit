<?php 

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