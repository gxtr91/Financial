@extends('layouts.backend')
@section('content')
    <button type="button" class="btn btn-lg rounded-0 btn-hero btn-primary me-1 mb-3" id="abrirModal">
        <i class="fa fa-fw fa-money-bill-1 me-1"></i> Agregar transacción
    </button>
    <div class="content">
        <div class="row justify-content-between align-items-center py-3 pt-md-3 pb-md-0">
            <div class="col-md-6">
                <h2 class="content-heading">
                    <i class="fa fa-angle-right text-muted me-1"></i> Últimas transacciones
                </h2>
            </div>
            <div class="col-md-6 d-md-flex justify-content-end align-items-center">
                <div class="col-md-6 mb-1">
                    <div id="fechas">
                        <div class="input-daterange input-group js-datepicker-enabled" data-date-format="dd/mm/yyyy"
                            data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            <input type="text" class="form-control" id="startDate" placeholder="Fecha inicial">
                            <span class="input-group-text fw-semibold">
                                <i class="fa fa-fw fa-arrow-right"></i>
                            </span>
                            <input type="text" class="form-control" id="endDate" placeholder="Fecha final">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3 mb-1 ms-md-3">
                    <select class="js-select2 form-select" id="cbo-cuenta" name="cbo-cuenta" style="width: 100%;">
                        <option value="">::. Cuentas .::</option>
                        @foreach ($cuentas as $cuenta)
                            <option value="{{ $cuenta->id }}">{{ $cuenta->nombre_cuenta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 mb-1 ms-md-3">
                    <select class="js-select2 form-select" id="cbo-usuario" name="cbo-usuario" style="width: 100%;">
                        <option value="">::. Usuarios .::</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-1 ms-md-3">
                    <button id="btn-limpiar-filtros" class="btn btn-secondary" type="button">Limpiar filtros</button>
                </div>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="tab-pane fade active show" id="btabs-animated-fade-home" role="tabpanel"
                aria-labelledby="btabs-animated-fade-home-tab" tabindex="0">
                <div class="block block-content block-content-full">
                    <table id="data-table"
                        class="table table-bordered table-striped table-vcenter js-dataTable-responsive dataTable no-footer dtr-inline"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th data-priority="1" style="width:70px">Fecha</th>
                                <th data-priority="1" style="width:100px">Cuenta</th>
                                <th data-priority="1" style="width:250px">Descripcion</th>
                                <th data-priority="1" style="width:90px">Monto</th>
                                <th data-priority="1" style="width:100px">Enviado por</th>
                            </tr>
                        </thead>
                    </table>
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
    <!-- TNS -->
    <link rel="stylesheet" href="{{ asset('/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <!-- END TNS -->
    <link rel="stylesheet" href="{{ asset('/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">

    <style>
        table#data-table tbody tr:nth-child(even):hover td {
            background-color: #f8f8f8 !important;
        }

        table#data-table tbody tr:nth-child(odd):hover td {
            background-color: #f8f8f8 !important;
        }
    </style>
    <style>
        @media (min-width: 768px) {

            /* Asume que 768px es el breakpoint md en Bootstrap */
            .d-md-flex.justify-content-between .dropdown,
            .form-group {
                flex: 1;
            }

            .d-md-flex.justify-content-between .dropdown {
                max-width: 350px;
                /* Ajustar según sea necesario */
            }

            .form-group {
                min-width: 200px;
                /* Ajustar según sea necesario */
            }

            .form-select {
                width: 100%;
                /* Asegura que select2 tome toda la anchura del form-group */
            }
        }

        #data-table {
            visibility: hidden;
        }

        .btn-pdf {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-pdf:hover {
            background-color: #2980b9;
        }

        .table> :not(caption)>*>* {
            padding: 0.20rem 0.75rem !important;
        }

        div.dt-buttons {
            position: relative;
            float: right;
            padding: 0 0 10px 10px;
            top: -5px;
        }

        /* Estilos para hacer el iframe responsivo */
        .responsive-iframe {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            /* Aspect ratio 16:9 */
        }

        .responsive-iframe iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        #data-table thead,
        td {
            font-size: small;
        }

        .custom-select-size {
            width: 50%;
            /* Ajusta el ancho */
            height: 35px;
            /* Ajusta la altura */
            font-size: 14px;
            /* Ajusta el tamaño del texto */
        }

        .centrar-texto {
            text-align: center;

        }

        .dataTables_filter {
            display: block;
        }

        .text-right {
            text-align: right;
        }

        #data-table th:nth-child(4) {
            text-align: center;
            /* Alinea el texto del encabezado a la derecha */
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('/js/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>

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

            var tabla = $('#data-table').DataTable({
                language: {
                    search: "Buscar:",
                    lengthMenu: "_MENU_",
                    info: "_START_ - _END_ de _TOTAL_",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros en total)",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    zeroRecords: "No se encontraron registros",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Sig",
                        previous: "Ant"
                    }
                },
                header: true,
                initComplete: function(settings, json) {
                    $('#data-table').css('visibility', 'visible');
                    $('#data-table thead th').css({
                        "background-color": "rgba(48, 138, 90, 0.8)",
                        "height": "30px",
                        "font-size": "14px",
                        "color": "white",
                        "width": "auto"
                    });
                },
                order: [
                    [0, "desc"]
                ],
                processing: true,
                serverSide: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: -1
                    }
                },
                searching: true,
                ordering: true,
                pageLength: 100,
                columnDefs: [{
                        orderable: false,
                        targets: [1, 2, 4]
                    },
                    {
                        responsivePriority: 1,
                        targets: 0 // Fecha - Siempre visible en móvil
                    },
                    {
                        responsivePriority: 2,
                        targets: 1 // Nombre de la cuenta - Siempre visible en móvil
                    },
                    {
                        responsivePriority: 3,
                        targets: 3 // Monto - Siempre visible en móvil
                    },
                    {
                        responsivePriority: 4,
                        targets: 2 // Descripción - Visible en móvil si hay espacio
                    },
                    {
                        targets: -1, // Última columna
                        visible: true, // Asegura que las columnas visibles en móvil estén siempre al frente
                        responsivePriority: 5 // Usuario - Visible en móvil si hay espacio
                    }
                ],
                ajax: {
                    url: "{{ route('transacciones.json') }}",
                    data: function(d) {
                        d.cuenta = $('#cbo-cuenta').val();
                        d.usuario = $('#cbo-usuario').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    }
                },
                columns: [{
                        data: 'fecha',
                        name: 'fecha'
                    },
                    {
                        data: 'cuenta.nombre_cuenta',
                        name: 'nombre_cuenta'
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion'
                    },
                    {
                        data: 'monto',
                        name: 'monto',
                        className: 'text-right',
                        render: function(data, type, row) {
                            return parseFloat(data).toLocaleString('en', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    },
                    {
                        data: 'usuario.name',
                        name: 'user'
                    }
                ],
                dom: 'Bfrtip',
                drawCallback: function() {
                    var api = this.api();
                    var total = api.column(api.columns().count() - 2).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    var totalFormateado = total.toLocaleString('en', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    $('#total').html('Total consumo: L ' + totalFormateado);
                },
            });

            $('.dt-buttons').append('<div style="font-weight:bold" id="total"></div>');
            $('.dt-buttons div').css({
                'padding': '5px',
                'display': 'inline-block'
            });

            $('#cbo-cuenta, #cbo-usuario, #startDate, #endDate').on('change', function() {
                tabla.ajax.reload();
            });

            $('#btn-limpiar-filtros').on('click', function() {
                $('#cbo-cuenta').val('');
                $('#cbo-usuario').val('');
                $('#startDate').val('');
                $('#endDate').val('');
                tabla.search('').columns().search('').draw();
            });

            $('#abrirModal').click(function() {
                $('#modal-block-fadein').modal('show');
            });

            $('#addAccountForm').on('submit', function(event) {
                event.preventDefault();
                const $submitButton = $(this).find('button[type="submit"]');
                $submitButton.prop('disabled', true).text('Espere...');
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
                    },
                    complete: function() {
                        $submitButton.prop('disabled', false).text('Guardar');
                    }
                });
            });
        });
    </script>
@endpush
