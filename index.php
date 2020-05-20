<!DOCTYPE html>
<html>
<head>
<title>LINK24.life : URL Shortener</title>
<body>
<?php
include('Route.php');

function get_all_links() {
    $myfile = fopen("links.txt", "r") or die("Unable to open file!");
    $file_data=fgets($myfile);
    fclose($myfile);
    $list_1=explode("##",$file_data);
    $a=array();
    for ($i=0;$i<sizeof($list_1)-1;$i++)
    {
        array_push($a,array(explode('**',$list_1[$i])[0],explode('**',$list_1[$i])[1]));
    }
    return $a;
}


function get_link($link_id) {
    $list=get_all_links();
    for($i=0;$i<sizeof($list);$i++)
    {
        if ($list[$i][0]==$link_id){
            return $list[$i][1];
        }
    }
    return 'link Not found!';
}

function add_link($link_id,$link) {
    if(get_link($link_id)=='link Not found!'){
        $myfile = fopen("links.txt", "a") or die("Unable to open file!");
        fwrite($myfile,$link_id.'**'.$link.'##');
        fclose($myfile);
        return "http://localhost/".$link_id;
    }
    else
    {
        $myfile = fopen("links.txt", "r") or die("Unable to open file!");
        $file_data=fgets($myfile);
        fclose($myfile);
        $file_data=str_replace(get_link($link_id),$link,$file_data);
        $myfile = fopen("links.txt", "w") or die("Unable to open file!");
        fwrite($myfile,$file_data);
        fclose($myfile);
        return 'updated';
    }
    
}

Route::add('/status/([0-9,a-z,A-Z]*)',function($link_id){
  $ipaddress = getenv("REMOTE_ADDR") ; 
  Echo "Your IP Address is " . $ipaddress; 
});

Route::add('/api/([0-9,a-z,A-Z]*)',function($link_id){
    if (isset($_GET['link']))
    {
        $link=$_GET['link'];
        $mess=add_link($link_id,$link);
        echo $mess;
    }
    else
    {
        echo 'link add failed!';
    }

});

Route::add('/([0-9,a-z,A-Z]*)', function($link) {
    if ($link=="")
    {
        echo '<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    
    
    <style>
    #rcorners2 {
      border-radius: 10px;
      border: 2px solid grey;
      padding: 7px;
      width: 200px;
    }
    #u_profile {
      padding: 7px;
      border-width: 10px;
    }
    #u_profile  p {
      padding: 15px;
      margin: 0px;
    }
    
    
    
    input[type=text], select {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    
    input[type=submit] {
      background-color: #4CAF50;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    
    input[type=submit]:hover {
      background-color: #45a049;
    }
    </style>
    </head>
    
    <body>
      <div class="jumbotron text-center">
        <h1>LINK24.<i style=\'font-family: "Sofia";\'>life</i></h1>
        <p>------ Free URL Shortener ------</p>
      </div>
    
      <div class="container">
        <div class="row">
          <div class="col-sm">
                    <input type="text" id="url_input" placeholder="Paste Your URL to Short" required>
                    <center><input type="submit" onclick="action1()" value="Short URL"></center>
                  <center><div id="server-results"></div></center>
            </div>
          </div>
    
    <script>
    
    function action1() {
      var r1=Math.floor(Math.random() * 100000) + 1;
      var link1=document.getElementById("url_input").value;
      document.getElementById("url_input").value="";
      $.ajax({
        url : \'/api/\'+r1,
        type: \'get\',
        data : {link:link1}
      }).done(function(response){ 
        if(response!=\'false\')
        {
            var link2 = response.split("<body>")[1].split("</body>")[0];
            var img=\'<br><br><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=\'+link2+\' width="150" height="150"><br>QR Code\'
            document.getElementById("server-results").innerHTML=\'<br><br>Your Original Link : <a href="\'+link1+\'">\'+link1+\'</a><br>Your Shorted Link : <a href="\'+link2+\'">\'+link2+\'</a>\'+img;
        }
      });
    }
    
    </script>';
    }
    else{
        $get_link=get_link($link);
        $html_code='<script>
          location.replace("'.$get_link.'");
        </script>';
        echo $html_code;
    }

  }, 'get');

Route::run('/');
?>

</body>
</html>