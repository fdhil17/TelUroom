<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <a href="#">Data Master</a>
        </div>
        <div class="breadcrumb-item">
            <span class="separator">/</span>
            <a href="{{ route('logistik.jadwal.index') }}">Jadwal Akademik</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Edit Jadwal
        </div>
    </x-slot>

    <x-slot name="header">
        Edit Jadwal Akademik
    </x-slot>

    <div class="d-flex justify-content-center">
        <div style="width: 100%; max-width: 800px;">
            <div class="teluroom-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('logistik.jadwal.update', $jadwal) }}" novalidate>
                        @method('PUT')
                        @include('logistik.jadwal._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>