<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Tambah Ruangan</h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header fw-bold card-header-dark">Form Ruangan</div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('logistik.ruangan.store') }}">
                            @php $ruangan = null; @endphp
                            @include('logistik.ruangan._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
