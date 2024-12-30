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
                    <i class="fa fa-angle-right text-muted me-1"></i> Reporte de gastos
                </h2>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <canvas id="barChart" style="width:100%; max-height:400px;"></canvas>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
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
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Datos para el gráfico de barras
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels), // Ejemplo: ["Cuenta 1", "Cuenta 2"]
                datasets: [{
                    label: 'Sumatoria de Transacciones',
                    data: @json($data), // Ejemplo: [1200, 1500]
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Sumatoria de Transacciones por Cuenta'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
