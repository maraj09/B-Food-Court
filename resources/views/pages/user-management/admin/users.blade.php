@extends('layouts.admin.app')
@section('contents')
  @include('pages.user-management.admin.toolbars.users-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Card-->
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
      <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-user-table-filter="search"
                class="form-control form-control-solid w-250px ps-13" placeholder="Search user" />
            </div>
            <!--end::Search-->
          </div>
          <!--begin::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
              <!--begin::Filter-->
              <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>Filter</button>
              <!--begin::Menu 1-->
              <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                <!--begin::Header-->
                <div class="px-7 py-5">
                  <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator border-gray-200"></div>
                <!--end::Separator-->
                <!--begin::Content-->
                <div class="px-7 py-5" data-kt-user-table-filter="form">
                  <!--begin::Input group-->
                  <div class="mb-10">
                    <label class="form-label fs-6 fw-semibold">Role:</label>
                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                      data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role"
                      data-hide-search="true">
                      <option></option>
                      @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <!--end::Input group-->
                </div>
                <!--end::Content-->
              </div>
              <!--end::Menu 1-->
              <!--end::Filter-->
              <!--begin::Add user-->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                <i class="ki-duotone ki-plus fs-2"></i>Add User</button>
              <!--end::Add user-->
            </div>
            <!--end::Toolbar-->
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
          <!--begin::Table-->
          <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
            <thead>
              <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_table_users .form-check-input" value="1" />
                  </div>
                </th>
                <th class="min-w-125px">User</th>
                <th class="min-w-125px">Role</th>
                <th class="min-w-125px">Joined Date</th>
                <th class="text-end min-w-100px">Actions</th>
              </tr>
            </thead>
            @php
              $colorClass = [
                  'bg-light-primary text-primary',
                  'bg-light-secondary text-white',
                  'bg-light-warning text-warning',
                  'bg-light-success text-success',
                  'bg-light-danger text-danger',
                  'bg-light-info text-info',
              ];
            @endphp
            <tbody class="text-gray-600 fw-semibold">
              @foreach ($users as $user)
                @php
                  $randomColorClass = $colorClass[array_rand($colorClass)];
                  $initials = collect(explode(' ', $user->name))
                      ->map(function ($word) {
                          return mb_substr($word, 0, 1);
                      })
                      ->join('');
                @endphp
                <tr>
                  <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                      <input class="form-check-input" type="checkbox" value="1" />
                    </div>
                  </td>
                  <td class="d-flex align-items-center">
                    <!--begin:: Avatar -->
                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                      <a href="#">
                        <div class="symbol-label {{ $randomColorClass }}">
                          {{ $initials }}
                        </div>
                      </a>
                    </div>
                    <!--end::Avatar-->
                    <!--begin::User details-->
                    <div class="d-flex flex-column">
                      <a href="#" class="text-gray-800 text-hover-primary mb-1 kt_modal_edit_user"
                        data-bs-target="#kt_modal_edit_user" data-bs-toggle="modal" data-user-id="{{ $user->id }}">
                        {{ $user->name }}
                      </a>
                      <span>{{ $user->email }}</span>
                    </div>
                    <!--end::User details-->
                  </td>
                  <td>{{ $user->roles->pluck('name')[0] ?? '-' }}</td>
                  <td data-order="{{ $user->created_at }}">
                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, g:i A') }}</td>
                  <td class="text-end">
                    <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                      data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                      <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                    <!--begin::Menu-->
                    <div
                      class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                      data-kt-menu="true">
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3 kt_modal_edit_user" data-bs-target="#kt_modal_edit_user"
                          data-bs-toggle="modal" data-user-id="{{ $user->id }}">Edit</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <form action="/dashboard/user-management/users/{{ $user->id }}/delete" method="post">
                          @csrf
                          @method('delete')
                          <a href="javascript:void(0)" class="menu-link px-3" onclick="submitParentForm(this)">Delete</a>
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
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.user-management.admin.modules.modals.add-user-modal')
  @include('pages.user-management.admin.modules.modals.edit-user-modal')
@endsection
@section('scripts')
  <script>
    // Function to open the modal
    function openModal(modalId) {
      var modal = new bootstrap.Modal(document.querySelector(modalId));
      modal.show();
    }

    // Check for any validation errors
    @if ($errors->any())
      openModal("#kt_modal_add_user");
    @endif
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
          (e = document.querySelector("#kt_table_users")) &&
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
            .querySelector('[data-kt-user-table-filter="search"]')
            .addEventListener("keyup", function(e) {
              t.search(e.target.value).draw();
            }),
            (() => {
              const e = document.querySelector(
                '[data-kt-user-table-filter="role"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(2).search(n).draw();
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
@endsection
