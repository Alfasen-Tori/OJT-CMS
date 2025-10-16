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
              <th width="10%">HTE ID</th>
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
               $status = $endorsements->contains('status', 'deployed') ? 'deployed' 
                          : ($endorsements->contains('status', 'processing') ? 'processing' 
                          : 'endorsed');
                          
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
                <td class="align-middle">{{ Str::limit($hte->address, 50) }}</td>
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
            <a class="dropdown-item btn btn-outline-light text-dark border-bottom border-lightgray" href="{{ route('coordinator.deployment.show', $hteId) }}">
                <i class="ph ph-list custom-icons-i mr-2"></i>Manage
            </a>
            
            <!-- Cancel Option: Only show when status is endorsed -->
            @if($status === 'endorsed')
            <a class="dropdown-item btn btn-outline-light text-dark" href="">
                <i class="ph ph-x custom-icons-i mr-2"></i>Cancel
            </a>
            @endif

            <!-- Quick Deploy Option: Only show when status is processing -->
            @if($status === 'processing')
            <a class="dropdown-item btn btn-outline-light text-primary" href="">
                <i class="ph ph-rocket-launch custom-icons-i mr-2"></i>Quick Deploy
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
@endsection


