<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>LOGIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="w-screen bg-white">
    <main class="flex items-start justify-center w-full h-screen p-20">
        <section class="w-1/4 p-4 bg-white border rounded-lg shadow-lg border-neutral-300">
            <h1 class="mb-4 text-2xl font-bold">ログイン</h1>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="p-4 mb-4 text-red-800 bg-red-200 rounded-md">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="grid grid-flow-row gap-4">
                @csrf
                <input type="text" name="username" placeholder="Username"
                    class="w-full h-12 p-2 border border-gray-300 rounded-md outline-none focus:border-neutral-700 hover:border-neutral-700">
                <input type="password" name="password" placeholder="Password"
                    class="w-full h-12 p-2 border border-gray-300 rounded-md outline-none focus:border-neutral-700 hover:border-neutral-700">
                <button type="submit"
                    class="w-full h-12 bg-white border rounded-md border-neutral-700 hover:bg-neutral-700 hover:text-neutral-100">
                    ログイン
                </button>
            </form>
        </section>
    </main>
</body>

</html>
