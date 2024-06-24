@for ($i = 1; $i < 10; $i++)
<div class="accordion accordion-flush" id="accordionFlushExample{{ $i }}">
    <div class="accordion-item">
        <h2 class="accordion-header" id="staff-listing-{{ $i }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapse{{ $i }}" aria-expanded="false"
                aria-controls="flush-collapse{{ $i }}">Accordion Item
                #{{ $i }}
            </button>
        </h2>
        <div id="flush-collapse{{ $i }}" class="accordion-collapse collapse"
            aria-labelledby="staff-listing-{{ $i }}"
            data-bs-parent="#accordionFlushExample{{ $i }}" style="">
            <div class="accordion-body">
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.nameTh') }}</div>
                    <div class="w-50">Tiger Nixon {{ $i }}</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.birthDateTh') }}</div>
                    <div class="w-50">2011/04/25</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.addressTh') }}</div>
                    <div class="w-50">Chandigarh</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.telephoneTh') }}</div>
                    <div class="w-50">987456321{{ $i }}</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.emailTh') }}</div>
                    <div class="w-50">test@yopmail.com</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.professionTh') }}</div>
                    <div class="w-50">Therapist</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.licenceNumberTh') }}</div>
                    <div class="w-50">987456321{{ $i }}</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.roleTh') }}</div>
                    <div class="w-50">Professional Therapist</div>
                </div>
                <div class="d-flex">
                    <div class="w-50">{{ __('staff.kindergartenTh') }}</div>
                    <div class="w-50">Hatsav</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endfor