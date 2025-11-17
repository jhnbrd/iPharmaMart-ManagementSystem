<x-auth-layout title="Forgot Password">
    <div class="min-h-screen flex">
        <!-- Left Side - Green Background with Logo -->
        <div
            class="hidden lg:flex lg:w-1/3 bg-gradient-to-br from-[#2c6356] to-[#3a7d6f] items-center justify-center p-12">
            <div class="text-center">
                <div class="mb-8 flex justify-center">
                    <div class="bg-white rounded-3xl p-8 shadow-2xl">
                        <img src="{{ asset('images/logo.png') }}" alt="iPharma Mart Logo" class="w-32 h-32 object-contain">
                    </div>
                </div>
                <h1 class="text-white text-4xl font-bold mb-2">iPharma Mart</h1>
                <p class="text-white/80 text-lg">Healthcare Management System</p>
            </div>
        </div>

        <!-- Right Side - Forgot Password Form -->
        <div class="flex-1 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-block bg-white rounded-2xl p-6 shadow-lg mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="iPharma Mart Logo"
                            class="w-24 h-24 object-contain">
                    </div>
                    <h1 class="text-[#2c6356] text-3xl font-bold">iPharma Mart</h1>
                </div>

                <!-- Forgot Password Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-[#2c6356] text-3xl font-bold mb-2">Forgot Password?</h2>
                        <p class="text-gray-600">No problem. Just let us know your email address and we'll email you a
                            password reset link.</p>
                    </div>

                    <!-- Success Message -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent transition-all"
                                placeholder="Enter your email address" required autofocus>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-[#3a7d6f] hover:bg-[#2c6356] text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                            Email Password Reset Link
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="mt-6 text-center text-sm text-gray-600">
                        <a href="{{ route('login') }}" class="text-[#ff9052] hover:text-[#ff7d3a] font-medium">
                            ← Back to login
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <p class="text-center text-sm text-gray-500 mt-8">
                    © 2024 iPharma Mart Healthcare Solutions. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</x-auth-layout>
