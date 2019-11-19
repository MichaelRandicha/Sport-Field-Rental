<?php

namespace App\Http\Controllers;

use Auth;
use DateTime;
use App\Discount;
use App\Lapangan;
use App\LapanganOlahraga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Image;
use File;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class LapanganOlahragaController extends Controller
{
    protected $jenis_olahraga;
    protected $imagePath;

    public function __construct(){
        $this->jenis_olahraga = LapanganOlahraga::$jenisOlahraga;
        $this->imagePath = public_path('storage/images/lapangan/olahraga');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Lapangan $lapangan)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id)
            return abort(401);
        return view('user.lapangan.olahraga.create', compact('lapangan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Lapangan $lapangan, Request $request)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id)
            return abort(401);
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'fasilitas' => ['required', 'string', 'max:191'],
            'harga' => ['required', 'min:0'],
            'jenis_olahraga' => ['required', 'string', Rule::in($this->jenis_olahraga)],
            'image' => ['image'],
        ]);


        $olahraga = LapanganOlahraga::create([
            'lapangan_id' => $lapangan->id,
            'name' => ucwords($request->name),
            'fasilitas' => $request->fasilitas,
            'harga_per_jam' => $request->harga,
            'jenis_olahraga' => $request->jenis_olahraga,
        ]);

        if($request->has('image')){
            if (!File::isDirectory($this->imagePath)) {
                File::makeDirectory($this->imagePath, 0755, true);
            }
            //Saving Original
            $file = $request->file('image');
            $image = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $resizedImage = Carbon::now()->timestamp . '_' . uniqid() . '_resized.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->imagePath . '/' . $image);
            //Saving Resized Image
            $canvas = Image::canvas(400,300);
            $resized = Image::make($file)->resize(400,300);
            $canvas->insert($resized, 'center');
            $canvas->save($this->imagePath . '/' . $resizedImage);

            $olahraga->image = $image;
            $olahraga->image_resized = $resizedImage;
            $olahraga->save();
        }

        return redirect()->route('lapangan.show', ['lapangan' => $lapangan]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LapanganOlahraga  $olahraga
     * @return \Illuminate\Http\Response
     */
    public function show(Lapangan $lapangan, LapanganOlahraga $olahraga, Request $request)
    {
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        if(Auth::user()->isPL() && $lapangan->user->id != Auth::user()->id){
            return abort(401);
        }
        if($olahraga->lapangan->id != $lapangan->id){
            return abort(401);
        }
        $items = array();
        $currentPage = Paginator::resolveCurrentPage();
        $endofmonths = (new Carbon())->addWeeks(4)->endOfWeek();
        $now = (new Carbon())->startOfWeek();

        $items = CarbonPeriod::create($now, $endofmonths);
        $currentItems = array_slice($items->toArray(), ($currentPage - 1) * 7, 7);

        $week = new Paginator($currentItems, $items->count(), 7, $request->page, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);
        return view('user.lapangan.olahraga.show', compact('lapangan', 'olahraga', 'week'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LapanganOlahraga  $olahraga
     * @return \Illuminate\Http\Response
     */
    public function edit(Lapangan $lapangan, LapanganOlahraga $olahraga)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id)
            return abort(401);
        return view('user.lapangan.olahraga.edit', compact('lapangan', 'olahraga'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LapanganOlahraga  $olahraga
     * @return \Illuminate\Http\Response
     */
    public function update(Lapangan $lapangan, Request $request, LapanganOlahraga $olahraga)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id)
            return abort(401);
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'fasilitas' => ['required', 'string', 'max:191'],
            'harga' => ['required', 'min:0'],
            'jenis_olahraga' => ['required', 'string', Rule::in($this->jenis_olahraga)],
            'image' => ['image'],
        ]);

        $olahraga->name = ucwords($request->name);
        $olahraga->fasilitas = $request->fasilitas;
        $olahraga->harga_per_jam = $request->harga;
        $olahraga->jenis_olahraga = $request->jenis_olahraga;

        if($request->has('image')){
            if (!File::isDirectory($this->imagePath)) {
                File::makeDirectory($this->imagePath, 0755, true);
            }

            if(File::exists($this->imagePath.'/'.$olahraga->image)){
                Storage::delete('public/images/lapangan/olahraga/'.$olahraga->image);
            }

            if(File::exists($this->imagePath.'/'.$olahraga->image_resized)){
                Storage::delete('public/images/lapangan/olahraga/'.$olahraga->image_resized);
            }
            // Saving Original
            $file = $request->file('image');
            $image = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $resizedImage = Carbon::now()->timestamp . '_' . uniqid() . '_resized.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->imagePath . '/' . $image);
            //Saving Resized Image
            $canvas = Image::canvas(400,300);
            $resized = Image::make($file)->resize(400,300);
            $canvas->insert($resized, 'center');
            $canvas->save($this->imagePath . '/' . $resizedImage);

            $olahraga->image = $image;
            $olahraga->image_resized = $resizedImage;
        }
        $olahraga->save();

        return redirect()->route('lapangan.show', ['lapangan' => $lapangan]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LapanganOlahraga  $olahraga
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lapangan $lapangan, LapanganOlahraga $olahraga)
    {
        Auth::user()->authorizeRoles('PL');
        if($olahraga->lapangan->user->id == Auth::user()->id)
            if(File::exists($this->imagePath.'/'.$olahraga->image)){
                Storage::delete('public/images/lapangan/olahraga/'.$olahraga->image);
            }
            if(File::exists($this->imagePath.'/'.$olahraga->image_resized)){
                Storage::delete('public/images/lapangan/olahraga/'.$olahraga->image_resized);
            }
            $olahraga->delete();
        return redirect()->back();
    }

    public function imageRemove(Lapangan $lapangan, LapanganOlahraga $olahraga){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id)
            if(File::exists($this->imagePath.'/'.$olahraga->image)){
                Storage::delete('public/images/lapangan/olahraga'.$olahraga->image);
            }

            if(File::exists($this->imagePath.'/'.$olahraga->image_resized)){
                Storage::delete('public/images/lapangan/olahraga'.$olahraga->image_resized);
            }
            $olahraga->image = null;
            $olahraga->image_resized = null;
            $olahraga->save();
        return redirect()->back();
    }

    public function discountManage(Lapangan $lapangan, LapanganOlahraga $olahraga){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id || $olahraga->harga_per_jam == 0)
            return abort(401);
        $discounts = $olahraga->discount()->orderBy('dari_tanggal', 'DESC')->paginate(5);
        return view('user.lapangan.olahraga.discount.manage', compact('lapangan', 'olahraga', 'discounts'));

    }

    public function discountAdd(Lapangan $lapangan, LapanganOlahraga $olahraga, Discount $discount){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id || $olahraga->harga_per_jam == 0)
            return abort(401);
        return view('user.lapangan.olahraga.discount.add', compact('lapangan', 'olahraga', 'discount'));
    }

    public function discountStore(Lapangan $lapangan, LapanganOlahraga $olahraga, Discount $discount, Request $request){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id || $olahraga->harga_per_jam == 0)
            return abort(401);
        $request->validate([
            'discount' => ['required', 'integer', 'between:0,100'],
            'dari_tanggal' => ['required', 'after_or_equal: '.Carbon::now()->toDateString()],
            'sampai_tanggal' => ['required', 'date', 'after_or_equal:dari_tanggal'],
            'dari_jam' => ['required', 'integer', 'between:'.$lapangan->jam_buka.','.$lapangan->jam_tutup],
            'sampai_jam' => ['required', 'integer', 'between:'.$lapangan->jam_buka.','.$lapangan->jam_tutup,'gt:dari_jam'],
        ]);

        if($test = $this->checkDiscountCollision($olahraga, $request->dari_tanggal, $request->sampai_tanggal, $request->dari_jam, $request->sampai_jam)){
            abort(401);
        }
        
        $olahraga->discount()->save(new Discount([
            'lapangan_olahraga_id' => $olahraga->id,
            'discount' => $request->discount,
            'dari_tanggal' => $request->dari_tanggal,
            'sampai_tanggal' => $request->sampai_tanggal,
            'dari_jam' => $request->dari_jam,
            'sampai_jam' => $request->sampai_jam,
        ]));

        return redirect()->route('olahraga.discount.manage', compact('lapangan', 'olahraga'));
    }

    public function checkDiscountCollision(LapanganOlahraga $olahraga, $dari_tanggal, $sampai_tanggal, $dari_jam, $sampai_jam, $diskon = null){
        $from = new DateTime($dari_tanggal);
        $to = new DateTime($sampai_tanggal);
        for($date = $from; $date <= $to; $date->modify('+1 day')){
            foreach(range($dari_jam, $sampai_jam) as $jam){
                if($this->checkDiskon($olahraga, $date, $jam, $dari_jam, $sampai_jam, $diskon)){
                    return true;
                }
            }
        }
    }

    public function checkDiskon(LapanganOlahraga $olahraga, $tanggal, $jam, $dari_jam, $sampai_jam, $diskon){
        if($diskon != null){
            $discount = $olahraga->discount()->where('id', '!=', $diskon->id)->whereDate('dari_tanggal', '<=', $tanggal)->whereDate('sampai_tanggal', '>=', $tanggal)->where('dari_jam', '<', $jam)->where('sampai_jam', '>', $jam)->get();
            $jam_diskon = $olahraga->discount()->where('id', '!=', $diskon->id)->whereDate('dari_tanggal', '<=', $tanggal)->whereDate('sampai_tanggal', '>=', $tanggal)->get();
        }else{
            $discount = $olahraga->discount()->whereDate('dari_tanggal', '<=', $tanggal)->whereDate('sampai_tanggal', '>=', $tanggal)->where('dari_jam', '<', $jam)->where('sampai_jam', '>', $jam)->get();
            $jam_diskon = $olahraga->discount()->whereDate('dari_tanggal', '<=', $tanggal)->whereDate('sampai_tanggal', '>=', $tanggal)->get();
        }
        foreach($jam_diskon as $jd){
            if($jd->sampai_jam - $jd->dari_jam == 1 && ($jd->sampai_jam <= $sampai_jam && $jd->dari_jam >= $dari_jam)){
                return true;
            }
        }
        return $discount->count() > 0;
    }

    public function discountEdit(Lapangan $lapangan, LapanganOlahraga $olahraga, Discount $discount){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id || $olahraga->harga_per_jam == 0)
            return abort(401);
        return view('user.lapangan.olahraga.discount.edit', compact('lapangan', 'olahraga', 'discount'));
    }

    public function discountUpdate(Lapangan $lapangan, LapanganOlahraga $olahraga, Discount $discount, Request $request){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id)
            return abort(401);
        $request->validate([
            'discount' => ['required', 'integer', 'between:0,100'],
            'dari_tanggal' => ['required', 'after_or_equal: '.Carbon::now()->toDateString()],
            'sampai_tanggal' => ['required', 'date', 'after_or_equal:dari_tanggal'],
            'dari_jam' => ['required', 'integer', 'between:'.$lapangan->jam_buka.','.$lapangan->jam_tutup],
            'sampai_jam' => ['required', 'integer', 'between:'.$lapangan->jam_buka.','.$lapangan->jam_tutup,'gt:dari_jam'],
        ]);

        if($test = $this->checkDiscountCollision($olahraga, $request->dari_tanggal, $request->sampai_tanggal, $request->dari_jam, $request->sampai_jam, $discount)){
            abort(401);
        }
        
        $discount->discount = $request->discount;
        $discount->dari_tanggal = $request->dari_tanggal;
        $discount->sampai_tanggal = $request->sampai_tanggal;
        $discount->dari_jam = $request->dari_jam;
        $discount->sampai_jam = $request->sampai_jam;
        $discount->save();

        return redirect()->route('olahraga.discount.manage', compact('lapangan', 'olahraga'));
    }

    public function discountRemove(Lapangan $lapangan, LapanganOlahraga $olahraga, Discount $discount){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id || $olahraga->lapangan->id != $lapangan->id || $olahraga->harga_per_jam == 0)
            return abort(401);
        $discount->delete();
        return redirect()->route('olahraga.discount.manage', compact('lapangan', 'olahraga'));
    }

    public function review(Lapangan $lapangan, LapanganOlahraga $olahraga){
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        if(Auth::user()->isPL() && $lapangan->user->id != Auth::user()->id){
            return abort(401);
        }
        if($olahraga->lapangan->id != $lapangan->id){
            return abort(401);
        }
        $reviews = $olahraga->review()->paginate(5);
        return view('user.lapangan.olahraga.show', compact('lapangan', 'olahraga', 'reviews'));
    }
}
