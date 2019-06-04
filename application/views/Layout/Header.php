
<?php if(!$this->session->userdata('logged_in')){   
   redirect('Login');  
} ?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sistema TrajesYa!</title>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Alianza Lima , Responsive, Futuro,Blanquiazul,2019,Administrador">
    <meta name="author" content="#">
    <!-- Favicon icon -->
    <link rel="icon" href="<?php echo base_url(); ?>\files\assets\images\favicon.ico" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\bootstrap\css\bootstrap.min.css">
     <!-- lightbox Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\lightbox2\css\lightbox.min.css">
      <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\icon\themify-icons\themify-icons.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\icon\icofont\css\icofont.css">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\icon\feather\css\feather.css">
    <!-- Select 2 css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>\files\bower_components\select2\css\select2.min.css">
    <!-- Multi Select css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\bootstrap-multiselect\css\bootstrap-multiselect.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\multiselect\css\multi-select.css">
   
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css"> 
  
     <!-- Notification.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\pages\notification\notification.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\assets\vendor\sweetalert\dist\sweetalert.css" />  
    
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\pages\advance-elements\css\bootstrap-datetimepicker.css">
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\bootstrap-daterangepicker\css\daterangepicker.css">
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\datedropper\css\datedropper.min.css">
    <!-- Color Picker css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\spectrum\css\spectrum.css">
    <!-- Mini-color css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\bower_components\jquery-minicolors\css\jquery.minicolors.css">
     
     <!-- Font Awesome --> 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\icon\font-awesome\css\font-awesome.min.css">
  
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\files\assets\css\jquery.mCustomScrollbar.css">
      <!-- Base.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>\assets\css\base.css">
    

    <!-- jquery file upload Frame work -->
    <link href="<?php echo base_url(); ?>\files\assets\pages\jquery.filer\css\jquery.filer.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>\files\assets\pages\jquery.filer\css\themes\jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet">


</head>   

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
