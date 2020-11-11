@if (Session::has('success'))

                  <div class="alert alert-success alert-dismissible">

            <button type="button" class="close" data-dismiss="alert">&times;</button>

                  {{ Session::get('success') }}

            </div>


<?php Session::forget('success') ?>


@endif



@if (Session::has('unsuccess'))



            <div class="alert alert-danger alert-dismissible">

            <button type="button" class="close" data-dismiss="alert">&times;</button>

                  {{ Session::get('unsuccess') }}

            </div>

<?php Session::forget('unsuccess') ?>
@endif



@if(session('message')==='f')

      <div class="alert alert-danger alert-dismissible">

      <button type="button" class="close" data-dismiss="alert">&times;</button>

            Credentials doesn't match

      </div>



@endif    

@if(Session::has('error'))
      <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ Session::get('error') }}
      </div>
      <?php Session::forget('error') ?>
@endif  