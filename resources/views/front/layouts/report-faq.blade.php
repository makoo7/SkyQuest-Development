<div class="faq-sec">
    <div class="container">
        <h3 class="title">FAQ's</h3>
        <div class="accordion faq-accordion" id="faqAccordion">
            @if(isset($report->report_faq))
            @foreach($report->report_faq as $k => $faq_item)
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeading{{$k}}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{$k}}" aria-expanded="true" aria-controls="faqCollapse{{$k}}">
                        {!! $faq_item->faq_question !!}
                    </button>
                </h2>
                <div id="faqCollapse{{$k}}" class="accordion-collapse collapse" aria-labelledby="faqHeading{{$k}}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {!! $faq_item->faq_answer !!}
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>