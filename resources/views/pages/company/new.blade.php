<x-app-layout>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Nueva empresa</h1>
            <div aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('companies.index') }}">Empresas</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Nueva empresa
                    </li>
                </ol>
            </div>
        </div>

        <!-- Page Content -->
       <div class="row">
            <div class="col-md-12 col-sm-12">

                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('companies.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header text-center">
                            <span>Datos de la empresa</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la empresa</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="emailE" class="form-label">Correo de la empresa</label>
                                <input type="email" class="form-control" id="emailE" name="emailE" value="{{ old('emailE') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="pb-2 pt-2"></div>
                    <div class="card">
                        <div class="card-header text-center">
                            <span>Datos del dueño</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="movil" class="form-label">Teléfono</label>
                                <input type="number" class="form-control" id="movil" name="movil" value="{{ old('movil') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <input type="text" class="form-control" id="observaciones" name="observaciones" value="{{ old('observaciones') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="pb-2 pt-2">
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-primary" type="submit">Crear</button>
                            <button class="btn btn-secondary" type="button">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
       </div>
    </div>
</x-app-layout>