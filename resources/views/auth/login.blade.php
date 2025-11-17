<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Weatherly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-3xl font-bold text-blue-600 text-center mb-6">Login</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="/login" method="POST">
        @csrf

        <input type="email" name="email" placeholder="Email"
               class="w-full p-3 mb-3 border rounded-lg focus:ring-2 focus:ring-blue-500">

        <input type="password" name="password" placeholder="Password"
               class="w-full p-3 mb-4 border rounded-lg focus:ring-2 focus:ring-blue-500">

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg mb-3">
            Login
        </button>
    </form>

    <p class="text-center text-gray-600">
        No account?
        <a href="/register" class="text-blue-600">Register</a>
    </p>
</div>

</body>
</html>
