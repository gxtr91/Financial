@extends('layouts.backend')
@section('content')
    <button onclick="openNotaModal()" type="button" class="btn btn-lg rounded-0 btn-hero btn-primary me-1 mb-3"
        id="abrirModal">
        <i class="fa fa-fw fa-plus me-1"></i> Agregar Nota
    </button>
    <div class="content">
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fa fa-filter me-1"></i> Filtrar
            </button>
        </div>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table id="data-table"
                    class="table table-bordered table-striped table-vcenter js-dataTable-responsive dataTable no-footer dtr-inline"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 8%">Estado</th>
                            <th>Nombre</th>
                            <th>Prioridad</th>
                            <th><i class="fa fa-calendar-alt me-1"></i>Fecha Límite</th>
                            <th>Responsable</th>
                            <th style="width: 8%">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal para agregar/editar notas -->
    <div class="modal fade" id="modal-block-fadein" tabindex="-1" aria-labelledby="modal-block-fadein" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditModalLabel">Nueva Nota</h5>
                </div>
                <form id="addEditForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Título -->
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>

                        <!-- Fecha -->
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>

                        <!-- Prioridad -->
                        <div class="form-group">
                            <label for="prioridad">Prioridad</label>
                            <select class="form-select" id="prioridad" name="prioridad" required>
                                <option value="baja">Baja</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>

                        <!-- Responsable -->
                        <div class="form-group mt-3">
                            <label for="responsable">Responsable</label>
                            <div class="d-flex align-items-center">
                                <!-- Burbuja para la foto -->
                                <img id="responsablePhoto" src="https://via.placeholder.com/40" class="rounded-circle me-2"
                                    width="40" height="40" alt="Foto Responsable">
                                <!-- Combo box -->
                                <select class="form-select" id="responsable" name="responsable" required
                                    onchange="updateResponsablePhoto()">
                                    <option value="" data-photo="https://via.placeholder.com/40">Selecciona un
                                        Responsable</option>
                                    <option value="1"
                                        data-photo="https://scontent.ftgu1-3.fna.fbcdn.net/v/t39.30808-1/450557001_122100854132403797_5929500965298241666_n.jpg?stp=dst-jpg_s200x200&_nc_cat=101&ccb=1-7&_nc_sid=0ecb9b&_nc_eui2=AeEiKNDAi0UOqBHA5MFTN2XZuwWnfJYgGia7Bad8liAaJsQCtcXv3aRxSQtYS44DSeymjTHUdPmyDuggDwU89arX&_nc_ohc=-vpii0aug1MQ7kNvgGqO9Tt&_nc_zt=24&_nc_ht=scontent.ftgu1-3.fna&_nc_gid=AGxiZ-oRT3oflpvyAFS0lY3&oh=00_AYBsKu1PC_vgy7tzvj_gw8CPq_c34VA4dzUQPzaE4bSrag&oe=674562F0">
                                        Gerson</option>
                                    <option value="2"
                                        data-photo="https://scontent.ftgu1-2.fna.fbcdn.net/v/t39.30808-1/459706924_27301687216111328_2219391849533807448_n.jpg?stp=dst-jpg_s100x100&_nc_cat=101&ccb=1-7&_nc_sid=0ecb9b&_nc_eui2=AeGG9CLOARw7MLWsYIjp9YJ3dp0twd9kMuB2nS3B32Qy4PU0EtjjjsOImj2m1Ec9KWy9VCa1rfZ6-NhRC9rmNBRS&_nc_ohc=W5zm1eW--DMQ7kNvgFW0hbv&_nc_zt=24&_nc_ht=scontent.ftgu1-2.fna&_nc_gid=Aak8lYEYqGXlL-DPW1pqg28&oh=00_AYBoCoX35zHKOxayigumUhEFBY2kcyUNFfiZZQxzDBWP8g&oe=67453B91">
                                        Cloris</option>

                                </select>
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
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .estado-completado {
            background-color: #d1f2eb;
            color: #1abc9c;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: small;
            text-align: center;
            display: inline-block;
        }

        .estado-sincomenzar {
            background-color: #f8c291;
            color: #e67e22;
            border-radius: 20px;
            padding: 5px;
            font-size: small;
            text-align: center;
            display: inline-block;
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

        #data-table th:nth-child(6) {
            text-align: center;
            /* Alinea el texto del encabezado a la derecha */
        }

        tbody {
            vertical-align: middle !important
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('/js/lib/jquery.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        function updateResponsablePhoto() {
            const select = document.getElementById('responsable');
            const selectedOption = select.options[select.selectedIndex];
            const photoUrl = selectedOption.getAttribute('data-photo');
            document.getElementById('responsablePhoto').src = photoUrl;
        }
        $(document).ready(function() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '/notas/json',
                    type: 'GET',
                    data: {
                        view: 'today',
                        current_date: new Date().toISOString().split('T')[0],
                    }
                },
                columns: [{
                        data: 'completada',
                        className: 'centrar-texto',
                        render: function(data, type, row) {
                            if (data === 0) {
                                return '<span class="estado-sincomenzar">Sin empezar</span>';
                            } else if (data === 1) {
                                return '<span class="estado estado-completado">Completado</span>';
                            }
                            return data; // Valor por defecto si no coincide
                        }
                    },
                    {
                        data: 'titulo',
                        name: 'titulo'
                    },
                    {
                        data: 'prioridad',
                        render: function(data) {
                            if (data === 'alta') {
                                return '<span class="badge bg-danger">Alta</span>';
                            } else if (data === 'baja') {
                                return '<span class="badge bg-success">Baja</span>';
                            } else {
                                return '<span class="badge bg-secondary">Sin Prioridad</span>';
                            }
                        }
                    },
                    {
                        data: 'fecha',
                        name: 'fecha_limite'
                    },
                    {
                        data: 'responsable',
                        render: function(data) {
                            if (data === 'Gerson') {
                                return `
                    <div class="d-flex align-items-center">
                        <img src="https://scontent.ftgu1-3.fna.fbcdn.net/v/t39.30808-1/450557001_122100854132403797_5929500965298241666_n.jpg?stp=dst-jpg_s200x200&_nc_cat=101&ccb=1-7&_nc_sid=0ecb9b&_nc_eui2=AeEiKNDAi0UOqBHA5MFTN2XZuwWnfJYgGia7Bad8liAaJsQCtcXv3aRxSQtYS44DSeymjTHUdPmyDuggDwU89arX&_nc_ohc=-vpii0aug1MQ7kNvgGqO9Tt&_nc_zt=24&_nc_ht=scontent.ftgu1-3.fna&_nc_gid=AGxiZ-oRT3oflpvyAFS0lY3&oh=00_AYBsKu1PC_vgy7tzvj_gw8CPq_c34VA4dzUQPzaE4bSrag&oe=674562F0" alt="${data.name}" class="rounded-circle me-2" width="30" height="30">
                        <span>${data}</span>
                    </div>
                `;
                            } else {
                                return `
                    <div class="d-flex align-items-center">
                        <img src="https://scontent.ftgu1-2.fna.fbcdn.net/v/t39.30808-1/459706924_27301687216111328_2219391849533807448_n.jpg?stp=dst-jpg_s100x100&_nc_cat=101&ccb=1-7&_nc_sid=0ecb9b&_nc_eui2=AeGG9CLOARw7MLWsYIjp9YJ3dp0twd9kMuB2nS3B32Qy4PU0EtjjjsOImj2m1Ec9KWy9VCa1rfZ6-NhRC9rmNBRS&_nc_ohc=W5zm1eW--DMQ7kNvgFW0hbv&_nc_zt=24&_nc_ht=scontent.ftgu1-2.fna&_nc_gid=Aak8lYEYqGXlL-DPW1pqg28&oh=00_AYBoCoX35zHKOxayigumUhEFBY2kcyUNFfiZZQxzDBWP8g&oe=67453B91" alt="${data.name}" class="rounded-circle me-2" width="30" height="30">
                        <span>${data}</span>
                    </div>
                `;
                            }
                            // Renderiza la burbuja con la foto y el nombre del responsable

                        },
                        name: 'responsable'
                    },
                    {
                        data: 'actions', // Esta columna mostrará las acciones
                        className: 'centrar-texto',
                        orderable: false, // Evita el orden en esta columna
                        searchable: false // Evita la búsqueda en esta columna

                    }
                ],
                drawCallback: function() {
                    // Rehabilita tooltips después de que DataTables haga el renderizado
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                pageLength: 10, // Registros por página
                language: {
                    paginate: {
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                    lengthMenu: 'Mostrar _MENU_ notas',
                },
            });
        });

        function openNotaModal(id = null) {
            // Limpiar el formulario antes de abrir el modal
            $('#addEditForm')[0].reset(); // Limpia el formulario
            $('#addEditForm').find('input[name="_method"]').remove(); // Remueve cualquier método oculto (PUT)

            // Configurar para agregar una nueva nota
            if (!id) {
                $('#addEditModalLabel').text('Nueva Nota'); // Cambia el título del modal
                $('#addEditForm').attr('action', '/notas'); // Acción para crear
                $('#addEditForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    saveNota('POST'); // Llama a la función saveNota con el método POST
                });
                $('#responsablePhoto').attr('src', 'https://via.placeholder.com/40');
            } else {
                // Configurar para editar una nota existente
                $.get(`/notas/${id}`, function(data) {
                    $('#titulo').val(data.titulo);
                    $('#descripcion').val(data.descripcion);
                    $('#fecha').val(data.fecha);
                    $('#prioridad').val(data.prioridad);
                    if (data.id_responsable) {
                        $('#responsable').val(data.id_responsable).change(); // Establece el responsable
                        if (data.id_responsable == 1) {
                            $('#responsablePhoto').attr('src',
                                'https://scontent.ftgu1-3.fna.fbcdn.net/v/t39.30808-1/450557001_122100854132403797_5929500965298241666_n.jpg?stp=dst-jpg_s200x200&_nc_cat=101&ccb=1-7&_nc_sid=0ecb9b&_nc_eui2=AeEiKNDAi0UOqBHA5MFTN2XZuwWnfJYgGia7Bad8liAaJsQCtcXv3aRxSQtYS44DSeymjTHUdPmyDuggDwU89arX&_nc_ohc=-vpii0aug1MQ7kNvgGqO9Tt&_nc_zt=24&_nc_ht=scontent.ftgu1-3.fna&_nc_gid=AGxiZ-oRT3oflpvyAFS0lY3&oh=00_AYBsKu1PC_vgy7tzvj_gw8CPq_c34VA4dzUQPzaE4bSrag&oe=674562F0'
                            );
                        } else if (data.id_responsable == 2)
                            $('#responsablePhoto').attr('src',
                                'https://scontent.ftgu1-2.fna.fbcdn.net/v/t39.30808-1/459706924_27301687216111328_2219391849533807448_n.jpg?stp=dst-jpg_s100x100&_nc_cat=101&ccb=1-7&_nc_sid=0ecb9b&_nc_eui2=AeGG9CLOARw7MLWsYIjp9YJ3dp0twd9kMuB2nS3B32Qy4PU0EtjjjsOImj2m1Ec9KWy9VCa1rfZ6-NhRC9rmNBRS&_nc_ohc=W5zm1eW--DMQ7kNvgFW0hbv&_nc_zt=24&_nc_ht=scontent.ftgu1-2.fna&_nc_gid=Aak8lYEYqGXlL-DPW1pqg28&oh=00_AYBoCoX35zHKOxayigumUhEFBY2kcyUNFfiZZQxzDBWP8g&oe=67453B91'
                            );

                    } else {
                        $('#responsablePhoto').attr('src', 'https://via.placeholder.com/40');
                    }
                    $('#addEditModalLabel').text('Editar Nota'); // Cambia el título del modal
                    $('#addEditForm').attr('action', `/notas/${id}`); // Acción para editar
                    $('#addEditForm').append(
                        '<input type="hidden" name="_method" value="PUT">'); // Agrega el método PUT
                    $('#addEditForm').off('submit').on('submit', function(e) {
                        e.preventDefault();
                        saveNota('PUT'); // Llama a la función saveNota con el método PUT
                    });
                }).fail(function() {
                    alert('No se pudo obtener la información de la nota.');
                });
            }

            // Mostrar el modal
            $('#modal-block-fadein').modal('show');
        }

        function saveNota(method) {
            const action = $('#addEditForm').attr('action');
            // Crear el objeto de datos explícito
            const data = {
                _token: '{{ csrf_token() }}', // CSRF token
                _method: method, // Método HTTP como DELETE o PUT
                titulo: $('#titulo').val(),
                descripcion: $('#descripcion').val(),
                fecha: $('#fecha').val(),
                prioridad: $('#prioridad').val(),
                id_responsable: $('#responsable').val() // Responsable seleccionado
            };

            $.ajax({
                url: action, // URL definida en el formulario
                method: 'POST', // Laravel interpretará DELETE o PUT mediante _method
                data: data, // Datos enviados en formato objeto
                success: function() {
                    $('#modal-block-fadein').modal('hide'); // Cierra el modal
                    $('#data-table').DataTable().ajax.reload(); // Recarga la tabla
                    alert(method === 'POST' ? 'Nota agregada con éxito.' : 'Nota actualizada con éxito.');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        // Iterar sobre los errores y mostrarlos
                        for (let field in errors) {
                            errorMessages += `${errors[field].join('<br>')}<br>`;
                        }
                        alert('Errores de validación:\n' + errorMessages);
                    } else {
                        console.error(xhr.responseText);
                        alert('Ocurrió un error al guardar la nota.');
                    }
                }
            });
        }

        function editNota(id) {
            $.get(`/notas/${id}`, function(data) {
                $('#titulo').val(data.titulo);
                $('#descripcion').val(data.descripcion);
                $('#fecha').val(data.fecha);
                $('#prioridad').val(data.prioridad);

                // Configura el formulario para actualizar la nota
                $('#addEditForm').attr('action', `/notas/${id}`);
                $('#addEditForm').find('input[name="_method"]').remove();
                $('#addEditForm').append('<input type="hidden" name="_method" value="PUT">');

                // Muestra el modal
                $('#modal-block-fadein').modal('show');
            }).fail(function() {
                alert('No se pudo obtener la información de la nota.');
            });
        }

        function markAsCompleted(id) {
            $.ajax({
                url: `/notas/${id}/completar`,
                method: 'POST', // Método HTTP correcto
                data: {
                    _token: '{{ csrf_token() }}' // Incluye el token CSRF
                },
                success: function(response) {
                    alert(response.message);
                    $('#data-table').DataTable().ajax.reload(); // Recarga la tabla
                },
                error: function(xhr) {
                    alert('Hubo un error al marcar la nota como completada.');
                }
            });
        }


        function deleteNota(id) {
            if (confirm('¿Estás seguro de eliminar esta nota?')) {
                $.ajax({
                    url: `/notas/${id}`,
                    method: 'POST', // Cambia a POST y añade _method si DELETE no funciona directamente
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE' // Indica a Laravel que es una solicitud DELETE
                    },
                    success: function() {
                        $('#data-table').DataTable().ajax.reload();
                        alert('Nota eliminada!.');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Imprime el error en consola para más detalles
                        alert('Ocurrió un error al intentar eliminar la nota.');
                    }
                });
            }
        }


        $('#btn-limpiar-filtros').on('click', function() {
            $('#startDate, #endDate, #prioridadFilter').val('');
            $('#data-table').DataTable().ajax.reload();
        });

        $('#btn-aplicar-filtros').on('click', function() {
            $('#data-table').DataTable().ajax.reload();
        });

        $('#abrirModal').click(function() {
            $('#modal-block-fadein').modal('show');
        });
    </script>
@endpush
