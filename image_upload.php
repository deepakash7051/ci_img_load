<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<style type="text/css">
  #blah {
  width: 600px;
  height: 300px;
  border: 2px solid;
  display: block;
  margin: 10px;
  background-color: white;
  border-radius: 5px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  transition: all 0.3s cubic-bezier(.25,.8,.25,1);
  overflow: hidden;
}
</style>
<body>
 <div  class="container">
   <div class="row">
   <form method="post" id="upload_form" enctype="multipart/form-data">  
     <div class="col-md-7">
       <h1> Upload &amp; Display Image</h1></br>
        <div id="divMsg" class="alert alert-success" style="display: none">
         <span id="msg"></span>
        </div>
        <img id="blah" src="http://www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/" alt="your image" /></br></br>
         <input type="file" name="image_file" multiple="true" accept="image/*" id="finput" onchange="readURL(this);"></br></br>
         <button class="btn btn-success">Submit</button>
     </div>
     <div class="col-md-5"></div>
   </form>
   </div>
 </div> 
</body>
</html>
<script type="text/javascript">
 function readURL(input) {
      if (input.files) {
          var reader = new FileReader();
 
          reader.onload = function (e) {
              $('#blah')
                  .attr('src', e.target.result);
          };
 
          reader.readAsDataURL(input.files[0]);
      }
  }
 
 $(document).ready(function(){  
      $('#upload_form').on('submit', function(e){  
           e.preventDefault();  
           if($('#image_file').val() == '')  
           {  
                alert("Please Select the File");  
           }  
           else 
           {  
                $.ajax({  
                     url:"<?php echo base_url(); ?>user/ajaxImageStore",   
                     method:"POST",  
                     data:new FormData(this),  
                     contentType: false,  
                     cache: false,  
                     processData:false,  
                     dataType: "json",
                     success:function(res)  
                     {  
                        console.log(res.success);
                        if(res.success == true){
                         $('#blah').attr('src','http://www.tutsmake.com/ajax-image-upload-with-preview-in-codeigniter/');   
                         $('#msg').html(res.msg);   
                         $('#divMsg').show();   
                        }
                        else if(res.success == false){
                          $('#msg').html(res.msg); 
                          $('#divMsg').show(); 
                        }
                        setTimeout(function(){
                         $('#msg').html('');
                         $('#divMsg').hide(); 
                        }, 3000);
                     }  
                });  
           }  
      });  
 });  
</script>