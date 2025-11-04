@extends('buyer.layouts.index')

@section('title')
    Upload Produk -
@endsection

@section('content')
    <div class="container py-10">
        {{-- HEADER DAN TOMBOL SIMPAN --}}
        <div class="d-flex justify-content-between align-items-center mb-6">
            <a href="{{ route('buyer.seller.informasi_toko') }}" class="text-dark fs-3 fw-bold">
                <i class="fas fa-arrow-left me-2"></i> {{-- Ikon kembali --}}
            </a>
            <h1 class="fs-2 fw-bold m-0">Upload Produk</h1>
            <button type="submit" form="formUploadProduk" class="btn btn-sm btn-link text-danger fw-bold">
                Simpan
            </button>
        </div>

        {{-- NAVIGATION STEPPER --}}
        <div class="d-flex justify-content-between align-items-center mb-10">
            <div class="text-center">
                <span class="d-inline-block bg-success rounded-circle" style="width:10px;height:10px;"></span>
                <div class="text-success fw-bold mt-2">Verifikasi Data Diri</div>
            </div>
            <div class="text-center">
                <span class="d-inline-block bg-success rounded-circle" style="width:10px;height:10px;"></span>
                <div class="text-success fw-bold mt-2">Informasi Toko</div>
            </div>
            <div class="text-center">
                <span class="d-inline-block bg-success rounded-circle" style="width:10px;height:10px;"></span>
                <div class="text-success fw-bold mt-2">Upload Produk</div>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="card shadow-sm border-0 rounded-4 p-6">
            {{-- Hapus method="POST" dan enctype, akan disubmit via Fetch --}}
            <form id="formUploadProduk" action="{{ route('buyer.seller.upload_produk.store') }}">
                @csrf

                {{-- Nama Produk --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nama Produk</label>
                    {{-- Nilai profil->name dihapus karena form ini untuk membuat produk baru --}}
                    <x-input name="name" caption="name" :value="old('name')" required />
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Deskripsi Produk</label>
                    <textarea name="description" id="description" class="form-control form-control-lg" rows="4"
                        placeholder="Tulis deskripsi produk...">{{ old('description') }}</textarea>
                </div>

                {{-- Harga dan Stok --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-bold">Harga (Rp) </label>
                        <x-input name="price" caption="price" required class="autonumeric" data-unformat="true" />
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-bold">Stok</label>
                        {{-- Nilai profil->stock dihapus karena form ini untuk membuat produk baru --}}
                        <x-input name="stock" caption="stock" :value="old('stock')" required class="autonumeric"
                            data-unformat="true" />
                    </div>
                </div>

                {{-- Sub Kategori dan Brand (opsional) --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="sub_category_id" class="form-label fw-bold">Sub Kategori</label>
                        {{-- Menggunakan 'old' untuk menjaga nilai jika validasi gagal --}}
                        <x-select name="sub_category_id" prefix="search_" :options="$list_sub_categories" class="form-select"
                            :value="old('sub_category_id')" data-control="select2" />
                    </div>

                    <div class="col-md-6">
                        <label for="brand_id" class="form-label fw-bold">Brand</label>
                        {{-- Menggunakan 'old' untuk menjaga nilai jika validasi gagal --}}
                        <x-select name="brand_id" prefix="search_" :options="$list_brands" class="form-select" :value="old('brand_id')"
                            data-control="select2" />
                    </div>
                </div>


                {{-- Upload Foto Produk --}}
                <div class="mb-4">
                    <label for="images" class="form-label fw-bold">Foto Produk</label>
                    {{-- Input file diubah, name harus tetap 'images[]' --}}
                    <input type="file" name="file_input_temp" id="images" class="form-control form-control-lg"
                        accept="image/jpeg,image/png,image/webp" multiple>
                    <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 5 foto
                        disarankan.</small>
                    <small id="file-error-message" class="text-danger d-block"></small>
                </div>

                {{-- Preview Foto --}}
                <div id="preview" class="d-flex flex-wrap gap-3 mt-3"></div>

                {{-- Tombol Loading --}}
                <div id="loading-indicator" class="text-center text-primary fw-bold mb-3 d-none">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div> Sedang memproses...
                </div>

                <div class="d-flex justify-content-between pt-6 border-top">
                    <a href="{{ route('buyer.seller.informasi_toko') }}" class="btn btn-secondary px-8">Kembali</a>
                    <button type="submit" class="btn btn-success px-8" id="submitButton">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        init_form_element();

        // 1. Variabel Global untuk Akumulasi File
        let uploadedFiles = [];
        const fileInput = document.getElementById('images');
        const previewContainer = document.getElementById('preview');
        const form = document.getElementById('formUploadProduk');
        const submitButton = document.getElementById('submitButton');
        const loadingIndicator = document.getElementById('loading-indicator');
        const fileErrorMessage = document.getElementById('file-error-message');

        // Fungsi untuk merender ulang semua preview dari array uploadedFiles
        function renderPreviews() {
            previewContainer.innerHTML = '';

            uploadedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    // Container untuk gambar dan tombol hapus
                    const container = document.createElement('div');
                    container.classList.add('position-relative');
                    container.style.width = '120px';
                    container.style.height = '120px';
                    container.dataset.index = index; // Tandai index di DOM

                    // Elemen Gambar
                    const img = document.createElement('img');
                    img.src = evt.target.result;
                    img.classList.add('rounded-3', 'shadow-sm', 'w-100', 'h-100');
                    img.style.objectFit = 'cover';

                    // Tombol Hapus (X)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.innerHTML =
                        '<i class="fas fa-times" style="position: relative; top: 0.5px;"></i>';


                    // ✅ Desain sederhana tapi modern
                    deleteBtn.classList.add(
                        'btn', 'p-0', 'position-absolute', 'top-0', 'end-0',
                        'd-flex', 'align-items-center', 'justify-content-center',
                        'rounded-circle'
                    );

                    Object.assign(deleteBtn.style, {
                        width: '22px',
                        height: '22px',
                        background: '#dc3545', // merah Bootstrap
                        color: '#fff',
                        fontSize: '11px',
                        border: 'none',
                        margin: '4px',
                        opacity: '0.9',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        lineHeight: '0', // ✅ penting: hilangkan ruang vertikal default
                        verticalAlign: 'middle',
                        padding: '0',
                        transition: 'opacity 0.2s ease, transform 0.2s ease'
                    });

                    // Efek hover ringan (modern tapi tidak lebay)
                    deleteBtn.addEventListener('mouseenter', () => {
                        deleteBtn.style.opacity = '1';
                        deleteBtn.style.transform = 'scale(1.05)';
                    });
                    deleteBtn.addEventListener('mouseleave', () => {
                        deleteBtn.style.opacity = '0.9';
                        deleteBtn.style.transform = 'scale(1)';
                    });


                    // Logic Hapus
                    deleteBtn.addEventListener('click', function() {
                        // Hapus file berdasarkan index
                        uploadedFiles.splice(index, 1);
                        renderPreviews(); // Render ulang tampilan
                    });

                    container.appendChild(img);
                    container.appendChild(deleteBtn);
                    previewContainer.appendChild(container);
                };
                reader.readAsDataURL(file);
            });
        }

        // 2. Event Listener untuk Akumulasi File
        fileInput.addEventListener('change', function(e) {
            const selected = Array.from(e.target.files);
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            // Filter berdasarkan tipe saja (tanpa batas ukuran)
            const validFiles = [];
            const rejected = [];
            for (const f of selected) {
                if (!allowedTypes.includes(f.type)) {
                    rejected.push(`${f.name}: format tidak didukung`);
                    continue;
                }
                validFiles.push(f);
            }

            // Validasi jumlah file total (setelah validasi tipe)
            if (uploadedFiles.length + validFiles.length > 5) {
                fileErrorMessage.textContent = 'Maksimal 5 foto disarankan.';
                e.target.value = '';
                return;
            }

            // Tampilkan pesan jika ada yang ditolak
            if (rejected.length) {
                fileErrorMessage.textContent = rejected.join(' | ');
            } else {
                fileErrorMessage.textContent = '';
            }

            // Gabungkan file valid ke array global
            uploadedFiles = uploadedFiles.concat(validFiles);
            e.target.value = '';

            // Render ulang
            renderPreviews();
        });

        // 3. Event Listener untuk Submit Form (Menggunakan FormData dan Fetch)
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit form bawaan

            // Handle Autonumeric dan format angka
            document.querySelectorAll('.autonumeric').forEach(el => {
                if (el.dataset.unformat === "true") {
                    if (el.name === 'stock') {
                        // Hanya angka untuk stok
                        el.value = el.value.replace(/[^0-9]/g, '');
                    } else {
                        // Harga: hilangkan pemisah ribuan dan gunakan titik sebagai desimal
                        el.value = el.value.replace(/\./g, '').replace(/,/g, '.');
                    }
                }
            });

            // Tampilkan loading
            submitButton.disabled = true;
            loadingIndicator.classList.remove('d-none');

            // 4. Buat objek FormData
            const formData = new FormData(form);

            // Hapus input file sementara (file_input_temp) dari FormData
            formData.delete('file_input_temp');

            // Tambahkan file yang terakumulasi dari array ke FormData
            uploadedFiles.forEach((file) => {
                // Penting: Key harus 'images[]' agar Laravel menerima sebagai array
                formData.append('images[]', file, file.name);
            });

            // 5. Kirim data menggunakan Fetch API
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        // Laravel akan otomatis menentukan Content-Type multipart/form-data
                        // saat Body adalah instance FormData, jadi tidak perlu disetel manual.
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json', // Minta respons JSON untuk error validasi
                        'X-Requested-With': 'XMLHttpRequest' // Tandai sebagai permintaan AJAX
                    },
                    body: formData
                })
                .then(response => {
                    // Handle response JSON (misalnya error validasi)
                    if (response.headers.get('content-type')?.includes('application/json')) {
                        return response.json().then(data => {
                            if (response.ok) {
                                // Jika sukses tapi response-nya JSON (jarang, tapi mungkin)
                                return data;
                            } else {
                                // Jika ada error validasi dari server (status 422)
                                throw data.errors || data.message || 'Terjadi kesalahan validasi.';
                            }
                        });
                    } else if (response.redirected) {
                        // Jika Laravel mengembalikan redirect (misalnya success redirect)
                        window.location.href = response.url;
                        return;
                    }
                    // Jika tidak ada redirect atau JSON (misalnya empty response), anggap error
                    throw new Error('Respons server tidak terduga.');
                })
                .catch(error => {
                    // Tampilkan error validasi dengan ramah
                    console.error('Error submitting form:', error);
                    let message = '';
                    if (typeof error === 'object' && error !== null) {
                        const msgs = [];
                        for (const key in error) {
                            const v = error[key];
                            if (Array.isArray(v)) msgs.push(...v);
                            else if (typeof v === 'string') msgs.push(v);
                        }
                        message = msgs.join(' ');
                    } else if (typeof error === 'string') {
                        message = error;
                    }
                    if (!message) message = 'Gagal menyimpan produk. Periksa kembali input Anda.';
                    fileErrorMessage.textContent = message;
                    fileErrorMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                })
                .finally(() => {
                    // Sembunyikan loading
                    submitButton.disabled = false;
                    loadingIndicator.classList.add('d-none');
                });
        });
    </script>
@endpush
