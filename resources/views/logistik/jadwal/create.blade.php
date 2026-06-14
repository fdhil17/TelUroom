<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Tambah Jadwal Akademik</h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header fw-bold card-header-dark">Form Jadwal Akademik</div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('logistik.jadwal.store') }}">
                            @php $jadwal = null; @endphp
                            @include('logistik.jadwal._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
