<form action="{{ route('rate-product') }}" method="POST" id="reviewForm">
    @csrf
    @honeypot
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="rating" id="rating" value="5">
    <div class="row">
        <div class="col-12">
            <div class="form-group starRatingWrap jqv-group">
                <div class="rwTle">Your rating</div>
                <div class="starRating reviewStarrating" data-value="5" id="starInput"></div>
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group jqv-group">
                <label class="visually-hidden">Write a review*</label>
                <textarea class="form-control" name="comment" placeholder="Write a review" required
                    data-msg-required="Enter a valid review" data-rule-validMessage="true"></textarea>
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="form-group jqv-group">
            <label class="visually-hidden">Profession / Company*</label>
            <input type="text" name="profession" class="form-control" placeholder="Profession/Company" required
                data-rule-alphanumericBasicPunctuation="true"
                data-msg-required="Please enter a valid profession or company" data-rule-minlength="2"
                data-rule-maxlength="191">
            <div class="help-block danger"></div>
        </div>
        <div class="col-12">
            <div class="form-group jqv-group">
                <label class="usr">Posting as</label>
                <div class="userInput">
                    <div class="icon">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold"
                            style="width: 14px; height: 14px; font-size: 10px;">
                            {{ getInitials(Auth::user()->name ?? 'User') }}
                        </div>
                    </div>
                    <input type="text" name="display_name" class="form-control" placeholder="author"
                        value="{{ Auth::user()->name }}" required data-rule-validName="true"
                        data-msg-required="Please enter a valid name" data-rule-minlength="2" data-rule-maxlength="191">
                </div>
                <div class="help-block danger"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group jqv-group">
                <label class="usr">Add Your Workspace Photo (optional)</label>
                <div class="userInput">
                    <div class="icon">
                        <img src="{{ asset('frontend/images/image-gallery.png') }}" width="14" height="14"
                            loading="lazy" alt="upload">
                    </div>
                    <input type="file" name="image" class="form-control" id="image" data-rule-imageFile="true">
                </div>
                <div class="help-block danger"></div>
            </div>
        </div>
    </div>
</form>

@push('js')
    {{-- STAR_RATING --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/star-rating-svg.css') }}">
    <script src="{{ asset('frontend/js/jquery.star-rating-svg.min.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // STAR_RATING
            var initialRating = $('#starInput').attr("data-value");
            $('#starInput').starRating({
                initialRating: initialRating,
                emptyColor: "#f6f6f6",
                activeColor: '#f7d168',
                ratedColor: "#f00",
                useGradient: false,
                starSize: 40,
                starShape: 'rounded',
                disableAfterRate: false,
                useFullStars: true,
                minRating: 1,
                maxRating: 5,
                callback: function(currentRating, $el) {
                    $('#rating').val(currentRating);
                }
            });
        });
    </script>
@endpush
