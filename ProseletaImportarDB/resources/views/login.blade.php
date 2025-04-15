<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>

<body class="bg-gray-800 flex items-center justify-center min-h-screen">

    <form action="{{ route('login.post') }}" method="post" class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm space-y-4">
        @csrf
        <h2 class="text-xl font-semibold text-center text-gray-800">PROSELETA - ImportarDB</h2>
        <h2 class="text-xl font-semibold text-center text-gray-800">Login</h2>

        <input type="email" name="email" placeholder="Email" required
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <input type="password" name="senha" placeholder="Senha" required
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Entrar</button>
    </form>

</body>

</html>
