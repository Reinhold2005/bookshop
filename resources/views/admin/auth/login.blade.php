<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bookshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .input-group {
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            transform: scale(1.02);
        }
        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center px-4">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-50"></div>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-2xl shadow-2xl w-full max-w-md relative z-10">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-t-2xl p-6 text-white text-center">
                <h1 class="text-3xl font-bold">Admin Portal</h1>
                <p class="text-purple-100 mt-2">Secure access to your dashboard</p>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span class="font-medium">Login Failed</span>
                        </div>
                        <ul class="mt-2 text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    
                    <div class="mb-6 input-group">
                        <label class="block text-gray-700 mb-2 font-semibold">
                            <i class="fas fa-envelope mr-2 text-purple-600"></i> Email Address
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                               placeholder="admin@bookshop.com" required>
                    </div>
                    
                    <div class="mb-6 input-group">
                        <label class="block text-gray-700 mb-2 font-semibold">
                            <i class="fas fa-lock mr-2 text-purple-600"></i> Password
                        </label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                               placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="btn-login w-full text-white py-3 rounded-lg font-semibold text-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
                    </button>
                </form>

                <!-- Additional Info -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <i class="fas fa-shield-alt mr-1"></i> Secure Admin Access Only
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 rounded-b-2xl p-4 text-center text-gray-500 text-sm">
                <i class="fas fa-store mr-1"></i> Bookshop Management System
            </div>
        </div>
    </div>
</body>
</html>