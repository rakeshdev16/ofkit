<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">{{ __('cluster.cluster') }} ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="">
        <a href="{{ route('cluster.create') }}" class="btn button">{{ __('cluster.addBtnText') }}</a>
        <button class="btn button moveToArchive">{{ __('cluster.moveBtnText') }}</button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive" id="dataTable">
            @include('cluster.table', ['clusters' => $clusters])
        </div>
        <div class="lising d-none" id="accordion">
            @include('cluster.accordion', ['clusters' => $clusters])
        </div>
    </div>
</div>