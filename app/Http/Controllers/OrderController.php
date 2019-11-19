<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\Lapangan;
use App\LapanganOlahraga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Lapangan $lapangan, LapanganOlahraga $olahraga, Request $request)
    {
        Auth::user()->authorizeRoles(['PO', 'CS']);
        if($olahraga->lapangan->id != $lapangan->id){
            return abort(401);
        }
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        $tanggal_pesan = $request->tanggal_pesan;
        
        $tanggal = Carbon::create($tanggal_pesan);
        $now = Carbon::now()->setTimezone($lapangan->timezone);
        if($olahraga->isBooked($tanggal, $request->jam_mulai)){
            return abort(401);
        }
        if($tanggal->toDateString() < $now->toDateString() || $lapangan->dayOfWeek($tanggal->dayOfWeek) == false){
            return redirect()->back();
        }
        if(Auth::user()->isPO() && $tanggal->toDateString() == $now->toDateString() && $request->jam_mulai <= $now->hour + Order::$timeLimit){
            return redirect()->route('olahraga.show', compact('lapangan', 'olahraga'));
            // return redirect()->back();
        }elseif(Auth::user()->isCS() && $tanggal->toDateString() == $now->toDateString() && $request->jam_mulai <= $now->hour){
            return redirect()->route('olahraga.show', compact('lapangan', 'olahraga'));
            // return redirect()->back();
        }

        $jam_mulai = $request->jam_mulai;
        $jam_tutup = $lapangan->jam_tutup;
        if($jam_mulai == $jam_tutup){
            return redirect()->back();
        }
        $jam_selesai = $this->jam_selesai($olahraga, $tanggal, $jam_mulai, $jam_tutup);
        return view('user.lapangan.olahraga.order.create', compact('lapangan', 'olahraga', 'jam_selesai'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Lapangan $lapangan, LapanganOlahraga $olahraga, Request $request)
    {
        Auth::user()->authorizeRoles(['PO', 'CS']);
        if($olahraga->lapangan->id != $lapangan->id){
            return abort(401);
        }
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        $jam_buka = $lapangan->jam_buka;
        $jam_tutup = $lapangan->jam_tutup;
        $jam_mulai = range($jam_buka, $jam_tutup - 1);

        $hari_buka = $lapangan->schedule()->pluck('hari_buka')->toArray();
        $tanggal = Carbon::create($request->tanggal_pesan);
        $now = Carbon::now()->setTimezone($lapangan->timezone);
        if($tanggal->toDateString() < $now->toDateString() || $lapangan->dayOfWeek($tanggal->dayOfWeek) == false){
            return redirect()->back();
        }
        if(Auth::user()->isPO() && $tanggal->toDateString() == $now->toDateString() && $request->jam_mulai <= $now->hour + Order::$timeLimit){
            return redirect()->route('olahraga.show', compact('lapangan', 'olahraga'));
            // return redirect()->back();
        }elseif(Auth::user()->isCS() && $tanggal->toDateString() == $now->toDateString() && $request->jam_mulai <= $now->hour){
            return redirect()->route('olahraga.show', compact('lapangan', 'olahraga'));
            // return redirect()->back();
        }
        
        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'tanggal_pesan' => ['required'],
            'jam_mulai' => ['required', Rule::in($jam_mulai)],
        ]);
        $jam_selesai = $this->jam_selesai($olahraga, $tanggal, $request->jam_mulai, $jam_tutup);
        
        $request->validate([
            'jam_selesai' => ['required', Rule::in($jam_selesai)],
        ]);

        foreach(range($request->jam_mulai, $request->jam_selesai) as $jam){
            if($olahraga->isBooked($tanggal, $jam)){
                abort(401);
            }
        }

        $order = Order::create([
            'user_id' => Auth::user()->id,
            'lapangan_olahraga_id' => $olahraga->id,
            'discount_id' => $olahraga->diskon != null ? $olahraga->diskon->id : null,
            'rekening_id' => $lapangan->rekeningTerbaru->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'harga_per_jam' => $olahraga->harga_per_jam,
            'tanggal_pesan' => $request->tanggal_pesan,
            'jam_pesan_start' => $request->jam_mulai,
            'jam_pesan_end' => $request->jam_selesai,
        ]);
        if(isset($request->name)){
            $order->name = $request->name;
            $order->save();
        }
        if(Auth::user()->isPO()){
            return redirect()->route('PO.pembayaran.index');
        }else{
            $order->payment_status = 'accepted';
            $order->save();
            return redirect()->route('olahraga.show', ['lapangan' => $lapangan, 'olahraga' => $olahraga]);
        }
    }

    public function jam_selesai(LapanganOlahraga $olahraga, $tanggal, $jam_mulai, $jam_tutup){
        $jam_selesai = array();
        for ($i= $jam_mulai + 1; $i <= $jam_tutup; $i++) { 
            if($olahraga->order()->whereNotIn('payment_status', ['canceled', 'denied'])->where('tanggal_pesan', $tanggal->toDateString())->where('jam_pesan_start', $i)->count() == 0){
                array_push($jam_selesai, $i);
            }else{
                array_push($jam_selesai, $i);
                break;
            }
        }
        if(empty($jam_selesai)){
            array_push($jam_selesai, $jam_mulai + 1);
        }
        return $jam_selesai;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
