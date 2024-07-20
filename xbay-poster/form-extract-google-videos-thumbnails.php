<div class="container">
    
	<br/><br/>
	<div class="form-group">
	  <textarea id="google-video-html" placeholder="Enter Google Video Search HTML" class="notranslate"></textarea>
	  <button onclick="extractThumbnails();" class="notranslate">Extract Thumbnails</button>
    </div>
	
  </div>
  
  
    <script>
	function extractThumbnails(){
		  const googleVideoHtml = document.getElementById('google-video-html').value; 
		  const titleInput = document.getElementById('title').value;
		  const videoID = document.getElementById('id').value;
		  const data = { googleVideoHtml, titleInput, videoID };
		  
		  fetch('xbay-poster/save-extract-google-videos-thumbnails.php', {
			method: 'POST', // Set method to POST
			headers: {
			  'Content-Type': 'application/json' // Set content type as JSON
			},
			body: JSON.stringify(data) // Convert data object to JSON string for body
		  })
		  .then(response => response.text())
                .then(data => {
                    if(data == 'ok'){}
					else{}
                })
				.catch(error => console.error(error));

		}
    </script>