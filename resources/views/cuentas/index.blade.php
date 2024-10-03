@extends('layouts.backend')
@section('content')
    <!-- Page Content -->
    <div class="content">



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
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="limite">Limite mensual</label>
                            <input type="number" class="form-control" id="limite" name="limite" step="0.01"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="alerta">Alerta</label>
                            <input type="number" class="form-control" id="alerta" name="alerta" step="0.01"
                                required>
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
            $('#addAccountForm').on('submit', function(event) {
                event.preventDefault(); // Previene el comportamiento por defecto del formulario

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
                    }
                });
            });
        });
    </script>
@endpush
