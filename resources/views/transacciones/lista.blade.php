@extends('layouts.backend')
@section('content')
    <button type="button" class="btn btn-primary btn-floating" id="abrirModal"
        style="z-index:100; position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; border-radius: 50%; background-color: #308a5ab3; display: flex; justify-content: center; align-items: center; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
        <i class="fa fa-plus" style="font-size: 24px; color: #fff;"></i>
    </button>
    <div class="content">
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fa fa-filter me-1"></i> Filtrar
            </button>
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
                                <th data-priority="1" style="width:100px">Fecha</th>
                                <th data-priority="1" style="width:100px">Cuenta</th>
                                <th data-priority="1" style="width:250px">Descripcion</th>
                                <th data-priority="1" style="width:90px"><i class="fa fa-money-bill-alt me-1"></i>
                                    Monto</th>
                                <th data-priority="1" style="width:100px">Enviado por</th>
                                <th data-priority="1" style="width:100px">Aciones</th>

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
    <!-- Modal de Filtros -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filtrar Transacciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Filtros aquí -->
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
                    <div class="form-group mt-3">
                        <label for="cbo-cuenta">Cuenta</label>
                        <select class="js-select2 form-select" id="cbo-cuenta" name="cbo-cuenta">
                            <option value="">::. Cuentas .::</option>
                            @foreach ($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}">{{ $cuenta->nombre_cuenta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="cbo-usuario">Usuario</label>
                        <select class="js-select2 form-select" id="cbo-usuario" name="cbo-usuario">
                            <option value="">::. Usuarios .::</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-limpiar-filtros" class="btn btn-secondary" type="button">Limpiar filtros</button>
                    <button id="btn-aplicar-filtros" class="btn btn-primary" type="button"
                        data-bs-dismiss="modal">Aplicar filtros</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <!-- TNS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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
         function deleteTransaction(id) {
                if (confirm('¿Estás seguro de eliminar esta transacción?')) {
                    $.ajax({
                        url: `/transacciones/${id}`,
                        method: 'POST', // Usamos POST porque estamos simulando un DELETE con _method
                        data: {
                            _token: '{{ csrf_token() }}', // Token CSRF necesario para las solicitudes POST en Laravel
                            _method: 'DELETE' // Indica a Laravel que esta solicitud es un DELETE, a pesar de ser POST
                        },
                        success: function() {
                            $('#data-table').DataTable().ajax.reload(); // Recarga la tabla de datos después de la eliminación
                            alert('Transacción eliminada!');
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText); // Imprime el error en consola para más detalles
                            alert('Ocurrió un error al intentar eliminar la transacción.');
                        }
                    });
                }
            }
        $(document).ready(function() {
            const fechaInput = document.getElementById("fecha");
            const hoy = new Date().toISOString().split("T")[0];
            fechaInput.value = hoy;
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
                    [0, 'desc']
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
                pageLength: 50,
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
                        className: 'text-end',
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
                    },
                    {
                        data: 'acciones',
                        name: 'acciones',
                        className: 'centrar-texto',
                    }
                ],
                dom: 'Bfrtip',
                drawCallback: function() {
                    var api = this.api();

                    // Obtener la respuesta JSON del servidor
                    var response = api.ajax.json();
                    var sumaMontoServidor = parseFloat(response.sumaMonto || 0);

                    // Verificar si el input de búsqueda está vacío
                    var searchValue = api.search();

                    var total = 0;

                    if (!searchValue) {
                        // Si el input de búsqueda está vacío, usar la suma del servidor
                        total = sumaMontoServidor;
                    } else {
                        // Si hay texto en el input de búsqueda, calcular la suma de la columna visible
                        total = api
                            .column(api.columns().count() - 3, { page: 'current' }) // Cambiar al índice correcto de tu columna "monto"
                            .data()
                            .reduce(function (a, b) {
                                return parseFloat(a || 0) + parseFloat(b || 0);
                            }, 0);
                    }

                    // Formatear el total para mostrarlo correctamente
                    var totalFormateado = total.toLocaleString('en', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Mostrar el total en el elemento con ID "total"
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

            var selectedCell = null;
            $('#data-table tbody').on('dblclick', 'td', function() {
                var cell = $(this);
                var columnIndex = cell.index();

                // Permitir la edición solo en ciertas columnas
                if (columnIndex === 2 || columnIndex === 3) {
                    if (selectedCell !==
                        this) { // Evitar múltiples instancias de inputField para la misma celda
                        if (selectedCell) {
                            $(selectedCell).find('input').trigger('focusout');
                        }
                        selectedCell = this;

                        var cellData = cell.text();
                        var rowData = tabla.row(cell.closest('tr')).data();
                        var inputField = $('<input>', {
                            type: 'text',
                            class: 'form-control form-control-sm',
                            value: cellData,
                            'data-id': rowData.id,
                            'data-type': columnIndex
                        });

                        cell.html(inputField);
                        inputField.focus();

                        if (columnIndex === 3) {
                            inputField.on('input', function() {
                                var inputNum = this.value.replace(/,/g, '');
                                if (!isNaN(inputNum) && inputNum !== '') {
                                    // Si ya existe un punto decimal, validamos que solo haya hasta dos decimales
                                    if (!inputNum.match(/^\d*\.?\d{0,2}$/)) {
                                        this.value = this.value.substring(0, this.value.length -
                                            1); // Limitar a dos decimales
                                        return;
                                    }

                                    // Formatear el número con separador de miles sin perder el punto decimal
                                    var partes = inputNum.split('.');
                                    partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g,
                                        ','); // Agregar comas en la parte entera
                                    this.value = partes.join(
                                        '.'); // Unir de nuevo la parte entera y decimal
                                } else {
                                    // Si el valor no es un número, lo limpiamos
                                    this.value = '';
                                }
                            });
                        }

                        inputField.on('keypress', function(e) {
                            if (e.which == 13) { // Código de Enter
                                inputField.blur(); // Forzar el evento focusout
                                e
                                    .preventDefault(); // Prevenir el comportamiento predeterminado del Enter
                            }
                        });
                    }
                }
            });

            // Manejar el evento focusout en el documento para evitar múltiples enlaces
            $(document).on('focusout', '#data-table tbody td input', function() {
                var input = $(this);
                var cell = input.closest('td');
                var columnIndex = cell.index();
                var inputValue = input.val().trim();

                if (!inputValue) {
                    alert('El campo no puede ser vacio.');
                    setTimeout(function() {
                        input.focus();
                    }, 1); // Restablece el foco después de cerrar la alerta
                    return false;
                }
                if (columnIndex === 3) {
                    var inputValue = input.val().replace(/,/g, '')
                        .trim(); // Remueve las comas antes de guardar

                }
                var originalValue = cell.data(
                    'original'); // Asumimos que guardamos el valor original en data-original

                if (originalValue !== inputValue) {
                    // Realizar la actualización solo si el valor ha cambiado
                    var inputId = input.data('id');
                    var dataType = input.data('type');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('transacciones.update') }}",
                        method: 'POST',
                        data: {
                            id: inputId,
                            data: dataType,
                            value: inputValue
                        },
                        success: function(response) {
                            if (response.success) {
                                tabla.ajax.reload(null, false);
                            } else {
                                alert(response.message)
                            }
                        },
                        error: function() {
                            console.log('Error updating data.');
                        }
                    });
                }

                //cell.text(inputValue); // Actualizar la celda con el nuevo valor
                selectedCell = null; // Restablecer la celda seleccionada
            });

        });
    </script>
@endpush
