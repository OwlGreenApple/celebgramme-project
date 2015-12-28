@extends('member.index')

@section('content-auto-manage')
<script type="text/javascript">

    function call_action(action,id){
        $.ajax({
            type: 'GET',
            url: "<?php echo url('call-action'); ?>",
            data: {
              action : action,
              id : id,
            },
            dataType: 'text',
            beforeSend: function()
            {
              $("#div-loading").show();
            },
            success: function(result) {
                // $('#result').html(data);
                $("#div-loading").hide();
                var data = jQuery.parseJSON(result);
                $("#alert").show();
                $("#alert").html(data.message);
                if(data.type=='success')
                {
                  $("#alert").addClass('alert-success');
                  $("#alert").removeClass('alert-danger');
                  if(data.action=='start'){
                    $(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Stop");
                    $(".btn-"+data.id).val("Stop");
                    $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>');
                    $(".btn-"+data.id).removeClass("btn-success");
                    $(".btn-"+data.id).addClass("btn-danger");
                  }
                  if(data.action=='stop'){
                    $(".btn-"+data.id).html("<span class='glyphicon glyphicon-stop'></span> Start");
                    $(".btn-"+data.id).val("Start");
                    $(".btn-"+data.id).parent().parent().parent().find(".status-activity p").html(' Status activity : <span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>');
                    $(".btn-"+data.id).removeClass("btn-danger");
                    $(".btn-"+data.id).addClass("btn-success");
                  }
                }
                else if(data.type=='error')
                {
                  $("#alert").addClass('alert-danger');
                  $("#alert").removeClass('alert-success');
                }
            }
        })
        return false;
    }

  $(document).ready(function() {

    // $( "body" ).on( "click", ".button-action", function(e) {
    $('.button-action').click(function(e){
      e.preventDefault();
      action = "";
      if ($(this).val()=="Start") { action = "start"; }
      if ($(this).val()=="Stop") { action = "stop"; }
      call_action(action,$(this).attr("data-id"));
    });

    $("#alert").hide();

    $('#button-save').click(function(e){
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('process-save-setting'); ?>",
          data: $("#form-setting").serialize(),
          dataType: 'text',
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          success: function(result) {
              // $('#result').html(data);
              // console.log(result);return false;
              window.scrollTo(0, 0);
              $("#div-loading").hide();
              var data = jQuery.parseJSON(result);
              $("#alert").show();
              $("#alert").html(data.message);
              if(data.type=='success')
              {
                $("#alert").addClass('alert-success');
                $("#alert").removeClass('alert-danger');
              }
              else if(data.type=='error')
              {
                $("#alert").addClass('alert-danger');
                $("#alert").removeClass('alert-success');
              }
          }
      })
    });

    $('.selectize-default').selectize({
      plugins:['remove_button'],
      delimiter: ',',
      persist: false,
      create: function(input) {
        return {
          value: input,
          text: input
        }
      },
    });


  });
</script>
  <link href="{{ asset('/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ asset('/selectize/js/standalone/selectize.js') }}"></script>

<div class="row">
  <div class="col-sm-8 col-md-8">            
    <div class="alert alert-danger col-sm-18 col-md-18" id="alert">
    </div>  
  </div>          
</div>                        
<form enctype="multipart/form-data" id="form-setting">


<div class="row">
  <div class="col-md-8 col-sm-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Data Users</h3>
      </div>
      <div class="panel-body">
<div class="col-md-5 col-sm-5 border-styling ">
  <div class="row"> <img src="{{url('images/profile-default.png')}}" class=""> </div>
  <div class="row"> <label>{{$settings->insta_username}}</label></div>
  <div class="row status-activity"> <p> Status activity : <?php if ($settings->status=='stopped') { echo '<span class="glyphicon glyphicon-stop"></span> <span style="color:#c12e2a; font-weight:Bold;">Stopped</span>'; } 
  else {echo '<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> <span style="color:#5cb85c; font-weight:Bold;">Started</span>';}?></p></div>
  <div class="row im-centered"> 

    <button data-id="{{$settings->id}}" class="btn <?php if ($settings->status=='stopped') { echo 'btn-success'; } else {echo 'btn-danger';} ?> button-action btn-{{$settings->id}}" value="<?php if ($settings->status=='stopped') { echo 'Start'; } else {echo 'Stop';}?>">
      <?php if ($settings->status=='stopped') { echo "<span class='glyphicon glyphicon-play'></span> Start"; } else {echo "<span class='glyphicon glyphicon-stop'></span> Stop";}?> 
    </button>
  </div>
</div>
<div class="col-md-7 col-sm-7 pricing" style="margin-left:-10px;">
  <div class="col-md-4 col-sm-4">
    <div class="row im-centered"> 
      <p class="header">7</p>
    </div>
    <div class="row im-centered"> 
      <p class="header-description">Days</p>
    </div>
    <div class="row im-centered"> 
      Rp. 100.000
    </div>
    <div class="row im-centered"> 
    </div>
    <div class="row im-centered button-price"> 
      <a href="{{url('buy-more/1')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="row im-centered"> 
      <p class="header">28 </p>
    </div>
    <div class="row im-centered"> 
      <p class="header-description">Days</p>
    </div>
    <div class="row im-centered"> 
      Rp. 175.000
    </div>
    <div class="row im-centered"> 
    </div>
    <div class="row im-centered button-price"> 
      <a href="{{url('buy-more/2')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
    </div>
  </div>
  <div class="col-md-4 col-sm-4">
    <div class="row im-centered"> 
      <p class="header">88 </p>
    </div>
    <div class="row im-centered"> 
      <p class="header-description">Days</p>
    </div>
    <div class="row im-centered"> 
      Rp. 395.000
    </div>
    <div class="row im-centered"> 
    </div>
    <div class="row im-centered button-price"> 
      <a href="{{url('buy-more/3')}}"><input type="button" value="Buy now" class="btn btn-success"></a>
    </div>
  </div>
</div>
      </div>
    </div>
  </div>  
</div>                        


<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Main Settings</h3>
      </div>
      <div class="panel-body">

        <div class="col-md-4">
          <label>Activity Speed</label> <span class="glyphicon glyphicon-question-sign" title="Slow - Kecepatan yang aman untuk melakukan sekitar 480 Likes, 144 comments, 336 follows, 240 unfollow per hari ( kecepatan terbaik untuk awal pemakaian )

Normal - Kecepatan yang tepat untuk melakukan sekitar 720 likes, 192 comments, 480 follows, 360 unfollows per hari.

Fast - Kecepatan tertinggi untuk melakukan 960 likes, 240 comments, 624 follows, 480 unfollows per hari.

cobalah untuk menggunakan kecepatan slow untuk awal pemakaian. Kemudian, anda dapat mengubahnya ke normal atau fast setelah beberapa hari."></span>
          <select class="form-control" name="data[activity_speed]" title="Slow - Kecepatan yang aman untuk melakukan sekitar 480 Likes, 144 comments, 336 follows, 240 unfollow per hari ( kecepatan terbaik untuk awal pemakaian )">
            <option value="normal" <?php if ($settings->activity_speed=='normal') echo "selected" ?>>normal</option>
            <option value="slow" <?php if ($settings->activity_speed=='slow') echo "selected" ?>>slow</option>
            <option value="fast" <?php if ($settings->activity_speed=='fast') echo "selected" ?>>fast</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Source</label> <span class="glyphicon glyphicon-question-sign" title="Pilih sumber foto dan video untuk aktivitas Anda :

Tags - untuk menentukan media sesuai tags yang di pilih

Follower/followings - untuk menentukan media berdasarkan username dari followers atau following

My Feed - untuk menentukan media berdasarkan feed (semua postingan Instagram) anda sendiri"></span>
          <select class="form-control" name="data[media_source]">
            <option value="tags" <?php if ($settings->media_source=='tags') echo "selected" ?>>Tags</option>
            <option value="locations" <?php if ($settings->media_source=='locations') echo "selected" ?>>Locations</option>
            <option value="followers of usernames" <?php if ($settings->media_source=='followers of usernames') echo "selected" ?>>Followers of usernames</option>
            <option value="my feed" <?php if ($settings->media_source=='my feed') echo "selected" ?>>My Feed</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Age</label> <span class="glyphicon glyphicon-question-sign" title="Pengaturan ini akan membantu anda untuk memilih Media Age yang akan berinteraksi dengan anda. Dari yang terbaru sampai dengan yang terlama.

Contohnya, pilih 1 Day jika anda hanya ingin berinteraksi dengan media yang diposting tidak lebih lama dari 1 hari."></span>
          <select class="form-control" name="data[media_age]">
            <option value="newest" <?php if ($settings->media_age=='newest') echo "selected" ?>>Newest</option>
            <option value="1 hour" <?php if ($settings->media_age=='1 hour') echo "selected" ?>>1 Hour</option>
            <option value="12 hours" <?php if ($settings->media_age=='12 hours') echo "selected" ?>>12 Hours</option>
            <option value="1 day" <?php if ($settings->media_age=='1 day') echo "selected" ?>>1 Day</option>
            <option value="3 day" <?php if ($settings->media_age=='3 day') echo "selected" ?>>3 Days</option>
            <option value="1 week" <?php if ($settings->media_age=='1 week') echo "selected" ?>>1 Week</option>
            <option value="2 week" <?php if ($settings->media_age=='2 week') echo "selected" ?>>2 Weeks</option>
            <option value="1 month" <?php if ($settings->media_age=='1 month') echo "selected" ?>>1 Month</option>
            <option value="any" <?php if ($settings->media_age=='any') echo "selected" ?>>Any</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Media Type</label> <span class="glyphicon glyphicon-question-sign" title="Pengaturan ini memungkinkan Anda berinteraksi dengan media yang lebih spesifik : foto atau video.  Anda juga dapat memilih salah satu."></span>
          <select class="form-control" name="data[media_type]">
            <option value="any" <?php if ($settings->media_type=='any') echo "selected" ?>>Any</option>
            <option value="photos" <?php if ($settings->media_type=='photos') echo "selected" ?>>Photos</option>
            <option value="videos" <?php if ($settings->media_type=='videos') echo "selected" ?>>Videos</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Min likes filter</label> <span class="glyphicon glyphicon-question-sign" title="Interaksi hanya dilakukan pada media (foto / video )  yang memiliki jumlah likes paling sedikit. 

Gunakan juga Max. likes filter untuk mengatur rentang kepopuleran media yang anda inginkan

Nilai yang disarankan : 0 - 5

Atur nilai ke 0 untuk menonaktifkan filter ini
"></span>
          <input type="number" class="form-control" name="data[min_likes_media]" value="{{$settings->min_likes_media}}">
        </div>
        <div class="col-md-4">
          <label>Max likes filter</label> <span class="glyphicon glyphicon-question-sign" title="Interaksi hanya dilakukan pada media (foto / video )  yang memiliki jumlah likes paling banyak.
 
Gunakan juga Minimum likes filter untuk mengatur rentang kepopuleran media yang anda inginkan.

Nilai yang disarankan :50 - 100

Atur nilai ke 0 untuk menonaktifkan filter ini
"></span>
          <input type="number" class="form-control" name="data[max_likes_media]" value="{{$settings->max_likes_media}}">
        </div>

      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Comment</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_comment_su]" <?php if($settings->dont_comment_su) echo "checked"; ?> >Dont Comment same user</label> <span class="glyphicon glyphicon-question-sign" title="Ketika anda memberikan centang ke kotak ini, anda tidak akan memberikan comment lebih dari 1 pada foto atau video pada user yang sama."></span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Comments</label> <span class="glyphicon glyphicon-question-sign" title="Tambahkan setidaknya satu komentar, jika anda mengaktifkan fitur comments

untuk setiap posting komentar baru, komentar akan dipilih secara acak dari daftar ini. Celebgramme hanya akan memberikan 1 kali komentar pada setiap posting foto atau video. 

Kami menyarankan, paling tidak 10 komentar netral yang berbeda seperti : nice!, awesome!, beautiful!, itu keren!, dll

Komentar tidak boleh lebih dari 300 karakter.
Komentar tidak boleh berisi lebih dari 4 hashtag
Komentar tidak boleh berisi lebih dari 1 URL
Komentar tidak boleh terdiri dari huruf kapital semua.
Komentar sebisa mungkin harus berbeda satu sama lain.

Anda dapat menambahkan sampai dengan 100 comments."></span>
            <textarea class="selectize-default" name="data[comments]">{{$settings->comments}}</textarea>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Follow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_follow_su]" <?php if($settings->dont_follow_su) echo "checked"; ?> >Dont Follow same user</label> <span class="glyphicon glyphicon-question-sign" title="Ketika anda memberikan centang ke kotak ini, anda tidak akan follow user yang sama sebanyak 2 kali setelah anda meng-unfollow mereka.
"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 checkbox">
            <label><input type="checkbox" name="data[dont_follow_pu]" <?php if($settings->dont_follow_pu) echo "checked"; ?> >Dont Follow private user</label> <span class="glyphicon glyphicon-question-sign" title="Ketika anda memberikan centang ke kotak ini, anda tidak akan memfollow user yang akun nya di private"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Follow source</label> <span class="glyphicon glyphicon-question-sign" title="Anda dapat memilih, antara menentukan sendiri media source anda atau berdasarkan username followers/following."></span>
            <select class="form-control" name="data[follow_source]">
              <option value="media" <?php if ($settings->follow_source=='media') echo "selected" ?>>Media</option>
              <option value="followers of username" <?php if ($settings->follow_source=='followers of username') echo "selected" ?>>Followers of username</option>
              <option value="following of username" <?php if ($settings->follow_source=='following of username') echo "selected" ?>>Following of username</option>
            </select>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Unfollow</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-5 checkbox">
            <label><input type="checkbox" name="data[unfollow_wdfm]" <?php if($settings->unfollow_wdfm) echo "checked"; ?> >Unfollow who dont follow me</label> <span class="glyphicon glyphicon-question-sign" title="Ketika anda memberikan centang ke kotak ini, anda hanya akan mengunfollow user yang tidak memfollow back anda. Mungkin diperlukan lebih banyak waktu untuk menemukan mereka, tergantung pada jumlah user yang anda follow."></span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label>Unfollow source</label> <span class="glyphicon glyphicon-question-sign" title="User yang mana yang akan anda unfollow?

Celebgramme - pilih opsi ini jika anda ingin unfollow user yang anda dapatkan dari service kami

All - pilih opsi ini jika anda ingin unfollow semua user yang anda follow"></span>
            <select class="form-control" name="data[unfollow_source]">
              <option value="celebgramme" <?php if ($settings->unfollow_source=='celebgramme') echo "selected" ?>>Celebgramme</option>
              <option value="all" <?php if ($settings->unfollow_source=='all') echo "selected" ?>>All</option>
            </select>
          </div>
        </div>


      </div>
    </div>
  </div>  
</div>                        

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Tags</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Tags</label> <span class="glyphicon glyphicon-question-sign" title="Tambahkan setidaknya satu tag untuk mendapatkan media jika anda menggunakan Tags sebagai media source anda.

Anda dapat mencari tags atau anda dapat meng-upload list tag anda dengan klik dikolom isian tags. Catatan bagi anda, bahwa simbol # (tanda pagar) tidak diperlukan. Gunakan 10 tags atau lebih, sangat direkomendasikan untuk pengaturan ini.

Anda dapat menambahkan sampai dengan 1000 hashtags."></span>
            <textarea class="selectize-default" name="data[tags]">{{$settings->tags}}</textarea>
          </div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-info ">
      <div class="panel-heading">
        <h3 class="panel-title">Username</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <label>Username</label> <span class="glyphicon glyphicon-question-sign" title="Tambahkan paling tidak satu username jika anda menggunakan followers/following of usernames sebagai media source anda. 

Celebgramme akan menggunakan username followers/followings untuk memfollow mereka dan memilih 5 postingan terakhir dari setiap akun untuk auto likes dan comments.

Anda dapat menambahkan sampai dengan 50 usernames.
"></span>
            <textarea class="selectize-default" name="data[username]">{{$settings->username}}</textarea>
          </div>
        </div>

      </div>
    </div>
  </div>  
</div>                    

<div class="row">
  <div class="col-md-3">
    <input type="button" value="Save" class="btn btn-info col-md-8 col-sm-12" id="button-save">    
  </div>                    
</div>                    
<input type="hidden" name="data[id]" value="{{$settings->setting_id}}">
</form>
@endsection
