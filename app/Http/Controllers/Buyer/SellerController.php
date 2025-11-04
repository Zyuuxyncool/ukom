<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\ProfilSellerService;
use App\Services\ProductService;
use App\Services\ProductImageService;
use App\Services\DocumentService;
use App\Services\BrandService;
use App\Services\SubCategoryService;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    protected $userService, $profilSellerService, $productService, $productImageService, $brandService, $subCategoryService;

    public function __construct()
    {
        $this->productImageService = new ProductImageService();
        $this->profilSellerService = new ProfilSellerService();
        $this->userService = new UserService();
        $this->productService = new ProductService();
        $this->brandService = new BrandService();
        $this->subCategoryService = new SubCategoryService();

        view()->share([
            'list_brands' => $this->brandService->list(),
            'list_sub_categories' => $this->subCategoryService->list(),
        ]);
    }

    public function index()
    {
        $user = Auth()->user();
        if ($user->seller) {
            return redirect()->route('seller.dashboard.index');
        }
        return view('buyer.seller.index');
    }

    public function verify()
    {
        $user = Auth()->user();
        $profil = $this->profilSellerService->find($user->id, 'user_id');
        return view('buyer.seller.verify', compact('profil'));
    }

    public function store(Request $request)
    {
        $user = Auth()->user();
        $profil = $this->profilSellerService->find($user->id, 'user_id');

        $data = [
            'jenis_usaha' => $request->jenis_usaha,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'nama_toko' => '',
            'flag' => 2, // ✅ Setelah verifikasi diri, ubah flag ke 2
        ];

        if ($profil) {
            $this->profilSellerService->update($profil->id, $data);
        } else {
            $data['user_id'] = $user->id;
            $this->profilSellerService->store($data);
        }

        return redirect()->route('buyer.seller.informasi_toko')
            ->with('success', 'Verifikasi berhasil disimpan!');
    }

    public function informasiToko()
    {
        $user = Auth()->user();
        $profil = $this->profilSellerService->find($user->id, 'user_id');
        return view('buyer.seller.informasi_toko', compact('profil'));
    }

    public function storeInformasiToko(Request $request)
    {
        $user = Auth()->user();

        $request->validate([
            'nama_toko'   => 'required|string|max:100',
            'no_telp'     => 'nullable|string|max:20',
            'alamat'      => 'required|string|max:255',
            'provinsi'    => 'required|string|max:100',
            'kabupaten'   => 'required|string|max:100',
            'kecamatan'   => 'required|string|max:100',
            'desa'        => 'required|string|max:100',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ], [
            'nama_toko.required'  => 'Nama toko wajib diisi.',
            'alamat.required'     => 'Alamat lengkap wajib diisi.',
            'provinsi.required'   => 'Provinsi wajib dipilih.',
            'kabupaten.required'  => 'Kabupaten wajib dipilih.',
            'kecamatan.required'  => 'Kecamatan wajib dipilih.',
            'desa.required'       => 'Desa wajib dipilih.',
            'latitude.required'   => 'Lokasi belum ditemukan, silakan gunakan tombol "Dapatkan Lokasi Saya".',
            'longitude.required'  => 'Lokasi belum ditemukan, silakan gunakan tombol "Dapatkan Lokasi Saya".',
        ]);

        $profil = $this->profilSellerService->find($user->id, 'user_id');

        $data = [
            'user_id'    => $user->id,
            'username'   => $request->username,
            'nama_toko'  => $request->nama_toko,
            'notlp'      => $request->no_telp,
            'alamat'     => $request->alamat,
            'provinsi'   => $request->provinsi,
            'kabupaten'  => $request->kabupaten,
            'kecamatan'  => $request->kecamatan,
            'desa'       => $request->desa,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'flag'       => 3, // ✅ Setelah isi informasi toko, ubah flag ke 3
        ];

        if ($profil) {
            $this->profilSellerService->update($profil->id, $data);
        } else {
            $this->profilSellerService->store($data);
        }

        return redirect()->route('buyer.seller.upload_produk')
            ->with('success', 'Informasi toko berhasil disimpan! Lanjut ke Upload Produk.');
    }

    public function uploadProduk()
    {
        $user = Auth()->user();
        $profil = $this->profilSellerService->find($user->id, 'user_id');
        if (!$profil || !$profil->nama_toko) {
            return redirect()->route('buyer.seller.informasi_toko')
                ->with('error', 'Lengkapi informasi toko terlebih dahulu sebelum mengupload produk.');
        }
        $produkList = $this->productService->search(['profil_id' => $profil->id]);
        return view('buyer.seller.upload_produk', compact('profil', 'produkList'));
    }

    public function storeUploadProduk(Request $request)
    {
        $user = Auth()->user();
        $profil = $this->profilSellerService->find($user->id, 'user_id');
        if (!$profil) {
            return redirect()->route('buyer.seller.informasi_toko')
                ->with('error', 'Profil seller tidak ditemukan. Lengkapi informasi toko terlebih dahulu.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'sub_category_id' => 'nullable|integer|exists:sub_categories,id',
            'brand_id'        => 'nullable|integer|exists:brands,id',
            // Allow common modern formats (no size limit here) without relying on GD's image() rule
            'images.*'        => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp|mimes:jpeg,png,jpg,webp',
        ], [
            'name.required'  => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
            'images.*' => 'Format foto harus jpg, jpeg, png, atau webp.',
        ]);

        $price = str_replace(['.', ','], ['', '.'], $request->price);
        $product = $this->productService->store([
            'profil_id'       => $profil->id,
            'name'            => $request->name,
            'description'     => $request->description,
            'price'           => $price,
            'stock'           => $request->stock,
            'sub_category_id' => $request->sub_category_id,
            'brand_id'        => $request->brand_id,
        ]);

        if ($request->hasFile('images')) {
            $files = DocumentService::save_multiple_file($request, 'images', 'public/products');
            foreach ($files as $path) {
                $this->productImageService->store([
                    'product_id' => $product->id,
                    'photo' => $path,
                ]);
            }
        }

        // ✅ Setelah upload produk pertama, ubah flag ke aktif (1)
        // if ($profil->flag < 1) {
        $this->profilSellerService->update($profil->id, ['flag' => 1]);
        // }

        return redirect()->route('seller.dashboard.index')
            ->with('success', 'Produk dan foto berhasil ditambahkan!');
    }
}
