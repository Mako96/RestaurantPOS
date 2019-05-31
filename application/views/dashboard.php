<!doctype html>
<html>
  <head>

    <!-- load MUI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- <script src="//cdn.muicss.com/mui-0.9.41/js/mui.min.js"></script> -->
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <title>Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  </head>
  <body>
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"  >
    <!-- Content Header (Page header) -->
    <section class="content">
    <?php if($is_admin == true): ?>
      <h1>
        Dashboard
        <small>Admin panel</small>
      </h1>
        <br><br><br>
        <!-- Small boxes (Stat box) -->
      

<div class="row">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua mui--z3">
      <div class="inner">
        <h3><?php echo $total_products ?></h3>

        <p>Total Products</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
      <a href="<?php echo base_url('products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green mui--z3">
      <div class="inner">
        <h3><?php echo $total_paid_orders ?></h3>

        <p>Total Paid Orders</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <a href="<?php echo base_url('reports') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow mui--z3">
      <div class="inner">
        <h3><?php echo $total_users; ?></h3>

        <p>Total Users</p>
      </div>
      <div class="icon">
        <i class="ion ion-android-people"></i>
      </div>
      <a href="<?php echo base_url('users/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red mui--z3">
      <div class="inner">
        <h3><?php echo $total_stores ?></h3>

        <p>Total Stores</p>
      </div>
      <div class="icon">
        <i class="ion ion-android-home"></i>
      </div>
      <a href="<?php echo base_url('stores/') ?>" class="small-box-footer">More info <i class="ion ion-android-home"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<!-- /.row -->
</section>
<?php endif; ?>
      <section class="card-panel">
      <h1>
      Manage
      <small>Orders</small>
    </h1>
    <br><br>
    <!-- Small boxes (Stat box) -->

    <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-purple mui--z3">
      <div class="inner">
        <h5>Take New Order</h5>
      </div>
      <?php if(in_array('createOrder', $user_permission)): ?>
      <div class="left-align">
      <a href="<?php echo base_url('orders/create') ?>" class="btn-floating btn-large deep-purple accent-1 pulse left-align" style="position: absolute; top: -30px;  right: 10px; z-index: 0;">  <i class="material-icons">add</i></a>
      </div>
      <?php endif; ?>
    </div>
  </div>

    <div class="row">
      <div class="col-md-12 col-xs-12">
        
        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible mui-app-bar" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('errors')): ?>
          <div class="alert alert-error alert-dismissible mui-app-bar" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('errors'); ?>
          </div>
        <?php endif; ?>

       

        <div class="box">
          <div class="box-header">
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th style="width:14.286%">Date Time</th>
                <th style="width:14.286%">Bill no</th>
                <th style="width:14.286%">Store</th>
                <th style="width:14.286%">Total Products</th>
                <th style="width:14.286%">Total Amount</th>
                <th style="width:14.286%">Paid status</th>
                <?php if(in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
                  <th style="width:14.286%">Action</th>
                <?php endif; ?>
              </tr>
              </thead>

            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php if(in_array('deleteOrder', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Order</h4>
      </div>

      <form role="form" action="<?php echo base_url('orders/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>
  </section>

  <!-- Main content -->
  



<script type="text/javascript">
var manageTable;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  // $("#OrderMainNav").addClass('active');
  // $("#manageOrderSubMenu").addClass('active');

  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': base_url + 'orders/fetchOrdersData',
    order : [],
    rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
  });

});

// remove functions 
function removeFunc(id)
{
  if(id) {
    $("#removeForm").on('submit', function() {

      var form = $(this);

      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: { order_id:id }, 
        dataType: 'json',
        success:function(response) {

          manageTable.ajax.reload(null, false); 

          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#removeModal").modal('hide');

          } else {

            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>'); 
          }
        }
      }); 

      return false;
    });
  }
}


</script>
      
    <!-- /.content -->
    <div class="mui-container">
  <!-- Content Header (Page header) -->
  

  </div>
  <!-- /.content-wrapper -->
  </body>
</html>
  

  <script type="text/javascript">
    $(document).ready(function() {
      $("#dashboardMainMenu").addClass('active');
    });
  </script>
