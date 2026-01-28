<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Aktivitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="container" style="max-width: 600px;">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white p-3">
                <h4 class="mb-0">📝 Buat Quest Baru</h4>
            </div>
            <div class="card-body p-4">

                <form action="{{ route('admin.activities.store') }}" method="POST">
                    @csrf <div class="mb-3">
                        <label class="form-label fw-bold">Nama Aktivitas</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Contoh: Push Up 100x" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hadiah EXP (Experience)</label>
                        <input type="number" name="exp_reward"
                            class="form-control @error('exp_reward') is-invalid @enderror" placeholder="Contoh: 50"
                            value="{{ old('exp_reward') }}">
                        <small class="text-muted">Masukkan angka saja.</small>
                        @error('exp_reward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon (Emoji)</label>
                        <input type="text" name="icon" class="form-control" placeholder="Contoh: 💪 atau 📚"
                            value="{{ old('icon') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Penjelasan singkat tugas...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Simpan Quest</button>
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-light text-secondary">Batal</a>
                    </div>

                </form>

            </div>
        </div>
    </div>

</body>

</html>
