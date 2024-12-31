<table id="data-table"
    class="table table-bordered table-striped table-vcenter js-dataTable-responsive dataTable no-footer dtr-inline"
    style="width:100%">
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>
</table>
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

        #data-table th:nth-child(3) {
            text-align: center;
            /* Alinea el texto del encabezado a la derecha */
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
            var initialColumns = {!! $jsonColumns !!};
            // Añadiendo la columna de estado con una función de renderizado
            initialColumns.splice(1, 0, {
                data: 'es_presupuesto',
                render: function(data, type, row) {
                    if (data === 'si') {
                        return '<span class="badge bg-success">Si</span>';
                    } else {
                        return '<span class="badge bg-warning">No</span>'; // Utilizando el color amarillo para 'Medio'
                    }
                }
            });
            initialColumns.splice(2, 0, {
                data: 'limite',
                title: 'Limite',
                className: 'text-right', // Alinea el contenido de la celda a la derecha
                render: function(data, type, row) {
                    // Si es para mostrar en pantalla, formatear el número
                    if (type === 'display' || type === 'filter') {
                        if (data === null || data === undefined) {
                            return '0.00'; // Muestra 0.00 si el valor es null o undefined
                        } else {
                            return parseFloat(data).toLocaleString('en', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }

                    // Si es para uso interno, como suma, devolver el valor numérico
                    return data === null || data === undefined ? 0 : parseFloat(data);
                }
            });
            initialColumns.splice(3, 0, {
                data: 'alerta',
                title: 'Alerta',
                className: 'text-right', // Alinea el contenido de la celda a la derecha
                render: function(data, type, row) {
                    if (data === null || data === undefined) {
                        return '0.00'; // Muestra 0.00 si el valor es null o undefined
                    } else {
                        return parseFloat(data).toLocaleString('en', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                    return data === null || data === undefined ? 0 : parseFloat(data);

                }
            });
            setTimeout(function() {
                //document.getElementById('fechas').style.display = 'block';
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
                header: true, // Enable custom header styles
                "initComplete": function(settings, json) {
                    $('#data-table').css('visibility', 'visible'); // Muestra la tabla
                    $('#data-table thead th').css({
                        "background-color": "rgba(48, 138, 90, 0.8)", // Color de fondo con transparencia
                        "height": "30px", // Reduce la altura a 30px
                        "font-size": "14px", // Tamaño de texto
                        "color": "white", // Color del texto
                        "width": "auto" // Ancho automático
                    });
                },
                order: [
                    [2, "desc"]
                ], // Sort by the second column in descending order
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                ordering: true,
                pageLength: 100,
                columnDefs: [{
                        orderable: false,
                        targets: [1, 2]
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

                ],
                ajax: {
                    url: '{!! $ajaxUrl !!}',
                    data: function(d) {
                        d.cuenta = $('#cbo-cuenta').val(); // Valor del primer combo box
                    }
                },
                columns: initialColumns,

                dom: 'Bfrtip',
                buttons: [{
                    text: 'Nueva cuenta',
                    className: 'btn btn-sm btn-primary',
                    action: function() {
                        $('#modal-block-fadein').modal('show');
                    }
                }, ],

                drawCallback: function() {
                    var api = this.api();

                    // Calcula la suma de la última columna para todas las páginas
                    var total = api.column(api.columns().count() - 3).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    var totalFormateado = total.toLocaleString('en', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    // Muestra el total en el elemento con ID 'total'
                    $('#total').html('Presupuesto: L ' + totalFormateado);
                },

            });
            var selectedCell = null;


            $('#data-table tbody').on('dblclick', 'td', function() {
                var cell = $(this);
                var columnIndex = cell.index();

                // Permitir la edición solo en ciertas columnas
                if (columnIndex === 0 || columnIndex === 2 || columnIndex === 3 || columnIndex === 4) {
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
                            class: 'form-control',
                            value: cellData,
                            'data-id': rowData.id,
                            'data-type': columnIndex
                        });

                        cell.html(inputField);
                        inputField.focus();

                        if (columnIndex === 2 || columnIndex === 3) {
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
                    alert('Ingrese un valor');
                    setTimeout(function() {
                        input.focus();
                    }, 1); // Restablece el foco después de cerrar la alerta
                    return false;
                }
                if (columnIndex === 2 || columnIndex === 3) {
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
                        url: "{{ route('cuentas.update') }}",
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

            $('.dt-buttons').append('<div style="font-weight:bold" id="total"></div>');

            var tabla = $('#data-table').DataTable();
            $('.dt-buttons div').css({
                'padding': '5px',
                'display': 'inline-block' // Esto garantiza que el padding se aplique correctamente
            });

            $('#cbo-cuenta').on('change', function() {
                var tabla = $('#data-table').DataTable();
                tabla.ajax.reload(); // Recarga la tabla manteniendo la posición de paginación actual
            });

            //FILTROS
            tabla.on('draw', function() {
                var info = tabla.page.info();
                //$('#total').html('Total cuentas: ' + info.recordsDisplay);


                var ids = [];

                // Selecciona todos los elementos que tienen un 'data-id' en las filas visibles de la tabla
                tabla.$('a[data-id]').each(function() {
                    // Añade el 'data-id' al array
                    var dataId = $(this).data('id');
                    if (dataId) { // Asegúrate de que el data-id existe y es válido
                        ids.push(dataId);
                    }
                });

            });

        });
    </script>
@endpush
