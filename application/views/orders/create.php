    <!doctype html>
    <html>

    <head>
      <meta charset="utf-8">
      <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">-->
      <link href="//cdn.muicss.com/mui-0.9.41/css/mui.min.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

      <style>
        .no-space {
          margin: 0px !important;
          padding: 0px !important;
        }
      </style>
    </head>

    <body>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="container-fluid">
          <h1>
            Manage Orders
            <small>New Order</small>
          </h1>
          <!-- Main content -->
          <section class="content no-space">

            <!-- Small boxes (Stat box) -->
            <div class="row">
              <div class="col-md-12 col-xs-12 no-space">
                <div id="messages">
                </div>

                <?php if ($this->session->flashdata('success')) : ?>
                  <div class="alert alert-success alert-dismissible mui-app-bar" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('success'); ?>
                  </div>
                <?php elseif ($this->session->flashdata('errors')) : ?>
                  <div class="alert alert-error alert-dismissible mui-app-bar" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('errors'); ?>
                  </div>
                <?php endif; ?>

                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Take Order</h3>
                    <!-- /.box-header -->
                  </div>
                  <form action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal" id="createOrderForm">

                    <input type="hidden" id='payment_type' name="payment_type" type='text'>
                    <input type="hidden" id='customerNumberSubmit' name="customerNumberSubmit" type='text'>
                    <div class="box-body">

                      <?php echo validation_errors(); ?>
                      <div class="form-group">
                        <label for="gross_amount" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                      </div>
                      <div class="form-group">
                        <label for="gross_amount" class="col-sm-12 control-label">Time: <?php echo date('h:i a') ?></label>
                      </div>

                      <br><br><br>

                      <div class="col-md-4 col-xs-12 pull pull-left">
                        <div class="form-group">
                          <div row>

                            <p>
                              <label>
                                <input id="healthy_cb" type="checkbox" class="filled-in order-check" />
                                <span>Healthy</span>
                              </label>
                            </p>

                            <p>
                              <label>
                                <input id="veg_cb" type="checkbox" class="filled-in order-check" />
                                <span>Vegetarian</span>
                              </label>
                            </p>
                          </div>
                        </div>
                      </div>


                      <br /> <br />
                      <table class="table table-bordered table-striped table-responsive no-wrap display" id="product_info_table" style="width:100%">
                        <thead>
                          <tr>
                            <th style="width:60%">Product</th>
                            <th style="width:20%">Quantity</th>
                            <!-- <th style="width:10%">Rate</th> -->
                            <!-- <th >Amount</th> -->
                            <th style="width:10%">Select</th>
                          </tr>
                        </thead>

                      </table>

                      <br /> <br />

                      <div class="col-md-6 col-xs-12 pull pull-right">

                        <div class="form-group">
                          <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                            <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                          </div>
                        </div>
                        <?php if ($is_service_enabled == true) : ?>
                          <div class="form-group">
                            <label for="service_charge" class="col-sm-5 control-label">S-Charge <?php echo $company_data['service_charge_value'] ?> %</label>
                            <div class="col-sm-7">
                              <input type="text" class="form-control" id="service_charge" name="service_charge" disabled autocomplete="off">
                              <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" autocomplete="off">
                            </div>
                          </div>
                        <?php endif; ?>
                        <?php if ($is_vat_enabled == true) : ?>
                          <div class="form-group">
                            <label for="vat_charge" class="col-sm-5 control-label">GST <?php echo $company_data['vat_charge_value'] ?> %</label>
                            <div class="col-sm-7">
                              <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled autocomplete="off">
                              <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" autocomplete="off">
                            </div>
                          </div>
                        <?php endif; ?>
                        <div class="form-group">
                          <label for="discount" class="col-sm-5 control-label">Discount</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" onkeyup="getTotalBill()" autocomplete="off">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="net_amount" class="col-sm-5 control-label">Net Amount</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off">
                            <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                          </div>
                        </div>

                      </div>

                      <div class="box-footer">
                        <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
                        <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">
                        <!-- <button type="submit" class="btn btn-primary">Create Order</button> -->
                        <button type="button" class="checkCustNum btn btn-primary" data-toggle="modal" data-target="#customerModal" data-dismiss='modal'>Next</button>
                        <a href="<?php echo base_url('orders/') ?>" class="btn btn-warning">Back</a>
                        <div class="container"></div>
                      </div>
                  </form>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
              <!-- col-md-12 -->
            </div>
            <!-- /.row -->

            <!--modal-->
            <div class="modal fade" tabindex="-1" role="dialog" id="customerModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Enter Customer Phone Number :</h4>
                  </div>

                  <form role="form" action="<?php echo base_url('orders/create') ?>" method="post" id="customerForm">

                    <div class="modal-body">

                      <div class="form-group">
                        <label for="customer_number">Phone Number</label>
                        <input type="number" class="form-control" id="customer_number" name="customer_number" placeholder="+91 " autocomplete="off" onchange="getCustomer()">
                      </div>
                      <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Name" autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label for="customer_email">Email Address</label>
                        <input type="text" class="form-control" id="customer_email" name="customer_email" placeholder="abcd@xyz.com" autocomplete="off">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">Next</button>
                      </div>

                  </form>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!--modal-->
            <div class="modal fade" role="dialog" id="paymentModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Select Payment Method :</h4>
                  </div>

                  <form role="form" action="<?php echo base_url('orders/create') ?>" method="post" id="paymentForm">

                    <div class="modal-body">

                      <!-- small box -->
                      <div class="small-box bg-aqua mui--z3">
                        <div class="inner">
                          <h3>Payment</h3>
                          <p>Cash, Card, Wallets</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-card"></i>
                        </div>
                        <a data-toggle="modal" data-target="#firstModal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>

                      <!-- ./col -->

                      <!-- small box -->
                      <div class="small-box bg-green mui--z3">
                        <div class="inner">
                          <h3>Delivery Service</h3>

                          <p>Zomato, Uber EATS, Swiggy</p>
                        </div>
                        <div class="icon">
                          <i class=" ion ion-android-bicycle"></i>
                        </div>
                        <a data-toggle="modal" data-target="#secondModal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                      <!-- ./col -->

                      <!-- small box -->
                      <div class="small-box bg-yellow mui--z3">
                        <div class="inner">
                          <h3>Cash on Delivery</h3>

                          <p>Pay later</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-cash"></i>
                        </div>
                        <a type="button" onclick='placeOrder(3)' class="small-box-footer">Create Order<i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                      <!-- ./col -->

                      <!-- small box -->
                      <div class="small-box bg-red mui--z3">
                        <div class="inner">
                          <h3>Cash Now</h3>
                        </div>
                        <div class="icon">
                          <i class="ion ion-cash"></i>
                        </div>
                        <a type="button" onclick='placeOrder(2)' class="small-box-footer">Create Order<i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                      <!-- ./col -->
                  </form>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

          </section>
          <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      </section>

      <div class="modal" id="firstModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Select Payment Method :</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form role="form" action="<?php echo base_url('orders/create') ?>" method="post" id="cardPaymentForm">

              <div class="modal-body">
                <!-- small box -->
                <div class="small-box bg-aqua mui--z3">
                  <div class="inner">
                    <h3>Card</h3>
                  </div>
                  <div class="icon">
                    <i class="ion ion-card"></i>
                  </div>
                  <a type="button" onclick='placeOrder(1)' class="small-box-footer">Create Order<i class="fa fa-arrow-circle-right"></i></a>
                </div>

                <!-- ./col -->

                <!-- small box -->
                <div class="small-box bg-green mui--z3">
                  <div class="inner">
                    <h3>Wallet</h3>
                  </div>
                  <div class="icon">
                    <i class="ion ion-wallet"></i>
                  </div>
                  <a type="button" onclick='placeOrder(7)' class="small-box-footer">Create Order<i class="fa fa-arrow-circle-right"></i></a>
                </div>

                <!-- ./col -->
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal" id='secondModal' tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Second title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form role="form" action="<?php echo base_url('orders/create') ?>" method="post" id="deliveryPaymentForm">

              <div class="modal-body">

                <!-- small box -->
                <div class="small-box bg-red mui--z3">
                  <div class="inner">
                    <h3>Zomato</h3>
                  </div>
                  <div class="icon">
                    <i class="ion ion-card"></i>
                  </div>
                  <a type="button" onclick='placeOrder(6)' class="small-box-footer">Create Order<i class="fa fa-arrow-circle-right"></i></a>
                </div>

                <!-- ./col -->

                <!-- small box -->
                <div class="small-box bg-green mui--z3">
                  <div class="inner">
                    <h3>UberEATS</h3>
                  </div>
                  <div class="icon">
                    <i class=" ion ion-android-bicycle"></i>
                  </div>
                  <a type="button" onclick='placeOrder(5)' class="small-box-footer">Create Order<i class="fa fa-arrow-circle-right"></i></a>
                </div>

                <!-- ./col -->

                <!-- small box -->
                <div class="small-box bg-orange mui--z3">
                  <div class="inner">
                    <h3>Swiggy</h3>

                    <p>Pay later</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-cash"></i>
                  </div>
                  <a type="button" onclick='placeOrder(4)' class="small-box-footer">Create Order <i class="fa fa-arrow-circle-right"></i></a>
                </div>

                <!-- ./col -->
            </form>
          </div>
        </div>
      </div>


      <!-- <div class="modal" id="thirdModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Third title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">Save changes</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div> -->


    </body>

    </html>


    <script type="text/javascript">
      var base_url = "<?php echo base_url(); ?>";
      var productInfoTable;
      var isCustomerExists = 0;
      var customerNumber;
      $(document).ready(function() {
        $(".select_group").select2();
        $("#OrderMainNav").addClass('active');
        $("#createOrderSubMenu").addClass('active');

        productInfoTable = $('#product_info_table').DataTable({
          'ajax': base_url + 'orders/getOrderProductData',
          "ordering": false,
          "paging": false,
          "info": false
        });
        $('#healthy_cb').on('change', function() {
          // clear the previous search
          productInfoTable.columns().every(function() {
            this.search('');
          });
          // apply new search
          $('#healthy_cb:checked').each(function() {
            console.log($(this).attr('name') + ": " + $(this).val());
            productInfoTable.column($(this).attr('name')).search('healthy');
          });
          productInfoTable.draw();
        });
        $('#veg_cb').on('change', function() {
          // clear the previous search
          productInfoTable.columns().every(function() {
            this.search('');
          });
          // apply new search
          $('#veg_cb:checked').each(function() {
            console.log($(this).attr('name') + ": " + $(this).val());
            productInfoTable.column($(this).attr('name')).search('vegetarian');
          });
          productInfoTable.draw();
        });

        // $("#customerForm").submit(function(event) {
        //   customerModalNextClick();
        //   return false;
        // });

      }); // /document



      function getProductOnCategory(sel) {
        category = parseInt(sel[sel.selectedIndex].value)
        $('#product_info_table').DataTable().destroy();
        productInfoCatTable = $('#product_info_table').DataTable({
          'ajax': base_url + 'orders/getOrderProductDataByCategory/' + category,
          'order': [],
          rowReorder: {
            selector: 'td:nth-child(2)'
          }
        });
      }

      function getTotal(row = null, price) {

        if (row) {
          $("#rate_value_" + row).val(price);
          var total = price * Number($("#qty_" + row).val());
          total = total.toFixed(2);
          $("#amount_" + row).val(total);
          $("#amount_value_" + row).val(total);
          getTotalBill();
        } else {
          alert('no row !! please refresh the page');
        }
      }

      //get the customer details
      function getCustomer() {
        var phone_number = $('#customer_number').val();
        if (phone_number == null) {
          alert("Empty or invalid phone number! Please Enter again.");
        } else { //alert("Sending AJAX request");
          customerNumber = phone_number;
          $.ajax({
            url: base_url + 'orders/getCustomerData/',
            type: 'post',
            data: {
              'phone_number': phone_number
            },
            success: function(response) {

                var data = JSON.parse(response);
                console.log(data);
                //console.log(data[0].name);
                // setting the name and email value
                if (data[0] != null) {
                  isCustomerExists = 1;
                  $('#customer_name').val(data[0].name);
                  $('#customer_email').val(data[0].email_address);
                }

              } // /success
              ,
            error: function(jqXHR, exception) {
              console.log(exception);
            }

          });
        }
      }



      // get the product information from the server
      function getProductData(row_id, price) {

        var cb = document.getElementById('product_' + row_id);
        if (cb.checked == true) {
          console.log('cb checked');
        } else {
          console.log('cb !checked');
        }

        if ($('#product_' + row_id).is(":checked")) {
          console.log('checked');
          console.log('row id=' + row_id + ",price=" + price);
          $("#qty_" + row_id).removeAttr("disabled");
          $("#qty_" + row_id).focus();
          $.ajax({
            url: base_url + 'orders/getProductValueById',
            type: 'post',
            data: {
              row_id: row_id
            },
            dataType: 'json',
            success: function(response) {
              // setting the rate value into the rate input field
              $("#qty_" + row_id).val(1);
              $("#qty_value_" + row_id).val(1);
              $("#rate_" + row_id).val("");
              $("#rate_value_" + row_id).val(price);
              var total = Number(response.price) * 1;
              total = total.toFixed(2);
              $("#amount_" + row_id).val(total);
              $("#amount_value_" + row_id).val(total);
              getTotalBill();
              // subAmount();
            } // /success
          }); // /ajax function to fetch the product data 
        } else {
          console.log('unchecked');
          console.log('row id=' + row_id + ",price=" + price);
          $("#qty_" + row_id).attr("disabled", "disabled");
          $("#qty_" + row_id).val(0);
        }

        return;
      }

      function getTotalBill() {
        var tableProductLength = $("#product_info_table tbody tr").length;
        console.log('length=' + tableProductLength);
        var totalSubAmount = 0;
        $checkedBoxes = $('.order-check:checkbox:checked');
        var totalAmount = 0;
        var tax = 0;
        $checkedBoxes.each(function() {
          $quantityId = $(this).attr("quantity-id");
          $quantity = $('#' + $quantityId).val();
          $price = $(this).attr("price");
          $price = parseInt($price);
          $quantity = parseInt($quantity);
          var total = $price * $quantity;
          totalAmount += total;
        });

        var taxRate = 10.0;
        var tax = parseFloat(totalAmount / taxRate);
        grandTotal = totalAmount + tax;

        $("#gross_amount").val(totalAmount);
        $("#gross_amount_value").val(totalAmount);
        $("#vat_charge").val(tax);
        $("#vat_charge_value").val(tax);

        var discount = $("#discount").val();
        if (discount) {
          var grandTotalFinal = Number(grandTotal) - Number(discount);
          grandTotalFinal = grandTotalFinal.toFixed(2);
          $("#net_amount").val(grandTotalFinal);
          $("#net_amount_value").val(grandTotalFinal);
        } else {
          $("#net_amount").val(grandTotal);
          $("#net_amount_value").val(grandTotal);
        }
      }

      function insertNewCustomer() {
        console.log('CLICKED' + isCustomerExists);
        if (!isCustomerExists) {
          $.ajax({
            type: "POST",
            url: base_url + 'orders/insertNewCustomer',
            cache: false,
            data: $('#customerForm').serialize(),
            success: function(response) {

            },
            error: function() {
              alert("Error");
            }
          });
        }
      }

      function placeOrder(paymentType) {
        console.log('called ' + paymentType);
        $('#payment_type').val(paymentType);
        $('#customerNumberSubmit').val(customerNumber);
        var val = $('#payment_type').val();
        console.log('val=' + val);

        var formdata = $('#createOrderForm').serialize();
        // formdata = formdata.append({'payment_method', "3"});
        $.ajax({
          type: "POST",
          url: base_url + 'orders/create',
          cache: false,
          data: $('#createOrderForm').serialize(),
          success: function(response) {
            console.log(response);
            console.log(JSON.parse(response));
            var data = JSON.parse(response);
            window.location.href = data.redirectURL;
          },
          error: function() {
            alert("Error");
          }
        });
      }
    </script>