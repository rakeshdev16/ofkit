<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Children Parent's Status {{ $parentsStatusCount }} </h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create', 'parents-status') }}" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="parents-status">{{ __('cluster.moveBtnText') }}</button>
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => "Children Parent's Status", 'count' => @$parentsStatusCount])
            <div id="dataTable">
                @include('table.children.parents-status.table', ['parentsStatus' => $parentsStatus])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => "Children Parent's Status", 'count' => $parentsStatusCount])
            <div id="accordion">
                @include('table.children.parents-status.accordion', ['parentsStatus' => $parentsStatus])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
