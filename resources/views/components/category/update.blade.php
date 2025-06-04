<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryNameUpdate">
                                <input class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="updateCategory()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function called when Edit button is clicked (loads category data)
    async function editCategory(id) {
        try {
            showLoader();
            
            // Set the ID in hidden field
            document.getElementById('updateID').value = id;
            
            // Fetch category details to populate the form
            let response = await axios.post(`/categoryById`,{
                id: id,
            } , HeaderToken());
            
            hideLoader();
            
            if (response.data.status === "success") {
                // Populate the form with existing data
                document.getElementById('categoryNameUpdate').value = response.data.data.name;
            } else {
                errorToast("Failed to load category details");
            }
            
        } catch (error) {
            hideLoader();
            errorToast("Failed to load category details");
        }
    }

    // Function to actually update the category
    async function updateCategory() {
        let categoryName = document.getElementById('categoryNameUpdate').value;
        let categoryId = document.getElementById('updateID').value;
        
        if (categoryName === "") {
            return errorToast("Category name is required");
        }
        
        if (!categoryId) {
            return errorToast("Category ID is missing");
        }

        try {
            showLoader();
            
            let response = await axios.post('/categoryUpdate', {
                name: categoryName,
                id: categoryId,
            }, HeaderToken());
            
            hideLoader();
            
            if (response.data.status === "success") {
                successToast(response.data.message);
                document.getElementById('update-form').reset();
                document.getElementById('update-modal-close').click();
                await getList(); // Refresh the category list
            } else {
                errorToast(response.data.message);
            }
            
        } catch (error) {
            hideLoader();
            
            if (error.response && error.response.status === 422) {
                let errors = error.response.data.message;
                if (typeof errors === 'object') {
                    for (let field in errors) {
                        errorToast(errors[field][0]);
                    }
                } else {
                    errorToast(errors);
                }
            } else if (error.response && error.response.data && error.response.data.message) {
                errorToast(error.response.data.message);
            } else {
                errorToast("Failed to update category. Please try again.");
            }
        }
    }
</script>