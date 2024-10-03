@extends('layouts.backend')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <div
            class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h2 class="content-heading">
                    <i class="fa fa-angle-right text-muted me-1"></i> Ultimas transacciones
                </h2>

            </div>
            <div class="mt-4 mt-md-0">

                <div class="dropdown d-inline-block">
                    <div style="display: none" id='fechas'>
                        <div class="input-daterange input-group js-datepicker-enabled" data-date-format="dd/mm/yyyy"
                            data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            <input type="text" id="startDate" placeholder="Fecha inicial">
                            <span class="input-group-text fw-semibold">
                                <i class="fa fa-fw fa-arrow-right"></i>
                            </span>
                            <input type="text" id="endDate" placeholder="Fecha final">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="tab-pane fade active show" id="btabs-animated-fade-home" role="tabpanel"
                aria-labelledby="btabs-animated-fade-home-tab" tabindex="0">
                <div class="block block-content block-content-full">
                    @foreach ($transacciones as $transaccion)
                        <a class="block block-rounded block-link-shadow border-start border-danger border-3"
                            href="javascript:void(0)">
                            <div class="block-content d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="fs-lg fw-semibold mb-0">
                                        {{ number_format($transaccion->monto, 2, '.', ',') }}
                                    </p>
                                    <p class="mb-0">
                                        {{ $transaccion->cuenta->nombre_cuenta }}
                                    </p>
                                    <p class="text-muted mt-1">
                                        {{ $transaccion->descripcion }}
                                    </p>
                                </div>
                                <div class="ms-3">
                                    <i class="fa fa-arrow-right text-danger"></i>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-sm bg-body-light">
                                <span class="fs-sm text-muted">Enviado por
                                    <strong>{{ $transaccion->usuario->name }}</strong> fecha
                                    <strong>@php
                                        setlocale(LC_ALL, 'es_ES@euro', 'es_ES', 'esp');
                                        $fecha = strftime('%d de %B, %Y', strtotime($transaccion->created_at));
                                        echo $fecha; // 09 de marzo de 2010
                                    @endphp</strong></span>
                            </div>
                        </a>
                    @endforeach
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
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@push('js')
    <script src="{{ asset('/js/lib/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                document.getElementById('fechas').style.display = 'block';
            }, 500);

            const opcionesFlatpickr = {
                altInput: true,
                altFormat: "Y-m-d",
                dateFormat: "Y-m-d",
                locale: "es" // Para español
            };

            // Inicializar Flatpickr en los campos de entrada
            const fechaInicio = flatpickr("#startDate", {
                ...opcionesFlatpickr,
                onChange: function(selectedDates, dateStr, instance) {
                    fechaFin.set("minDate", dateStr);
                }
            });

            const fechaFin = flatpickr("#endDate", {
                ...opcionesFlatpickr,
                onChange: function(selectedDates, dateStr, instance) {
                    fechaInicio.set("maxDate", dateStr);
                }
            });


        });
    </script>
@endpush
