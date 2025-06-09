@extends('layout.sidenav')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email:  <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">Customer ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Total</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                       <div class="col-12">
                           <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                           <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                           <span class="text-xxs">Discount(%):</span>
                           <input onkeydown="return false" value="0" min="0" type="number" step="0.25" onchange="DiscountChange()" class="form-control w-40 " id="discountP"/>
                           <p>
                              <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                           </p>
                       </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Customer</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



    {{-- Product Selecting Modal --}}

    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" id="PId">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="PName">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="PQty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success" >Add</button>
                </div>
            </div>
        </div>
    </div>


    <script>


        (async ()=>{
          showLoader();
          await  CustomerList();
          await ProductList();
          hideLoader();
        })()


        let InvoiceItemList=[];


        function ShowInvoiceItem() {

            let invoiceList=$('#invoiceList');

            invoiceList.empty();

            InvoiceItemList.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td>${item['product_name']}</td>
                        <td>${item['qty']}</td>
                        <td>${item['sale_price']}</td>
                        <td><a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a></td>
                     </tr>`
                invoiceList.append(row)
            })

            CalculateGrandTotal();

            $('.remove').on('click', async function () {
                let index= $(this).data('index');
                removeItem(index);
            })

        }


        function removeItem(index) {
            InvoiceItemList.splice(index,1);
            ShowInvoiceItem()
        }

        function DiscountChange() {
            CalculateGrandTotal();
        }

        function CalculateGrandTotal(){
            let Total=0;
            let Vat=0;
            let Payable=0;
            let Discount=0;
            let discountPercentage=(parseFloat(document.getElementById('discountP').value));

            InvoiceItemList.forEach((item,index)=>{
                Total=Total+parseFloat(item['sale_price'])
            })

             if(discountPercentage===0){
                 Vat= ((Total*5)/100).toFixed(2);
             }
             else {
                 Discount=((Total*discountPercentage)/100).toFixed(2);
                 Total=(Total-((Total*discountPercentage)/100)).toFixed(2);
                 Vat= ((Total*5)/100).toFixed(2);
             }

             Payable=(parseFloat(Total)+parseFloat(Vat)).toFixed(2);


            document.getElementById('total').innerText=Total;
            document.getElementById('payable').innerText=Payable;
            document.getElementById('vat').innerText=Vat;
            document.getElementById('discount').innerText=Discount;
        }


        function add() {
           let PId= document.getElementById('PId').value;
           let PName= document.getElementById('PName').value;
           let PPrice=document.getElementById('PPrice').value;
           let PQty= document.getElementById('PQty').value;
           let PTotalPrice=(parseFloat(PPrice)*parseFloat(PQty)).toFixed(2);
           if(PId.length===0){
               errorToast("Product ID Required");
           }
           else if(PName.length===0){
               errorToast("Product Name Required");
           }
           else if(PPrice.length===0){
               errorToast("Product Price Required");
           }
           else if(PQty.length===0){
               errorToast("Product Quantity Required");
           }
           else{
               let item={product_name:PName,product_id:PId,qty:PQty,sale_price:PTotalPrice};
               InvoiceItemList.push(item);
               console.log(InvoiceItemList);
               $('#create-modal').modal('hide')
               ShowInvoiceItem();
           }
        }




        function addModal(id,name,price) {
            document.getElementById('PId').value=id
            document.getElementById('PName').value=name
            document.getElementById('PPrice').value=price
            $('#create-modal').modal('show')
        }


        async function CustomerList(){
            try {
                let res = await axios.get("/customerList", HeaderToken());
                console.log('Customer response:', res.data);

                let customerList = $("#customerList");
                let customerTable = $("#customerTable");

                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#customerTable')) {
                    customerTable.DataTable().destroy();
                }
                customerList.empty();

                // ✅ Your controller returns: res.data.data
                if (res.data.status === 'success' && res.data.data) {
                    res.data.data.forEach(function (item, index) {
                        let row = `<tr class="text-xs">
                                <td><i class="bi bi-person"></i> ${item['name']}</td>
                                <td><a data-name="${item['name']}" data-email="${item['email']}" data-id="${item['id']}" class="btn btn-outline-dark addCustomer text-xxs px-2 py-1 btn-sm m-0">Add</a></td>
                             </tr>`
                        customerList.append(row)
                    });
                } else {
                    console.error('No customers found or invalid response');
                    errorToast("No customers found");
                }

                $('.addCustomer').on('click', async function () {
                    let CName = $(this).data('name');
                    let CEmail = $(this).data('email');
                    let CId = $(this).data('id');

                    $("#CName").text(CName);
                    $("#CEmail").text(CEmail);
                    $("#CId").text(CId);
                });

                new DataTable('#customerTable', {
                    order: [[0, 'desc']],
                    scrollCollapse: false,
                    info: false,
                    lengthChange: false
                });

            } catch (error) {
                console.error('CustomerList error:', error);
                errorToast("Failed to load customers");
            }
        }


        async function ProductList(){
            try {
                let res = await axios.get("/productList", HeaderToken());
                console.log('Product response:', res.data);

                let productList = $("#productList");
                let productTable = $("#productTable");

                if ($.fn.DataTable.isDataTable('#productTable')) {
                    productTable.DataTable().destroy();
                }
                productList.empty();

                // ✅ Access res.data.data for products too
                if (res.data.status === 'success' && res.data.data) {
                    res.data.data.forEach(function (item, index) {
                        let row = `<tr class="text-xs">
                                <td> <img class="w-10" src="${item['img_url']}"/> ${item['name']} ($ ${item['price']})</td>
                                <td><a data-name="${item['name']}" data-price="${item['price']}" data-id="${item['id']}" class="btn btn-outline-dark text-xxs px-2 py-1 addProduct btn-sm m-0">Add</a></td>
                             </tr>`
                        productList.append(row)
                    });
                } else {
                    console.error('No products found or invalid response');
                    errorToast("No products found");
                }

                $('.addProduct').on('click', async function () {
                    let PName = $(this).data('name');
                    let PPrice = $(this).data('price');
                    let PId = $(this).data('id');
                    addModal(PId, PName, PPrice)
                });

                new DataTable('#productTable', {
                    order: [[0, 'desc']],
                    scrollCollapse: false,
                    info: false,
                    lengthChange: false
                });

            } catch (error) {
                console.error('ProductList error:', error);
                errorToast("Failed to load products");
            }
        }



      async function createInvoice() {
    try {
        let total = document.getElementById('total').innerText;
        let discount = document.getElementById('discount').innerText;
        let vat = document.getElementById('vat').innerText;
        let payable = document.getElementById('payable').innerText;
        let CId = document.getElementById('CId').innerText;

        let Data = {
            "total": total,
            "discount": discount,
            "vat": vat,
            "payable": payable,
            "customer_id": CId,
            "products": InvoiceItemList
        };

        // ✅ Debug: Log the data being sent
        console.log('Invoice data being sent:', Data);

        if (CId.length === 0) {
            errorToast("Customer Required!");
            return;
        }

        if (InvoiceItemList.length === 0) {
            errorToast("Product Required!");
            return;
        }

        showLoader();

        // ✅ Fix: Add HeaderToken() for authentication
        let res = await axios.post("/invoiceCreate", Data, HeaderToken());

        hideLoader();

        // ✅ Fix: Handle new JSON response format from controller
        if (res.data.status === 'success') {
            successToast(res.data.message);

            // ✅ Reset the form
            resetInvoiceForm();

            // ✅ Redirect to invoice page
            window.location.href = '/invoicePage';
        } else {
            errorToast(res.data.message || "Failed to create invoice");
        }

    } catch (error) {
        hideLoader();
        console.error('Invoice creation error:', error);

        // ✅ Handle different types of errors
        if (error.response) {
            if (error.response.status === 422) {
                // Validation errors
                let errors = error.response.data.errors;
                if (errors) {
                    for (let field in errors) {
                        errorToast(`${field}: ${errors[field][0]}`);
                        break; // Show first error
                    }
                } else {
                    errorToast(error.response.data.message || "Validation failed");
                }
            } else {
                errorToast(error.response.data.message || "Server error occurred");
            }
        } else if (error.request) {
            errorToast("Network error - please check your connection");
        } else {
            errorToast("Something went wrong");
        }
    }
}

// ✅ Add function to reset the invoice form
function resetInvoiceForm() {
    // Clear invoice items
    InvoiceItemList = [];
    ShowInvoiceItem();

    // Clear customer info
    $("#CName").text('');
    $("#CEmail").text('');
    $("#CId").text('');

    // Reset discount
    document.getElementById('discountP').value = 0;

    // Clear totals
    document.getElementById('total').innerText = '0';
    document.getElementById('payable').innerText = '0';
    document.getElementById('vat').innerText = '0';
    document.getElementById('discount').innerText = '0';
}
    </script>




@endsection
