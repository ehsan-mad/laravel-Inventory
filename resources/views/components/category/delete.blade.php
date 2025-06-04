<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3 text-warning">Delete !</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID"/>
            </div>
            <div class="modal-footer justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn bg-gradient-success mx-2" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="confirmDelete()" type="button" id="confirmDelete" class="btn bg-gradient-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to set the category ID for deletion (called from list)
    function itemDelete(id) {
        document.getElementById('deleteID').value = id;
    }

    // Function to actually perform the deletion (called from modal button)
    async function confirmDelete() {
        let category_id = document.getElementById('deleteID').value;
        
        if (!category_id) {
            errorToast("No category selected for deletion");
            return;
        }
        
        showLoader();
        
        try {
            const response = await axios.post('/categoryDelete', {
                id: category_id
            }, HeaderToken()); 
            
            hideLoader();
            
            if (response.data.status === "success") {
                successToast(response.data.message);
                
                // ✅ Properly close modal using Bootstrap
                document.getElementById('delete-modal-close').click();
                
                await getList(); // Refresh the category list
                
                // // Clear the deleteID
                // document.getElementById('deleteID').value = '';
                
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
                errorToast("Failed to delete category. Please try again.");
            }
        }
    }
</script>