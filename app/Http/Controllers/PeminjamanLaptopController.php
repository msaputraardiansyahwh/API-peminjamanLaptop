<?php

namespace App\Http\Controllers;

use App\Models\peminjamanLaptop;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Exception;
use Spatie\FlareClient\Api;

class PeminjamanLaptopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //ambil data dari key search_nama bagian params nya postman
        $search = $request->search_nama;
        //ambil data dari key limit bagian params nya postman
        $limit = $request->limit;
        //caridata berdasarkan yang di search
        $laptops = peminjamanLaptop::where('nama', 'LIKE', '%'.$search.'%')
        ->limit($limit)->get();
        // $laptops = peminjamanLaptop::all();
        if ($laptops) {
            // kalau data berehasil diambil
            return ApiFormatter::createAPI(200, 'succes', $laptops);
        }else {
            // kalau data gagal diambil
            return ApiFormatter::createAPI(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //untuk memvalidasi data
            $request->validate([
                'no_laptop' =>'required',
                'nama' => 'required|min:8',
                'nis' => 'required|min:3',
                'rombel' => 'required',
                'rayon' => 'required',
                'tanggal_peminjaman' => 'required',
            ]);
            //untuk mengrim data ke table laptop lewat model Student
            $laptop = peminjamanLaptop::create([
                'no_laptop' => $request->no_laptop,
                'nama' => $request->nama,
                'nis' => $request->nis,
                'rombel' => $request->rombel,
                'rayon' => $request->rayon,
                'tanggal_peminjaman' => \Carbon\Carbon::Parse($request->tanggal_peminjaman)->format('Y-m-d'),
            ]);

            $tambahData = peminjamanLaptop::where('id', $laptop->id)->first();

            if($tambahData) {
                return ApiFormatter::createAPI(200, 'succes', $laptop);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }


    public function createToken() {
        return csrf_token();
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //coba bari code didalam try
        try {
            //ambil data dari table student yang id nya sama kaya $id dari path routnya
            // where & find fungsi mencari, bedanya : where nyari berdasarkan column apa aja boleh, kalau find cuma bisa cari berdasarkan id
            $laptops = peminjamanLaptop::find($id);
            if ($laptops) {
                //kalau ada data berhasil diambil, tampilkan data dari $student nya dengan tanda status code 200
                return ApiFormatter::createAPI(200, 'succes', $laptops);
            }else {
                //kalau data gagal diambil/data gaada, yang dikembalikan status code 400
                return ApiFormatter::createAPI(400, 'failded');
            }
        } catch (Exception $error) {
            //kalau pas try ada error,deskripsi errornya ditampilkan dengan statuscode 400
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(peminjamanLaptop $peminjamanLaptop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, peminjamanLaptop $peminjamanLaptop, $id)
    {
        try {
            $request->validate([
                'no_laptop' => 'required',
                'nama' => 'required|min:3',
                'nis' => 'required|numeric',
                'rombel' => 'required',
                'rayon' => 'required',
                'tanggal_peminjaman' => 'required',
            ]);

            $laptop = peminjamanLaptop::find($id);

            $laptop->update([
                'no_laptop' => $request->no_laptop,
                'nama' => $request->nama,
                'nis' => $request->nis,
                'rombel' => $request->rombel,
                'rayon' => $request->rayon,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
            ]);

            $dataTerbaru = peminjamanLaptop::where('id', $laptop->id)->first();
            if ($dataTerbaru) {
                //jika update berhasl, tampilkan data dari $updateStudent diatas (data yang sudah berhasil diubah)
                return ApiFormatter::createAPI(200, 'succes', $dataTerbaru);
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            //jika di baris code try ada trouble, error dimunculkan dengan desc error nya dengan sttatus code 400
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            //ambil data yang mau dihapus
            $laptop = peminjamanLaptop::findOrFail($id);
            // hapus data yang diambil diatas
            $cekBerhasil = $laptop->delete();
            if($cekBerhasil) {
                // kalau berhasil hapus, data yang dimunculkan teks konfirm dengan status code 200
                return ApiFormatter::createAPI(200, 'success delete data!');
            } else {
                return ApiFormatter::createAPI(400, 'Failed');
            }
        } catch (Exception $error) {
            // kalau ada trouble di baris code dalam try, error desc nya dimunculkan
            return ApiFormatter::createAPI(400, 'Failed',$error->getMessage());
        }
    }

    public function trash()
    {
        try {
            //mengammbil data yang sudah sementara
            $laptop = peminjamanLaptop::onlyTrashed()->get();
            if ($laptop) {
                // kalau data berhasil terambil. tampilkan status 200 dengan data dari $students
                return ApiFormatter::createAPI(200, 'succes', $laptop);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            // kalau ada error di try catch akan menampilkan desc errornya
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function restore($id) 
    {
        try {
            //ambil data yang akan di batal hapus, diambil berdasarkan id dari routenya
            $laptop = peminjamanLaptop::onlyTrashed()->where('id' , $id);
            //kembalikan data
            $laptop->restore();
            //ambil kembali data yang sudah di restore
            $dataKembali = peminjamanLaptop::where('id', $id)->first();
            if ($dataKembali) {
                //jika seluruh proses nya dapat dijalankan data yang sudah ditambahkan dan diambil tadi ditampilkan pada response 200
                return ApiFormatter::createAPI(200, 'succes', $dataKembali);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanenDelete($id)
    {
        try{
            //ambil data yang dihapus
            $laptop = peminjamanLaptop::onlyTrashed()->where('id', $id);
            //hapus permanen data yang diambil
            $proses = $laptop->forceDelete();
            return ApiFormatter::createAPI(200, 'succes', 'Berhasil hapus permanent!');
            return ApiFormatter::createAPI(400, 'failed');
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}
