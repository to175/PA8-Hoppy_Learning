<!doctype html>
<html lang="{{ app()->getLocale() }}">  
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title or 'Hoppy' }}</title>

        <!-- Fonts -->
        

        @yield('additional')
    </head>
    <body>
        <div class="flex-center position-ref full-height">
                @include('layouts/partials/_nav')

                @yield('titleHome')            
        </div>
        
        @yield('content', 'Erreur sur le contenu')

        <script type="text/javascript">
      $( ".slider" ).click(function() {
        if($( "body" ).hasClass('daltonien')){
            $( "body" ).removeClass('daltonien');
            if($( "#btreg" ).hasClass('btn-primary')){
                $( "#btreg" ).addClass('btn-danger');
                $( "#btreg" ).removeClass('btn-primary');
            }
        }else{
            $( "body" ).addClass('daltonien');
            if($( "#btreg" ).hasClass('btn-danger')){
                $( "#btreg" ).addClass('btn-primary');
                $( "#btreg" ).removeClass('btn-danger');
            }
        }
        });

      window.scrollReveal = new scrollReveal();
    </script>
    </body>
</html>
@section('additional') 
<style>
    .full-height {
        height: 100vh;
    }
</style>
@stop

@section('content')
        <div class="arrow bounce">

        </div>
        <div class="descr" data-scrollreveal="over 2s"><section>descriptif avec beaucoup de texte
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur</p></section> 
        
          <div class="row">
              <div class="col-sm-4">
                 <img class="rounded-circle" src="{!! asset('profile.png') !!}" width="150px" height="150px"/>
              </div>

              <div class="col-sm-4">
                 <img class="rounded-circle" src="{!! asset('profile.png') !!}" width="150px" height="150px" />
              </div>

              <div class="col-sm-4">
                 <img class="rounded-circle" src="{!! asset('profile.png') !!}" width="150px" height="150px" />
              </div>
           </div>
        </div>
        

        {{-- Commentaire ! --}}

        <!-- @{{ 'Toute cette ligne sera affichée, sans le arobase' }}-->

        <script type="text/javascript">
      var lastScrollTop = 0;
        $(window).scroll(function(event){
           var st = $(this).scrollTop();
           if (st > lastScrollTop){
               // downscroll code
           } else {
              $( ".arrow" ).css('display', 'block');
           }
           lastScrollTop = st;
        });
      
        $(window).scroll(function () {
            if ($(window).scrollTop() == 0){
                //si haut de page
            }else if ($(window).height() + $(window).scrollTop() >= $(document).height() - 3 ) {
                $( ".arrow" ).css('display', 'none');
            }
        });

      window.scrollReveal = new scrollReveal();
    </script>
@stop

@section('titleHome')
<div class="content">
    <div class="title m-b-md">
        Hoppy Learning
    </div>
</div>
@stop