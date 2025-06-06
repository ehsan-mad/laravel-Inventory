<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function getProfile(){
        try {
            showLoader();
            let response = await axios.get('/Profile', HeaderToken());
            hideLoader();
            console.log(response.data);
            if (response.data.status === "success") {
                let data = response.data.data;
                document.getElementById('email').value = data.email;
                document.getElementById('firstName').value = data.first_name;
                document.getElementById('lastName').value = data.last_name;
                document.getElementById('mobile').value = data.mobile;
                document.getElementById('password').value = data.password;
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            errorToast("Failed to fetch profile");
        }
    }
    getProfile();

   async function onUpdate() {
    try {
        showLoader();
        
        let first_name = document.getElementById('firstName').value;
        let last_name = document.getElementById('lastName').value;
        let mobile = document.getElementById('mobile').value;
        let password = document.getElementById('password').value;
        let email = document.getElementById('email').value;
        
        // ✅ Debug: Log the values being sent
        console.log('Data being sent:', {
            first_name,
            last_name,
            mobile,
            password,
            email
        });
        
        if (!first_name || !last_name || !mobile || !password) {
            errorToast("Please fill all fields");
            hideLoader();
            return;
        }
        
        let data = new FormData();
        data.append('first_name', first_name  );
        data.append('last_name', last_name);
        data.append('mobile', mobile);
        data.append('password', password);
        data.append('email', email);

        // ✅ Debug: Log FormData content
        for (let [key, value] of data.entries()) {
            console.log(key, value);
        }

        let response = await axios.post('/updateProfile', data, HeaderToken());
        hideLoader();
        
        if (response.data.status === "success") {
            successToast(response.data.message);
            getProfile();
        } else {
            errorToast(response.data.message);
        }
    } catch (error) {
        hideLoader();
        console.error('Full error object:', error);
        console.error('Error response:', error.response);
        console.error('Error response data:', error.response?.data);
        
        if (error.response && error.response.data) {
            errorToast(JSON.stringify(error.response.data));
        } else {
            errorToast("Failed to update profile");
        }
    }
}
</script>
