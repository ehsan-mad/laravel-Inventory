<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <label>New Password</label>
                    <input id="password" placeholder="New Password" class="form-control" type="password"/>
                    <br/>
                    <label>Confirm Password</label>
                    <input id="cpassword" placeholder="Confirm Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="ResetPass()" class="btn w-100 bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function ResetPass() {
        let password = document.getElementById('password').value;
        let cpassword = document.getElementById('cpassword').value;

        if (password === "") {
            return errorToast("Password is required");
        } else if (cpassword === "") {
            return errorToast("Confirm Password is required");
        } else if (password !== cpassword) {
            return errorToast("Passwords do not match");
        }

        showLoader();
        try {
            const response = await axios.post('/reset-Password', { password: password ,
                password_confirmation: cpassword
            });

            if (response.data.status === "success" && response.status === 200) {
                successToast(response.data.message);
                setTimeout(() => {
                    window.location.href = '/userLogin';
                }, 1000);
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            if (error.response && error.response.status === 422) {
                let errors = error.response.data.message;
                // Display each error message
                errorToast(errors);
            } else {
                errorToast("An unexpected error occurred. Please try again.");
            }
        }
    }
</script>
