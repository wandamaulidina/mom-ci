<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">


<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="card">
     <?php $uri = service('uri'); ?>
      <div class="card-header">
        <h3 class="card-title <?= ($uri->getSegment(1) == 'company') ? 'active' : '' ?>">
          Data Company 
          <button id="button-add" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-company"> + Tambah</button>
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <table class="table" id="table-company">
          <thead>
            <tr class="text-center bg-cyan">
              <th scope="col">No</th>
              <th scope="col">Name Company</th>
              <th scope="col">Contact Company</th>
              <th scope="col">Email Company</th>
              <th scope="col">Address Company</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    
    
    <!-- modal bootstrap -->
    <div class="modal" id="modal-company" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"> Form Company </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php $validation = \Config\Services::validation(); ?>
            <form id="form-company" method="POST" class="was-validated">
              <input type="hidden" name="id" id="id">
              <?=csrf_field() ?>
              <div class="form-group">
                <label for="name">Name Company</label>
                <input type="text" class="form-control was-validated" name="name" id="name" placeholder="Masukan name company" required="true" autofocus>
                <div class="valid-feedback text-success">Valid</div>
                <div class="invalid-feedback text-danger"></div>
              </div>
              <div class="form-group">
                <label for="contact">Contact Company</label>
                <input type="text" class="form-control was-validated" name="contact" id="contact" placeholder="Masukan contact company" required="true" autofocus>
                <div class="valid-feedback text-success">Valid</div>
                <div class="invalid-feedback text-danger"></div>
              </div>
              <div class="form-group">
                <label for="email">Email Company</label>
                <input type="text" class="form-control was-validated" name="email" id="email" placeholder="Masukan email company" required="true" autofocus>
                <div class="valid-feedback text-success">Valid</div>
                <div class="invalid-feedback text-danger"></div>
              </div>
              <div class="form-group">
                <label for="address">Address Company</label>
                <input type="text" class="form-control was-validated" name="address" id="address" placeholder="Masukan address company" required="true" autofocus>
                <div class="valid-feedback text-success">Valid</div>
                <div class="invalid-feedback text-danger"></div>
              </div>
              <button type="submit" class="btn btn-primary" id="button-submit-company" hidden="true"></button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="$('#button-submit-company').click()">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal bootstrap -->
  </section>
</div>

<script type="text/javascript">
  $(document).ready(function() {

    // inisialisasi datatable
    //$("#table-company").DataTable();
    
    var table = $('#table-company').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?php echo base_url('/master/company/list') ?>",
        "type": "POST"
      },
      "columnDefs": [{
        "targets": [],
        "orderable": false,
      }, ],
      "pageLength": 5,
    });


    

    const FormCompany = $("#form-company");
    FormCompany.submit(function(event) {
      event.preventDefault();
      
      // ketika form disubmit
      // periksa, apakah ada value id atau tidak
      // kalau ada, berarti action = edit
      // kalau gak ada, berarti action = submit
      let action;
      
      const id = $("#id").val();
      if (id) {
        action = 'master/company/edit';
      }else{
        action = 'master/company/submit';
      }

      if(confirm("Apakah data yang diinput sudah benar ?")) {
        $.post(action, FormCompany.serialize()).done((res,xhr,status) => {
          if (res) {
            table.ajax.reload();

            // parse json menggunakan jQuery.parseJSON(res) -> supaya bisa digunakan. Lihat response
            let response = jQuery.parseJSON(res),
            status  = response.status,
            message = response.message;

            // jika status false
            if (!status) {

              // list error dinamis: jumlah berdasarkan error
              const errors = message.errors;
              $.each(errors, function(index, val) {
                alert(val);
                $(`#${index}`).val('');
              });
            }else{
              alert("Data berhasil diinput");
            }
          }
        });
      }
    });

    // ambil id berdasarkan button yang diklik
    // hapus
    $("#table-company").on('click', '#button-delete', function(event) {
      event.preventDefault();
      const idValue = $(this).data('id');
      if (confirm("Apakah Anda yakin ingin menghapus data ini ?")) {
        $.post('/master/company/delete', {id: idValue}).done((res,xhr,status) => {
          if (res) {
            alert("Company Berhasil di hapus");
            table.ajax.reload();
          }
        })
      }
    });

    // edit
    $("#table-company").on('click', '#button-edit', function(event) {
      event.preventDefault();
      const idValue = $(this).data('id');
      $.post('/master/company/get', {id: idValue}).done((res,xhr,status) => {
        if (res) {
          const response = jQuery.parseJSON(res),
          data = response.data;

          // set id
          $("#id").val(data.id);
          $("#name").val(data.name);
          $("#contact").val(data.contact);
          $("#email").val(data.email);
          $("#address").val(data.address);
          $("#modal-company").modal('show');
        }
      })
    });
  });
</script>