<?php
use app\records\CategoryRecord;
Flight::render('admin/partials/top'); 
?>
  
<?php Flight::render('admin/partials/navbar'); ?>

<?php Flight::render('admin/partials/sidebar'); ?>

<?php

if(!empty($article)){
    $actionUri = route('admin.articles.update',[
      'id' => $article->id
    ]);
}else{
    $actionUri = route('admin.articles.store');
}

$categories = (new CategoryRecord())->order('id desc')->findAll();

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

       <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Article</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo route('admin.dashboard.index'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Article</li>
                    </ol>
                </div>
                </div>
            </div>
        </section>

        <div class="row">
            <div class="col-md-8">
            <div class="card card-primary mx-4">
            <form action="<?php echo $actionUri; ?>" method="POST" enctype="multipart/form-data">   
                <div class="card-body">

                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" value="<?php echo $article->name??null; ?>" name="name" class="form-control" id="name" placeholder="Enter Name">
                  </div>

                  <div>
                    <label for="category">Alias</label>
                    <select name="alias" class="form-control" id="alias">
                      <!-- <option value="">Select</option> -->
                      <?php foreach ($categories as $category) {
                        $cat_name = $category->displayName;
                        $aliasName = $category->aliasName;
                        $selected_cat = "";
                        if($aliasName == ($article->alias??null)){
                          $selected_cat = "selected";
                        }
                        echo "<option value='$aliasName' $selected_cat>$cat_name</option>";
                      }?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="aliasId">AliasId</label>
                    <input type="text" value="<?php echo $article->aliasId??null; ?>" name="aliasId" class="form-control" id="aliasId" placeholder="Enter" readonly>
                  </div>

                  <div class="form-group">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control" id="thumbnail" placeholder="Enter Thumbnail" accept="image/*">
                    <?php if(!empty($article->pic)){ ?>
                    <img src="<?php echo get_url($article->pic??null); ?>" alt="" width="50" height="50"/>
                    <?php } ?>
                  </div>

                  <div class="form-group">
                    <label for="description">Desscription</label>
                    <textarea id="summernote" name="description" class="form-control"><?php echo $article->desc??null; ?></textarea>
                  </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
           </div>

            </div>
            <div class="col-md-4">
            <?php Flight::render('admin/media/media'); ?>
            </div>
        </div>

  
  </div>
  <!-- /.content-wrapper -->
  <?php Flight::render('admin/partials/footer'); ?>


</div>
<!-- ./wrapper -->
<?php Flight::render('admin/partials/bottom'); ?>
<!-- Page specific script -->
<link rel="stylesheet" href="<?php echo asset('/assets/css/summernote-bs4.min.css'); ?>">
<script src="<?php echo asset('/assets/js/summernote-bs4.min.js'); ?>"></script>
<script>
    $('#summernote').summernote({
      height: 250
    })


    $(window).on('load', function() {

      function setAliasId(){
        const alias = $("#alias").val()
        fetch(`<?php echo route('admin.articles.get_aliasId',['alias' => '']); ?>/${alias}`).
        then((response) => response.json()).then((res) => {
          $("#aliasId").val(res.aliasId)
        }).catch((error) => {
          console.log('error media files')
        })
      }

      if('<?php echo $actionUri; ?>'.includes('create')){
        setAliasId()
      }

      $("#alias").on("change", function(){
        setAliasId()
      })

    });
</script>
</body>
</html>
