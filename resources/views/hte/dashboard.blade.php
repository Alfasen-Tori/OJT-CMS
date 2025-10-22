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
      {{-- Status Card --}}
      <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-danger">
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">0</h2>
            <p>Interns</p>
          </div>
          <div class="infobox-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM96,48h64a8,8,0,0,1,0,16H96a8,8,0,0,1,0-16Zm84.81,150.4a8,8,0,0,1-11.21-1.6,52,52,0,0,0-83.2,0,8,8,0,1,1-12.8-9.6A67.88,67.88,0,0,1,101,165.51a40,40,0,1,1,53.94,0A67.88,67.88,0,0,1,182.4,187.2,8,8,0,0,1,180.81,198.4ZM152,136a24,24,0,1,1-24-24A24,24,0,0,1,152,136Z"></path></svg>                
          </div>
          <a href="#" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Docs -->
      <div class="col-lg-4 col-md-6 col-12">
          <!-- small card -->
          <div class="small-box bg-info"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">{{ $moaStatus }}</h2>
                  <p>MOA Status</p>
              </div>
              <div class="infobox-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M80.3,120.26A58.29,58.29,0,0,1,81,97.07C83.32,87,87.89,80,92.1,80c2.57,0,2.94.67,3.12,1,.88,1.61,4,10.93-12.63,46.52A28.87,28.87,0,0,1,80.3,120.26ZM232,56V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V56A16,16,0,0,1,40,40H216A16,16,0,0,1,232,56ZM84,160c2-3.59,3.94-7.32,5.9-11.14,10.34-.32,22.21-7.57,35.47-21.68,5,9.69,11.38,15.25,18.87,16.55,8,1.38,16-2.38,23.94-11.2,6,5.53,16.15,11.47,31.8,11.47a8,8,0,0,0,0-16c-17.91,0-24.3-10.88-24.84-11.86a7.83,7.83,0,0,0-6.54-4.51,8,8,0,0,0-7.25,3.6c-6.78,10-11.87,13.16-14.39,12.73-4-.69-9.15-10-11.23-18a8,8,0,0,0-14-3c-8.88,10.94-16.3,17.79-22.13,21.66,15.8-35.65,13.27-48.59,9.6-55.3C107.35,69.84,102.59,64,92.1,64,79.66,64,69.68,75,65.41,93.46a75,75,0,0,0-.83,29.81c1.7,8.9,5.17,15.73,10.16,20.12-3,5.81-6.09,11.43-9,16.61H56a8,8,0,0,0,0,16h.44c-4.26,7.12-7.11,11.59-7.18,11.69a8,8,0,0,0,13.48,8.62c.36-.55,5.47-8.57,12.29-20.31H200a8,8,0,0,0,0-16Z"></path></svg>                
              </div>
              <a href="#" class="small-box-footer">
                  View <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>

      <!-- My Internship -->
      <div class="col-lg-4 col-md-6 col-12">
          <div class="small-box bg-success"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">0</h2>
                  <p>For Evaluation</p>
              </div>
              <div class="infobox-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM84,184a12,12,0,1,1,12-12A12,12,0,0,1,84,184Zm44,0a12,12,0,1,1,12-12A12,12,0,0,1,128,184Zm0-40a12,12,0,1,1,12-12A12,12,0,0,1,128,144Zm44,40a12,12,0,1,1,12-12A12,12,0,0,1,172,184Zm0-40a12,12,0,1,1,12-12A12,12,0,0,1,172,144Zm36-64H48V48H72v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24Z"></path></svg>                
              </div>
              <a href="#" class="small-box-footer">
                  View <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>


    </div>
  </div>
</section>
@endsection
