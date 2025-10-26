@extends('layouts.coordinator')

@section('title', 'Deployment')

@section('content')
<section class="content-header">
  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DEPLOYMENTS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Deployments</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">My Deployments & Endorsements</h3>
      </div>
      <div class="card-body table-responsive py-0 px-3">
        <!-- Loading Overlay -->
        <div id="tableLoadingOverlay" 
          style="position: absolute; 
          width: 100%; 
          height: 100%; 
          background: rgba(255,255,255,0.85); 
          display: flex; 
          flex-direction: column;
          justify-content: center; 
          align-items: center; 
          z-index: 1000;
          gap: 1rem;">
          <i class="ph-bold ph-arrows-clockwise fa-spin fs-3 text-primary"></i>
          <span class="text-primary">Loading deployments . . .</span>
        </div>
        
        <table id="deploymentsTable" class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th width="13%">HTE ID</th>
              <th>Name</th>
              <th>Address</th>
              <th width="12%">Status</th>
              <th width="10%">No. of Students</th>
              <th width="8%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($deployments as $hteId => $endorsements)
              @php
                $firstEndorsement = $endorsements->first();
                $hte = $firstEndorsement->hte;
                $studentCount = $endorsements->count();
                $status = $endorsements->contains('status', 'completed') ? 'completed'
                    : ($endorsements->contains('status', 'deployed') ? 'deployed'
                    : ($endorsements->contains('status', 'processing') ? 'processing'
                    : 'endorsed'));

                          
                $statusClass = match($status) {
                    'deployed' => 'bg-success-subtle text-success',
                    'processing' => 'bg-info-subtle text-info',
                    'endorsed' => 'bg-primary-subtle text-primary',
                    default => 'bg-secondary-subtle text-secondary'
                };
              @endphp
              <tr>
                <td class="align-middle">HTE-{{ str_pad($hteId, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="align-middle">{{ $hte->organization_name }}</td>
                <td class="align-middle small">{{ Str::limit($hte->address, 50) }}</td>
                <td class="align-middle">
                  <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-capitalize">
                    {{ $status }}
                  </span>
                </td>
                <td class="align-middle text-center">
                  <span class="fw-bold">{{ $studentCount }}</span>
                </td>
                <td class="text-center px-2 align-middle">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ph-fill ph-gear custom-icons-i"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown">
                            <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('coordinator.deployment.show', $hteId) }}">
                                <i class="ph ph-wrench custom-icons-i mr-2"></i>Manage
                            </a>
                            
                            <!-- Cancel Option: Only show when status is endorsed -->
                            @if($status === 'endorsed')
                            <a class="dropdown-item border-lightgray border-top rounded-0 btn btn-outline-light text-danger" href="#" data-toggle="modal" data-target="#cancelEndorsementModal{{ $hteId }}">
                                <i class="ph ph-x-circle custom-icons-i mr-2"></i>Cancel Endorsement
                            </a>
                            @endif
                        </div>
                    </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Modals for each HTE -->
@foreach($deployments as $hteId => $endorsements)
@php
    $firstEndorsement = $endorsements->first();
    $hte = $firstEndorsement->hte;
@endphp
<div class="modal fade" id="cancelEndorsementModal{{ $hteId }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="ph-bold ph-warning details-icons-i mr-1"></i>
                    Cancel Endorsement
                </h5>                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel the endorsement to <strong>{{ $hte->organization_name }}</strong>? This action will:</p>
                <ul>
                    <li>Remove all student endorsements to this HTE</li>
                    <li>Revert student status back to "Ready for Deployment"</li>
                </ul>
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                @foreach($endorsements as $endorsement)
                <form action="{{ route('coordinator.deployment.cancel-endorsement', $endorsement->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Cancel Endorsement</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection