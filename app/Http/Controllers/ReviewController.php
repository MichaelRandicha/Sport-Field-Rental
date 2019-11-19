<?php

namespace App\Http\Controllers;

use Auth;
use App\Review;
use App\Lapangan;
use App\LapanganOlahraga;
use App\Notifications\NewReview;
use App\Notifications\ReviewReply;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function add(Lapangan $lapangan, LapanganOlahraga $olahraga, Request $request)
    {
    	Auth::user()->authorizeRoles('PO');
        if($olahraga->lapangan->id != $lapangan->id){
            return abort(401);
        }
        $request->validate([
        	'rating' => ['sometimes', 'integer', 'between:0,5'],
            'review' => ['required', 'string', 'max:191'],
        ]);
        
        $review = Review::create([
        	'user_id' => Auth::user()->id,
        	'lapangan_olahraga_id' => $olahraga->id,
        	'rating' => $request->rating ? $request->rating : 0,
        	'review' => $request->review,
        ]);
        Notification::send($lapangan->cs, new NewReview($review));
        return redirect()->route('olahraga.review.index', compact('lapangan', 'olahraga'));
    }

    public function reply(Lapangan $lapangan, LapanganOlahraga $olahraga, Review $review, Request $request)
    {
    	Auth::user()->authorizeRoles('CS');
        if($olahraga->lapangan->id != $lapangan->id || $review->olahraga->id != $olahraga->id || Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        $request->validate([
            'review' => ['required', 'string', 'max:191'],
        ]);
        $review->tanggapan = $request->review;
        $review->save();
        $review->user->notify(new ReviewReply($review));
        return redirect()->route('olahraga.review.index', compact('lapangan', 'olahraga'));
    }

    public function edit(Lapangan $lapangan, LapanganOlahraga $olahraga, Review $review)
    {
    	Auth::user()->authorizeRoles(['CS', 'PO']);
    	if($olahraga->lapangan->id != $lapangan->id || $review->olahraga->id != $olahraga->id){
            return abort(401);
        }
        if(Auth::user()->isPO() && $review->user->id != Auth::user()->id){
            return abort(401);
        }
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        return view('user.lapangan.olahraga.review.edit', compact('lapangan', 'olahraga', 'review'));
    }

    public function update(Lapangan $lapangan, LapanganOlahraga $olahraga, Review $review, Request $request)
    {
    	Auth::user()->authorizeRoles(['CS', 'PO']);
    	if($olahraga->lapangan->id != $lapangan->id || $review->olahraga->id != $olahraga->id){
            return abort(401);
        }
        if(Auth::user()->isPO() && $review->user->id != Auth::user()->id){
            return abort(401);
        }
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        $request->validate([
        	'rating' => ['sometimes', 'integer', 'between:0,5'],
            'review' => ['required', 'string', 'max:191'],
        ]);
        if($request->has('rating') && Auth::user()->isPO()){
        	$review->rating = $request->rating;
            $review->review = $request->review;
        }
        if(Auth::user()->isCS()){
            $review->tanggapan = $request->review;
        }
        $review->save();
        return redirect()->route('olahraga.review.index', compact('lapangan', 'olahraga'));
    }
}
