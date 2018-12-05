<?php echo date('m-d-Y'); ?>

<html>
<head>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script>
  $(document).ready(function(){
   $("#normal").keyup(function(){
    var words = $("#normal").val();
    
     words = words.replace(/friend/, "matey");
     words = words.replace(/hello/gi, "ahoy");
     words = words.replace(/you/gi, "ye");
     words = words.replace(/your/gi, "yer");
     words = words.replace(/food/gi, "grub");
     words = words.replace(/stop/gi, "avast");
     words = words.replace(/food/gi, "grub");
     words = words.replace(/eyes/gi, "deadlights");
     words = words.replace(/yes/gi, "aye");
     words = words.replace(/quickly/gi, "smartly");
     words = words.replace(/carefully/gi, "handsomely");
     words = words.replace(/stranded/gi, "marooned");
     words = words.replace(/rob /gi, "pillage ");
     words = words.replace(/ship/gi, "vessel");
     words = words.replace(/this /gi, "this 'ere ");
     words = words.replace(/ is /gi, " be ");
     words = words.replace(/nearby/gi, "broadside");
     words = words.replace(/the /gi, "th' ");
     words = words.replace(/find /gi, "come across ");
     words = words.replace(/and /gi, "an' ");
     words = words.replace(/of /gi, "o' ");
     words = words.replace(/am /gi, "be  ");
     words = words.replace(/i'm /gi, "I be ");
     words = words.replace(/my /gi, "me ");
     words = words.replace(/old pirate/gi, "seadog ");

     words = words.replace(/(\w+)ev(\w+)\s/g, "$1e'$2 ");
     words = words.replace(/(\w+)ing\s/g, "$1in' ");

    $("#pirate").val(words);
    });
   });
 </script>
 <title>Talk like a Pirate</title>
 <style type="text/css">
  textarea {
   width: 400px;
   height: 200px;
   font-family: arial;
  	}
 </style>
</head>
<body>
 <h1>With this you can talk like a Pirate</h1>
 Just type something into the Landlubbers box and it will be translated in the Pirate Talk box. 
 <h2>Landlubbers</h2>
 <textarea id="normal"></textarea>
 <h2>Pirate Talk</h2>
 <textarea id="pirate"></textarea>
</body>
</html>