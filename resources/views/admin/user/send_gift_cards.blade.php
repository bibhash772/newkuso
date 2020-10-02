@extends('layouts.default')
@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <!-- [ breadcrumb ] start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="/admin/home"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="#!">Gift Cards</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [ breadcrumb ] end -->
                        <div class="row">
                            <div class="col-sm-12">
                              <div class="card">
                                <div class="card-header">
                                    <h5>Gift Cards</h5>
                                    <a class="_rightsideBtn" href="javascript:void(0)"><button type="button" class="btn btn-primary" id="_nextBtnSendCards" disabled="">Next</button></a>
                                </div>
                                <div class="card-body">
                                  <div class="col-xl-12 table-responsive">
                                    <table class="table table-striped table-bordered nowrap" id = "_actualGiftCardsListing">
                                      <thead>
                                        <tr>
                                          <th><input type="checkbox" class="input-chk check" id="ckbCheckAll"></th>
                                          <th>Image</th>
                                          <th>Name</th>
                                          <th>Category</th>
                                          <th>Fixed Values</th>
                                          <!-- <th>Denomination Type | Barcode Type</th> -->
                                          <th>Redemption Details</th>
                                          <th>Action</th>
                                        </tr>
                                      </thead>
                                      <tbody> </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--  popup for Sending gift cards start --> 
<div class="modal fade show" id="md-gift-card-popup" tabindex="-1" role="dialog">
  <div class="modal-dialog full-width">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal_headng"> Details</h3>
        <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="s7-close"> X </span></button>
      </div>
      <div class="modal-body">
         <h6><div class="_sendgiftcardFormAlert"></div></h6>
          <div class="row">
            <div class="col-12 col-xl-12">
              <form action="{{route('admin.sendGiftCards-Users')}}" method="post" id="_sendGiftCardToUsersForm" enctype="multipart/form-data">
                <div class="form-group">
                  <div class="form-group">
                    <label class="form_label"><h6>Sender Name</h6> </label>
                    <input class="form-control" id="sender_name" name="sender_name" placeholder="Sender Name" type="text">
                  </div>
                  <input type="hidden" class="selectedgiftcardData" value="" name="giftcarddetails">
                  <div class="form-group">
                    <input class="company_logoinputfile" name="company_logo" id="company_logo" type="file">
                    <label class="btn btn-primary" for="company_logo">Company Logo <i class="icon s7-upload v-align-over"></i><span class="FileControl"> </span> </label>
                  </div>
                  <div class="form-group">
                      <label class="form_label"><h6>Email Address</h6> (Please enter email address with comma seprated).</label>
                      <textarea col="40" rows="8" class="form-control pad_ovr" id="email_address" name="email_address" placeholder="Email Addresses"></textarea>

                  </div>
                  <div class="form-group">
                      <label class="form_label"><h6>Message</h6> </label>
                      <textarea col="40" rows="3" class="form-control pad_ovr" id="message" name="message" placeholder="Message"></textarea>
                  </div>
                    <button class="btn btn-sm btn-primary" id="_sendgiftcardformsBtn">Submit</button>
                    <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<!--  popup for Sending gift cards close -->  
@endsection
@section('footer-script')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
    var giftcardslisting;
    var makeTable  = function (params = null){

      if (giftcardslisting) {
          giftcardslisting.destroy();
      }

      var jsonData          = <?php echo json_encode($giftcards); ?>;
      var MerchantjsonData  = <?php echo json_encode($Merchants); ?>;

      giftcardslisting = $('#_actualGiftCardsListing').DataTable({
          "searchPlaceholder": "search",
          "data": jsonData,
          "sScrollX": true,
          "drawCallback" : function () {
              $('[data-toggle="tooltip"]').tooltip({
                  container: 'body'
              });
          },
          "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              $(nRow).attr("id",'th_' + aData['id']);
              return nRow;
          },
          "columns": [
                    {"data": "ObjectID","orderable" : false},
                    {"data": "DefaultFaceplateUrl","orderable" : false},
                    {"data": "MerchantCudosId","orderable" : false},
                    {"data": "MerchantCudosId","orderable" : false},
                    {"data": "FixedValues","orderable" : false},
                    /*{"data": "DenominationType","orderable" : false},*/
                    {"data": "RedemptionDetails","orderable" : false},
                    {"data": "ObjectID","orderable" : false,className: "list_icon",}
          ],
          "columnDefs": [
              {
                        "targets" : 0,
                        "render" : function (data , type, full, meta){

                          var giftname = '@';
                          var giftcategory = '@';
                          if(data){
                            let giftcardDetails = MerchantjsonData.find(o => o.CudosID === full.MerchantCudosId);

                            if(giftcardDetails === undefined){
                              giftcategory  =  'N/A';
                              giftname      =  'N/A';
                            }else{
                              giftcategory  =  giftcardDetails.CategoryCudosID;
                              giftname      =  giftcardDetails.Name;
                            }
                          }

                        var giftdatastring = data+'%-%'+full.DefaultFaceplateUrl+'%#%'+giftname+'%*%'+giftcategory;

                        let html =
                            '<div class="select_allChk"><label><input type="checkbox" class="checkBoxClass check" name="selectedcards" value="'+giftdatastring+'"></label></div>';
                            return html;
                        }
              },
              {
                  "targets" : 1,
                  "render" : function (data , type, full, meta){
                      return '<img src="'+data+'" class="_smallgiftimg">';
                  }
              },
              /*{
                  "targets" : 4,
                  "render" : function (data , type, full, meta){

                      var html = data+" | "+ (full.BarcodeType ? full.BarcodeType : 'N/A');
                      return html;
                  }
              },*/
              {
                  "targets" : 2,
                  "render" : function (data , type, full, meta){

                      if(data){
                        let giftcardDetails = MerchantjsonData.find(o => o.CudosID === data);

                        if(giftcardDetails === undefined){
                          return 'N/A';
                        }else{
                          return giftcardDetails.Name;
                        }
                      }else{
                        return 'N/A';
                      }
                  }
              },
              {
                  "targets" : 3,
                  "render" : function (data , type, full, meta){

                      if(data){
                        let giftcardDetails = MerchantjsonData.find(o => o.CudosID === data);

                        if(giftcardDetails === undefined){
                          return 'N/A';
                        }else{
                          return giftcardDetails.CategoryCudosID;
                        }
                      }else{
                        return 'N/A';
                      }
                  }
              },

              {
                  "targets" : 5,
                  "render" : function (data , type, full, meta){
                      if(data.length > 100){
                          var trimmedString = data.substr(0, 100);
                          return trimmedString = trimmedString.substr(0, Math.min(100, trimmedString.lastIndexOf(" ")))
                      }else{
                        return data;
                      }
                  }
              },
              {
                  "targets" : -1,
                  "render" : function (data , type, full, meta){
                      var html = "<a title='edit' href='/admin/user-edit/"+full.user_id+"'><i class='fas fa-trash '></i></a>";
                      return html;
                  }
              },
          ],
          buttons: [
          ],
          "dom": 'Blfrtip',
          "processing": true,
          "paging": true,
          "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          "pagingType": "full_numbers",
          "pageLength": 10,
      });
      $(".dataTables_filter input").attr("placeholder", "Search...");
    }

    makeTable();
    /* Action offer request start */
  });

  $(document).ready(function () {
      $("#ckbCheckAll").click(function () {
          $(".checkBoxClass").prop('checked', $(this).prop('checked'));
          $('#_nextBtnSendCards').attr('disabled', !$(this).prop('checked'));
      });
      
      $(".checkBoxClass").change(function(){
          if (!$(this).prop("checked")){
              $("#ckbCheckAll").prop("checked",false);
          }else{
            $('#_nextBtnSendCards').attr('disabled', !$(this).prop('checked'));
          }
      });

      $("#_nextBtnSendCards").click(function(event){
        event.preventDefault();
        var clickedcardsId = $("#_actualGiftCardsListing .checkBoxClass:checked").map(function(){
          return $(this).val();
        }).get();

        if(clickedcardsId.length > 0){
            $("#md-gift-card-popup").find(".selectedgiftcardData").val(clickedcardsId);
            $("#md-gift-card-popup").modal('show');
        }else{
          swal.fire("Warning","Select one card atleast.", 'warning')
        }
      });

    $('#_sendGiftCardToUsersForm').validate({
          ignore: '.ignore, .select2-input',
          focusInvalid: false,
          rules: {
              'sender_name': {
                  required: true,
              },
              'company_logo': {
                  required: true,
              },
              'email_address': {
                  required: true,
              },
          },

          errorPlacement: function errorPlacement(error, element) {
              var $parent = $(element).parent('.form-group');

              if ($parent.find('.jquery-validation-error').length) {
                  return;
              }

              $parent.append(
                  error.addClass('jquery-validation-error small form-text invalid-feedback')
              );
          },
          highlight: function(element) {
              var $el     = $(element);
              var $parent = $el.parent('.form-group');

              $el.addClass('is-invalid');
              if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                  $el.parent().addClass('is-invalid');
              }
          },
          unhighlight: function(element) {
              $(element).parent('.form-group').find('.is-invalid').removeClass('is-invalid');
          },
          submitHandler: function(form) {

              $.ajax({
                  url:"{{route('admin.sendGiftCards-Users')}}",
                  type: "POST",
                  data: new FormData(form),
                  processData: false,
                  contentType:false,
                  beforeSend: function(){
                     $("#_sendgiftcardformsBtn").html("Processing....").prop("disabled", true);
                  },
                  success:function(res){
                      $(document).find("._sendgiftcardFormAlert").html('');
                      $("#_sendgiftcardformsBtn").html("Submit").removeAttr("disabled");
                      if(res.status == true){
                          $("#md-gift-card-popup").modal('hide');
                          swal.fire("Success","Cards sent successfully.", 'success')
                      }else{
                          $(document).find("._sendgiftcardFormAlert").html('<div class="alert alert-danger alert-dismissible fade show">'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
                      }
                  }
              });
          }
    });

  }); 



  $(document.body).on('click','.ustatus',function(){
      var text    = $(this).html();
      var uid     = $(this).attr('data-id');
      var data    = 'id='+uid+'&text='+text; 
      var url     = '/api/userstatus';
      var button  = $(this);
      if(text && uid){
        $.ajax({
          url: url,
          type: 'get',
          data: data,
          dataType: 'json',
          success:function(response){
            $(button).text(response['user_status']);
            $(button).attr('class',response['status_class']);
          },
        });
      }
  });
</script>
@endsection
