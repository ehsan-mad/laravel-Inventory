<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email"/>
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
                                <button onclick="onRegistration()" class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function onRegistration(){
        let email = document.getElementById('email').value;
        let firstName= document.getElementById('firstName').value;
        let lastName= document.getElementById('lastName').value;
        let mobile= document.getElementById('mobile').value;
        let password= document.getElementById('password').value;

        if(email === ""){
            return errorToast("Email is required");
        }else if(firstName === ""){
            return errorToast("First Name is required");
        }else if(lastName === ""){
            return errorToast("Last Name is required");
        }else if(mobile === ""){
            return errorToast("Mobile Number is required");
        }else if(password === ""){
            return errorToast("Password is required");
        }else{
            showLoader();
            try{
                let response = await axios.post('/user-Registration', {
                    email: email,
                    first_name: firstName,
                    last_name: lastName,
                    mobile: mobile,
                    password: password
                });
                hideLoader();
                if(response.data.status === "success" && response.status === 200){
                    successToast(response.data.message);
                    setTimeout(() => {
                        window.location.href = '/userLogin';
                    }, 1000);
                }else{
                    errorToast(response.data.message);
                }
            }catch(e){
                hideLoader();
                if(e.response && e.response.data.status===422 ){
                    let error= e.response.data.errors;
                    for (let field in error){
                        errorToast(error[field][0]);
                    }
                }else{
                    errorToast("An error occurred during registration. Please try again.");
            }
            }
        }
    }
</script>
