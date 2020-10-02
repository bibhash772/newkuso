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
                                        <a class="_rightsideBtn" href="{{route('admin.send-gift-cards')}}"><button type="button" class="btn btn-primary">Send Gift Card</button></a>
                                    </div>
                                    <div class="card-body">
                                      <div class="col-xl-12 table-responsive">
                                      <table class="table table-striped table-bordered nowrap" id = "offers-table">
                                          <thead>
                                            <tr>
                                              <th>Sender Name</th>
                                              <th>Company Logo</th>
                                              <th>User Email</th>
                                              <!-- <th>Phone Number</th>
                                              <th>City</th> -->
                                              <!-- <th>Status</th>
                                              <th>Created At</th> -->
                                              <th>Sent On</th>
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
        <h3 class="modal_headng">View Details</h3>
        <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="s7-close"> X </span></button>
      </div>
      <div class="modal-body">
         <h6><div class="_sendgiftcardFormAlert"></div></h6>
          <div class="row">
            <div class="col-12 col-xl-12">
                <div class="form-group">
                  <div class="form-group">
                    <label class="form_label"><h6>Sender Name : </h6> </label>
                    <span class="_sendername"></span>
                  </div>
                  <div class="form-group">
                    <label class="form_label"><h6>Company Logo : </h6> </label>
                    <span class="_companylogo"></span>
                  </div>
                  <div class="form-group">
                    <label class="form_label"><h6>User Email : </h6> </label>
                    <span class="_useremail"></span>
                  </div>
            </div>
          </div>
        </div>
        <div class="row pd_of">
            <div class="col-12 col-xl-12">
                <div class="pd_ovrflw">
                    <table class="table table-stripped table-hover" width="100%" id="TermsList">
                        <thead>
                            <tr>
                                <th width="20%"><strong>Image</strong></th>
                                <th width="60%" ><strong>Name</strong></th>
                                <th width="20%"><strong>Category<strong></th>
                            </tr>
                        </thead>
                        <tbody class="_giftcardDetailsTbody">
                        </tbody>
                    </table>
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
  $(document).ready(function(){
    var offerTable;
    
    var makeTable  = function (params = null){

      if (offerTable) {
            offerTable.destroy();
      }

      var url = "/api/userlist";
      if (params != null) {
          url += url.indexOf('?') == -1 ? '?'+params : '&'+params;
      }

      offerTable = $('#offers-table').DataTable({
          responsive: true,
          "searchPlaceholder": "search",
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

                    {"data": "sender_name","orderable" : false},
                    {"data": "company_logo","orderable" : false},
                    {"data": "email","orderable" : false},
                    {"data": "created_at","orderable" : false},
                    /*{"data": "phone_no","orderable" : true},
                    {"data": "city","orderable" : true},
                    {"data": "status","orderable" : true},
                    {"data": "created_at","orderable" : true},*/
                    { "data": "id","orderable" : false,className: "list_icon",}
          ],
          "columnDefs": [
              {
                  "targets" : 1,
                  "render" : function (data , type, full, meta){
                      var html = "<img src='"+data+"' class='_usergiftlistngtableimg'>";
                      return html;
                  }
              },
              {
                  "targets" : -1,
                  "render" : function (data , type, full, meta){
                      var html = "<a title='edit' href='#' data-id='"+JSON.stringify(full.details_json)+"' data-sender='"+full.sender_name+"' data-company = '"+full.company_logo+"' data-email = '"+full.email+"'><i class='fas fa-eye _viewgiftcardDetails'></i></a>";
                      return html;
                  }
              },
          ],
          buttons: [
          ],
          "dom": 'Blfrtip',
          "processing": true,
          "serverSide": true,
          "paging": true,
          "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          "pagingType": "full_numbers",
          "pageLength": 50,
          "ajax": url
      });
      offerTable.buttons().container().appendTo('#exportListOffers');
      $(".dataTables_filter input").attr("placeholder", "Search...");
    }

    makeTable();
    /* Action offer request start */
  });
  $(document.body).on('click','.ustatus',function(){
      var text = $(this).html();
      var uid = $(this).attr('data-id');
      var data = 'id='+uid+'&text='+text; 
      var url = '/api/userstatus';
      var button = $(this);
      if(text && uid)
      {
        $.ajax({
        url: url,
        type: 'get',
        data: data,
        dataType: 'json',
        success:function(response)
        {
          $(button).text(response['user_status']);
          $(button).attr('class',response['status_class']);
        },
        });
      }
  });

  $(document).on("click", "._viewgiftcardDetails", function(){
    var json_details    = JSON.parse($(this).parent('a').attr("data-id"));
    var sender_name     = $(this).parent('a').attr("data-sender");
    var email           = $(this).parent('a').attr("data-email");
    var company_logo    = $(this).parent('a').attr("data-company");

    $("#md-gift-card-popup").find("._sendername").html(sender_name);
    $("#md-gift-card-popup").find("._companylogo").html('<img src="'+company_logo+'" class="_companylogopopup">');
    $("#md-gift-card-popup").find("._useremail").html(email);
    var htmldata        = '';
    if(json_details){
      for(var i= 0; i < json_details.length; i++){
        htmldata += '<tr> <td width="60%"><img src="'+json_details[i].img+'" class="_companylogopopup"></td> <td width="60%">'+json_details[i].name+'</td> <td width="20%"> '+json_details[i].category+'</td> </tr>';
      }
    }

    $("#md-gift-card-popup").find("._giftcardDetailsTbody").html(htmldata);
    $("#md-gift-card-popup").modal('show');
  })
</script>
@endsection
