@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-edit me-2 text-primary"></i> Edit Quest
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST">
                            @csrf
                            @method('PUT') <div class="mb-4">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Nama Quest</label>
                                <input type="text" name="name" class="form-control form-control-lg fw-bold"
                                    value="{{ old('name', $activity->name) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Kategori Quest
                                    (Warna)</label>
                                <select name="icon" class="form-control" required>
                                    <option value="" disabled>-- Pilih Jenis Quest --</option>

                                    <option value="main" {{ old('icon', $activity->icon) == 'main' ? 'selected' : '' }}>
                                        🟡 Quest Utama (Kuning)
                                    </option>

                                    <option value="character"
                                        {{ old('icon', $activity->icon) == 'character' ? 'selected' : '' }}>
                                        🟣 Quest Karakter (Ungu)
                                    </option>

                                    <option value="exploration"
                                        {{ old('icon', $activity->icon) == 'exploration' ? 'selected' : '' }}>
                                        🟢 Quest Explorasi (Hijau)
                                    </option>

                                    <option value="event" {{ old('icon', $activity->icon) == 'event' ? 'selected' : '' }}>
                                        🟠 Quest Event (Orange)
                                    </option>

                                    <option value="side" {{ old('icon', $activity->icon) == 'side' ? 'selected' : '' }}>
                                        🔵 Quest NPC (Biru)
                                    </option>
                                </select>
                                <div class="form-text text-muted">
                                    *Mengubah kategori akan mengubah warna indikator di daftar Quest.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary text-uppercase small">XP Reward</label>
                                <input type="number" name="exp_reward" class="form-control"
                                    value="{{ old('exp_reward', $activity->exp_reward) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary text-uppercase small">Deskripsi Misi</label>
                                <textarea name="description" rows="4" class="form-control">{{ old('description', $activity->description) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between pt-3 border-top">
                                <a href="{{ route('admin.activities.index') }}"
                                    class="btn btn-light fw-bold text-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary fw-bold px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
