<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información del perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Actualiza la información de perfil y la dirección de correo electrónico de tu cuenta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Formulario para la información del perfil -->
<form method="POST" action="{{ route('profile.update')}}">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        <!-- Información personal -->
        <div class="border-b border-gray-200 pb-4">
            <h2 class="text-lg font-medium text-gray-900">Información personal</h2>
        </div>

        <!-- Tipo de documento -->
        <div>
            <label for="t_doc" class="block text-sm font-medium text-gray-700">Tipo de documento</label>
            <select id="t_doc" name="t_doc" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Seleccione una opción</option>
                @foreach($tiposDocumentos as $tipo)
                    <option value="{{ $tipo['id_tipo_documento'] }}" {{ $tipo['id_tipo_documento'] == $usuario['t_doc'] ? 'selected' : '' }}>
                        {{ $tipo['tip_doc_descripcion'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Número de documento -->
        <div>
            <label for="num_doc" class="block text-sm font-medium text-gray-700">Número de documento</label>
            <input id="num_doc" name="num_doc" type="text" pattern="[0-9-]{1,20}" value="{{ $usuario['num_doc'] }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Nombres -->
        <div>
            <label for="usu_nombres" class="block text-sm font-medium text-gray-700">Nombres</label>
            <input id="usu_nombres" name="usu_nombres" type="text" maxlength="60" value="{{ $usuario['usu_nombres'] }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Apellidos -->
        <div>
            <label for="usu_apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
            <input id="usu_apellidos" name="usu_apellidos" type="text" maxlength="40" value="{{ $usuario['usu_apellidos'] }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Fecha de nacimiento -->
        <div>
            <label for="usu_fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
            <input id="usu_fecha_nacimiento" name="usu_fecha_nacimiento" type="date"
                   value="{{ $usuario['usu_fecha_nacimiento'] ? \Carbon\Carbon::parse($usuario['usu_fecha_nacimiento'])->format('Y-m-d') : now()->format('Y-m-d') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Sexo -->
        <div>
            <label for="usu_sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
            <select id="usu_sexo" name="usu_sexo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="F" {{ 'F' == $usuario['usu_sexo'] ? 'selected' : '' }}>
                    {{ __('Femenino') }}
                </option>
                <option value="M" {{ 'M' == $usuario['usu_sexo'] ? 'selected' : '' }}>
                    {{ __('Masculino') }}
                </option>
            </select>
        </div>

        <!-- Dirección -->
        <div>
            <label for="usu_direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
            <input id="usu_direccion" name="usu_direccion" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ()# ]{1,190}"
                   maxlength="190" value="{{ $usuario['usu_direccion'] }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Teléfono -->
        <div>
            <label for="usu_telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input id="usu_telefono" name="usu_telefono" type="text" pattern="[0-9()+]{1,20}" maxlength="20"
                   value="{{ $usuario['usu_telefono'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ $usuario['email'] }}"
                   maxlength="70" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- Botón submit -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Actualizar') }}</x-primary-button>
        </div>

    </div>
</form>

    <!-- Formulario para la imagen de perfil -->
    <form method="post" action="{{ route('storeImage', $name = auth()->user()->num_doc) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        
        <div>
            <x-input-label for="imag_perfil" :value="__('Imagen de Perfil')" />
            <div class="mt-3 mb-4  relative w-fit">
                <label for="imag_perfil" class="cursor-pointer group">
                    @if ($usuario->imag_perfil)
                    @php
                        $name = auth()->user()->usu_nombres;
                        $perfil = auth()->user()->cargos()->first()->car_nombre;
                
                        $external_id = auth()->user()->external_id;
                
                        if ($external_id) {
                            $img_route = Auth::user()->imag_perfil;
                        } else {
                            $img_route = 'storage/' . Auth::user()->imag_perfil;
                        }
                    @endphp
            
                    <img src="{{ asset($img_route) }}" alt="Profile Image" class="w-36 h-36 rounded-full object-cover border-2 border-gray-300 shadow-sm  group-hover:opacity-75 transition duration-300">

                    <div class="absolute inset-0 rounded-full bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-300">
                        <span class="text-white text-sm">Cambiar imagen</span>
                    </div>
                    @else
                        <p class="text-sm text-gray-500 italic">{{ __('No se ha cargado ninguna imagen de perfil.') }}</p>
                    @endif
                </label>
        <!-- Input de imagen oculto -->
        <input id="imag_perfil" name="imag_perfil" type="file" class="hidden" accept="image/*">            </div>
        </div>
 <x-input-error class="mt-2" :messages="$errors->get('imag_perfil')" />
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Subir Imagen') }}</x-primary-button>
        </div>
    </form>
</section>
