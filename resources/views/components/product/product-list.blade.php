<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Product</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0  bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-dark "/>
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>Sl</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tableList">

                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
    let table; // Global variable to store DataTable instance

    window.getList=async function (){
         try {
            
              showLoader();
              let response = await axios.get('/productList', HeaderToken());
              hideLoader();
            
              if(response.data.status === "success"){
                let data = response.data.data;
                
                let tableList = document.getElementById('tableList');

// 🔥 Clear and destroy old DataTable FIRST
                 if ($.fn.DataTable.isDataTable ('#tableData')) {
                 $('#tableData').DataTable().clear().destroy();
                    }

            tableList.innerHTML = ''; // Clear table body

                data.forEach((item, index) => {
                     let row = `<tr>
                     <td>${index+1}</td>
                        <td><img src="${item.img_url}" alt="Product Image" style="width: 50px; height: 50px;"></td>
                     <td>${item.name}</td>
                     <td>${item.price}</td>
                     <td>${item.unit}</td>
                     <td>
                     <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#update-modal" onclick="editProduct(${item.id})">Edit</button>
                     <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-modal" onclick="itemDelete(${item.id})">Delete</button>
                     </td>
                     </tr>`;
                     tableList.innerHTML += row;
                });

                

                // Initialize DataTable
                if ($.fn.DataTable.isDataTable('#tableData')) {
                     $('#tableData').DataTable().destroy();
                }
                table = $('#tableData').DataTable({
                     "order": [[0, "asc"]],
                     "pageLength": 10,
                });

                

            } else {
   
                errorToast(response.data.message);
            }

         } catch (error) {
              hideLoader();
              
              errorToast("Failed to fetch product list");
         }
    }

    // Function to edit a product
 
    getList(); // Initial call to populate the list

</script>
