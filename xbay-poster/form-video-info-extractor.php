<div class="container">
    <div class="form-group">
      <input type="text" id="url" placeholder="Enter URL" class="notranslate">
      <button onclick="extractInfo()" class="notranslate">Extract Info</button>
	</div>
    <br/>
    <input type="text" id="id" readonly placeholder="Video ID" class="notranslate"><br/><br/>
    <input type="text" id="titleInput" readonly onclick="copyToClipboard('titleInput')" placeholder="Title" class="notranslate">
	<p id='titleCopyAlert' class="notranslate"></p>
	<a href="" id="google-video-link" style="display:none;" target="_blank" class="notranslate">Search for google video</a>
    <input type="text" id="thumbnail" readonly placeholder="Thumbnail URL" class="notranslate">
	<img src="" id="thumbnail-img" />
	<img src="" id="thumbnail-img2" />
    <input type="text" id="videoUrl" readonly placeholder="Video URL" class="notranslate">
	<br/><br/>
    <input type="text" id="duration" readonly placeholder="Duration" class="notranslate">
	<br/><br/>
	<button onclick="saveInfo()" class="notranslate">Save Info</button>
		
  </div>
  
  
    <script>//https://www.google.com/search?q=DeepLush+24+07+17+Cc+Doll+Getting+In+Deep+XXX&tbm=vid
        function extractInfo() {
            const url = document.getElementById('url').value;
            const id = url.split('x=')[1];
            const videoID = document.getElementById('id');
			videoID.value = id;
			
            const titleInput = document.getElementById('titleInput');
            const googleVideoLink = document.getElementById('google-video-link');
            const thumbnailInput = document.getElementById('thumbnail');
            const thumbnaiImg = document.getElementById('thumbnail-img');
            const thumbnaiImg2 = document.getElementById('thumbnail-img2');
            const videoUrlInput = document.getElementById('videoUrl');
            const durationInput = document.getElementById('duration');
			
			//translation form title
			const translationTitleInput = document.getElementById('title');

            // Fetch the page and get the title
            fetch(`xbay-poster/fetch.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const title = doc.querySelector('title').innerText;
					
					//remove date from title
					let title_mod = title.replace(/\d{2}\s\d{2}\s\d{2}/g, '');
                    titleInput.value = title_mod;
					
					//translation form title
					translationTitleInput.value = title_mod;
					
					googleVideoLink.href = `https://www.google.com/search?q=${title}&tbm=vid`;
					googleVideoLink.style.display = '';
                });

            // Set the thumbnail and video URLs
            thumbnailInput.value = `https://xbay.me//t/${id}.jpg`;
			thumbnaiImg.src = `https://xbay.me//t/${id}.jpg`;
			thumbnaiImg2.src = `https://xbay.me//t/${id}.jpg?n`;
            videoUrlInput.value = `https://s1.xbay.me/x/${id}.mp4`;
			

            // Fetch the duration via AJAX
            fetch(`xbay-poster/get-duration.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    durationInput.value = data;
                });
        }
		
		function saveInfo() {
			const videoID = document.getElementById('id').value;			
            const titleInput = document.getElementById('titleInput').value;            
            const durationInput = document.getElementById('duration').value;
			
			const data = { videoID, titleInput, durationInput };
		  
			fetch('xbay-poster/save-video-info.php', {
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

        function copyToClipboard(elementId) {
            const input = document.getElementById(elementId);
            const titleCopyAlert = document.getElementById('titleCopyAlert');
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
			
			titleCopyAlert.innerText = "Copied the text: " + input.value;
            //alert("Copied the text: " + input.value);
			
			setTimeout(function() {
				titleCopyAlert.style.display = 'none';
			}, 4000);
        }
		
		
    </script>
	