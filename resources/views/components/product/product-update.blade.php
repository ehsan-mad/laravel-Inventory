<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>
                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">
                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">
                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                                <input type="text" class="d-none" id="updateID">
                                <label class="form-label mt-2">Image</label>
                                <input type="file" class="form-control" id="productImageUpdate">

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="updateProduct()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>

        </div>
    </div>
</div>

<script>
    async function getCategoryListUpdate() {
        try {
            showLoader();
            let response = await axios.get('/category-list', HeaderToken());
            hideLoader();

            if (response.data.status === "success") {
                let data = response.data.data;
                let productCategory = document.getElementById('productCategoryUpdate');
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

    getCategoryListUpdate();

    // ✅ Separate function to load product data (called when Edit button is clicked)
    async function editProduct(id) {
        try {
            // Set the ID in hidden field
            document.getElementById('updateID').value = id;

            // Fetch product details to populate the form
            showLoader();
            let response = await axios.post(`/productById`, {
                id: id,
            }, HeaderToken());
            hideLoader();

            if (response.data.status === "success") {
                let product = response.data.data;
                // Populate the form with existing data
                document.getElementById('productNameUpdate').value = product.name;
                document.getElementById('productPriceUpdate').value = product.price;
                document.getElementById('productUnitUpdate').value = product.unit;
                document.getElementById('productCategoryUpdate').value = product.category_id;

                // Clear the file input (user can choose to update image or not)
                document.getElementById('productImageUpdate').value = '';
            } else {
                errorToast("Failed to load product details");
            }
        } catch (error) {
            hideLoader();
            errorToast("Failed to load product details");
        }
    }

    // ✅ Separate function to update product (called when Update button is clicked)
    async function updateProduct() {
        try {
            // Validate required fields
            if (!document.getElementById('productNameUpdate').value ||
                !document.getElementById('productPriceUpdate').value ||
                !document.getElementById('productUnitUpdate').value ||
                !document.getElementById('productCategoryUpdate').value) {
                errorToast("All fields are required");
                return;
            }

            // Create FormData to handle file upload
            const formData = new FormData();
            formData.append('id', document.getElementById('updateID').value);
            formData.append('name', document.getElementById('productNameUpdate').value);
            formData.append('price', document.getElementById('productPriceUpdate').value);
            formData.append('unit', document.getElementById('productUnitUpdate').value);
            formData.append('category_id', document.getElementById('productCategoryUpdate').value);

            // ✅ Only append image if user selected a new one
            let image = document.getElementById('productImageUpdate').files[0];
            if (image) {
                formData.append('image', image);
            }

            showLoader();
            let response = await axios.post('/productUpdate', formData, HeaderToken());
            hideLoader();

            if (response.data.status === "success") {
                successToast(response.data.message);
                document.getElementById('update-form').reset();
                document.getElementById('update-modal-close').click();
                
                // ✅ Add delay to ensure modal fully closes before refresh
                setTimeout(async () => {
                    await getList();
                }, 1000); // Wait 1000ms for modal to close

            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            if (error.response && error.response.data && error.response.data.message) {
                errorToast(error.response.data.message);
            } else {
                errorToast("Failed to update product");
            }
        }
    }

</script>
