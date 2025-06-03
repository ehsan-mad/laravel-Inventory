<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>4 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function VerifyOtp() {
        let otp = document.getElementById('otp').value;

        if (otp === "") {
            return errorToast("OTP is required");
        }
        if (otp.length !== 4 || isNaN(otp)) {
            return errorToast("OTP must be 4 digits and numeric");
        }
        showLoader();
        try {
            const response = await axios.post('/verifyotp', { otp: otp ,
                email: sessionStorage.getItem('email') 
            });

            if (response.data.status === "success" && response.status === 200) {
                successToast(response.data.message);
                sessionStorage.clear(); // Clear email from session storage
                setTimeout(() => {
                    window.location.href = '/resetPassword';
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
            } else {
                errorToast("An unexpected error occurred. Please try again.");
            }
        }
    }
</script>
