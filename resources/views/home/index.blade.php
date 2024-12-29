<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>HOME</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-neutral-50 text-neutral-700">
    <header class="sticky top-0 flex justify-end h-16 px-5 py-2 border border-b-neutral-200">
        <div class="grid grid-cols-[1fr,8rem,8rem,8rem] gap-4">

            <!-- ユーザー -->
            <div class="flex items-center justify-center w-full">
                <img src="{{ asset('images/profile.jpg') }}" alt="プロフィール画像" class="w-12 h-12 mr-2 rounded-full" />
                {{ $user->username }}さん
            </div>

            <!-- カテゴリ作成 -->
            <button type="button" onclick="window.location='{{ route('create-category') }}'"
                class="w-32 px-2 bg-white border rounded-md hover:text-neutral-100 hover:bg-neutral-700 border-neutral-700 hover:cursor-pointer">
                カテゴリ作成
            </button>

            <!-- 問題作成 -->
            <button type="button" onclick="window.location='{{ route('create-question') }}'"
                class="w-32 px-2 bg-white border rounded-md hover:text-neutral-100 hover:bg-neutral-700 border-neutral-700 hover:cursor-pointer">
                問題作成
            </button>

            <!-- ログアウト -->
            <form action="{{ route('logout') }}" method="POST" class="flex items-center justify-end w-full h-full">
                @csrf

                <button type="submit"
                    class="w-32 h-full bg-white border rounded-md border-neutral-700 hover:text-neutral-100 hover:bg-neutral-700">
                    <h2>ログアウト</h2>
                </button>
            </form>
        </div>
    </header>
    <main class="grid w-full grid-cols-3 gap-12 px-40 py-20">
        @foreach ($categories as $category)
            <a href="{{ route('get-questions', ['category_id' => $category->id]) }}"
                class="w-full h-full p-4 transition-transform duration-300 bg-white border rounded-lg shadow-lg border-neutral-300 hover:cursor-pointer hover:scale-105">
                <h1 class="text-3xl font-bold">{{ $category->category_name }}</h1>

                <div style="w-full mx-auto h-full">
                    <canvas id="graph_{{ $category->id }}" height="140"></canvas>
                </div>
            </a>
        @endforeach
    </main>

    <!-- Chart.js Bar Chart -->
    <!-- https://www.chartjs.org/docs/latest/getting-started/ -->
    <script>
        @foreach ($graph_datas as $graph_data)

            new Chart(document.getElementById('graph_{{ $graph_data['category_id'] }}').getContext('2d'), {
                type: 'bar', // Bar chart type
                data: @json($graph_data),
                options: {
                    responsive: true,
                    animation: {
                        duration: 2000, // Set animation duration (e.g., 2000ms = 2 seconds)
                        easing: 'easeOutBounce', // Choose a slower easing function
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Start Y-axis at 0
                            max: 100, // Set max value to 100
                            min: 0, // Set min value to 0
                        },
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw; // Get the raw value
                                    return value + '%'; // Append '%' to the tooltip value
                                }
                            }
                        }
                    }
                },
            });
        @endforeach
    </script>
</body>

</html>
