<html lang="en">
<head>
  <title>
    Coursera Scraping Test | Patrick
  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Patrick Casey">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <style>
    #showloading{
      visibility: hidden;
    }
    #cstable{
      visibility: hidden;
    }
  </style>
</head>
<body>
<section class="jumbotron text-center">
  <div class="container">
    <h1>Coursera Scraping Test</h1>
  </div>
</section>
<div class="container">
  <form id="loginform" method="post">
    <div class="form-group">
      <label for="usr">SELECT Category*</label>
      <select class="form-control" id="categoryoption">
        <option value="1">Data Science</option>
        <option value="2">Business</option>
        <option value="3">Computer Science</option>
        <option value="4">Health</option>
        <option value="5">Social Sciences</option>
        <option value="6">Personal Development</option>
        <option value="7">Arts and Humanities</option>
        <option value="8">Physical Science and Engineering</option>
        <option value="9">Language and Learning</option>
        <option value="10">Information Technology</option>
        <option value="11">Math and Logic</option>
      </select>
      <button type="submit" class="form-control mt-3 btn btn-primary" id="searchbutton">
        <span id="showsearch">Search</span>
        <div id="showloading">
          <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
          Loading...
        </div>
      </button>
    </div>
  </form>
  <div class="table-responsive mt-5" id="cstable">
    <h2>Scraping Results </h2>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Link</th>
              <th>Category</th>
              <th>Course</th>
              <th>Instructor</th>
              <th>Enroll</th>
              <th>Rating</th>
            </tr>
          </thead>
          <tbody id="scraptable">
          </tbody>
        </table>
      </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  // All links for selector options
  const catearray=['','https://www.coursera.org/browse/data-science','https://www.coursera.org/browse/business','https://www.coursera.org/browse/computer-science','https://www.coursera.org/browse/life-sciences','https://www.coursera.org/browse/social-sciences','https://www.coursera.org/browse/personal-development','https://www.coursera.org/browse/arts-and-humanities','https://www.coursera.org/browse/physical-science-and-engineering','https://www.coursera.org/browse/language-learning','https://www.coursera.org/browse/information-technology','https://www.coursera.org/browse/math-and-logic'];
  
  //Form Submit

  $("#loginform").submit(function(e){
    e.preventDefault();
    var title = $("#categoryoption").val();
    console.log("name:"+catearray[Number(title)]);
    $('#searchbutton').prop('disabled', true);
    $('#showsearch').hide();
    $('#showloading').css("visibility", "visible");
    // send post request via ajax

    $.ajax({ 
      url: 'query.php', 
      type: 'post', 
      data: {title:catearray[Number(title)]}, 
      dataType: 'json', 
      success: function(res){ 
        // console.log(res);
        $('#cstable').css("visibility", "visible");
        $('#searchbutton').prop('disabled', false);
        $('#showsearch').show();
        $('#showloading').css("visibility", "hidden");

        // Add response data to creating a table

        for(var i=0;i<res.length;i++){
          if(res[i].length>1)
            var temp='<tr><td>'+res[i][0]+'</td><td>'+res[i][1]+'</td><td>'+res[i][2]+'</td><td>'+res[i][3]+'</td><td>'+res[i][4]+'</td><td>'+res[i][5]+'</td></tr>';
          else
            var temp='<tr><td>'+res[i][0]+'</td><td>'+''+'</td><td>'+''+'</td><td>'+''+'</td><td>'+''+'</td><td>'+''+'</td></tr>';
          $('#scraptable').before(temp);          
        }
      },
      error: function(err){
        console.log("error", err);
        $('#cstable').css("visibility", "visible");
        $('#searchbutton').prop('disabled', false);
        $('#showsearch').show();
        $('#showloading').css("visibility", "hidden");
      }
    });
  });
});
</script>
</body>
</html>