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
                    <button type="button" id="delete-modal-close" class="btn bg-gradient-success mx-2" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="Delete()" type="button" id="confirmDelete" class="btn bg-gradient-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function itemDelete(id){
        document.getElementById('deleteID').value = id;

    }
    async function Delete() {
        try {
            let id = document.getElementById('deleteID').value;
            showLoader();
            
            let response = await axios.post(`/productDelete`,   {id : id}, HeaderToken());
            hideLoader();

            if (response.data.status === "success") {
                successToast(response.data.message);
                getList(); // Refresh the list after deletion
                document.getElementById('delete-modal-close').click();
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            errorToast("Something went wrong!");
        }
    }
</script>
