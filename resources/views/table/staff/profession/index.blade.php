<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Saff Academic Profession ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('staff-table.create') }}?type=profession" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        {{-- <button class="btn button moveToArchive" data-type="profession">Move to Archive</button> --}}
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="table-search">
                <label>Search: <input type="search" class="search" value="{{ request()->search }}" placeholder=""></label>
            </div>
            <div id="dataTable">
                @include('table.staff.profession.table', ['professions' => $professions])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.staff.profession.accordion', ['professions' => $professions])
        </div>
    </div>
</div>
@push('customScript')
    <script>

    </script>
@endpush