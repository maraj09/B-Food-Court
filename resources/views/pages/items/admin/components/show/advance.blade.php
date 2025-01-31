<div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
  <div class="d-flex flex-column gap-7 gap-lg-10">

    <!--begin::Inventory-->
    <div class="card card-flush py-4">
      <!--begin::Card header-->
      <div class="card-header">
        <div class="card-title">
          <h2>Inventory</h2>
        </div>
      </div>
      <!--end::Card header-->

      <!--begin::Card body-->
      <div class="card-body pt-0">
        <!--begin::Input group-->
        <div class="mb-10 fv-row fv-plugins-icon-container">
          <!--begin::Label-->
          <label class="required form-label">SKU</label>
          <!--end::Label-->

          <!--begin::Input-->
          <input type="text" name="sku" class="form-control mb-2" placeholder="SKU Number" value="011985001">
          <!--end::Input-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Enter the product SKU.</div>
          <!--end::Description-->
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
          </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10 fv-row fv-plugins-icon-container">
          <!--begin::Label-->
          <label class="required form-label">Barcode</label>
          <!--end::Label-->

          <!--begin::Input-->
          <input type="text" name="barcode" class="form-control mb-2" placeholder="Barcode Number"
            value="45874521458">
          <!--end::Input-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Enter the product barcode number.</div>
          <!--end::Description-->
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
          </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10 fv-row fv-plugins-icon-container">
          <!--begin::Label-->
          <label class="required form-label">Quantity</label>
          <!--end::Label-->

          <!--begin::Input-->
          <div class="d-flex gap-3">
            <input type="number" name="shelf" class="form-control mb-2" placeholder="On shelf" value="24">
            <input type="number" name="warehouse" class="form-control mb-2" placeholder="In warehouse">
          </div>
          <!--end::Input-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Enter the product quantity.</div>
          <!--end::Description-->
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
          </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row">
          <!--begin::Label-->
          <label class="form-label">Allow Backorders</label>
          <!--end::Label-->

          <!--begin::Input-->
          <div class="form-check form-check-custom form-check-solid mb-2">
            <input class="form-check-input" type="checkbox" value="">
            <label class="form-check-label">
              Yes
            </label>
          </div>
          <!--end::Input-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Allow customers to purchase products that are out of stock.</div>
          <!--end::Description-->
        </div>
        <!--end::Input group-->
      </div>
      <!--end::Card header-->
    </div>
    <!--end::Inventory-->

    <!--begin::Variations-->
    <div class="card card-flush py-4">
      <!--begin::Card header-->
      <div class="card-header">
        <div class="card-title">
          <h2>Variations</h2>
        </div>
      </div>
      <!--end::Card header-->

      <!--begin::Card body-->
      <div class="card-body pt-0">
        <!--begin::Input group-->
        <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
          <!--begin::Label-->
          <label class="form-label">Add Product Variations</label>
          <!--end::Label-->

          <!--begin::Repeater-->
          <div id="kt_ecommerce_add_product_options">
            <!--begin::Form group-->
            <div class="form-group">
              <div data-repeater-list="kt_ecommerce_add_product_options" class="d-flex flex-column gap-3">
                <div data-repeater-item="" class="form-group d-flex flex-wrap align-items-center gap-5">
                  <!--begin::Select2-->
                  <div class="w-100 w-md-200px">
                    <select class="form-select select2-hidden-accessible"
                      name="kt_ecommerce_add_product_options[0][product_option]" data-placeholder="Select a variation"
                      data-kt-ecommerce-catalog-add-product="product_option" data-select2-id="select2-data-126-n6fg"
                      tabindex="-1" aria-hidden="true">
                      <option data-select2-id="select2-data-128-wqdu"></option>
                      <option value="color">Color</option>
                      <option value="size">Size</option>
                      <option value="material">Material</option>
                      <option value="style">Style</option>
                    </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr"
                      data-select2-id="select2-data-127-je39" style="width: 100%;"><span class="selection"><span
                          class="select2-selection select2-selection--single form-select" role="combobox"
                          aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false"
                          aria-labelledby="select2-kt_ecommerce_add_product_options0product_option-zs-container"
                          aria-controls="select2-kt_ecommerce_add_product_options0product_option-zs-container"><span
                            class="select2-selection__rendered"
                            id="select2-kt_ecommerce_add_product_options0product_option-zs-container" role="textbox"
                            aria-readonly="true" title="Select a variation"><span
                              class="select2-selection__placeholder">Select a
                              variation</span></span><span class="select2-selection__arrow" role="presentation"><b
                              role="presentation"></b></span></span></span><span class="dropdown-wrapper"
                        aria-hidden="true"></span></span>
                  </div>
                  <!--end::Select2-->

                  <!--begin::Input-->
                  <input type="text" class="form-control mw-100 w-200px"
                    name="kt_ecommerce_add_product_options[0][product_option_value]" placeholder="Variation">
                  <!--end::Input-->

                  <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                  </button>
                </div>
              </div>
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group mt-5">
              <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-plus fs-2"></i> Add another variation
              </button>
            </div>
            <!--end::Form group-->
          </div>
          <!--end::Repeater-->
        </div>
        <!--end::Input group-->
      </div>
      <!--end::Card header-->
    </div>
    <!--end::Variations-->

    <!--begin::Shipping-->
    <div class="card card-flush py-4">
      <!--begin::Card header-->
      <div class="card-header">
        <div class="card-title">
          <h2>Shipping</h2>
        </div>
      </div>
      <!--end::Card header-->

      <!--begin::Card body-->
      <div class="card-body pt-0">
        <!--begin::Input group-->
        <div class="fv-row">
          <!--begin::Input-->
          <div class="form-check form-check-custom form-check-solid mb-2">
            <input class="form-check-input" type="checkbox" id="kt_ecommerce_add_product_shipping_checkbox"
              value="1" checked="">
            <label class="form-check-label">
              This is a physical product
            </label>
          </div>
          <!--end::Input-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Set if the product is a physical or digital item. Physical
            products may require shipping.</div>
          <!--end::Description-->
        </div>
        <!--end::Input group-->

        <!--begin::Shipping form-->
        <div id="kt_ecommerce_add_product_shipping" class=" mt-10">
          <!--begin::Input group-->
          <div class="mb-10 fv-row">
            <!--begin::Label-->
            <label class="form-label">Weight</label>
            <!--end::Label-->

            <!--begin::Editor-->
            <input type="text" name="weight" class="form-control mb-2" placeholder="Product weight"
              value="4.3">
            <!--end::Editor-->

            <!--begin::Description-->
            <div class="text-muted fs-7">Set a product weight in kilograms (kg).</div>
            <!--end::Description-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="fv-row">
            <!--begin::Label-->
            <label class="form-label">Dimension</label>
            <!--end::Label-->

            <!--begin::Input-->
            <div class="d-flex flex-wrap flex-sm-nowrap gap-3">
              <input type="number" name="width" class="form-control mb-2" placeholder="Width (w)"
                value="12">
              <input type="number" name="height" class="form-control mb-2" placeholder="Height (h)"
                value="4">
              <input type="number" name="length" class="form-control mb-2" placeholder="Lengtn (l)"
                value="8.5">
            </div>
            <!--end::Input-->

            <!--begin::Description-->
            <div class="text-muted fs-7">Enter the product dimensions in centimeters (cm).</div>
            <!--end::Description-->
          </div>
          <!--end::Input group-->
        </div>
        <!--end::Shipping form-->
      </div>
      <!--end::Card header-->
    </div>
    <!--end::Shipping-->
    <!--begin::Meta options-->
    <div class="card card-flush py-4">
      <!--begin::Card header-->
      <div class="card-header">
        <div class="card-title">
          <h2>Meta Options</h2>
        </div>
      </div>
      <!--end::Card header-->

      <!--begin::Card body-->
      <div class="card-body pt-0">
        <!--begin::Input group-->
        <div class="mb-10">
          <!--begin::Label-->
          <label class="form-label">Meta Tag Title</label>
          <!--end::Label-->

          <!--begin::Input-->
          <input type="text" class="form-control mb-2" name="meta_title" placeholder="Meta tag name">
          <!--end::Input-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Set a meta tag title. Recommended to be simple and precise
            keywords.</div>
          <!--end::Description-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10">
          <!--begin::Label-->
          <label class="form-label">Meta Tag Description</label>
          <!--end::Label-->

          <!--begin::Editor-->
          <div class="ql-toolbar ql-snow"><span class="ql-formats"><span class="ql-header ql-picker"><span
                  class="ql-picker-label" tabindex="0" role="button" aria-expanded="false"
                  aria-controls="ql-picker-options-1"><svg viewBox="0 0 18 18">
                    <polygon class="ql-stroke" points="7 11 9 13 11 11 7 11"></polygon>
                    <polygon class="ql-stroke" points="7 7 9 5 11 7 7 7"></polygon>
                  </svg></span><span class="ql-picker-options" aria-hidden="true" tabindex="-1"
                  id="ql-picker-options-1"><span tabindex="0" role="button" class="ql-picker-item"
                    data-value="1"></span><span tabindex="0" role="button" class="ql-picker-item"
                    data-value="2"></span><span tabindex="0" role="button"
                    class="ql-picker-item ql-selected"></span></span></span><select class="ql-header"
                style="display: none;">
                <option value="1"></option>
                <option value="2"></option>
                <option selected="selected"></option>
              </select></span><span class="ql-formats"><button type="button" class="ql-bold"><svg
                  viewBox="0 0 18 18">
                  <path class="ql-stroke"
                    d="M5,4H9.5A2.5,2.5,0,0,1,12,6.5v0A2.5,2.5,0,0,1,9.5,9H5A0,0,0,0,1,5,9V4A0,0,0,0,1,5,4Z">
                  </path>
                  <path class="ql-stroke"
                    d="M5,9h5.5A2.5,2.5,0,0,1,13,11.5v0A2.5,2.5,0,0,1,10.5,14H5a0,0,0,0,1,0,0V9A0,0,0,0,1,5,9Z">
                  </path>
                </svg></button><button type="button" class="ql-italic"><svg viewBox="0 0 18 18">
                  <line class="ql-stroke" x1="7" x2="13" y1="4" y2="4">
                  </line>
                  <line class="ql-stroke" x1="5" x2="11" y1="14" y2="14">
                  </line>
                  <line class="ql-stroke" x1="8" x2="10" y1="14" y2="4">
                  </line>
                </svg></button><button type="button" class="ql-underline"><svg viewBox="0 0 18 18">
                  <path class="ql-stroke" d="M5,3V9a4.012,4.012,0,0,0,4,4H9a4.012,4.012,0,0,0,4-4V3">
                  </path>
                  <rect class="ql-fill" height="1" rx="0.5" ry="0.5" width="12" x="3" y="15">
                  </rect>
                </svg></button></span><span class="ql-formats"><button type="button" class="ql-image"><svg
                  viewBox="0 0 18 18">
                  <rect class="ql-stroke" height="10" width="12" x="3" y="4"></rect>
                  <circle class="ql-fill" cx="6" cy="7" r="1"></circle>
                  <polyline class="ql-even ql-fill" points="5 12 5 11 7 9 8 10 11 7 13 9 13 12 5 12">
                  </polyline>
                </svg></button><button type="button" class="ql-code-block"><svg viewBox="0 0 18 18">
                  <polyline class="ql-even ql-stroke" points="5 7 3 9 5 11"></polyline>
                  <polyline class="ql-even ql-stroke" points="13 7 15 9 13 11"></polyline>
                  <line class="ql-stroke" x1="10" x2="8" y1="5" y2="13"></line>
                </svg></button></span></div>
          <div id="kt_ecommerce_add_product_meta_description" name="kt_ecommerce_add_product_meta_description"
            class="min-h-100px mb-2 ql-container ql-snow">
            <div class="ql-editor ql-blank" data-gramm="false" contenteditable="true"
              data-placeholder="Type your text here...">
              <p><br></p>
            </div>
            <div class="ql-clipboard" contenteditable="true" tabindex="-1"></div>
            <div class="ql-tooltip ql-hidden"><a class="ql-preview" rel="noopener noreferrer" target="_blank"
                href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com"
                data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>
          </div>
          <!--end::Editor-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Set a meta tag description to the product for increased SEO
            ranking.</div>
          <!--end::Description-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div>
          <!--begin::Label-->
          <label class="form-label">Meta Tag Keywords</label>
          <!--end::Label-->

          <!--begin::Editor-->
          <input id="kt_ecommerce_add_product_meta_keywords" name="kt_ecommerce_add_product_meta_keywords"
            class="form-control mb-2">
          <!--end::Editor-->

          <!--begin::Description-->
          <div class="text-muted fs-7">Set a list of keywords that the product is related to. Separate
            the keywords by adding a comma <code>,</code> between each keyword.</div>
          <!--end::Description-->
        </div>
        <!--end::Input group-->
      </div>
      <!--end::Card header-->
    </div>
    <!--end::Meta options-->
  </div>
</div>
