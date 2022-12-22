
@extends('layouts.master')
@section('content')
   
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-lists-center">
                    <div class="col">
                        <h3 class="page-title">Employes</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Employes</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_employee"><i class="fa fa-pencil"></i> AJOUTER ABSENCE</a>
                        <a href="#" class="btn btn-dark add-btn" data-toggle="modal" data-target="#add_employe"><i class="fa fa-plus"></i> AJOUTER Employe</a>
                        <div class="view-icons">
                            <a href="{{ route('all/employee/card') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                            <a href="{{ route('all/employee/list') }}" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('all/employee/search') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id">
                            <label class="focus-label">Employee ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="name">
                            <label class="focus-label">Employee Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="position">
                            <label class="focus-label">Position</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <button type="submit" class="btn btn-success btn-block"> Search </button>  
                    </div>
                </div>
            </form>
            <!-- Search Filter -->
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row staff-grid-row">
                @foreach ($conges as $lists )
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                    <div class="profile-widget">
                        <div class="profile-img">
                            <a href="{{ url('employee/profile/'.$lists->matricule) }}" class="avatar"><img src="{{ URL::to('/assets/images/') }}" alt="" alt=""></a>
                        </div>
                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ url('all/employee/view/edit/'.$lists->user_id) }}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a class="dropdown-item" href="{{url('all/employee/delete/'.$lists->user_id)}}"onclick="return confirm('Are you sure to want to delete it?')"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                            </div>
                        </div>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile.html">{{ $lists->libelle }}</a></h4>
                        <div class="small text-muted">{{ $lists->nbre_jours }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Ajouter Absence Modal -->
        <div id="add_employee" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouet Absence</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('all/employee/save') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">NOM COMPLET</label>
                                        <select class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="name" name="nom">
                                            <option value="">-- Select --</option>
                                            @foreach ($employeList as $key=>$user )
                                                <option value="{{ $user->name }}" data-employee_id={{ $user->matricule}} data-email={{ $user->email }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" id="email" name="email" placeholder="Auto email" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Employe Matricule <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employee_id" name="matricule" placeholder="Auto id employee" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Compagnie</label>
                                        <select class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="company" name="compagnie">
                                            <option value="">-- Select --</option>
                                            <option value="C3S YARAKH">C3S YARAKH</option>
                                            <option value="C3S SOPRIM">C3S SOPRIM</option>
                                            <option value="C3S PIKINE">C3S PIKINE</option>
                                            <option value="C3S KEUR MASSAR">C3S KEUR MASSAR</option>
                                            <option value="C3S RUFISQUE">C3S RUFISQUE</option>
                                            <option value="C3S Kaolack">C3S Kaolack</option>
                                            <option value="C3S Mbour">C3S Mbour</option>
                                            <option value="C3S THIES">C3S THIES</option>
                                            <option value="C3S TOUBA CORNICHE">C3S TOUBA CORNICHE</option>
                                            <option value="C3S TOUBA DAARA">C3S TOUBA DAARA</option>
                                            <option value="C3S TOUBA KHAIRA">C3S TOUBA KHAIRA</option>
                                            <option value="C3S LOUGA">C3S LOUGA</option>
                                            <option value="C3S ST LOUIS">C3S ST LOUIS</option>
                                            <option value="C3S OUROSSOGUI">C3S OUROSSOGUI</option>
                                            <option value="C3S TAMACOUNDA"> C3S TAMACOUNDA</option>
                                            <option value="C3S ZIGUINCHOR">C3S ZIGUINCHOR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive m-t-15">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            
                                            <th class="text-center">LIBELLE</th>
                                            <th class="text-center">TYPE CONGE</th>
                                            <th class="text-center">DATE DEBUT</th>
                                            <th class="text-center">DATE FIN</th>
                                            <th class="text-center">NOMBRE DE JOURS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">
                                                <input type="text" class="" id="read" name="libelle" value="" >
                                            </td>
                                            <td class="text-center">
                                               <select class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="company" name="type_conge">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($typeconges as $key=>$conge )
                                                        <option value="{{ $conge->nom }}">{{ $conge->nom }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="date" class="" id="read" name="date_debut" value="" >
                                            </td>
                                            <td class="text-center">
                                                <input type="date" class="" id="read" name="date_fin" value="" >
                                            </td>
                                           <td class="text-center">
                                                <input type="text" class="" id="read" name="nbre_jours" value="" >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add ABsence Modal -->

         <!-- Ajouter Employe Modal -->
        <div id="add_employe" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">NOUVEAU EMPLOYE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('all/employee/saveEmp') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">NOM COMPLET</label>
                                        <input class="form-control" type="text" id="nom" name="nom" placeholder="Votre nom">
                                    </div>
                                </div>
                            
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Date de naissance </label>
                                        <input class="form-control" type="text" id="datenaissance" name="datenaissance" placeholder="00/00/0000">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Adresse </label>
                                        <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse" >
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Telephone </label>
                                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="77 000 00 00/78 000 00 00/76 000 00 00/75 000 00 00" >
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Genre <span class="text-danger">*</span></label>
                                       <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="genre" name="genre">
                                            <option value="homme">Homme</option>
                                            <option value="femme">Femme</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Matricule <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="matricule" name="matricule" placeholder="ID EMPLOYE" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Compagnie</label>
                                          <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="compagnie" name="compagnie">
                                            <option value="C3S YARAKH">C3S YARAKH</option>
                                            <option value="C3S SOPRIM">C3S SOPRIM</option>
                                            <option value="C3S PIKINE">C3S PIKINE</option>
                                            <option value="C3S KEUR MASSAR">C3S KEUR MASSAR</option>
                                            <option value="C3S RUFISQUE">C3S RUFISQUE</option>
                                            <option value="C3S Kaolack">C3S Kaolack</option>
                                            <option value="C3S Mbour">C3S Mbour</option>
                                            <option value="C3S THIES">C3S THIES</option>
                                            <option value="C3S TOUBA CORNICHE">C3S TOUBA CORNICHE</option>
                                            <option value="C3S TOUBA DAARA">C3S TOUBA DAARA</option>
                                            <option value="C3S TOUBA KHAIRA">C3S TOUBA KHAIRA</option>
                                            <option value="C3S LOUGA">C3S LOUGA</option>
                                            <option value="C3S ST LOUIS">C3S ST LOUIS</option>
                                            <option value="C3S OUROSSOGUI">C3S OUROSSOGUI</option>
                                            <option value="C3S TAMACOUNDA"> C3S TAMACOUNDA</option>
                                            <option value="C3S ZIGUINCHOR">C3S ZIGUINCHOR</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">CIN </label>
                                        <input type="text" class="form-control" id="cin" name="cin" placeholder="0 000 00000 00000" >
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Passport </label>
                                        <input type="text" class="form-control" id="passport" name="passport" placeholder="N passport" >
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Nationalite </label>
                                        <input type="text" class="form-control" id="nationalite" name="nationalite" value="Senegalaise">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="radio" class="" id="marie" name="situation_matrimoniale" value="marie">Marie
                                        <input type="radio" class="" id="divorce" name="situation_matrimoniale" value="divorce">Divorce
                                        <input type="radio" class="" id="celibataire" name="situation_matrimoniale" value="celibataire">Celibataire
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Nombre d'epouses </label>
                                        <input type="text" class="form-control" id="nombre_epouse" name="nombre_epouse">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Nombre d'enfant </label>
                                        <input type="text" class="form-control" id="nombre_enfant" name="nombre_enfant" >
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Region </label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ville" name="ville">
                                            <option value="Dakar">Dakar</option>
                                            <option value="Kaolack">Kaolack</option>
                                            <option value="Mbour">Mbour</option>
                                            <option value="THIES">THIES</option>
                                            <option value="TOUBA">TOUBA</option>
                                            <option value="LOUGA">LOUGA</option>
                                            <option value="ST LOUIS">ST LOUIS</option>
                                            <option value="OUROSSOGUI">OUROSSOGUI</option>
                                            <option value="TAMACOUNDA">TAMACOUNDA</option>
                                            <option value="ZIGUINCHOR">ZIGUINCHOR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Absence Modal -->
         
        
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
    <script>
        $(document).ready(function() {
            $('.select2s-hidden-accessible').select2({
                closeOnSelect: false
            });
        });
    </script>
    <script>
        // select auto id and email
        $('#name').on('change',function()
        {
            $('#employee_id').val($(this).find(':selected').data('employee_id'));
            $('#email').val($(this).find(':selected').data('email'));
        });
    </script>
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            $('#e_image').val(_this.find('.image').text());

            var name_role = (_this.find(".role_name").text());
            var _option = '<option selected value="' + name_role+ '">' + _this.find('.role_name').text() + '</option>'
            $( _option).appendTo("#e_role_name");

            var position = (_this.find(".position").text());
            var _option = '<option selected value="' +position+ '">' + _this.find('.position').text() + '</option>'
            $( _option).appendTo("#e_position");

            var department = (_this.find(".department").text());
            var _option = '<option selected value="' +department+ '">' + _this.find('.department').text() + '</option>'
            $( _option).appendTo("#e_department");

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' +statuss+ '">' + _this.find('.statuss').text() + '</option>'
            $( _option).appendTo("#e_status");
            
        });
    </script>
    @endsection

@endsection
