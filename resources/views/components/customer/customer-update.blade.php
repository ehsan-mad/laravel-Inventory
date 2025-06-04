<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameUpdate">

                                <label class="form-label mt-3">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailUpdate">

                                <label class="form-label mt-3">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobileUpdate">

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function called when Edit button is clicked (loads customer data)
    async function editCustomer(id) {
        try {
            showLoader();

            // Set the ID in hidden field
            document.getElementById('updateID').value = id;

            // Fetch customer details to populate the form
            let response = await axios.post(`/customer-id`,{
                id: id,
            } , HeaderToken());

            hideLoader();

            if (response.data.status === "success") {
                // Populate the form with existing data
                document.getElementById('customerNameUpdate').value = response.data.data.name;
                document.getElementById('customerEmailUpdate').value = response.data.data.email;
                document.getElementById('customerMobileUpdate').value = response.data.data.mobile;
            } else {
                errorToast("Failed to load customer details");
            }

        } catch (error) {
            hideLoader();
            errorToast("Failed to load customer details");
        }
    }

    // Function to actually update the customer
    async function Update() {
        let customerName = document.getElementById('customerNameUpdate').value;
        let customerEmail = document.getElementById('customerEmailUpdate').value;
        let customerMobile = document.getElementById('customerMobileUpdate').value;
        let customerId = document.getElementById('updateID').value;

        if (!customerName || !customerEmail || !customerMobile) {
            errorToast("All fields are required");
            return;
        }

        try {
            showLoader();
            let response = await axios.post('/customerUpdate', {
                id: customerId,
                name: customerName,
                email: customerEmail,
                mobile: customerMobile
            }, HeaderToken());

            hideLoader();

            if (response.data.status === "success") {
                successToast("Customer updated successfully");
                document.getElementById('update-form').reset();
    document.getElementById('update-modal-close').click();
    setTimeout(() => {
        window.location.reload();
    }, 1000);
            } else {
                errorToast("Failed to update customer");
            }
        } catch (error) {
            hideLoader();
            errorToast("Failed to update customer");
        }
    }


</script>
