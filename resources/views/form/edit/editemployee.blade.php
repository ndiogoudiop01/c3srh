
@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Modification Employe</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Modification</li>
                        </ul>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Modification employe</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('all/employee/update') }}" method="POST">
                                @csrf
                                <input type="hidden" class="form-control" id="id" name="id" value="{{ $employes[0]->matricule }}">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Nom Complet</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $employes[0]->nom }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Telephone</label>
                                    <div class="col-md-10">
                                        <input type="email" class="form-control" id="telephone" name="telephone" value="{{ $employes[0]->telephone }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Date de naissance</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control datetimepicker" id="datenaissane" name="datenaissance" value="{{ $employes[0]->datenaissance }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Genre</label>
                                    <div class="col-md-10">
                                        <select class="select form-control" id="genre" name="genre">
                                            <option value="{{ $employes[0]->genre }}" {{ ( $employes[0]->genre == $employes[0]->genre) ? 'selected' : '' }}>{{ $employes[0]->genre }} </option>
                                            <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Matricule Employe</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="matricule" name="matricule" value="{{ $employes[0]->matricule }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Compagnie</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" id="compagnie" name="compagnie" value="{{ $employes[0]->compagnie }}">
                                    </div>
                                    <label class="col-form-label col-md-2">Departement</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" id="departement" name="departement" value="{{ $employes[0]->adresse }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">CIN</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" id="cin" name="cin" value="{{ $employes[0]->cin }}">
                                    </div>
                                    <label class="col-form-label col-md-2">Nationalite</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" id="nationalite" name="nationalite" value="{{ $employes[0]->nationalite }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2"></label>
                                    <div class="col-md-10">
                                        <button type="submit" class="btn btn-primary submit-btn">MAJ</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        $("input:checkbox").on('click', function()
        {
            var $box = $(this);
            if ($box.is(":checked"))
            {
                var group = "input:checkbox[class='" + $box.attr("class") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            }
            else
            {
                $box.prop("checked", false);
            }
        });
    </script>
    @endsection

@endsection
