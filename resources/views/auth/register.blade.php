<x-auth-layout title="Register">
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

        <!-- Right Side - Register Form -->
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

                <!-- Register Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-[#2c6356] text-3xl font-bold mb-2">Create Account</h2>
                        <p class="text-gray-600">Sign up to get started with iPharma Mart</p>
                    </div>

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

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Full Name
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent transition-all"
                                placeholder="Enter your full name" required autofocus>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent transition-all"
                                placeholder="Enter your email address" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Password
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent transition-all"
                                placeholder="Create a password" required>
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent transition-all"
                                placeholder="Confirm your password" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-[#3a7d6f] hover:bg-[#2c6356] text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                            Create Account
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-6 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[#ff9052] hover:text-[#ff7d3a] font-medium">
                            Sign in
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
