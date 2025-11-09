{{-- resources/views/coordinator/dashboard.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Coordinator | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DASHBOARD</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <!-- Quick Stats Row -->
    <div class="row">
      <!-- Student Interns Card -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="custom-card bg-primary">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $myStudentsCount }}</h3>
              <p class="label">Student Interns</p>
            </div>
            <div class="card-icon">
              <i class="ph-fill ph-graduation-cap"></i>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('coordinator.interns') }}" class="card-link">
              Manage Interns <i class="ph ph-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- HTE Partners Card -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="custom-card bg-warning">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $totalHtesCount }}</h3>
              <p class="label">Host Training Establishments</p>
            </div>
            <div class="card-icon">
              <i class="ph-fill ph-building-apartment"></i>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('coordinator.htes') }}" class="card-link">
              Manage HTEs <i class="ph ph-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Active Deployments Card -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="custom-card bg-success">
          <div class="card-content">
            <div class="card-text">
              <h3 class="count">{{ $activeDeploymentsCount }}</h3>
              <p class="label">Deployments</p>
            </div>
            <div class="card-icon">
              <i class="ph-fill ph-rocket-launch"></i>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('coordinator.deployments') }}" class="card-link">
              View Deployments <i class="ph ph-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

    </div>

    <!-- Analytics & Quick Actions Row -->
    <div class="row">
      <!-- Enhanced Intern Status Analytics - 3x2 Grid -->
      <div class="col-lg-8 mb-4">
        <div class="card analytics-card h-100">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">
              <i class="ph ph-chart-pie-slice me-2"></i>Intern Status Overview
            </h5>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Row 1 -->
              <div class="col-md-4 mb-3">
                <div class="status-card status-pending h-100">
                  <div class="status-header">
                    <i class="ph ph-clock status-icon"></i>
                    <div class="status-info">
                      <div class="status-count">{{ $pendingRequirementsCount }}</div>
                      <div class="status-label">Pending Requirements</div>
                    </div>
                  </div>
                  <div class="status-progress">
                    <div class="progress">
                      <div class="progress-bar bg-danger" style="width: {{ $myStudentsCount > 0 ? ($pendingRequirementsCount / $myStudentsCount) * 100 : 0 }}%"></div>
                    </div>
                    <div class="status-percentage">
                      {{ $myStudentsCount > 0 ? number_format(($pendingRequirementsCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <div class="status-card status-ready h-100">
                  <div class="status-header">
                    <i class="ph ph-check-circle status-icon"></i>
                    <div class="status-info">
                      <div class="status-count">{{ $readyForDeploymentCount }}</div>
                      <div class="status-label">Ready for Deployment</div>
                    </div>
                  </div>
                  <div class="status-progress">
                    <div class="progress">
                      <div class="progress-bar bg-warning" style="width: {{ $myStudentsCount > 0 ? ($readyForDeploymentCount / $myStudentsCount) * 100 : 0 }}%"></div>
                    </div>
                    <div class="status-percentage">
                      {{ $myStudentsCount > 0 ? number_format(($readyForDeploymentCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <div class="status-card status-endorsed h-100">
                  <div class="status-header">
                    <i class="ph ph-paper-plane-tilt status-icon"></i>
                    <div class="status-info">
                      <div class="status-count">{{ $endorsedInternsCount }}</div>
                      <div class="status-label">Endorsed</div>
                    </div>
                  </div>
                  <div class="status-progress">
                    <div class="progress">
                      <div class="progress-bar bg-primary" style="width: {{ $myStudentsCount > 0 ? ($endorsedInternsCount / $myStudentsCount) * 100 : 0 }}%"></div>
                    </div>
                    <div class="status-percentage">
                      {{ $myStudentsCount > 0 ? number_format(($endorsedInternsCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>

              <!-- Row 2 -->
              <div class="col-md-4 mb-3">
                <div class="status-card status-processing h-100">
                  <div class="status-header">
                    <i class="ph ph-gear status-icon"></i>
                    <div class="status-info">
                      <div class="status-count">{{ $processingCount }}</div>
                      <div class="status-label">Processing</div>
                    </div>
                  </div>
                  <div class="status-progress">
                    <div class="progress">
                      <div class="progress-bar bg-info" style="width: {{ $myStudentsCount > 0 ? ($processingCount / $myStudentsCount) * 100 : 0 }}%"></div>
                    </div>
                    <div class="status-percentage">
                      {{ $myStudentsCount > 0 ? number_format(($processingCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <div class="status-card status-deployed h-100">
                  <div class="status-header">
                    <i class="ph ph-rocket-launch status-icon"></i>
                    <div class="status-info">
                      <div class="status-count">{{ $deployedCount }}</div>
                      <div class="status-label">Deployed</div>
                    </div>
                  </div>
                  <div class="status-progress">
                    <div class="progress">
                      <div class="progress-bar bg-success" style="width: {{ $myStudentsCount > 0 ? ($deployedCount / $myStudentsCount) * 100 : 0 }}%"></div>
                    </div>
                    <div class="status-percentage">
                      {{ $myStudentsCount > 0 ? number_format(($deployedCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <div class="status-card status-completed h-100">
                  <div class="status-header">
                    <i class="ph ph-seal-check status-icon"></i>
                    <div class="status-info">
                      <div class="status-count">{{ $completedCount }}</div>
                      <div class="status-label">Completed</div>
                    </div>
                  </div>
                  <div class="status-progress">
                    <div class="progress">
                      <div class="progress-bar bg-dark" style="width: {{ $myStudentsCount > 0 ? ($completedCount / $myStudentsCount) * 100 : 0 }}%"></div>
                    </div>
                    <div class="status-percentage">
                      {{ $myStudentsCount > 0 ? number_format(($completedCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Summary Stats -->
            <div class="row mt-4">
              <div class="col-12">
                <div class="summary-stats">
                  <!-- <div class="summary-item">
                    <div class="summary-label">Total Interns</div>
                    <div class="summary-value">{{ $myStudentsCount }}</div>
                  </div> -->
                  <div class="summary-item">
                    <div class="summary-label">Deployment Rate</div>
                    <div class="summary-value text-success">
                      {{ $myStudentsCount > 0 ? number_format(($deployedCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                  <div class="summary-item">
                    <div class="summary-label">Completion Rate</div>
                    <div class="summary-value text-dark">
                      {{ $myStudentsCount > 0 ? number_format(($completedCount / $myStudentsCount) * 100, 1) : 0 }}%
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="col-lg-4 mb-4">
        <div class="card quick-actions-card h-100">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">
              <i class="ph ph-lightning me-2"></i>Quick Actions
            </h5>
          </div>

          <div class="card-body p-3 d-flex align-items-center">
            <div class="quick-stack w-100">
              
              <a href="{{ route('coordinator.new_i') }}" class="quick-btn bg-secondary-subtle">
                <i class="ph ph-user-plus"></i>
                <span>Register New Intern</span>
                <i class="ph ph-arrow-circle-up-right fs-1"></i>
              </a>

              <a href="{{ route('coordinator.endorse') }}" class="quick-btn bg-secondary-subtle">
                <i class="ph ph-paper-plane-tilt"></i>
                <span>Endorse Interns</span>
                <i class="ph ph-arrow-circle-up-right fs-1"></i>
              </a>

              <a href="{{ route('coordinator.new_h') }}" class="quick-btn bg-secondary-subtle">
                <i class="ph ph-building"></i>
                <span>Add HTE Partner</span>
                <i class="ph ph-arrow-circle-up-right fs-1"></i>
              </a>

              <a href="{{ route('coordinator.documents') }}" class="quick-btn bg-secondary-subtle">
                <i class="ph ph-files"></i>
                <span>Honorarium Docs</span>
                <i class="ph ph-arrow-circle-up-right fs-1"></i>
              </a>

            </div>
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

/* Enhanced Status Cards */
.status-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  border: 1px solid #e9ecef;
  transition: transform 0.2s ease;
  display: flex;
  flex-direction: column;
}

.status-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
}

.status-header {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.status-icon {
  font-size: 2rem;
  margin-right: 1rem;
}

.status-pending .status-icon { color: #dc3545; }
.status-ready .status-icon { color: #ffc107; }
.status-endorsed .status-icon { color: #007bff; }
.status-processing .status-icon { color: #17a2b8; }
.status-deployed .status-icon { color: #28a745; }
.status-completed .status-icon { color: #6c757d; }

.status-info {
  flex: 1;
}

.status-count {
  font-size: 1.75rem;
  font-weight: 700;
  line-height: 1;
  color: #2c3e50;
}

.status-label {
  font-size: 0.85rem;
  color: #6c757d;
  font-weight: 600;
  margin-top: 0.25rem;
}

.status-progress {
  margin-top: auto;
}

.status-progress .progress {
  height: 6px;
  border-radius: 3px;
  background-color: #e9ecef;
}

.status-percentage {
  text-align: right;
  font-size: 0.8rem;
  font-weight: 600;
  color: #6c757d;
  margin-top: 0.5rem;
}

/* Summary Stats */
.summary-stats {
  display: flex;
  justify-content: space-around;
  background: #f8f9fa;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.summary-item {
  text-align: center;
}

.summary-label {
  font-size: 0.8rem;
  color: #6c757d;
  text-transform: uppercase;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.summary-value {
  font-size: 1.25rem;
  font-weight: 700;
  color: #2c3e50;
}

/* Quick Actions - Stacked Layout */
.quick-actions-card, .analytics-card, .recent-activity-card {
  border-radius: 12px;
  box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
  border: none;
}

.quick-stack {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  height: 100%;
}

.quick-btn {
  display: flex;
  align-items: center;
  padding: 1.25rem 1.5rem;
  border-radius: 10px;
  color: currentColor;
  text-decoration: none;
  transition: all 0.5s ease;
  flex: 1;
  min-height: 70px;
}

.quick-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  color: white;
  background: #007bff !important;
}

.quick-btn i {
  font-size: 1.75rem;
  margin-right: 1rem;
  width: 40px;
  text-align: center;
}

.quick-btn span {
  font-size: 1rem;
  font-weight: 600;
  flex: 1;
}

/* Recent Activity */
.activity-avatar {
  width: 40px;
  height: 40px;
  font-size: 1.2rem;
}

.list-group-item {
  border: none;
  border-bottom: 1px solid #e9ecef;
  padding: 1rem 0;
}

.list-group-item:last-child {
  border-bottom: none;
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
  
  .status-count {
    font-size: 1.5rem;
  }
  
  .summary-stats {
    flex-direction: column;
    gap: 1rem;
  }
  
  .quick-btn {
    padding: 1rem 1.25rem;
    min-height: 60px;
  }
  
  .quick-btn i {
    font-size: 1.5rem;
    margin-right: 0.75rem;
  }
  
  .quick-btn span {
    font-size: 0.9rem;
  }
}
</style>
@endsection