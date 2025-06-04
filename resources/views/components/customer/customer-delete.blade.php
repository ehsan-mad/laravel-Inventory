<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class=" mt-3 text-warning">Delete !</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID"/>

            </div>
            <div class="modal-footer justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn mx-2 bg-gradient-primary" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="confirmDelete()" type="button" id="confirmDelete" class="btn  bg-gradient-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to set the customer ID for deletion (called from list)
    function itemDelete(id) {
        document.getElementById('deleteID').value = id;
    }

    // Function to actually perform the deletion (called from modal button)
    async function confirmDelete() {
        let customer_id = document.getElementById('deleteID').value;

        if (!customer_id) {
            errorToast("No customer selected for deletion");
            return;
        }

        showLoader();

        try {
            const response = await axios.post('/customerDelete', {
                id: customer_id
            }, HeaderToken());


            if (response.data.status === "success") {
                successToast(response.data.message);

                // Properly close modal using Bootstrap
                document.getElementById('delete-modal-close').click();
                document.getElementById('deleteID').value = ''; // Clear the deleteID
                setTimeout(() => {
        window.location.reload();
    }, 1500);


            } else {
                errorToast(response.data.message);
            }

        } catch (error) {
            hideLoader();
            errorToast("Failed to delete customer");
        }
    }
</script>
