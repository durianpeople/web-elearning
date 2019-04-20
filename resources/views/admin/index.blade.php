@extends('layouts.app')

@section('content')


<div class="container">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(count( $errors ) > 0)
        @foreach ($errors->all() as $error)
        <div class="alert alert-success" role="alert">
            {{ $error }}
        </div>
        @endforeach
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Komunitas {{$komunitas->nama}}</div>
                <div class="card-body">
                    {{$komunitas->deskripsi}}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User</div>
                <div class="card-body">
                    <table class="table table-hover table-responsive">
                        <thead class="thead-inverse">
                            <tr>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($user_komunitas as $user)
                                <tr>
                                    <td scope="row">{{$user->NRP}}</td>
                                    <td>{{$user->name}}</td>
                                    @if ($user->pivot->status == 2)
                                        <td>Admin</td>
                                    @elseif ($user->pivot->status == 1)
                                        <td>Anggota</td>
                                    @else
                                        <td>Menunggu Konfirmasi</td>
                                    @endif
                                    <td>{{$user->NRP}}</td>
                                    <td>
                                        @if ($user->pivot->status == 0)
                                            <form action="{{url()->current()}}/user/accept" method="post" id="accept_user" hidden>
                                                @csrf
                                                <input type="text" name="id" value="{{$user->id}}">
                                            </form>
                                            <button class="btn btn-success" form="accept_user">Terima</button>
                                        @endif
                                        <button class="btn btn-primary">Cek KTM</button>
                                        @if ($user->pivot->status != 2)
                                            <form action="{{url()->current()}}/user/remove" method="post" id="remove_user" hidden>
                                                @csrf
                                                <input type="text" name="id" value="{{$user->id}}">
                                            </form>
                                            <button class="btn btn-danger" form="remove_user">Hapus</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modul</div>

                <div class="card-body">
                    @if ($modul_komunitas != NULL)
                        <table class="table table-hover table-inverse table-responsive">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Modul</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modul_komunitas as $modul)
                                        <tr>
                                            <td scope="row">{{$modul->nama}}</td>
                                            <td>
                                                <button class="btn btn-info">View</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                    @else
                        Komunitas ini belum memiliki modul
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pengunguman</div>
                <div class="card-body">
                    <button class="btn btn-primary" onclick="createPengunguman()">Buat Pengunguman</button>
                    @if ($pengunguman_komunitas != NULL)
                        <table class="table table-hover table-inverse table-responsive">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Pengunguman</th>
                                    <th>Tanggal Tampil</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengunguman_komunitas as $pengunguman)
                                        <tr>
                                            <td scope="row">{{$pengunguman->nama}}</td>
                                            <td>{{$pengunguman->tgl_tampil}}</td>
                                            <td>{{$pengunguman->tgl_selesai}}</td>
                                            <td>
                                                    <button class="btn btn-info" onclick="updatePengunguman({{$pengunguman->id}})">View</button>
                                                    <button class="btn btn-info" type="submit" form="delete_pengunguman">Hapus</button>
                                                    <form action="{{url()->current()}}/pengunguman/delete" method="post" id="delete_pengunguman" hidden>
                                                        @csrf
                                                        <input type="text" name="id" value="{{$pengunguman->id}}" hidden>
                                                    </form>
                                                </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                    @else
                        Komunitas ini tidak memiliki pengunguman
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
      
<!-- Modal -->
<div class="modal fade" id="create_pengunguman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create Pengunguman</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{url()->current()}}/pengunguman/create" method="post" id="create_form">
                @csrf
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nama Pengunguman" name="nama" id="nama" required>
                </div>
                <div class="input-group">
                    <textarea class="form-control" placeholder="Konten Pengunguman" name="konten" id="konten" required></textarea>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Mulai</span>
                    </div>
                    <input type="date" class="form-control" placeholder="Tanggal Tampil" name="tgl_tampil" id="tgl_tampil" required>
                </div>
                <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Selesai</span>
                        </div>
                    <input type="date" class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai" id="tgl_selesai" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="create_form">Submit</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_pengunguman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Pengunguman</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{url()->current()}}/pengunguman/edit" method="post" id="edit_form">
                @method('PATCH')
                @csrf
                <input type="text" hidden name="id_pen" id="id_pen">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nama Pengunguman" name="nama" id="nama_new" required>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Konten Pengunguman" name="konten" id="konten_new" required>
                </div>
                <div class="input-group">
                    <input type="datetime" class="form-control" placeholder="Tanggal Tampil" name="tgl_tampil" id="tgl_tampil_new" required>
                </div>
                <div class="input-group">
                    <input type="datetime" class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai" id="tgl_selesai_new" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="edit_form">Submit</button>
        </div>
        </div>
    </div>
</div>

<script>
    function createPengunguman() {
        $('#create_pengunguman').modal('show');
    }

    function updatePengunguman($id) {
        $.ajax({
            url: "{{url()->current()}}/pengunguman/view/"+$id,
            dataType: 'JSON',
            type: 'get',
            success: function (data) {
                $('#id_pen').val(data['id']);
                $('#nama_new').val(data['nama']);
                $('#konten_new').val(data['konten']);
                $('#tgl_tampil_new').val(data['tgl_tampil']);
                $('#tgl_selesai_new').val(data['tgl_selesai']);
            }
        })
        $('#update_pengunguman').modal('show');
    }

</script>
@endsection
