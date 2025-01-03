@extends('layouts.backend')

@section('content')
    <button type="button" class="btn btn-primary btn-floating" id="abrirModal"
        style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; border-radius: 50%; background-color: #308a5ab3; display: flex; justify-content: center; align-items: center; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
        <i class="fa fa-plus" style="font-size: 24px; color: #fff;"></i>
    </button>
    <div class="content">

        <div class="d-md-flex justify-content-between align-items-center py-3 pt-md-3 pb-md-0">
            <div>
                <h2 class="content-heading">
                    <i class="fa fa-angle-right text-muted me-1"></i> Vista rapida mes actual {{ $mes_actual }}
                </h2>
            </div>

        </div>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="row text-center">
                    <div class="col-md-4 py-3">
                        <div class="fs-1 fw-light text-dark mb-1">
                            L {{ number_format($presupuesto, 2, '.', ',') }}

                        </div>
                        <a class="link-fx fs-sm fw-bold text-uppercase text-muted" href="javascript:void(0)">Presupuesto</a>
                    </div>
                    <div class="col-md-4 py-3">
                        <div class="fs-1 fw-light text-danger mb-1">
                            L {{ number_format($gasto_mes, 2, '.', ',') }}
                        </div>
                        <a class="link-fx fs-sm fw-bold text-uppercase text-muted" href="javascript:void(0)">Gastos del
                            mes</a>
                    </div>
                    <div class="col-md-4 py-3">
                        @if ($diferencia > 0)
                            <div class="fs-1 fw-light mb-1 text-success">
                            @else
                                <div class="fs-1 fw-light text-danger mb-1">
                        @endif
                        L {{ number_format($diferencia, 2, '.', ',') }}

                    </div>
                    <a class="link-fx fs-sm fw-bold text-uppercase text-muted" href="javascript:void(0)">
                        @if ($diferencia > 0)
                        Disponible
                        @else
                        Déficit
                        @endif
                        </a>
                </div>
            </div>
        </div>


    </div>
    <div class="d-md-flex justify-content-between align-items-center py-3 pt-md-3 pb-md-0">
        <div>
            <h2 class="content-heading">
                <i class="fa fa-angle-right text-muted me-1"></i> Mes anterior {{ $mes_anterior }}
            </h2>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row text-center">
                <div class="col-md-4 py-3">
                    <div class="fs-1 fw-light text-dark mb-1">
                        L {{ number_format($presupuesto, 2, '.', ',') }}

                    </div>
                    <a class="link-fx fs-sm fw-bold text-uppercase text-muted" href="javascript:void(0)">Presupuesto</a>
                </div>
                <div class="col-md-4 py-3">
                    <div class="fs-1 fw-light text-danger mb-1">
                        L {{ number_format($gasto_anterior, 2, '.', ',') }}
                    </div>
                    <a class="link-fx fs-sm fw-bold text-uppercase text-muted" href="javascript:void(0)">Gastos del
                        mes</a>
                </div>
                <div class="col-md-4 py-3">
                    @if ($diferencia_anterior > 0)
                        <div class="fs-1 fw-light mb-1 text-success">
                        @else
                            <div class="fs-1 fw-light text-danger mb-1">
                    @endif
                    L {{ number_format($diferencia_anterior, 2, '.', ',') }}

                </div>
                <a class="link-fx fs-sm fw-bold text-uppercase text-muted" href="javascript:void(0)">
                    @if ($diferencia_anterior > 0)
                    Disponible
                    @else
                    Déficit
                    @endif
                    </a>
            </div>
        </div>
    </div>

    </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-block-fadein" tabindex="-1" aria-labelledby="modal-block-fadein" aria-hidden="true">
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
@push('css')
    <style>
        .btn-floating {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #28a745;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-floating:hover {
            background-color: #218838;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
@endpush


@push('js')
    <script src="{{ asset('/js/lib/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const fechaInput = document.getElementById("fecha");
            const hoy = new Date().toISOString().split("T")[0];
            fechaInput.value = hoy;
            $('#abrirModal').click(function() {
                $('#modal-block-fadein').modal('show');
            });

            $('#addAccountForm').on('submit', function(event) {
                event.preventDefault(); // Previene el comportamiento por defecto del formulario

                // Cambiar texto y deshabilitar el botón de guardar
                const $submitButton = $(this).find('button[type="submit"]');
                $submitButton.prop('disabled', true).text('Espere...');

                // Limpiar mensajes de error previos
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    url: '{{ route('transacciones.store') }}', // Ruta para agregar cuenta
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Cerrar el modal si la transacción fue agregada exitosamente
                            $('#modal-block-fadein').modal('hide');
                            // Limpiar el formulario
                            $('#addAccountForm')[0].reset();
                            alert('Transacción agregada con éxito.');

                            // Recargar la página después de cerrar el modal
                            location.reload();
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

            // Recargar la página al cerrar el modal si se ha agregado una transacción
            $('#modal-block-fadein').on('hidden.bs.modal', function() {
                if ($('#addAccountForm')[0].checkValidity()) {
                    location.reload();
                }
            });
        });
    </script>
@endpush
