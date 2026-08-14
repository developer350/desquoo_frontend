<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogCommentRequest;
use App\Http\Requests\EnquiryRequest;
use App\Http\Requests\NewsletterRequest;
use App\Http\Requests\NotifyMeRequest;
use App\Http\Requests\QuestionFormRequest;
use App\Http\Requests\RateProductRequest;
use App\Http\Requests\VisitRequest;
use App\Mail\BlogCommentAdminMail;
use App\Mail\BlogCommentMail;
use App\Mail\EnquiryMail;
use App\Mail\EnquiryMailAdmin;
use App\Mail\GotAQuestionAdminMail;
use App\Mail\NewsletterAdminMail;
use App\Mail\NewsletterMail;
use App\Mail\NotifyAdminMail;
use App\Mail\NotifyMail;
use App\Mail\ProductReviewAdminMail;
use App\Mail\ProductReviewMail;
use App\Mail\VisitMailAdmin;
use App\Models\BlogComment;
use App\Models\GotAQuestion;
use App\Models\NewsletterSubscription;
use App\Models\NotifyMe;
use App\Models\ProductReview;
use App\Models\VisitEnquiry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function visitForm(VisitRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->merge([
                'phone_number' => $request->country_code.$request->phone_number,
            ]);
            $data = $request->all();
            $enquiry = VisitEnquiry::create($data);
            DB::commit();

            // send mail
            defer(function () use ($enquiry) {
                Mail::to($enquiry->email)->send(new EnquiryMail($enquiry));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new VisitMailAdmin($enquiry));
            })->always();

            return response()->json(['status' => true, 'message' => 'Your submission has been received.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function enquiryForm(EnquiryRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $model = $data['model'];
            $modelClass = 'App\\Models\\'.$model;
            $enquiry = $modelClass::create($data);
            DB::commit();

            // send mail
            defer(function () use ($enquiry) {
                Mail::to($enquiry->email)->send(new EnquiryMail($enquiry));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new EnquiryMailAdmin($enquiry));
            })->always();

            return response()->json(['status' => true, 'message' => 'Your submission has been received.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function rateProduct(RateProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $review = ProductReview::create([
                'product_id' => $data['product_id'],
                'user_id' => auth()->user()->id,
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'display_name' => $data['display_name'],
                'profession' => $data['profession'],
                'status' => false,
            ]);

            $review->addMediaFromRequest('image')->toMediaCollection('review_image');

            DB::commit();

            // send mail
            defer(function () use ($review) {
                Mail::to($review->user->email)->send(new ProductReviewMail($review));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new ProductReviewAdminMail($review));
            })->always();

            return response()->json(['status' => true, 'message' => 'Your review has been submitted and will be published soon.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function notifyMe(NotifyMeRequest $request)
    {
        DB::beginTransaction();
        try {

            $notify = NotifyMe::create($request->all());
            DB::commit();

            // send mail
            defer(function () use ($notify) {
                Mail::to($notify->email)->send(new NotifyMail($notify));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new NotifyAdminMail($notify));
            })->always();

            return response()->json(['status' => true, 'message' => 'We will notify you when the stock is available.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function blogComment(BlogCommentRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $comment = BlogComment::create($data);
            DB::commit();

            // send mail
            defer(function () use ($comment) {
                Mail::to($comment->email)->send(new BlogCommentMail($comment));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new BlogCommentAdminMail($comment));
            })->always();

            return response()->json(['status' => true, 'message' => 'Your submission has been received.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function newsletterSubmit(NewsletterRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $newsletter = NewsletterSubscription::create($data);
            DB::commit();

            // send mail
            defer(function () use ($newsletter) {
                Mail::to($newsletter->email)->send(new NewsletterMail($newsletter));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new NewsletterAdminMail($newsletter));
            })->always();

            return response()->json(['status' => true, 'message' => 'Newsletter subscribed successfully.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function questionForm(QuestionFormRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->merge([
                'phone_number' => $request->country_code.$request->phone_number,
            ])->all();

            $enquiry = GotAQuestion::create($data);

            DB::commit();

            // send mail
            defer(function () use ($enquiry) {
                Mail::to($enquiry->email)->send(new EnquiryMail($enquiry));
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new GotAQuestionAdminMail($enquiry->load('product:id,name')));
            })->always();

            return response()->json(['status' => true, 'message' => 'Your question has been submitted.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['status' => false, 'message' => 'Something went wrong.']);
        }
    }
}
