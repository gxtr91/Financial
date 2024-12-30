@extends('layouts.backend')
@section('content')
    <button type="button" class="btn btn-primary btn-floating" id="abrirModal"
        style="z-index:100; position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; border-radius: 50%; background-color: #308a5ab3; display: flex; justify-content: center; align-items: center; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
        <i class="fa fa-plus" style="font-size: 24px; color: #fff;"></i>
    </button>
    <div class="content">
        <div class="row justify-content-between align-items-center py-3 pt-md-3 pb-md-0">
            <div class="col-md-6">
                <h2 class="content-heading">
                    <i class="fa fa-angle-right text-muted me-1"></i> Reporte de gastos {{ $mes_actual }}
                </h2>
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
                                <th data-priority="1" style="width:100px">Cuenta</th>
                                <th data-priority="1" style="width:250px">Descripcion</th>
                                <th data-priority="1" style="width:90px">Limite</th>
                                <th data-priority="1" style="width:100px">Gasto actual</th>
                                <th data-priority="1" style="width:100px">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos serán insertados aquí por DataTables usando Ajax -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>TOTALES</th>
                                <th colspan="4" style="text-align:right">Total:</th>
                            </tr>
                        </tfoot>
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
       .estado {
           cursor: pointer;
           padding: 5px 10px;
           border-radius: 10px;
           font-weight: bold;
           text-align: center;
           display: inline-block;
       }

       .estado-sincomenzar {
           background-color: #f8d7da;
           color: #721c24;
       }

       .estado-completado {
           background-color: #d4edda;
           color: #155724;
       }

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
            display: flex;
            justify-content: space-between; /* Distribuir los elementos: uno a la izquierda, otro a la derecha */
            align-items: center; /* Centrar los elementos verticalmente */
            flex-wrap: nowrap; /* Evitar que se envuelvan inicialmente */
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

       #data-table th:nth-child(6) {
           text-align: center;
           /* Alinea el texto del encabezado a la derecha */
       }

       tbody {
           vertical-align: middle !important
       }

       .dataTables_wrapper .dataTables_length,
       .dataTables_wrapper .dataTables_filter {
           display: inline-block;
           /* Mantener en la misma fila */
           margin-bottom: 10px;
           vertical-align: middle;
           padding: 5px;
       }

       /* Input y Select: Diseño normal */
       .dataTables_wrapper .dataTables_filter input,
       .dataTables_wrapper .dataTables_length select {
           border: 1px solid #ddd;
           border-radius: 5px;
           padding: 3px 20px;
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
            const fechaInput = document.getElementById("fecha");
            const hoy = new Date().toISOString().split("T")[0];
            fechaInput.value = hoy;
            var tabla = $('#data-table').DataTable({
                language: {
                    search: "Descripción:",
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
                order: [
                    [3, "desc"]
                ],
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                ordering: true,
                pageLength: 100,
                columnDefs: [{
                        orderable: false,
                        targets: [1, 2, 4]
                    },
                    {
                        responsivePriority: 1,
                        targets: 0
                    }, // Nombre de cuenta - siempre visible
                    {
                        responsivePriority: 2,
                        targets: 2
                    }, // Límite - siempre visible
                    {
                        responsivePriority: 3,
                        targets: 3
                    }, // Gasto actual - siempre visible
                    {
                        responsivePriority: 4,
                        targets: 4
                    }, // Saldo disponible - siempre visible
                    {
                        targets: -1,
                        visible: true,
                        responsivePriority: 5
                    }
                ],
                ajax: {
                    url: "{{ route('reportes.json_gastos_mensuales') }}"
                },
                columns: [{
                        data: 'nombre_cuenta',
                        name: 'nombre_cuenta'
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion'
                    },
                    {
                        data: 'limite',
                        name: 'limite',
                        className: 'text-end',
                        render: function(data, type, row) {
                            return parseFloat(data).toLocaleString('en', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    },
                    {
                        data: 'sumatoria_transacciones',
                        name: 'sumatoria_transacciones',
                        className: 'text-end',
                        render: function(data, type, row) {
                            return parseFloat(data).toLocaleString('en', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    },
                    {
                        data: 'saldo_disponible',
                        name: 'saldo_disponible',
                        className: 'text-end',
                        render: function(data, type, row) {
                            var saldo = parseFloat(data);
                            var formatted = saldo.toLocaleString('en', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            if (saldo < 0) {
                                return '<span style="font-weight:bold; color:red;">' + formatted +
                                    '</span>';
                            }
                            if (saldo > 0) {
                                return '<span style="font-weight:bold; color:green;">' + formatted +
                                    '</span>';
                            } else {
                                return '<span style="font-weight:bold; color:black;">' +
                                    formatted +
                                    '</span>';
                            }
                        }
                    }
                ],
                dom: 'Bfrtip',

                footerCallback: function(row, data, start, end, display) {
    var api = this.api();

    // 1. Cálculo del Gasto actual (columna 3)
    var totalSaldo = api.column(3, { page: 'current' }).data().reduce(function(a, b) {
        return parseFloat(a) + parseFloat(b);
    }, 0);
    var formattedSaldo = totalSaldo.toLocaleString('en', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // 2. Cálculo del Presupuesto total (columna -3 desde el final)
    var totalPresupuesto = api.column(api.columns().count() - 3).data().reduce(function(a, b) {
        return parseFloat(a) + parseFloat(b);
    }, 0);
    var formattedPresupuesto = totalPresupuesto.toLocaleString('en', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // 3. Cálculo de la Diferencia y determinación del estado
    var diferencia = totalPresupuesto - totalSaldo;
    var estado = diferencia >= 0 ? '<span style="color:green">Disponible</span>' : '<span style="color:red">Sobre gasto</span>';
    var formattedDiferencia = Math.abs(diferencia).toLocaleString('en', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Renderización en el footer
    $(api.column(3).footer()).html(
        `<div>Presupuesto: L ${formattedPresupuesto}</div>
         <div>Gasto actual: L ${formattedSaldo}</div>
         <div>Diferencia: L ${formattedDiferencia} (${estado})</div>`
    );
}

            });

        

            $('#abrirModal').click(function() {
                $('#modal-block-fadein').modal('show');
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
