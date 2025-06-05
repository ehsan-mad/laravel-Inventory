<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>

                                <select type="text" class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPrice">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit">
                                <label class="form-label mt-2">Photo</label>
                                <input type="file" class="form-control" id="productImage">

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>


<script>
    getCategoryList();
    async function getCategoryList() {
        try {
            showLoader();
            let response = await axios.get('/category-list', HeaderToken());
            hideLoader();

            if (response.data.status === "success") {
                let data = response.data.data;
                let productCategory = document.getElementById('productCategory');
                productCategory.innerHTML = '<option value="">Select Category</option>';

                data.forEach(item => {
                    let option = `<option value="${item.id}">${item.name}</option>`;
                    productCategory.innerHTML += option;
                });
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            errorToast("Failed to fetch categories");
        }
    }

    async function Save() {
        let name = document.getElementById('productName').value;
        let price = document.getElementById('productPrice').value;
        let unit = document.getElementById('productUnit').value;
        let category_id = document.getElementById('productCategory').value;
        let image = document.getElementById('productImage').files[0];

        if (!name || !price || !unit || !category_id) {
            errorToast("All fields are required");
            return;
        }

        showLoader();

        try {

            // Create FormData to handle file upload
            const formData = new FormData();
            formData.append('name', name);
            formData.append('price', price);
            formData.append('unit', unit);
            formData.append('category_id', category_id);
            formData.append('image', image);

            // Make the API request with FormData
            const response = await axios.post('/productCreate', formData, HeaderToken());

            hideLoader();

            if (response.data.status === "success") {

                successToast(response.data.message);
                document.getElementById('modal-close').click();
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();

            if (error.response && error.response.data) {
                errorToast(error.response.data.message || "Failed to create product");
            } else {
                errorToast("Failed to create product");
            }
        }
    }
</script>
