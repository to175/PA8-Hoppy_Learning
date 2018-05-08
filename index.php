<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en;">
<!-- LANGUAGE OF THE PAGE -->

<head>
  <?php include ('head.html'); ?>
  <title>Hoppy learning</title>
  <style>
    .full-height {
      height: 100vh;
    }
  </style>
</head>

<body>
  <div class="flex-center position-ref full-height">
    <?php include ('header.php'); ?>

    <div class="content">
      <div class="title m-b-md">
        <img src="images/lightbubble.png" style="height:4.5rem;position: absolute; margin-left: -90px;margin-top: 25px;"> Hoppy Learning
      </div>
    </div>
  </div>


  <div class="arrow bounce">
  </div>

  <?php 
    $default = "http://theofleury.fr/hoppy/images/profile.png";
    $size = 120;

    $hajar = 'images/profile.png';
    $jordan = 'images/jordan.jpg';
    $linzhou = 'images/linzhou.jpg';
    $theo = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( "theo175@gmail.com" ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
    $anthony = 'images/anthony.jpg';
    $freder = 'images/freder.jpg';
    $johlian = 'images/johlian.jpg';
    $philippe = 'images/philippe.jpg';
  ?>

  <div class="descr" data-scrollreveal="over 2s">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title" id="about">HOPPY, We will make you learn differently</h4>
        <p class="card-text">
          Hoppy is the opportunity for everyone to learn at their own pace and in the best conditions. Thanks to virtual reality, we offer a unique learning experience. Join the Hoppy movement!
          <br><br> A team of 8 students motivated by the advancement of technology and concern for others. We thought this application to allow everyone to study the program of their choice, at their own pace. We are students in computer engineering school
          (EFREI Paris) and have chosen to create this startup as part of our project. Dynamic, curious, hardworking, organized the team HOPPY knew how to make the most of the qualities of each one. The team consists of Jordan, Theo, Johlian, Anthony,
          Freder, Philippe, Linzhou and Hajar.
          <br><br> HOPPY is the opportunity to personalize your education, to truly immerse yourself in a new world and to live an experience that is still unique in the world! Through virtual reality, stroll through the streets of Moscow, attend a conference
          of the Nobel Prize for Science and discover the world.
        </p>
        <br>
        <div class="row justify-content-around">
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $hajar; ?>" width="120px" height="120px" />
            </div>
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $jordan; ?>" width="120px" height="120px" />
            </div>
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $linzhou; ?>" width="120px" height="120px" />
            </div>
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $theo; ?>" width="120px" height="120px" />
            </div>
          </div>
          <br><br>
          <div class="row justify-content-around">                                                                                    
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $anthony; ?>" width="120px" height="120px" />
            </div>
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $freder; ?>" width="120px" height="120px" />
            </div>
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $johlian; ?>" width="120px" height="120px" />
            </div>
            <div class="col-2 col-sm-auto">
              <img class="rounded-circle" src="<?php echo $philippe; ?>" width="120px" height="120px" />
            </div>
          </div>
        <br><br>
        <small style="border:none;">
            To contact us by e-mail: team.Hoppy@pa8.com <br>
            To contact us by phone: 01 00 00 00 00 <br>
        </small>
        <br><br>
        <div class="row justify-content-sm-center">
        	<a href="login.php" class="btn btn-success btn-lg">Create my account</a>
        </div>
        <br><br><br>
        <div style="width:100%;">
          <div style="width:100px;margin:auto;">
            <div style="display:inline-table;">
              <img src="https://cdn3.iconfinder.com/data/icons/free-social-icons/67/facebook_circle_color-64.png" style="width:40px;">
              <img src="https://cdn3.iconfinder.com/data/icons/free-social-icons/67/youtube_circle_color-64.png" style="width:40px;margin-left:10px;">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <br>
  <br>
  

  </div>

  <!-- @{{ 'Toute cette ligne sera affichée, sans le arobase' }}-->

  <script type="text/javascript">
    var lastScrollTop = 0;
    $(window).scroll(function(event) {
      var st = $(this).scrollTop();
      if (st > lastScrollTop) {
        // downscroll code
      } else {
        $(".arrow").css('display', 'block');
      }
      lastScrollTop = st;
    });

    $(window).scroll(function() {
      if ($(window).scrollTop() == 0) {
        //si haut de page
      } else if ($(window).height() + $(window).scrollTop() >= $(document).height() - 3) {
        $(".arrow").css('display', 'none');
      }
    });

    window.scrollReveal = new scrollReveal();
  </script>


  <script type="text/javascript">
    $(".slider").click(function() {
      if ($("body").hasClass('daltonien')) {
        $("body").removeClass('daltonien');
        if ($("#btreg").hasClass('btn-primary')) {
          $("#btreg").addClass('btn-danger');
          $("#btreg").removeClass('btn-primary');
        }
      } else {
        $("body").addClass('daltonien');
        if ($("#btreg").hasClass('btn-danger')) {
          $("#btreg").addClass('btn-primary');
          $("#btreg").removeClass('btn-danger');
        }
      }
    });

    window.scrollReveal = new scrollReveal();
  </script>
</body>

</html>