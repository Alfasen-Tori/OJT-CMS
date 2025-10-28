{{-- resources/views/dashboard.blade.php --}}
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
      {{-- Status Card --}}
      <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-danger">
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">1</h2>
            <p>Coordinators</p>
          </div>
          <div class="infobox-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M176,207.24a119,119,0,0,0,16-7.73V240a8,8,0,0,1-16,0Zm11.76-88.43-56-29.87a8,8,0,0,0-7.52,14.12L171,128l17-9.06Zm64-29.87-120-64a8,8,0,0,0-7.52,0l-120,64a8,8,0,0,0,0,14.12L32,117.87v48.42a15.91,15.91,0,0,0,4.06,10.65C49.16,191.53,78.51,216,128,216a130,130,0,0,0,48-8.76V130.67L171,128l-43,22.93L43.83,106l0,0L25,96,128,41.07,231,96l-18.78,10-.06,0L188,118.94a8,8,0,0,1,4,6.93v73.64a115.63,115.63,0,0,0,27.94-22.57A15.91,15.91,0,0,0,224,166.29V117.87l27.76-14.81a8,8,0,0,0,0-14.12Z"></path></svg>          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Docs -->
      <div class="col-lg-4 col-md-6 col-12">
          <!-- small card -->
          <div class="small-box bg-info"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">3</h2>
                  <p>Departments</p>
              </div>
              <div class="infobox-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M144,96V80H128a8,8,0,0,0-8,8v80a8,8,0,0,0,8,8h16V160a16,16,0,0,1,16-16h48a16,16,0,0,1,16,16v48a16,16,0,0,1-16,16H160a16,16,0,0,1-16-16V192H128a24,24,0,0,1-24-24V136H72v8a16,16,0,0,1-16,16H24A16,16,0,0,1,8,144V112A16,16,0,0,1,24,96H56a16,16,0,0,1,16,16v8h32V88a24,24,0,0,1,24-24h16V48a16,16,0,0,1,16-16h48a16,16,0,0,1,16,16V96a16,16,0,0,1-16,16H160A16,16,0,0,1,144,96Z"></path></svg>              </div>
              <a href="#" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>

      <!-- My Internship -->
      <div class="col-lg-4 col-md-6 col-12">
          <div class="small-box bg-success"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">45</h2>
                  <p>Skills</p>
              </div>
              <div class="infobox-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M232,86.53V56a16,16,0,0,0-16-16H40A16,16,0,0,0,24,56V184a16,16,0,0,0,16,16H160v24A8,8,0,0,0,172,231l24-13.74L220,231A8,8,0,0,0,232,224V161.47a51.88,51.88,0,0,0,0-74.94ZM128,144H72a8,8,0,0,1,0-16h56a8,8,0,0,1,0,16Zm0-32H72a8,8,0,0,1,0-16h56a8,8,0,0,1,0,16Zm88,98.21-16-9.16a8,8,0,0,0-7.94,0l-16,9.16V172a51.88,51.88,0,0,0,40,0ZM196,160a36,36,0,1,1,36-36A36,36,0,0,1,196,160Z"></path></svg>              </div>
              <a href="#" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>


    </div>
  </div>
</section>

    @include('layouts.partials.scripts-main')
@endsection
