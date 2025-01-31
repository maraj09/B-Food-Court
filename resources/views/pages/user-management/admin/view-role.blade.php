@extends('layouts.admin.app')
@section('contents')
  @include('pages.user-management.admin.toolbars.view-role-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Layout-->
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
      <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
          <!--begin::Card-->
          <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header">
              <!--begin::Card title-->
              <div class="card-title">
                <h2 class="mb-0 text-capitalize">{{ $role->name }}</h2>
              </div>

              <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
              <!--begin::Permissions-->
              <div class="fw-bold text-gray-600 mb-5">
                Total users with this role: {{ $role->users->count() }}
              </div>
              <div class="d-flex flex-column text-gray-600">
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
              </div>
              <!--end::Permissions-->
            </div>
            <!--end::Card body-->
            <!--begin::Card footer-->
            <div class="card-footer d-flex pt-0">
              @if ($role->name != 'admin' && $role->name != 'vendor' && $role->name != 'customer')
                <button type="button" class="btn btn-light btn-active-light-primary my-1 kt_modal_edit_role"
                  data-bs-toggle="modal" data-bs-target="#kt_modal_update_role" data-role-id="{{ $role->id }}">
                  Edit Role
                </button>
                <form action="/dashboard/user-management/roles/{{ $role->id }}/delete" method="post">
                  @csrf
                  @method('delete')
                  <button type="button" class="btn btn-light btn-light-danger ms-3 my-1"
                    onclick="submitParentForm(this)">Delete</button>
                </form>
              @endif
            </div>
            <!--end::Card footer-->
          </div>
          <!--end::Card-->
        </div>
        <!--end::Sidebar-->
        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-10">
          <!--begin::Card-->
          <div class="card card-flush mb-6 mb-xl-9">
            <!--begin::Card header-->
            <div class="card-header pt-5">
              <!--begin::Card title-->
              <div class="card-title">
                <h2 class="d-flex align-items-center">Users Assigned
                  <span class="text-gray-600 fs-6 ms-1">({{ $role->users->count() }})</span>
                </h2>
              </div>
              <!--end::Card title-->
              <!--begin::Card toolbar-->
              <div class="card-toolbar">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                  <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <input type="text" data-kt-roles-table-filter="search"
                    class="form-control form-control-solid w-250px ps-15" placeholder="Search Users" />
                </div>
                <!--end::Search-->
              </div>
              <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
              <!--begin::Table-->
              <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                <thead>
                  <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2">
                      <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                          data-kt-check-target="#kt_roles_view_table .form-check-input" value="1" />
                      </div>
                    </th>
                    <th class="min-w-50px">ID</th>
                    <th class="min-w-150px">User</th>
                    <th class="min-w-125px">Joined Date</th>
                    <th class="text-end min-w-100px">Actions</th>
                  </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                  @foreach ($role->users as $user)
                    <tr>
                      <td>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                          <input class="form-check-input" type="checkbox" value="1" />
                        </div>
                      </td>
                      <td>{{ $user->id }}</td>
                      <td class="d-flex align-items-center">
                        <!--begin::User details-->
                        <div class="d-flex flex-column">
                          <a href="apps/user-management/users/view.html" class="text-gray-800 text-hover-primary mb-1">
                            {{ $user->name }}
                          </a>
                          <span>{{ $user->email }}</span>
                        </div>
                        <!--begin::User details-->
                      </td>
                      <td data-order="{{ $user->created_at }}">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, g:i a') }}
                      </td>
                      <td class="text-end">
                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                          data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                          <i class="ki-duotone ki-down fs-5 m-0"></i></a>
                        <!--begin::Menu-->
                        <div
                          class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                          data-kt-menu="true">
                          <!--begin::Menu item-->
                          <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 kt_modal_edit_user"
                              data-bs-target="#kt_modal_edit_user" data-bs-toggle="modal"
                              data-user-id="{{ $user->id }}">Edit</a>
                          </div>
                          <!--end::Menu item-->
                          <!--begin::Menu item-->
                          <div class="menu-item px-3">
                            <form action="/dashboard/user-management/users/{{ $user->id }}/delete" method="post">
                              @csrf
                              @method('delete')
                              <a href="javascript:void(0)" class="menu-link px-3"
                                onclick="submitParentForm(this)">Delete</a>
                            </form>
                          </div>
                          <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <!--end::Table-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card-->
        </div>
        <!--end::Content-->
      </div>
      <!--end::Layout-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.user-management.admin.modules.modals.edit-role-modal')
  @include('pages.user-management.admin.modules.modals.edit-user-modal')
@endsection
@section('scripts')
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
  <script>
    "use strict";
    var KTUserListing = (function() {
      var e,
        t,
        n,
        r,
        o,
        a = (e, n, a) => {
          (r = e[0] ? new Date(e[0]) : null),
          (o = e[1] ? new Date(e[1]) : null),
          $.fn.dataTable.ext.search.push(function(e, t, n) {
              var a = r,
                c = o,
                l = new Date(moment($(t[2]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[2]).text(), "DD/MM/YYYY"));
              return (
                (null === a && null === c) ||
                (null === a && c >= u) ||
                (a <= l && null === c) ||
                (a <= l && c >= u)
              );
            }),
            t.draw();
        };
      return {
        init: function() {
          (e = document.querySelector("#kt_roles_view_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [{
                orderable: !1,
                targets: 4
              }, ],
            })).on("draw", function() {

            }),
            (() => {
              const e = document.querySelector("#kt_ecommerce_sales_flatpickr");
              n = $(e).flatpickr({
                altInput: !0,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                onChange: function(e, t, n) {
                  a(e, t, n);
                },
              });
            })(),
            document
            .querySelector('[data-kt-roles-table-filter="search"]')
            .addEventListener("keyup", function(e) {
              t.search(e.target.value).draw();
            }),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="status"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(7).search(n).draw();
              });
            })(),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear")
            .addEventListener("click", (e) => {
              n.clear();
            }));
        },
      };
    })();
    KTUtil.onDOMContentLoaded(function() {
      KTUserListing.init();
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.kt_modal_edit_user', function(e) {
        e.preventDefault();

        var userId = $(this).data('user-id');
        $.ajax({
          url: '/dashboard/user-management/users/' + userId + '/edit', // Updated URL to use RESTful route
          type: 'GET',
          success: function(response) {
            $('#kt_modal_edit_user_content').html(response.drawerContent);
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
