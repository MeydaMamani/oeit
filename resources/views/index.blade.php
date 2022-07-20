<!DOCTYPE html>
<html lang="en">
  	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>{{ __('DEIT - PASCO') }}</title>
		<!-- Tailwind CSS Link -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.1/tailwind.min.css">
  	</head>
  	<body class="bg-gray-100 text-gray-800">
		<nav class="flex bg-gray-500 text-white">
		<div class="w-1/2 px-12 mr-auto pt-2">
			<img src="./img/minsa.jpg" alt="" width="210">
		</div>

		<ul class="w-1/2 px-16 ml-auto flex justify-end pt-1 m-5">
			<li class="mx-6">
				<a href="#" class="font-semibold hover:bg-indigo-700 py-3 px-4 rounded-md">Iniciar Sesión</a>
			</li>
			<li>
				<a href="#" class="font-semibold border-2 border-white py-2 px-4 rounded-md hover:bg-white hover:text-indigo-700">Registro</a>
			</li>
		</ul>

		</nav>
		<div class="block mx-auto my-12 p-8 bg-white w-1/3 border border-gray-200 rounded-lg shadow-lg">
			<h1 class="text-3xl text-center font-bold">Iniciar Sesión</h1>
			<form class="mt-4" method="POST" action="">
				@csrf
				<input type="text" class="border border-gray-200 rounded-md bg-gray-200 w-full text-lg placeholder-gray-900 p-2 my-2 focus:bg-white" placeholder="Usuario" id="email" name="email">
				<input type="password" class="border border-gray-200 rounded-md bg-gray-200 w-full text-lg placeholder-gray-900 p-2 my-2 focus:bg-white" placeholder="Contraseña" id="password" name="password">
				<br>
				{{-- <button type="submit"  class="rounded-md bg-gray-500 w-full text-lg text-white font-semibold p-2 my-3 hover:bg-gray-600">Ingresar</button> --}}
				<a href="{{ url('/metals') }}" class="rounded-md bg-gray-500 w-full block text-center text-lg text-white font-semibold p-2 my-3 hover:bg-gray-600">Ingresar</a>
			</form>
		</div>
  	</body>
</html>
