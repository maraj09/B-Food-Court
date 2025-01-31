@extends('layouts.admin.app')
@section('contents')
  @include('pages.user-management.admin.toolbars.roles-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Row-->
      @if (session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
        <!--begin::Col-->
        @foreach ($roles as $role)
          <div class="col-md-4">
            <!--begin::Card-->
            <div class="card card-flush h-md-100">
              <!--begin::Card header-->
              <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                  <h2 class="text-capitalize">{{ $role->name }}</h2>
                </div>
                <!--end::Card title-->
              </div>
              <!--end::Card header-->
              <!--begin::Card body-->
              <div class="card-body pt-1">
                <!--begin::Users-->
                <div class="fw-bold text-gray-600 mb-5">
                  Total users with this role: {{ $role->users->count() }}
                </div>
                <!--end::Users-->
                <!--begin::Permissions-->
                @if ($role->name == 'admin')
                  <div class="d-flex align-items-center py-2">
                    <span class="bullet bg-primary me-3"></span>Allowed full access to the system
                  </div>
                @elseif ($role->name == 'vendor')
                  <div class="d-flex align-items-center py-2">
                    <span class="bullet bg-primary me-3"></span>Have permissions for regular vendor's tasks
                  </div>
                @elseif ($role->name == 'customer')
                  <div class="d-flex align-items-center py-2">
                    <span class="bullet bg-primary me-3"></span>Limited access to purchasing and browsing products
                  </div>
                @else
                  <div class="d-flex flex-column text-gray-600">
                    @php
                      $permissions = $role->permissions;
                      $displayLimit = 5; // Number of permissions to display before showing "and X more..."
                    @endphp

                    @foreach ($permissions->take($displayLimit) as $permission)
                      <div class="d-flex align-items-center py-2">
                        <span class="bullet bg-primary me-3"></span>{{ $permission->name }}
                      </div>
                    @endforeach

                    @if ($permissions->count() > $displayLimit)
                      <div class='d-flex align-items-center py-2'>
                        <span class='bullet bg-primary me-3'></span>
                        <em>and {{ $permissions->count() - $displayLimit }} more...</em>
                      </div>
                    @endif
                  </div>
                @endif
                <!--end::Permissions-->
              </div>
              <!--end::Card body-->
              <!--begin::Card footer-->
              <div class="card-footer flex-wrap pt-0">
                <a href="/dashboard/user-management/roles/{{ $role->id }}"
                  class="btn btn-light btn-active-primary my-1 me-2">
                  View Role
                </a>
                @if ($role->name != 'admin' && $role->name != 'vendor' && $role->name != 'customer')
                  <button type="button" class="btn btn-light btn-active-light-primary my-1 kt_modal_edit_role"
                    data-bs-toggle="modal" data-bs-target="#kt_modal_update_role" data-role-id="{{ $role->id }}">
                    Edit Role
                  </button>
                @endif
              </div>
              <!--end::Card footer-->
            </div>
            <!--end::Card-->
          </div>
        @endforeach
        <!--end::Col-->
        <!--begin::Add new card-->
        <div class="col-md-4">
          <!--begin::Card-->
          <div class="card h-md-100">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-center">
              <!--begin::Button-->
              <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal"
                data-bs-target="#kt_modal_add_role">
                <!--begin::Illustration-->
                <img src="{{ asset('assets/media/illustrations/sketchy-1/4.png') }}" alt=""
                  class="mw-100 mh-150px mb-7" />
                <!--end::Illustration-->
                <!--begin::Label-->
                <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                <!--end::Label-->
              </button>
              <!--begin::Button-->
            </div>
            <!--begin::Card body-->
          </div>
          <!--begin::Card-->
        </div>
        <!--begin::Add new card-->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.user-management.admin.modules.modals.add-role-modal')
  @include('pages.user-management.admin.modules.modals.edit-role-modal')
@endsection
@section('scripts')
  <script>
    var modal = new bootstrap.Modal(
      document.querySelector("#kt_modal_add_role")
    );
    @if ($errors->any())
      modal.show()
    @endif
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.kt_modal_edit_role', function(e) {
        e.preventDefault();

        var roleId = $(this).data('role-id');

        $.ajax({
          url: '/dashboard/user-management/roles/' + roleId + '/edit', // Updated URL to use RESTful route
          type: 'GET',
          success: function(response) {
            $('#kt_edit_role_modal_content').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            var errorMessage = 'Error fetching play area data.';
            if (xhr.status === 404) {
              errorMessage = 'Play area not found.';
            }
            console.error(errorMessage);
            Swal.fire({
              text: errorMessage,
              icon: 'error',
              buttonsStyling: false,
              confirmButtonText: 'Ok, got it!',
              customClass: {
                confirmButton: 'btn btn-primary',
              },
            });
          }
        });
      });
    });
  </script>
@endsection
