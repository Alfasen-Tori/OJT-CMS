{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Panel')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DASHBOARD</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Interns Card -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-card bg-gradient-primary">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $internsCount }}</h3>
              <p class="label">Total Interns</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-student"></i>
            </div>
          </div>
          <div class="card-footer">
            <span>All registered student interns</span>
          </div>
        </div>
      </div>

      <!-- Coordinators Card -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-card bg-gradient-info">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $coordinatorsCount }}</h3>
              <p class="label">Coordinators</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-chalkboard-teacher"></i>
            </div>
          </div>
          <div class="card-footer">
            <span>Department coordinators</span>
          </div>
        </div>
      </div>

      <!-- HTEs Card -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-card bg-gradient-success">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $htesCount }}</h3>
              <p class="label">HTE Partners</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-buildings"></i>
            </div>
          </div>
          <div class="card-footer">
            <span>Industry partners</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Additional Stats Row -->
    <div class="row">
      <!-- Departments Card -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-card bg-gradient-warning">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $departmentsCount }}</h3>
              <p class="label">Departments</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-department"></i>
            </div>
          </div>
          <div class="card-footer">
            <span>Academic departments</span>
          </div>
        </div>
      </div>

      <!-- Skills Card -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-card bg-gradient-danger">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $skillsCount }}</h3>
              <p class="label">Skills</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-gear"></i>
            </div>
          </div>
          <div class="card-footer">
            <span>Available skills</span>
          </div>
        </div>
      </div>

      <!-- Active Deployments Card -->
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <div class="custom-card bg-gradient-secondary">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $activeDeploymentsCount }}</h3>
              <p class="label">Active Deployments</p>
            </div>
            <div class="card-icon">
              <i class="ph ph-rocket-launch"></i>
            </div>
          </div>
          <div class="card-footer">
            <span>Currently deployed interns</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
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

.card-footer span {
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.8);
  font-weight: 500;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-success {
  background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.bg-gradient-danger {
  background: linear-gradient(135deg, #ff6b6b 0%, #ffa8a8 100%);
}

.bg-gradient-secondary {
  background: linear-gradient(135deg, #a8caba 0%, #5d4157 100%);
}

/* Responsive adjustments */
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

@include('layouts.partials.scripts-main')
@endsection