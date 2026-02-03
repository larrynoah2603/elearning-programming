@if(session('success'))
    <div class="alert-auto-hide max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-success-50 border-l-4 border-success-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-success-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-success-700">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.closest('.alert-auto-hide').remove()" class="text-success-500 hover:text-success-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert-auto-hide max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-danger-50 border-l-4 border-danger-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-danger-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-danger-700">{{ session('error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.closest('.alert-auto-hide').remove()" class="text-danger-500 hover:text-danger-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="alert-auto-hide max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-warning-50 border-l-4 border-warning-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-warning-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-warning-700">{{ session('warning') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.closest('.alert-auto-hide').remove()" class="text-warning-500 hover:text-warning-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="alert-auto-hide max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-primary-50 border-l-4 border-primary-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-primary-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-primary-700">{{ session('info') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.closest('.alert-auto-hide').remove()" class="text-primary-500 hover:text-primary-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-danger-50 border-l-4 border-danger-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-danger-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-danger-700 font-medium">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="mt-2 text-sm text-danger-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
