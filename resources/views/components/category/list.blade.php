<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Category</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-secondary"/>
            <div class="table-responsive">
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>No</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tableList">
                    <!-- <tr>
                        <td>1</td>
                        <td>Category 1</td>
                        <td>
                    <button data-bs-toggle="modal" data-bs-target="#update-modal">Edit</button>
                     <button data-bs-toggle="modal" data-bs-target="#delete-modal">Delete</button>
                    </td>

                    </tr> -->
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    let table; // Global variable to store DataTable instance
    
    getList();
    
    async function getList(){
        try {
            showLoader();
            let response = await axios.get('/category-list', HeaderToken()); // ✅ Add auth header
            hideLoader();
            
            if(response.data.status === "success"){
                let data = response.data.data;
                let tableList = document.getElementById('tableList');
                tableList.innerHTML = '';
                
                data.forEach((item, index) => {
                    let row = `<tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#update-modal" onclick="editCategory(${item.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-modal" onclick="itemDelete(${item.id})">Delete</button>
                        </td>
                    </tr>`;
                    tableList.innerHTML += row;
                });
                
                // ✅ Destroy existing DataTable before creating new one
                if (table) {
                    table.destroy();
                }
                
                // ✅ Create new DataTable and store reference
                table = new DataTable('#tableData', {
                    "pageLength": 10,
                    "searching": true,
                    "lengthChange": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true
                });
                
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            errorToast("Failed to load categories");
        }
    }
</script>