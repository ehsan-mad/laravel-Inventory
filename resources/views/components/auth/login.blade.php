<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
            <div class="card w-90  p-4">
                <div class="card-body">
                    <h4>SIGN IN</h4>
                    <br/>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <input id="password" placeholder="User Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="SubmitLogin()" class="btn w-100 bg-gradient-primary">Next</button>
                    <hr/>
                    <div class="float-end mt-3">
                        <span>
                            <a class="text-center ms-3 h6" href="{{url('/userRegistration')}}">Sign Up </a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{url('/sendOtp')}}">Forget Password</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function SubmitLogin() {
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;

        if (email === "") {
            return errorToast("Email is required");
        } else if (password === "") {
            return errorToast("Password is required");
        }
showLoader();
        try {

            const response = await axios.post('/user-Login', {
                email: email,
                password: password
            });

            if (response.data.status === "success" && response.status === 200) {
                successToast(response.data.message);
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            } else {
                hideLoader();
                errorToast(response.data.message);
            }
        } catch (error) {
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
</script>
