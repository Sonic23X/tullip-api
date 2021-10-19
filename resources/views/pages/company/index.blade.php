<x-app-layout>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Empresas</h1>
            <a href="{{ route('companies.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> 
                Crear empresa
            </a>
        </div>

        <!-- Page Content -->
       <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Propietario</th>
                                        <th scope="col">Usuarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                    <tr>
                                        <th scope="row">
                                            {{ $company->id }}
                                        </th>
                                        <td>
                                            {{ $company->nombre }}
                                        </td>
                                        <td>
                                            {{ $company->admin->name }} - {{ $company->admin->email }}
                                        </td>
                                        <td>
                                            {{ $company->users->count() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>
</x-app-layout>