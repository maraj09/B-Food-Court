<div class="col-xl-4 mb-xl-10 d-flex flex-row flex-column-fluid draggable">
  <!--begin::Tables Widget 4-->
  <div class="card shadow-sm d-flex flex-row-fluid">
    <div class="card-header">
      <h3 class="card-title">Support</h3>
      <div class="card-toolbar">
        <button id="toggleFormBtn" class="btn btn-primary btn-hover-rise collapsible cursor-pointer rotate p-2 me-2"><i
            class="fas fa-plus me-2 rotate-180"></i>Add New</button>
      </div>
    </div>
    @if (Session::has('success'))
      <div class="alert mx-5 alert-success">
        {{ Session::get('success') }}
      </div>
    @endif
    <!--begin::Support Form-->
    <div id="kt_docs_card_collapsible" class="collapse hide shadow-sm border border-dashed border-danger rounded-1">
      <div class="card-body">
        <!--begin:Form-->
        <form id="kt_modal_new_ticket_form" class="form" method="POST" action="/dashboard/tickets/store">
          <!--begin::Heading-->
          @csrf
          <div class="mb-4 text-center">
            <!--begin::Title-->
            <h1 class="mb-3">Create Ticket</h1>
            <!--end::Title-->
            <!--begin::Description-->
            <div class="text-gray-400 fw-bold fs-6">If you need more info, please check
              <a href="" class="fw-bolder link-primary">FAQ</a>.
            </div>
            <!--end::Description-->
          </div>
          <!--end::Heading-->
          <!--begin::Input group-->
          <div class="d-flex flex-column mb-4 fv-row">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-7 fw-bold mb-2">
              <span class="required">Subject</span>
              <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                aria-label="Specify a subject for your issue" data-bs-original-title="Specify a subject for your issue"
                data-kt-initialized="1"></i>
            </label>
            <!--end::Label-->
            <input type="text" class="form-control form-control-solid" placeholder="Enter your ticket subject"
              name="subject">

            @error('subject')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="d-flex flex-column mb-4 fv-row">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-7 fw-bold mb-2">
              <span class="required">User</span>
              <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                aria-label="User Name can be search by name and mobile no."
                data-bs-original-title="User Name can be search by name and mobile no." data-kt-initialized="1"></i>
            </label>
            <!--end::Label-->
            <select class="form-select  kt_select2_status" name="user_id" data-placeholder="Select User">
              <option value=""></option>
              @foreach ($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
              @endforeach
            </select>
            @error('user_id')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="d-flex flex-column mb-4 fv-row">
            <label class="fs-6 fw-bold mb-2">Description</label>
            <textarea class="form-control form-control-solid" rows="4" name="description"
              placeholder="Type your ticket description"></textarea>
            @error('description')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Input group-->

          <!--begin::Actions-->
          <div class="text-center">
            <button type="reset" id="kt_modal_new_ticket_cancel" class="btn btn-light me-3">Cancel</button>
            <button type="submit" id="kt_modal_new_ticket_submit" class="btn btn-primary">
              <span class="indicator-label">Submit</span>
              <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
          </div>
          <!--end::Actions-->
        </form>
        <!--end:Form-->
      </div>
    </div>
    <!--end::Support Form-->
    <div class="card-body px-5">
      <!--begin::Row-->
      <div class="row gx-10 mt-n10">
        <!--begin::Col-->
        <div class="col px-3 py-3 rounded-2 me-3 mb-3">
          <select class="form-select" id="statusFilter" aria-label="Select Status">
            <option value="all" selected>All Status</option>
            <option value="open">Open</option>
            <option value="updated">updated</option>
            <option value="closed">Closed</option>
          </select>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col px-3 py-3 rounded-2 me-3 mb-3">
          <!--begin::Input group-->
          <select class="form-select " aria-label="Select Type">
            <option>Ticket</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
          <!--end::Input group-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
      <div class="hover-scroll-overlay-y" style="height: 450px">
        @foreach ($tickets as $ticket)
          <!-- Ticket Accordion -->
          <div class="accordion mb-2 border border-dashed border-gray-500 rounded-1"
            id="kt_accordion_{{ $ticket->id }}" data-status="{{ $ticket->status }}">
            <!-- Accordion Header -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="kt_accordion_{{ $ticket->id }}_header_1">
                <button class="accordion-button fs-6 fw-semibold py-2" type="button" data-bs-toggle="collapse"
                  data-bs-target="#kt_accordion_{{ $ticket->id }}_body_1" aria-expanded="true"
                  aria-controls="kt_accordion_{{ $ticket->id }}_body_1">
                  <div class="d-flex flex-column flex-grow-1 py-0">
                    <a href="#" class="text-dark text-hover-primary mb-1">{{ $ticket->title }}
                      @if ($ticket->status == 'open')
                        <span class="badge badge-warning ms-2">Open</span>
                      @elseif ($ticket->status == 'closed')
                        <span class="badge badge-success ms-2">Closed</span>
                      @else
                        <span class="badge badge-info ms-2">Updated</span>
                      @endif
                    </a>
                    <div>
                      <span class="text-muted d-inline fs-8 me-2">{{ $ticket->created_at->format('l, d F Y') }}</span>
                      <span class="text-warning d-inline fs-8 me-2">By: {{ $ticket->user->name }}</span>
                    </div>
                  </div>
                </button>
              </h2>
              <!-- Accordion Body -->
              <div id="kt_accordion_{{ $ticket->id }}_body_1" class="accordion-collapse collapse hide"
                aria-labelledby="kt_accordion_{{ $ticket->id }}_header_1"
                data-bs-parent="#kt_accordion_{{ $ticket->id }}">
                <div class="accordion-body">
                  <!-- Ticket Messages -->
                  <div class="timeline-label">
                    @foreach ($ticket->messages as $message)
                      <div class="timeline-item">
                        <div class="timeline-label fw-bolder text-gray-800 fs-7">
                          {{ $message->created_at->format('d M Y') }}
                        </div>
                        <div class="timeline-badge">
                          <i
                            class="fa fa-genderless {{ $message->user_id == auth()->id() ? 'text-success' : 'text-warning' }} fs-1"></i>
                        </div>
                        <div class="fw-mormal timeline-content text-muted ps-3">
                          {{ $message->message }}
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <!-- Ticket Form for New Message -->
                  <form id="kt_modal_new_ticket_form" method="POST" class="form"
                    action="/dashboard/tickets/{{ $ticket->id }}/update">
                    @csrf
                    <div class="d-flex flex-column mb-4 fv-row">
                      <label class="fs-6 fw-bold mb-2">Description</label>
                      <textarea class="form-control form-control-solid" rows="4" name="message"
                        placeholder="Type your ticket description"></textarea>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary p-2">
                        <span class="indicator-label">Send</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                      </button>
                      <a href="/dashboard/tickets/{{ $ticket->id }}/update-status">
                        <button type="button" class="btn btn-sm p-2 btn-outline-success btn-dashed">Close
                          X</button>
                      </a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="card-footer pb-5 pt-5">
      <!--begin::Description-->
      <div class="text-gray-400 fw-bold fs-6">If you need more info, please check
        <a href="" class="fw-bolder link-primary">FAQ</a>.
      </div>
      <!--end::Description-->
    </div>
  </div>
  <!--end::Tables Widget 4-->
</div>
