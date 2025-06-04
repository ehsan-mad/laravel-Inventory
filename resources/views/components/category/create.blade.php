<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Create Category</h6>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryName">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryInput = document.getElementById('categoryName');
        
        // Listen for Enter key press in the input field
        categoryInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                Save(); // Call the Save function
            }
        });
    });

    async function Save(){
        let category = document.getElementById('categoryName').value;
        
        if(category === ""){
            return errorToast("Category name is required");
        }
      
        try {
            showLoader();

            let response = await axios.post('/categoryCreate', {
                name: category,
            }, HeaderToken());  // ← Add this for authentication
        
            hideLoader();
            
            if(response.data.status === "success"){
                successToast(response.data.message);
                document.getElementById('save-form').reset();
                document.getElementById('modal-close').click();
                await getList(); // ← Add await to ensure it completes
            } else {
                errorToast(response.data.message);
            }
            
        } catch (error) {
            hideLoader();
            
            if (error.response && error.response.status === 422) {
                // Handle validation errors
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
                errorToast("Failed to create category. Please try again.");
            }
        }
    }
</script>
