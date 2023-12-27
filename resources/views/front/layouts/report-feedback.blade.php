<div class="feedback-form">
    <div class="container">
    <div class="feedbackform-inner">
        <h2>Feedback From Our Clients</h2>
        <div class="feedback-containt">
            <div class="containt-inner">
                <div class="feedback-slider mb-0" id="feedbackSlider" style="position: relative;">
                    @foreach ($clientfeedbacks as $clientfeedback)
                    <div class="item">
                        {!! isset($clientfeedback->feedback) ? $clientfeedback->feedback : '' !!}
                        <p style="line-height:inherit;">- {!! isset($clientfeedback->name) ? $clientfeedback->name : '' !!}{!! isset($clientfeedback->designation) ? ', '.$clientfeedback->designation : '' !!}{!! isset($clientfeedback->company_name) ? ', '.$clientfeedback->company_name : '' !!}.</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
</div>