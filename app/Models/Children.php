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

        // if (request('status')) {
        //     $query->where('status', request('status'));
        // }else{
        //     $query->where('status', 'active');
        // }
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
            $backgroundColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            $rgb = sscanf($backgroundColor, "#%02x%02x%02x");
            $luminance = (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255;
            $textColor = $luminance > 0.5 ? '#000000' : '#FFFFFF';
            $model->color = json_encode([
                "background-color: $backgroundColor",
                "color: $textColor"
            ]);
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

    // public function kinderGarten()
    // {
    //     return $this->belongsTo(Kindergarten::class, 'kindergarten_id');
    // }
}
