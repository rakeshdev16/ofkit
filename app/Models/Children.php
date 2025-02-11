<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Children extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kindergarten_id',
        'name',
        'family_name',
        'identification',
        'gender',
        'dob',
        'age',
        'address',
        'functionality_id',
        'diagnosis_id',
        'status_id',
        'service_start_date',
        'hmo_id',
        'photo',
        'status',
        'color',
    ];

    protected $appends = ['date_of_birth', 'profile', 'calclulated_age'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            Session::put('children_sorting', [
                'key' => request('sort'),
                'value' => request('sorting'),
            ]);
            if (request('sort') == 'kindergarten_id') {
                if (Auth::user()->hasRole(['manager', 'therapist'])) {
                    $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
                } else {
                    $kindergartenIds = Kindergarten::pluck('id')->toArray();
                }
                $query->select('childrens.*', 'kindergartens.name as kindergarten_name')
                        ->join('kindergartens', 'childrens.kindergarten_id', '=', 'kindergartens.id')
                        ->whereIn('kindergartens.id', $kindergartenIds)
                        ->orderBy('kindergarten_name', request('sorting'));
            } else {
                $query->orderBy(request('sort'), request('sorting'));
            }
        } else {
            $query->orderBy('childrens.name', 'ASC');
        }

        if (request('kindergarten_id')) {
            Session::put('children_kindergarten', request('kindergarten_id'));
            $query->whereIn('childrens.kindergarten_id', explode(',',request('kindergarten_id')));
        }else{
            Session::forget('children_kindergarten');
        }

        if (request('status') == 'inactive') {
            $query->whereIn('childrens.status', ['active', 'inactive']);
        }else{
            $query->where('childrens.status', 'active');
        }

        if (request('search')) {
            $search = request('search');

            $query->where(function ($query) use ($search) {
                $query->where('childrens.name', 'like', '%' . $search . '%')
                    ->orWhere('childrens.family_name', 'like', '%' . $search . '%')
                    ->orWhere('childrens.identification', 'like', '%' . $search . '%')
                    ->orWhere('childrens.address', 'like', '%' . $search . '%');
            });
        }

        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
            $query->whereIn('childrens.kindergarten_id', $kindergartenIds);
        }

        return $query;
    }

    public function setServiceStartDateAttribute($value)
    {
        if (is_string($value)) {
            $value = Carbon::createFromFormat('d/m/Y', $value);
            $this->attributes['service_start_date'] = $value->format('Y-m-d');
        }
    }

    public function getServiceStartDateAttribute($value)
    {
        return isset($this->attributes['service_start_date']) ? @date('d/m/Y', strtotime($this->attributes['service_start_date'])) : NULL;
    }

    public function setDobAttribute($value)
    {
        if (!empty($value)) {
            if (is_string($value)) {
                $value = Carbon::createFromFormat('d/m/Y', $value);
            }
            $this->attributes['dob'] = $value->format('Y-m-d');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $colors = [
                json_encode(["background-color: #43a047;", "color: #000000;"]),
                json_encode(["background-color: #2a9d8f;", "color: #000000;"]),
                json_encode(["background-color: #5fc89c;", "color: #000000;"]),
                json_encode(["background-color: #9ccc65;", "color: #000000;"]),
                json_encode(["background-color: #f8e16c;", "color: #000000;"]),
                json_encode(["background-color: #ffb39a;", "color: #000000;"]),
                json_encode(["background-color: #f4a261;", "color: #000000;"]),
                json_encode(["background-color: #d68c45;", "color: #000000;"]),
                json_encode(["background-color: #e76f51;", "color: #000000;"]),
                json_encode(["background-color: #4a90e2;", "color: #000000;"]),
                json_encode(["background-color: #56c8d8;", "color: #000000;"]),
                json_encode(["background-color: #a48ddb;", "color: #000000;"]),
                json_encode(["background-color: #ede0d4;", "color: #000000;"]),
                json_encode(["background-color: #ff6392;", "color: #000000;"]),
                json_encode(["background-color: #c07f8a;", "color: #000000;"]),
            ];
            $lastRecord = self::latest('id')->first();
            $lastIndex = 0;
            if ($lastRecord && $lastRecord->color) {
                $lastColor = $lastRecord->color;
                $lastIndex = array_search(json_encode($lastColor), $colors);
                $lastIndex = $lastIndex === false ? 0 : $lastIndex;
            }
            $nextIndex = ($lastIndex + 1) % count($colors);
            $model->color = $colors[$nextIndex];
        });
    }


    public function getColorAttribute($value)
    {
        return json_decode($value);
    }

    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }

    public function getDateOfBirthAttribute()
    {
        return isset($this->attributes['dob']) ? date('d/m/Y', strtotime($this->attributes['dob'])) : NULL;
    }

    public function getProfileAttribute($value)
    {
        return isset($this->attributes['photo']) ? asset('storage/' . $this->attributes['photo']) : asset('assets/images/avatars/dummy-image.webp');
    }

    public function getCalclulatedAgeAttribute()
    {
        if (isset($this->attributes['dob'])) {
            return Carbon::parse($this->attributes['dob'])->diff(Carbon::now())->format('%y.%m');
        }
    }

    public function documentation()
    {
        return $this->hasMany(ChildrenDocumentation::class, 'children_id');
    }
    public function staffMeetingChildren()
    {
        return $this->hasMany(StaffMeetingChildren::class, 'children_id');
    }

    public function parent()
    {
        return $this->hasOne(ChildrenParent::class);
    }

    public function medicalInformation()
    {
        return $this->hasOne(ChildrenMedicalInformation::class);
    }

    public function medicine()
    {
        return $this->hasMany(ChildrenMedicine::class);
    }

    public function diagnosis()
    {
        return $this->hasMany(ChildrenDiagnosis::class);
    }

    public function language()
    {
        return $this->hasMany(FamilyLanguage::class);
    }
}
