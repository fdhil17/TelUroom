<?php

namespace App\Traits;

trait HasRoleImpersonation
{
    /**
     * Mendapatkan role aktif user, dengan memperhitungkan fitur admin impersonation.
     * Jika user adalah admin dan sedang impersonate (memiliki session admin_role),
     * maka session role tersebut yang dikembalikan.
     * 
     * @param \Illuminate\Http\Request|null $request
     * @return string
     */
    public function getActiveRole(?\Illuminate\Http\Request $request = null): string
    {
        $request = $request ?? request();
        $user = $request->user();
        
        if (!$user) {
            return '';
        }

        if ($user->role === 'admin' && $request->session()->has('admin_role')) {
            return $request->session()->get('admin_role');
        }

        return $user->role;
    }
}
