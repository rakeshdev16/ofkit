<?php

use App\Models\Children;
use App\Models\GroupChildren;
use App\Models\Kindergarten;
use App\Models\Setting;
use App\Models\StaffKindergarten;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\StaffSchedule;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
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

function getChildrenNamesById($ids)
{
    return Children::whereIn('id', $ids)
        ->get(['name', 'family_name'])
        ->map(function ($child) {
            return "{$child->name} {$child->family_name}";
        })->implode(', ');
}


function getKindergartenNameById($id)
{
    return Kindergarten::where('id', $id)->pluck('name')->first();
}

function getKindergartenInfoById($id)
{
   $info = Kindergarten::where('id', $id)->select('name', 'telephone')->first();

    if ($info) {
        $infomation = __('kindergarten.telephoneTh') . ': ' . $info->telephone;
        return $infomation;
    }

    return null;
}

function getKindergartenNamesById($ids)
{
    return Kindergarten::whereIn('id', $ids)->pluck('name')->toArray();
}

function getKindergartenInfoByIds($ids)
{
    $kindergartens = Kindergarten::whereIn('id', $ids)
        ->select('name', 'telephone')
        ->get();

    $htmlOutput = "<div dir='rtl'>";
    foreach ($kindergartens as $kindergarten) {
        $htmlOutput .= '<ul>'.$kindergarten->name.'<li>'.__('kindergarten.telephoneTh').': '.$kindergarten->telephone.'</li></ul>';
    }
    $htmlOutput .= '</div>';

    return $htmlOutput;
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
    $startOfLast7Days = Carbon::now()->subDays(6)->format('Y-m-d');
    $endOfLast7Days = Carbon::now()->format('Y-m-d'); // today
    $last7Days = json_encode([$startOfLast7Days, $endOfLast7Days]);

    $startOfLast30Days = Carbon::now()->subDays(29)->format('Y-m-d'); // 29 days before today (including today)
    $endOfLast30Days = Carbon::now()->format('Y-m-d'); // today
    $last30Days = json_encode([$startOfLast30Days, $endOfLast30Days]);

    $startDateOfPast3Month = Carbon::now()->subMonths(3)->format('Y-m-d');
    $pastThreeMonth = json_encode([$startDateOfPast3Month, $endOfLast30Days]);

    $startDateOfPast6Month = Carbon::now()->subMonths(6)->format('Y-m-d');
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

function appointmentIcon($icon)
{
    $icons = [
        'individual' => 'user',
        'group' => 'users',
        'parental-guidance' => 'child',
        'staff-meeting' => 'handshake-o',
        'documentation-break' => 'book',
        'preparation' => 'cogs',
        'tutorial' => 'laptop',
        'other' => 'th'
    ];

    return $icons[$icon];
}

function scheduleResponse($events, $schedule = null, $childId = null)
{
    return $events->map(function ($event) use($events, $schedule, $childId) {

        $kindergartenId = !empty($schedule) ? $schedule->kindergarten_id : optional($event->schedule)->kindergarten_id;
        $scheduleId = !empty($schedule) ? $schedule->id : optional($event->schedule)->id;
        $findEvent = ScheduleEvent::where([
            'schedule_id' => $scheduleId,
            'therapist_id' => $event->therapist_id,
            'day' => $event->day,
            'start_time' => $event->start_time
        ]);

        $therapistIds = ScheduleEvent::where('schedule_id', $scheduleId)->where('unique_id', $event->unique_id)->pluck('therapist_id')->toArray();
        $event->eventCount = $findEvent->count();
        $event->last_id = $findEvent->orderBy('id', 'DESC')->pluck('id')->first();
        $event->therapistIds = $therapistIds;
        $event->childrenId = $event->childrens->pluck('children_id')->toArray();
        if (Route::currentRouteName() == 'documentation.calendar') {
            $event->allChildrens = Children::select('id as key', DB::raw('CONCAT(name, " ", family_name) as value'))->where('kindergarten_id', $kindergartenId)->orderBy('name')->get()->toArray();
            $event->allTherapists = StaffSchedule::filter(['kindergarten_id' => $kindergartenId])->with('user')->select('user_id')->distinct('user_id')->get()
                ->map(function ($schedule) {
                    return [
                        'key' => $schedule->user_id,
                        'value' => $schedule->user->name ?? 'N/A',
                    ];
                })->toArray();
        }
        return [
            'id' => $event->id,
            'start' => date('Y-m-d').' '.$event->start_time,
            'end' => date('Y-m-d').' '.$event->end_time,
            'resource' => ($childId ?? $event->therapist_id) . strtolower($event->day),
            'data' => ($event),
            'form' => view('components.event-status-form', ['data' => $event])->render(),
            'eventSlotHtml' => view('components.event-html', ['data' => $event])->render(),
            'eventDetailSlotHtml' => view('components.event-detail-html', ['data' => $event])->render(),
        ];
    });
}
