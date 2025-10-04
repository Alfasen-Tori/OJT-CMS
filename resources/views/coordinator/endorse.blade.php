@extends('layouts.coordinator')

@section('title', 'Deployment')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">ENDORSEMENT</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Deploy</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header bg-white">
        <h3 class="card-title">Select HTE</h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label>Host Training Establishment</label>
          <select id="hteSelect" class="form-control select2">
            <option value="" selected disabled>Select an HTE</option>
            @foreach($htes as $hte)
              <option value="{{ $hte->id }}" data-skills="{{ $hte->skills->pluck('skill_id')->toJson() }}" data-slots="{{ $hte->slots }}">
                {{ $hte->organization_name }} 
              </option>
            @endforeach
          </select>

          <div id="slots-info" class="mt-2 text-muted small"></div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="card-title" id="internsTableHeader">Top Candidates</h3>
        <button id="endorseSelectedBtn" class="btn btn-primary ml-auto" style="display:none;">
          <i class="ph ph-paper-plane-tilted"></i> Endorse Selected
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="internsReccTable" class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Matching Skills</th>
                <th>Match %</th>
                <th width="7%" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted">Select an HTE to view recommended interns</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')

@endsection