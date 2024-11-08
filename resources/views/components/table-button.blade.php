<button class="btn button moveToArchive">{{(request()->status ?? 'active') == 'active' || '' ? __('comon.inactive') : __('comon.active') }} </button>
