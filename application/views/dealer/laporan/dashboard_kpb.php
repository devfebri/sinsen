

    <?php 
    if($set=="view"){
    ?>

<body>


<div class="content-wrapper">
  <section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">h1</li>
    <li class="">SLA Fincoy</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
  <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
              <iframe width="800" height="600" src="https://app.powerbi.com/view?r=eyJrIjoiNGVjMTIyODktYjZmNi00MTVhLTlmODAtN2FmMWFkODliYTM5IiwidCI6IjNkMmYxMGM4LTVlNGMtNDIwMy04YTA2LWJlNWQ1NGZmYjc1YSIsImMiOjEwfQ%3D%3D"></iframe>
          </div>
        </div>
      </div>
  </div>
  </section>


</div>

</body>



<?php }