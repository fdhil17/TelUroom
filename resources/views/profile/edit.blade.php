<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Profile</h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- Profile Information --}}
                <div class="card mb-4">
                    <div class="card-header fw-bold card-header-dark">Informasi Profil</div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="card mb-4">
                    <div class="card-header fw-bold card-header-dark">Ubah Password</div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="card mb-4 border-danger">
                    <div class="card-header fw-bold" style="background-color: #FFF5F5; color: #991B1B; border-bottom: 1px solid #FECACA;">
                        Hapus Akun
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
