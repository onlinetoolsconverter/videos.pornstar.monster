<div class="container">
    
	<br/><br/>
	<div class="form-group">
	  
	  <center><button onclick="createPosts();" class="notranslate">Create Posts</button></center>
    </div>
	
  </div>
  
  <script>
        function createPosts() {
            
            const videoID = document.getElementById('id').value;
			

            // Fetch the page and get the title
            fetch(`xbay-poster/create-post.php?id=${videoID}`)
                .then(response => response.text())
					.then(data => {
						if(data == 'ok'){ alert("All posts created successfully."); }
						else{}
					})
					.catch(error => console.error(error));
				
		}
		
</script>