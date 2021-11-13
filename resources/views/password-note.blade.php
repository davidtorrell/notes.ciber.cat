<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="ciberNotes, notas encriptadas y autodestructivas">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <title>ciberNotes - Notas encriptadas</title>
</head>

<body class="" style="background-color : #1d1d1d">
    <div id="content" class="fadeIn">


        <header>
            <div id="header" class="w-full py-4 text-white">
                <div class="ml-6">
                    <div class="text-center md:text-left">
                        <h1 class="inline -ml-4 text-2xl font-extrabold md:ml-0 font-default lg:text-5xl"><a
                                href="{{ route('home') }}" class="cursor-pointer">ciberNotes</a>
                        </h1>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="lg:h-12 lg:w-12 w-8 h-8 -ml-0.5 -mt-4 lg:-mt-7 inline" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>

                    <div class="mt-1 text-center md:text-lg md:text-left">
                        <h3 class="font-default md:ml-0.5 -ml-4 font-semibold">
                            Notas encriptadas y autodestructivas <img src="{{ asset('assets/leaf.png') }}"
                                class="inline w-6 h-6 -mt-2" alt="leaficon">
                        </h3>
                    </div>
                </div>
            </div>
        </header>
        <style>
            *::-webkit-scrollbar {
                display: none;
            }

        </style>
        <main class="text-center md:ml-4 md:text-left">
            @if (!isset($note))
                <div class="text-red-500 py-2 text-xl px-2 md:px-0 md:ml-2.5">
                    <p class="font-default">
                        Lo sentimos, esta nota no existe, ya ha sido leída o su fecha de caducidad ha pasado.
                    </p>
                </div>
            @else
                <form action="{{ route('note.decrypt', $note->slug) }}" method="POST">
                    @csrf
                    <div class="ml-2.5 mt-10">
                        @if ($password)
                            <h2 class="px-2 text-xl font-bold text-white font-default md:px-0">Ingrese la contraseña de la nota</h2>
                            <input type="password" required
                                class="rounded-sm focus:outline-none text-white px-2 py-0.5 w-64 mt-2"
                                style="background-color : #585858" name="decrypt_password">
                            <br>
                            <br>
                            @if ($errors->any())
                                <div class="py-2 text-red-500">
                                    <p class="font-default">
                                        {{ $errors->first() }}
                                    </p>
                                </div>

                            @endif
                        @endif
                        <input type="submit" 
                           class="bg-red-800 text-center focus:outline-none text-white align-middle px-4 font-default font-semibold cursor-pointer pt-1.5 pb-2 text-xl rounded"
                            value="Descifrar" onclick="disableButton(this)">
                        <p class="px-2 mt-8 text-white font-default md:px-0">Después de descifrar esta nota, se eliminará de la base de datos.</p>

                    </div>
                </form>
            @endif
        </main>
        <footer id="footer" class="w-full mt-8 mb-4 text-center text-white font-default">
            <p>developed by <a href="https://ciber.cat">ciber</a></p>
        </footer>
    </div>

    <script>
        function disableButton(e) {
            setTimeout(function() {
                e.disabled = true;
            }, (100));
        }
    </script>
</body>

</html>
