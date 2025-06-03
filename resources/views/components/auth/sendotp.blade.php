<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br/>
                    <label>Your email address</label>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <button onclick="VerifyEmail()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function VerifyEmail() {
        let email = document.getElementById('email').value;

        if (email === "") {
            return errorToast("Email is required");
        }
        showLoader();
        try {
            const response = await axios.post('/sendotp', { email: email });

            if (response.data.status === "success" && response.status === 200) {
                successToast(response.data.message);
                sessionStorage.setItem('email', email);
                setTimeout(() => {
                    window.location.href = '/verifyOtp';
                }, 1000);
            } else {
                errorToast(response.data.message);
            }
        } catch (error) {
            hideLoader();
            if (error.response && error.response.status === 422) {
                let errors = error.response.data.errors;
                // Display each error message   

                errorToast(errors);


            }
            else {

                errorToast("An unexpected error occurred. Please try again.");
            }
        }
    }


</script>
