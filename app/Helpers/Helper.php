<?php

use App\Models\Children;
use App\Models\GroupChildren;
use App\Models\Kindergarten;
use App\Models\Setting;
use App\Models\StaffKindergarten;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\StaffSchedule;
use App\Models\TherapySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

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

function getUserNameByIds($ids)
{
    return  User::whereIn('id', $ids)->pluck('name')->implode(', ');
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
    $children = Children::where('id', $id)->select('name', 'family_name')->first();
    return $children->name.' '.$children->family_name;
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
        $kindergarten = Kindergarten::whereIn('id', $kindergartenIds)->where('status', 'active')->select('id as key', 'name as value')->orderBy('name', 'ASC')->get()->toArray();
    } else {
        $kindergarten = Kindergarten::select('id as key', 'name as value')->where('status', 'active')->orderBy('name', 'ASC')->get()->toArray();
    }
    return $kindergarten;
}

function getDocGroupChildDetail($docId, $childId)
{
    return GroupChildren::select('id', 'participated', 'reason', 'description', 'file')->where(['children_documentation_id' => $docId, 'children_id' => $childId])->first();
}

function getCurrentLang()
{
    return Setting::where('key', 'lang')->pluck('value')->first();
    // return session('lang');
}

function description($desc, $length)
{
    $truncatedDesc = \Str::limit($desc, $length, '');
    $showMore = '';
    if ($desc && strlen($desc) > $length) {
        $showMore = '<a href="javascript:void(0);" class="toggle-text" data-status="less">' . __('comon.showMore') . '</a>';
    }
    return <<<HTML
        <span data-toggle="tooltip" data-placement="bottom" title="{$desc}">
            <span class="truncated-text">{$truncatedDesc}</span>
            <span class="full-text" style="display: none;">{$desc}</span>
            {$showMore}
        </span>
    HTML;
}

function filterDate()
{
    // Get start and end date of last week in d/m/Y format
    // $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->format('d/m/Y');
    // $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->format('d/m/Y');
    // $lastWeek = json_encode([$startOfLastWeek, $endOfLastWeek]);

    $startOfLast7Days = Carbon::now()->subDays(6)->format('d/m/Y'); // 6 days before today (including today)
    $endOfLast7Days = Carbon::now()->format('d/m/Y'); // today
    $last7Days = json_encode([$startOfLast7Days, $endOfLast7Days]);

    // Get start and end date of current month in d/m/Y format
    // $startOfMonth = Carbon::now()->startOfMonth()->format('d/m/Y');
    // $endOfMonth = Carbon::now()->endOfMonth()->format('d/m/Y');
    // $month = json_encode([$startOfMonth, $endOfMonth]);

    $startOfLast30Days = Carbon::now()->subDays(29)->format('d/m/Y'); // 29 days before today (including today)
    $endOfLast30Days = Carbon::now()->format('d/m/Y'); // today
    $last30Days = json_encode([$startOfLast30Days, $endOfLast30Days]);

    // Get start and end date of past three months in d/m/Y format
    // $startDateOfPast3Month = Carbon::now()->subMonths(3)->startOfMonth()->format('d/m/Y');
    $startDateOfPast3Month = Carbon::now()->subMonths(3)->format('d/m/Y');
    $pastThreeMonth = json_encode([$startDateOfPast3Month, $endOfLast30Days]);

    // Get start and end date of past six months in d/m/Y format
    // $startDateOfPast6Month = Carbon::now()->subMonths(6)->startOfMonth()->format('d/m/Y');
    $startDateOfPast6Month = Carbon::now()->subMonths(6)->format('d/m/Y');
    $pastSixMonth = json_encode([$startDateOfPast6Month, $endOfLast30Days]);

    return [
        'lastWeek' => $last7Days,
        'month' => $last30Days,
        'pastThreeMonth' => $pastThreeMonth,
        'pastSixMonth' => $pastSixMonth
    ];
}

function activityLog($modelName, $modalId, $type)
{
    $user = Auth::user();
    $request = request();
        switch ($type) {
        case 'ADD':
            $subject = $user->name.' has added new '.$modelName;
            break;
        case 'UPDATE':
            $subject = $user->name.' has updated the '.$modelName;
            break;
        case 'DELETE':
            $subject = $user->name.' has deleted the '.$modelName;
            break;
        default:
            $subject = $user->name.' has performed an action on '.$modelName;
            break;
    }

    ActivityLog::create([
        'user_id' => $user->id,
        'subject' => $subject,
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'ip' => $request->ip(),
        'modal_id' => $modalId,
        'model_name' => $modelName,
    ]);
}

function calenderHeader()
{
    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $data = [];
    foreach ($days as $day) {
        $schedules = StaffSchedule::filter()->with('user')->where('day', $day)->get()
            ->map(function ($schedule) use ($day) {
                return [
                    'id' => $schedule->user->id.''.strtolower($day),
                    'name' => $schedule->user->name ?? 'N/A',
                    'workingHours' => [
                        'start' => date('H:i', strtotime($schedule->start_time)),
                        'end' => date('H:i', strtotime($schedule->end_time))
                    ]
                ];
            })->unique('id')->values()->toArray();

        $data[] = [
            'name' => $day,
            'children' => $schedules,
        ];
    }
    return $data;
}

function calenderEvents()
{
    $schedules = TherapySchedule::orderBy('schedule_time')->get();
    $events = $schedules->map(function ($schedule) {
        $scheduleTime = Carbon::parse($schedule->schedule_time);
        return [
            'id' => $schedule->id,
            'description' => $schedule->description,
            'start' => Carbon::parse($schedule->start_date)->format('Y-m-d H:i:s'),
            'end' => Carbon::parse($schedule->end_date)->format('Y-m-d H:i:s'),
            'resource' => $schedule->therapist_id . strtolower(date('l', strtotime($schedule->schedule_time))),
            'therapistName' => getUserNameById($schedule->therapist_id),
            'type' => $schedule->type,
            'groupName' => $schedule->group_name,
            'frequencyRepeat' => $schedule->frequency_repeat,
            'frequencyRepeatAt' => $schedule->start,
            'description' => $schedule->description,
            'file' => $schedule->file,
            'workingTime' => true
        ];
    });
    return $events;
}


