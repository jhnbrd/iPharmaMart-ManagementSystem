<x-auth-layout title="Sign In">
    <div class="min-h-screen flex">
        <!-- Left Side - Green Background with Logo -->
        <div class="hidden lg:flex lg:w-2/5 bg-[#2c6356] items-center justify-center p-12">
            <div class="text-center">
                <div class="mb-8 flex justify-center">
                    <div class="bg-white rounded-3xl p-12 shadow-2xl">
                        <img src="{{ asset('images/logo/ipharma-logo.png') }}" alt="iPharma Mart Logo"
                            class="w-40 h-40 object-contain">
                    </div>
                </div>
                <h1 class="text-white text-5xl font-bold">iPharma Mart</h1>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex items-center justify-center p-8 lg:p-12 bg-[#f5f5f5]">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-block bg-white rounded-2xl p-6 shadow-lg mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="iPharma Mart Logo"
                            class="w-24 h-24 object-contain">
                    </div>
                    <h1 class="text-[#2c6356] text-3xl font-bold">iPharma Mart</h1>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-2xl shadow-xl p-10">
                    <div class="text-center mb-10">
                        <h2 class="text-[#2c6356] text-3xl font-bold mb-3">Welcome Back</h2>
                        <p class="text-gray-500 text-base">Sign in to access your healthcare management dashboard</p>
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

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" autocomplete="off">
                        @csrf

                        <!-- Username -->
                        <div class="mb-5">
                            <label for="username" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Username
                            </label>
                            <div class="relative">
                                <input type="text" id="username" name="username" value="{{ old('username') }}"
                                    class="w-full px-4 py-3.5 bg-[#f8f9fa] border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent focus:bg-white transition-all"
                                    placeholder="Enter your username" required autofocus autocomplete="off">
                                <svg class="w-5 h-5 text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-5">
                            <label for="password" class="block text-[#2c6356] text-sm font-semibold mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-3.5 bg-[#f8f9fa] border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3a7d6f] focus:border-transparent focus:bg-white transition-all"
                                    placeholder="Enter your password" required autocomplete="new-password">
                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                    <svg id="eye-icon" class="w-5 h-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between mb-7">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-[#3a7d6f] border-gray-300 rounded focus:ring-[#3a7d6f]">
                                <span class="ml-2 text-sm text-gray-700">Keep me signed in</span>
                            </label>
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-[#ff9052] hover:text-[#ff7d3a] font-semibold transition-colors">
                                Forgot your password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-[#3a7d6f] hover:bg-[#2c6356] text-white font-semibold py-4 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                            Sign In to Dashboard
                        </button>
                    </form>

                    <!-- Footer -->
                    <p class="text-center text-sm text-gray-400 mt-8">
                        © 2024 iPharma Mart Healthcare Solutions. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
                }
            }
        </script>
</x-auth-layout>
