{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.hte')

@section('title', 'HTE | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DASHBOARD</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">HTE</li>
          <li class="breadcrumb-item active text-muted">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Status Card -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="custom-card bg-primary">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">0</h3>
              <p class="label">Interns</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-users"></i>
            </div>
          </div>
          <div class="card-footer">
            <a href="#" class="card-link">
              View <i class="ph ph-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- MOA Status Card -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="custom-card {{ $moaStatus === 'Submitted' ? 'bg-info' : 'bg-danger' }}">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $moaStatus }}</h3>
              <p class="label">MOA Status</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-file-text"></i>
            </div>
          </div>
          <div class="card-footer">
            @if($moaStatus === 'Missing')
              <a href="{{ route('hte.profile') }}" class="card-link">
                Upload MOA <i class="ph ph-arrow-right"></i>
              </a>
            @else
              <a href="{{ route('hte.profile') }}" class="card-link">
                View MOA <i class="ph ph-arrow-right"></i>
              </a>
            @endif
          </div>
        </div>
      </div>

      <!-- For Evaluation Card -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="custom-card bg-success">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">0</h3>
              <p class="label">For Evaluation</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-clipboard-text"></i>
            </div>
          </div>
          <div class="card-footer">
            <a href="#" class="card-link">
              View <i class="ph ph-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* Custom Card Styles */
.custom-card {
  border-radius: 12px;
  box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
  border: none;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  height: 140px;
}

.custom-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.15);
}

.card-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  height: 100px;
}

.card-text .count {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  line-height: 1;
  color: white;
}

.card-text .label {
  font-size: 1rem;
  font-weight: 600;
  margin: 0.5rem 0 0 0;
  color: rgba(255, 255, 255, 0.9);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-icon {
  font-size: 3rem;
  opacity: 0.8;
  color: rgba(255, 255, 255, 0.9);
}

.card-footer {
  background: rgba(0, 0, 0, 0.1);
  padding: 0.75rem 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.card-link {
  color: rgba(255, 255, 255, 0.9);
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.card-link:hover {
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .card-content {
    padding: 1rem;
  }
  
  .card-text .count {
    font-size: 2rem;
  }
  
  .card-icon {
    font-size: 2.5rem;
  }
}
</style>
@endsection