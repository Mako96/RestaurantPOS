<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Stores</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Stores</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if (in_array('createStore', $user_permission)) : ?>
          <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Store</button>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Stores</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Store name</th>
                  <th>Location</th>
                  <th>Store Type</th>
                  <th>GST Number</th>
                  <th>Status</th>
                  <?php if (in_array('updateStore', $user_permission) || in_array('deleteStore', $user_permission)) : ?>
                    <th>Action</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>

              </tbody>
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

<?php if (in_array('createStore', $user_permission)) : ?>
  <!-- create brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Store</h4>
        </div>

        <form role="form" action="<?php echo base_url('stores/create') ?>" method="post" id="createForm">

          <div class="modal-body">

            <div class="form-group">
              <label for="brand_name">Store Name</label>
              <input type="text" class="form-control" id="store_name" name="store_name" placeholder="Enter store name" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="brand_location">Store Location</label>
              <input type="text" class="form-control" id="store_location" name="store_location" placeholder="Enter store location" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="store_type">Store Type</label>
              <select class="form-control" id="store_type" name="store_type">
                <option value="COCM">COCM</option>
                <option value="COFM">COFM</option>
                <option value="FOCM">FOCM</option>
                <option value="FOFM">FOFM</option>
              </select>
            </div>
            <div class="form-group">
              <label for="brand_gst">GST Number</label>
              <input type="text" class="form-control" id="store_gst" name="store_gst" placeholder="Enter GST Number" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="active">Status</label>
              <select class="form-control" id="active" name="active">
                <option value="1">Active</option>
                <option value="2">Inactive</option>
              </select>
            </div>
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

<?php if (in_array('updateStore', $user_permission)) : ?>
  <!-- edit brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit Store</h4>
        </div>

        <form role="form" action="<?php echo base_url('stores/update') ?>" method="post" id="updateForm">

          <div class="modal-body">
            <div id="messages"></div>

            <div class="form-group">
              <label for="brand_name">Store Name</label>
              <input type="text" class="form-control" id="edit_store_name" name="edit_store_name" placeholder="Enter store name" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="brand_location">Store Location</label>
              <input type="text" class="form-control" id="edit_store_location" name="edit_store_location" placeholder="Enter store location" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="store_type">Store Type</label>
              <select class="form-control" id="edit_store_type" name="edit_store_type">
                <option value="COCM">COCM</option>
                <option value="COFM">COFM</option>
                <option value="FOCM">FOCM</option>
                <option value="FOFM">FOFM</option>
              </select>
            </div>
            <div class="form-group">
              <label for="brand_gst">GST Number</label>
              <input type="text" class="form-control" id="edit_store_gst" name="edit_store_gst" placeholder="Enter GST Number" autocomplete="off">
            </div>
            <div class="form-group">
              <label for="active">Status</label>
              <select class="form-control" id="edit_active" name="edit_active">
                <option value="1">Active</option>
                <option value="2">Inactive</option>
              </select>
            </div>
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

<?php if (in_array('deleteStore', $user_permission)) : ?>
  <!-- remove brand modal -->
  <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Remove Store</h4>
        </div>

        <form role="form" action="<?php echo base_url('stores/remove') ?>" method="post" id="removeForm">
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


<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
  var manageTable;
  var base_url = "<?php echo base_url(); ?>";


  $(document).ready(function() {
    $('#storesMainNav').addClass('active');
    // initialize the datatable 
    manageTable = $('#manageTable').DataTable({
      'ajax': base_url + 'stores/fetchCategoryData',
      'order': [],
      responsive: true
    });

    // submit the create from 
    $("#createForm").unbind('submit').on('submit', function() {
      var form = $(this);

      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(), // /converting the form data into array and sending it to server
        dataType: 'json',
        success: function(response) {

          manageTable.ajax.reload(null, false);

          if (response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
              '</div>');


            // hide the modal
            $("#addModal").modal('hide');

            // reset the form
            $("#createForm")[0].reset();
            $("#createForm .form-group").removeClass('has-error').removeClass('has-success');

          } else {

            if (response.messages instanceof Object) {
              $.each(response.messages, function(index, value) {
                var id = $("#" + index);

                id.closest('.form-group')
                  .removeClass('has-error')
                  .removeClass('has-success')
                  .addClass(value.length > 0 ? 'has-error' : 'has-success');

                id.after(value);

              });
            } else {
              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        }
      });

      return false;
    });

  });

  // edit function
  function editFunc(id) {
    $.ajax({
      url: base_url + 'stores/fetchStoresDataById/' + id,
      type: 'post',
      dataType: 'json',
      success: function(response) {

        $("#edit_store_name").val(response.name);
        $("#edit_store_location").val(response.location);
        $("#edit_store_type").val(response.store_type);
        $("#edit_store_gst").val(response.gst_number);
        $("#edit_active").val(response.active);

        // submit the edit from 
        $("#updateForm").unbind('submit').bind('submit', function() {
          var form = $(this);

          // remove the text-danger
          $(".text-danger").remove();

          $.ajax({
            url: form.attr('action') + '/' + id,
            type: form.attr('method'),
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(response) {

              manageTable.ajax.reload(null, false);

              if (response.success === true) {
                $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                  '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                  '</div>');

                // hide the modal
                $("#editModal").modal('hide');
                // reset the form 
                $("#updateForm .form-group").removeClass('has-error').removeClass('has-success');

              } else {

                if (response.messages instanceof Object) {
                  $.each(response.messages, function(index, value) {
                    var id = $("#" + index);

                    id.closest('.form-group')
                      .removeClass('has-error')
                      .removeClass('has-success')
                      .addClass(value.length > 0 ? 'has-error' : 'has-success');

                    id.after(value);

                  });
                } else {
                  $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                    '</div>');
                }
              }
            }
          });

          return false;
        });

      }
    });
  }

  // remove functions 
  function removeFunc(id) {
    if (id) {
      $("#removeForm").on('submit', function() {

        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: {
            store_id: id
          },
          dataType: 'json',
          success: function(response) {

            manageTable.ajax.reload(null, false);
            // hide the modal
            $("#removeModal").modal('hide');

            if (response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>' + response.messages +
                '</div>');



            } else {

              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>' + response.messages +
                '</div>');
            }
          }
        });

        return false;
      });
    }
  }
</script>