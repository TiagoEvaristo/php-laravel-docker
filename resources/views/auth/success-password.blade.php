<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Email Address -->

    <div>
        <p class="text-center" style="color: black text-weight: bold">Sua senha foi alterada com sucesso!</p>
        <p class="text-center" style="color: black text-weight: bold">Volte para a aplicação e faça o login.</p>
    </div>
   
</x-guest-layout>
