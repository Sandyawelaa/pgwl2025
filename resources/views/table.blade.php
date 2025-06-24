@extends('layout.template')

@section('content')
    <div class="container mt-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h4>Data Titik Wisata</h4>
            </div>
            <div class="card-body"></div>
            <table class="table table-striped" id="pointstable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($points as $point)
                        <tr>

                            <td>{{ $point->id }}</td>
                            <td>{{ $point->name }}</td>
                            <td>{{ $point->description }}</td>
                            <td>
                                <img src="{{ asset('storage/images/' . $point->image) }}" alt="" width="200"
                                    tittle="{{ $point->image }}">
                            </td>
                            <td>{{ $point->created_at }}</td>
                            <td>{{ $point->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <style>
        /* Gaya untuk tabel */
        #pointstable {
            color: #333; /* Warna teks */
        }

        #pointstable thead th {
            background-color: #fff9c4; /* Kuning untuk header */
            color: #000; /* Warna teks header */
        }

        /* Kolom 1: No */
        #pointstable tbody tr td:nth-child(1),
        #pointstable thead tr th:nth-child(1) {
            background-color: #fff9c4; /* Kuning terang */
        }

        /* Kolom 2: Name */
        #pointstable tbody tr td:nth-child(2),
        #pointstable thead tr th:nth-child(2) {
            background-color: #fff9c4; /* Kuning lebih cerah */
        }

        /* Kolom 3: Description */
        #pointstable tbody tr td:nth-child(3),
        #pointstable thead tr th:nth-child(3) {
            background-color: #fff9c4; /* Kuning pastel */
        }

        /* Kolom 4: Image */
        #pointstable tbody tr td:nth-child(4),
        #pointstable thead tr th:nth-child(4) {
            background-color: #fff9c4; /* Kuning mencolok */
        }

        /* Kolom 5: Created At */
        #pointstable tbody tr td:nth-child(5),
        #pointstable thead tr th:nth-child(5) {
            background-color: #fff9c4; /* Kuning lebih cerah */
        }

        /* Kolom 6: Updated At */
        #pointstable tbody tr td:nth-child(6),
        #pointstable thead tr th:nth-child(6) {
            background-color: #fff9c4; /* Kuning sangat terang */
        }

        /* Efek hover */
        #pointstable tbody tr:hover td {
            background-color: #fbc02d; /* Kuning cerah untuk hover */
        }
    </style>
@endsection



@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script>
        let tablepoints = new DataTable('#pointstable');
    </script>
@endsection
