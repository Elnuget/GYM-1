{{-- Modales para el formulario de registro --}}

{{-- Modal de Éxito --}}
<div x-show="showSuccessModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
     style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-center text-gray-900 mb-2">¡Éxito!</h3>
        <p class="text-center text-gray-600" x-text="modalMessage"></p>
    </div>
</div>

{{-- Modal de Error --}}
<div x-show="showErrorModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
     style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-center text-gray-900 mb-2">Error</h3>
        <p class="text-center text-gray-600" x-text="errorMessage"></p>
        <div class="mt-4 flex justify-center">
            <button @click="showErrorModal = false" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                Cerrar
            </button>
        </div>
    </div>
</div> 