@extends('layouts.admin.app')
@section('contents')
  @include('pages.user-management.admin.toolbars.permissions-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Card-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header mt-6">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1 me-5">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-permissions-table-filter="search"
                class="form-control form-control-solid w-250px ps-13" placeholder="Search Permissions" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar">
            <!--begin::Button-->
            <button type="button" class="btn btn-light-primary" data-bs-toggle="modal"
              data-bs-target="#kt_modal_add_permission">
              <i class="ki-duotone ki-plus-square fs-3">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>Add Permission</button>
            <!--end::Button-->
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="min-w-125px">Name</th>
                <th class="min-w-250px">Assigned to</th>
                <th class="min-w-125px">Created Date</th>
                <th class="text-end min-w-100px">Actions</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
              @php
                $roleColors = [
                    'badge-light-primary',
                    'badge-light-success',
                    'badge-light-info',
                    'badge-light-warning',
                    'badge-light-danger',
                    'badge-light-secondary',
                ];
                $totalColors = count($roleColors);
              @endphp

              @foreach ($permissions as $permission)
                <tr>
                  <td>{{ $permission->name }}</td>
                  <td>
                    @foreach ($permission->roles as $role)
                      @php
                        $colorIndex = $loop->index % $totalColors;
                      @endphp
                      <a href="#" class="badge {{ $roleColors[$colorIndex] }} fs-7 m-1">{{ $role->name }}</a>
                    @endforeach
                  </td>
                  <td data-order="{{ $permission->created_at }}">{{ $permission->created_at->format('d M Y, h:i a') }}
                  </td>
                  <td class="text-end">
                    <form action="/dashboard/user-management/permissions/{{ $permission->id }}" method="post">
                      @csrf
                      @method('delete')
                      <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                        onclick="submitParentForm(this)">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span
                            class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <!--end::Table-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Modals-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.user-management.admin.modules.modals.add-premission-modal')
@endsection
@section('scripts')
  <script>
    var modal = new bootstrap.Modal(
      document.querySelector("#kt_modal_add_permission")
    );
    @if ($errors->any())
      modal.show()
    @endif
  </script>
  <script>
    "use strict";
    var KTPermissionListing = (function() {
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
          (e = document.querySelector("#kt_permissions_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [{
                orderable: !1,
                targets: 3
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
            .querySelector('[data-kt-permissions-table-filter="search"]')
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
      KTPermissionListing.init();
    });
  </script>
@endsection
