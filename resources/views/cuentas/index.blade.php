@extends('layouts.backend')
@section('content')
    <button type="button" class="btn btn-lg rounded-0 btn-hero btn-primary me-1 mb-3" id="abrirModal">
        <i class="fa fa-fw fa-money-bill-1 me-1"></i> Agregar transacción
    </button>
    <!-- Page Content -->
    <div class="content">
        <div class="row justify-content-between align-items-center py-3 pt-md-3 pb-md-0">
            <div class="col-md-6">
                <h2 class="content-heading">
                    <i class="fa fa-angle-right text-muted me-1"></i> Lista de cuentas y presupuestos
                </h2>
            </div>

        </div>
        <div class="block block-rounded">

            <div class="tab-pane fade active show" id="btabs-animated-fade-home" role="tabpanel"
                aria-labelledby="btabs-animated-fade-home-tab" tabindex="0">
                <div class="block block-content block-content-full">
                    <x-data-table id="cuentas-table" ajax-url="{{ route('cuentas.json') }}" :columns="$columns"
                        :json-columns="$json" :nuevo-boton="true" />
                </div>
            </div>
        </div>
        <!-- END Page Content -->
        <!--start modal -->

    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-block-fadein" tabindex="-1" aria-labelledby="modal-block-fadein"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">Agregar Nueva Cuenta</h5>

                </div>
                <form id="addAccountForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre_cuenta">Nombre de la Cuenta</label>
                            <input type="text" class="form-control" id="nombre_cuenta" name="nombre_cuenta" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">

                            <div class="form-check form-switch mt-4 mb-4">
                                <input class="form-check-input" type="checkbox" value="" name ="active"
                                    id="active">
                                <label class="form-check-label" for="example-switch-default2">Es parte del presupuesto
                                    mensual?</label>

                            </div>
                        </div>
                        <div style="display:none" id="presupuesto" class="col-xl-7 mb-4">
                            <div class="form-group mt-4">
                                <label for="limite">Limite mensual</label>
                                <input type="number" class="form-control" id="limite" name="limite" step="0.01">
                            </div>
                            <div class="form-group mt-4">
                                <label for="alerta">Alertar en este monto (opcional)</label>
                                <input type="number" class="form-control" id="alerta" name="alerta" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end modal -->
    <!-- Modal -->
    <div class="modal fade" id="modal-block-transaccion" tabindex="-1" aria-labelledby="modal-block-transaccion"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">Nueva transaccion</h5>

                </div>
                <form id="addAccountForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre_cuenta">Seleccione la Cuenta</label>
                            <div class="form-group col-12 ">
                                <select class="js-select2 form-select" id="cuenta" name="cuenta" style="width: 100%;">
                                    <option value="">::. Seleccione la cuenta .::</option>
                                    @foreach ($cuentas as $cuenta)
                                        <option value="{{ $cuenta->id }}">{{ $cuenta->nombre_cuenta }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="limite">Monto</label>
                            <input type="number" class="form-control" id="monto" name="monto" step="0.01"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>

                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end modal -->
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            const fechaInput = document.getElementById("fecha");
            const hoy = new Date().toISOString().split("T")[0];
            fechaInput.value = hoy;
            $('#addAccountForm').on('submit', function(event) {

                event.preventDefault(); // Previene el comportamiento por defecto del formulario

                // Cambiar texto y deshabilitar el botón de guardar
                const $submitButton = $(this).find('button[type="submit"]');
                $submitButton.prop('disabled', true).text('Espere...');

                // Limpiar mensajes de error previos
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    url: '{{ route('cuentas.store') }}', // Ruta para agregar cuenta
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Cerrar el modal si la cuenta fue agregada exitosamente
                            $('#modal-block-fadein').modal('hide');
                            // Limpiar el formulario
                            $('#addAccountForm')[0].reset();
                            // Refrescar el DataTable
                            $('#data-table').DataTable().ajax.reload(null,
                                false); // Recargar sin reiniciar paginación
                        } else {
                            alert('Hubo un error al agregar la cuenta.');
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            // Mostrar errores de validación
                            $('#' + field).addClass('is-invalid');
                            $('#' + field).after('<div class="invalid-feedback">' +
                                messages[0] + '</div>');
                        });
                    },
                    complete: function() {
                        // Restaurar el texto y habilitar el botón de guardar
                        $submitButton.prop('disabled', false).text('Guardar');
                    }
                });
            });

            const targetDiv = document.getElementById("presupuesto");
            const btn = document.getElementById("add-active");
            $("#active").on("click", function() {
                $("#presupuesto").fadeToggle("medium");
            });

            //Insertar transaccion
            $('#abrirModal').click(function() {
                $('#modal-block-transaccion').modal('show');
            });
            $('#addAccountForm').on('submit', function(event) {
                event.preventDefault();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    url: '{{ route('transacciones.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#modal-block-fadein').modal('hide');
                            $('#addAccountForm')[0].reset();
                            $('#data-table').DataTable().ajax.reload(null, false);
                            alert('Transacción agregada con éxito.');
                        } else {
                            alert('Hubo un error al agregar la cuenta.');
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field).after('<div class="invalid-feedback">' +
                                messages[0] + '</div>');
                        });
                    }
                });
            });

        });
    </script>
@endpush
