<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Edit Jadwal Akademik</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('logistik.jadwal.update', $jadwal) }}">
                    @method('PUT')
                    @include('logistik.jadwal._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
