@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link " data-bs-toggle="tab" href="#home">Iniciar sesion</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#menu1">Crear cuenta</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container  active" id="menu1">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="name" class="col-md-4 col-form-label text-md-end">Nombre</label>

                                            <div class="col-md-6">
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" required autocomplete="given-name"
                                                    autofocus>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-form-label text-md-end">Apellido</label>

                                            <div class="col-md-6">
                                                <input id="last_name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    name="last_name" value="{{ old('last_name') }}" required
                                                    autocomplete="last_name">
                                            </div>
                                        </div>



                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email">
                                                <span id="validacionCorreo"></span>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="password"
                                                class="col-md-4 col-form-label text-md-end">Contraseña</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="new-password">
                                                <span id="validacion">La contraseña debe tener al menos 8 caracteres</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="password-confirm"
                                                class="col-md-4 col-form-label text-md-end">Confirme su
                                                contraseña</label>

                                            <div class="col-md-6">
                                                <input id="password-confirm" type="password" class="form-control"
                                                    name="password_confirmation" required autocomplete="new-password">
                                                <span id="validacionConfirmacion">La contraseña debe tener al menos 8
                                                    caracteres</span>
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" required name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        Acepto las <a target="_blank"
                                                            href="https://adsupp.com/politicas-de-privacidad"
                                                            class="fw-bold">Politicas de Privacidad</a> y <a class="fw-bold"
                                                            target="_blank"
                                                            href="https://adsupp.com/terminos-y-condiciones">Terminos y
                                                            Condiciones</a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                    {{ __('Register') }}
                                                </button>
                                            </div>
                                        </div>


                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane container fade" id="home">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" required
                                                    autocomplete="email" autofocus>
                                                <span id="validacionCorreo"></span>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="password"
                                                class="col-md-4 col-form-label text-md-end">Contraseña</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password">
                                                <span id="validacion">La contraseña debe tener al menos 8 caracteres</span>

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        Recordarme
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-0 text-center">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                    Ingresar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-0 text-center">
                                            <div class="col-md-12 text-center">
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    ¿Olvidaste tu contraseña?
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const campoContrasena = document.getElementById('password');
            const campoConfirmacion = document.getElementById('password-confirm');
            const validacion = document.getElementById('validacion');
            const validacionConfirmacion = document.getElementById('validacionConfirmacion');
            const campoCorreo = document.getElementById('email');
            const validacionCorreo = document.getElementById('validacionCorreo');


            campoContrasena.addEventListener('keyup', function() {
                const cantidadCaracteres = campoContrasena.value.length;

                // Validación: Verificar si la contraseña tiene al menos 8 caracteres
                if (cantidadCaracteres >= 8) {
                    validacion.textContent = 'Contraseña válida';
                    validacion.style.color = 'green';
                } else {
                    validacion.textContent = 'La contraseña debe tener al menos 8 caracteres';
                    validacion.style.color = 'red';
                }
            });

            campoConfirmacion.addEventListener('keyup', function() {
                const contrasena = campoContrasena.value;
                const confirmacion = campoConfirmacion.value;

                // Validación: Verificar si la confirmación coincide con la contraseña
                if (contrasena === confirmacion) {
                    validacionConfirmacion.textContent = 'Las contraseñas coinciden';
                    validacionConfirmacion.style.color = 'green';
                } else {
                    validacionConfirmacion.textContent = 'Las contraseñas no coinciden';
                    validacionConfirmacion.style.color = 'red';
                }
            });
            campoCorreo.addEventListener('keyup', function() {
                const correo = campoCorreo.value;

                // Validación: Verificar si el correo es válido
                if (validarCorreo(correo)) {
                    validacionCorreo.textContent = 'Correo electrónico válido';
                    validacionCorreo.style.color = 'green';
                } else {
                    validacionCorreo.textContent = 'Correo electrónico no válido';
                    validacionCorreo.style.color = 'red';
                }
            });

            function validarCorreo(correo) {
                // Expresión regular para validar una dirección de correo electrónico
                const patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return patronCorreo.test(correo);
            }
        });
    </script>
@endsection
