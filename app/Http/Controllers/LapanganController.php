<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Rekening;
use App\Lapangan;
use App\LapanganOlahraga;
use App\LapanganSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Image;
use File;

class LapanganController extends Controller
{
    protected $imagePath;
    protected $jenis_rekening;

    public function __construct(){
        $this->imagePath = storage_path('app/public/images/lapangan');
        $this->jenis_rekening = Rekening::$jenisRekening;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $validator = Validator::make($request->all(), [
            'filter' => 'sometimes',
            'filter.*' => [Rule::in(LapanganOlahraga::$jenisOlahraga)]
        ]);
        if($validator->fails()){
            return redirect()->route('lapangan.index');
        }

        if($request->has('filter')){
            if(Auth::user()->isPL()){
                $lapangans = Auth::user()->lapangan()->whereHas('olahraga', function($query) use ($filter){
                    $query->whereIn('jenis_olahraga', $filter);
                })->orderBy('lapangans.id')->paginate(8);
            }elseif(Auth::user()->isCS()){
                $lapangans = Auth::user()->serve()->whereHas('olahraga', function($query) use ($filter){
                    $query->whereIn('jenis_olahraga', $filter);
                })->orderBy('lapangans.id')->paginate(8);
            }else{
                $lapangans = Lapangan::whereHas('olahraga', function($query) use ($filter){
                    $query->whereIn('jenis_olahraga', $filter);
                })->orderBy('lapangans.id')->paginate(8);
            }
        }else{
            if(Auth::user()->isPL()){
                $lapangans = Auth::user()->lapangan()->where(function($query) use ($search){
                    $query->where('name', 'like', '%'.$search.'%')->orWhereHas('olahraga', function($query) use ($search){
                        $query->where('jenis_olahraga', 'like', '%'.$search.'%');
                    });
                })->orderBy('lapangans.id')->paginate(8);
            }elseif(Auth::user()->isCS()){
                $lapangans = Auth::user()->serve()->where(function($query) use ($search){
                    $query->where('name', 'like', '%'.$search.'%')->orWhereHas('olahraga', function($query) use ($search){
                        $query->where('jenis_olahraga', 'like', '%'.$search.'%');
                    });
                })->groupBy('lapangans.id')->orderBy('lapangans.id')->paginate(8);
            }else{
                $lapangans = Lapangan::whereHas('olahraga', function($query) use ($search){
                    $query->where('lapangans.name', 'like', '%'.$search.'%')->orWhere('jenis_olahraga', 'like', '%'.$search.'%');
                })->orderBy('lapangans.id')->paginate(8);
            }
        }

        if($request->has('filter')){
            $lapangans->appends(['filter' => $request->filter]);
        }elseif($request->has('search')){
            $lapangans->appends(['search' => $request->search]);
        }
        return view('user.dashboard', compact('lapangans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles('PL');
        return view('user.lapangan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->authorizeRoles('PL');
        $request->validate([
            'name' => ['required', 'string', 'max:191', 'unique:lapangans'],
            'location' => ['required', 'string', 'max:191'],
            'jam_buka' => ['required', 'integer', 'between:0,24'],
            'jam_tutup' => ['required', 'integer', 'between:0,24', 'gt:jam_buka'],
            'hari_buka' => ['required', 'min:1'],
            'hari_buka.*' => [Rule::in([1, 2, 3, 4, 5, 6, 0])],
            'no_telepon' => ['nullable', 'digits_between:9,13'],
            'image' => ['image'],
            'jenis_rekening' => ['required', Rule::in($this->jenis_rekening)],
            'rekening' => ['required', 'digits_between:10,15'],
            'atas_nama' => ['required', 'string', 'max:191'],
        ]);

        $lapangan = Lapangan::create([
            'name' => ucwords($request->name),
            'user_id' => Auth::user()->id,
            'location' => ucwords($request->location),
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup
        ]);

        $rekening = new Rekening([
            'lapangan_id' => $lapangan->id,
            'jenis_rekening' => $request->jenis_rekening,
            'rekening' => $request->rekening,
            'rekening_atas_nama' => $request->atas_nama,
        ]);

        $lapangan->rekening()->save($rekening);

        if($request->no_telepon){
            $lapangan->no_telepon = $request->no_telepon;
            $lapangan->save();
        }

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

            $lapangan->image = $image;
            $lapangan->image_resized = $resizedImage;
            $lapangan->save();
        }

        foreach($request->hari_buka as $hari_buka){
            LapanganSchedule::create([
                'lapangan_id' => $lapangan->id,
                'hari_buka' => $hari_buka,
            ]);
        }

        return redirect()->route('lapangan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lapangan  $lapangan
     * @return \Illuminate\Http\Response
     */
    public function show(Lapangan $lapangan)
    {
        if(Auth::user()->isCS() && Auth::user()->serve()->where('lapangan_id', $lapangan->id)->count() == 0){
            return abort(401);
        }
        if(Auth::user()->isPO() && $lapangan->olahraga->count() == 0){
            return abort(401);
        }
        if(Auth::user()->isPL() && $lapangan->user->id != Auth::user()->id){
            return abort(401);
        }
        $olahragas = $lapangan->olahraga()->paginate(4);
        return view('user.lapangan.show', compact('lapangan', 'olahragas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lapangan  $lapangan
     * @return \Illuminate\Http\Response
     */
    public function edit(Lapangan $lapangan)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id == Auth::user()->id)
            return view('user.lapangan.edit', compact('lapangan'));
        else
            return abort(401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lapangan  $lapangan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lapangan $lapangan)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id != Auth::user()->id)
            return abort(401);
        $request->validate([
            'name' => ['required', 'string', 'max:191', Rule::unique('lapangans')->ignore($lapangan->id)],
            'location' => ['required', 'string', 'max:191'],
            'jam_buka' => ['required', 'integer', 'between:0,24'],
            'jam_tutup' => ['required', 'integer', 'between:0,24', 'gt:jam_buka'],
            'hari_buka' => ['required', 'min:1'],
            'hari_buka.*' => [Rule::in([1, 2, 3, 4, 5, 6, 0])],
            'no_telepon' => ['nullable', 'digits_between:9,13'],
            'image' => ['image'],
            'jenis_rekening' => ['required', Rule::in($this->jenis_rekening)],
            'rekening' => ['required', 'digits_between:10,15'],
            'atas_nama' => ['required', 'string', 'max:191'],
        ]);

        $lapangan->name = ucwords($request->name);
        $lapangan->location = ucwords($request->location);
        $lapangan->jam_buka = $request->jam_buka;
        $lapangan->jam_tutup = $request->jam_tutup;
        if($request->no_telepon){
            $lapangan->no_telepon = $request->no_telepon;
        }
        $rekeningTerbaru = $lapangan->rekeningTerbaru;
        if($rekeningTerbaru->jenis_rekening != $request->jenis_rekening || $rekeningTerbaru->rekening != $request->rekening || $rekeningTerbaru->rekening_atas_nama != $request->atas_nama){
            $rekening = new Rekening([
                'lapangan_id' => $lapangan->id,
                'jenis_rekening' => $request->jenis_rekening,
                'rekening' => $request->rekening,
                'rekening_atas_nama' => $request->atas_nama,
            ]);
            $lapangan->rekening()->save($rekening);
        }
        if($request->has('image')){
            if (!File::isDirectory($this->imagePath)) {
                File::makeDirectory($this->imagePath, 0755, true);
            }
            if(File::exists($this->imagePath.'/'.$lapangan->image)){
                Storage::delete('public/images/lapangan/'.$lapangan->image);
            }

            if(File::exists($this->imagePath.'/'.$lapangan->image_resized)){
                Storage::delete('public/images/lapangan/'.$lapangan->image_resized);
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

            $lapangan->image = $image;
            $lapangan->image_resized = $resizedImage;
        }
        $lapangan->save();

        $lapangan->schedule()->delete();

        foreach($request->hari_buka as $hari_buka){
            LapanganSchedule::create([
                'lapangan_id' => $lapangan->id,
                'hari_buka' => $hari_buka,
            ]);
        }

        return redirect()->route('lapangan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lapangan  $lapangan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lapangan $lapangan)
    {
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id == Auth::user()->id)
            if(File::exists($this->imagePath.'/'.$lapangan->image)){
                Storage::delete('public/images/lapangan/'.$lapangan->image);
            }

            if(File::exists($this->imagePath.'/'.$lapangan->image_resized)){
                Storage::delete('public/images/lapangan/'.$lapangan->image_resized);
            }
            $lapangan->delete();
        return redirect()->back();
    }

    public function imageRemove(Lapangan $lapangan){
        Auth::user()->authorizeRoles('PL');
        if($lapangan->user->id == Auth::user()->id)
            if(File::exists($this->imagePath.'/'.$lapangan->image)){
                Storage::delete('public/images/lapangan/'.$lapangan->image);
            }

            if(File::exists($this->imagePath.'/'.$lapangan->image_resized)){
                Storage::delete('public/images/lapangan/'.$lapangan->image_resized);
            }
            $lapangan->image = null;
            $lapangan->image_resized = null;
            $lapangan->save();
        return redirect()->back();
    }
}
