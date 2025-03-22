{{-- Indicador de Progreso del Registro --}}
<div class="mb-8">
    <!-- Indicador de progreso para móviles (vertical) -->
    <div class="sm:hidden">
        <div class="space-y-4">
            <div class="flex items-center">
                <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                    <span class="text-white font-bold text-sm">1</span>
                </div>
                <div class="ml-2 flex-1">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Membresía</p>
                    <div x-bind:class="{ 'bg-emerald-300': step > 1, 'bg-gray-200': step <= 1 }" class="h-1 w-full mt-2"></div>
                </div>
            </div>
            
            <div class="flex items-center">
                <div x-bind:class="{ 'bg-emerald-500': step >= 2, 'bg-gray-300': step < 2 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                    <span class="text-white font-bold text-sm">2</span>
                </div>
                <div class="ml-2 flex-1">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 2, 'text-gray-500': step < 2 }">Pago</p>
                    <div x-bind:class="{ 'bg-emerald-300': step > 2, 'bg-gray-200': step <= 2 }" class="h-1 w-full mt-2"></div>
                </div>
            </div>
            
            <div class="flex items-center">
                <div x-bind:class="{ 'bg-emerald-500': step >= 3, 'bg-gray-300': step < 3 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                    <span class="text-white font-bold text-sm">3</span>
                </div>
                <div class="ml-2 flex-1">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 3, 'text-gray-500': step < 3 }">Información Personal</p>
                    <div x-bind:class="{ 'bg-emerald-300': step > 3, 'bg-gray-200': step <= 3 }" class="h-1 w-full mt-2"></div>
                </div>
            </div>
            
            <div class="flex items-center">
                <div x-bind:class="{ 'bg-emerald-500': step >= 4, 'bg-gray-300': step < 4 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                    <span class="text-white font-bold text-sm">4</span>
                </div>
                <div class="ml-2 flex-1">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 4, 'text-gray-500': step < 4 }">Medidas Corporales</p>
                    <div x-bind:class="{ 'bg-emerald-300': step > 4, 'bg-gray-200': step <= 4 }" class="h-1 w-full mt-2"></div>
                </div>
            </div>
            
            <div class="flex items-center">
                <div x-bind:class="{ 'bg-emerald-500': step >= 5, 'bg-gray-300': step < 5 }" class="flex items-center justify-center w-8 h-8 rounded-full">
                    <span class="text-white font-bold text-sm">5</span>
                </div>
                <div class="ml-2 flex-1">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 5, 'text-gray-500': step < 5 }">Objetivos Fitness</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Indicador de progreso para desktop (horizontal) -->
    <div class="hidden sm:block">
        <div class="flex items-center">
            <div class="flex items-center relative">
                <div x-bind:class="{ 'bg-emerald-500': step >= 1, 'bg-gray-300': step < 1 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                    <span class="text-white font-bold">1</span>
                </div>
                <div class="ml-2 mr-8">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 1, 'text-gray-500': step < 1 }">Membresía</p>
                </div>
                <div x-bind:class="{ 'bg-emerald-300': step > 1, 'bg-gray-200': step <= 1 }" class="flex-1 h-1 w-12 md:w-24"></div>
            </div>
            
            <div class="flex items-center relative">
                <div x-bind:class="{ 'bg-emerald-500': step >= 2, 'bg-gray-300': step < 2 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                    <span class="text-white font-bold">2</span>
                </div>
                <div class="ml-2 mr-8">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 2, 'text-gray-500': step < 2 }">Pago</p>
                </div>
                <div x-bind:class="{ 'bg-emerald-300': step > 2, 'bg-gray-200': step <= 2 }" class="flex-1 h-1 w-12 md:w-24"></div>
            </div>
            
            <div class="flex items-center relative">
                <div x-bind:class="{ 'bg-emerald-500': step >= 3, 'bg-gray-300': step < 3 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                    <span class="text-white font-bold">3</span>
                </div>
                <div class="ml-2 mr-8">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 3, 'text-gray-500': step < 3 }">Información Personal</p>
                </div>
                <div x-bind:class="{ 'bg-emerald-300': step > 3, 'bg-gray-200': step <= 3 }" class="flex-1 h-1 w-12 md:w-24"></div>
            </div>
            
            <div class="flex items-center relative">
                <div x-bind:class="{ 'bg-emerald-500': step >= 4, 'bg-gray-300': step < 4 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                    <span class="text-white font-bold">4</span>
                </div>
                <div class="ml-2 mr-8">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 4, 'text-gray-500': step < 4 }">Medidas Corporales</p>
                </div>
                <div x-bind:class="{ 'bg-emerald-300': step > 4, 'bg-gray-200': step <= 4 }" class="flex-1 h-1 w-12 md:w-24"></div>
            </div>
            
            <div class="flex items-center relative">
                <div x-bind:class="{ 'bg-emerald-500': step >= 5, 'bg-gray-300': step < 5 }" class="flex items-center justify-center w-10 h-10 rounded-full">
                    <span class="text-white font-bold">5</span>
                </div>
                <div class="ml-2">
                    <p x-bind:class="{ 'text-emerald-500 font-semibold': step >= 5, 'text-gray-500': step < 5 }">Objetivos Fitness</p>
                </div>
            </div>
        </div>
    </div>
</div> 
</div> 