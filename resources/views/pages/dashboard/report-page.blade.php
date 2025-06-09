@extends('layout.sidenav')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Sales Report</h4>
                        <label class="form-label mt-2">Date From</label>
                        <input id="FromDate" type="date" class="form-control"/>
                        <label class="form-label mt-2">Date To</label>
                        <input id="ToDate" type="date" class="form-control"/>
                        <button onclick="SalesReport()" class="btn mt-3 bg-gradient-primary">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function SalesReport(){
        let from_date = document.getElementById('FromDate').value;
        let to_date = document.getElementById('ToDate').value;
        
        if(from_date.length ===0 || to_date.length===0){
            errorToast('Please select both dates');
            return;
        }else{
          
            window.open('/salesReport/'+ from_date +'/'+ to_date , '_blank');
        }

       
    }
</script>